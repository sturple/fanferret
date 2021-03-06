<?php

namespace FanFerret\QuestionBundle\Survey;

class Survey implements SurveyInterface
{
    use \FanFerret\QuestionBundle\Utility\HasObject;

    private $survey;
    private $twig;
    private $factory;
    private $groups;
    private $rules;
    private $rfactory;
    private $tokens;
    private $swift;

    private function getDefaultObject()
    {
        return $this->survey->getParams();
    }

    private function getTimezone()
    {
        return new \DateTimeZone($this->getString('timezone'));
    }
    
    private function getGroups()
    {
        $retr = [];
        foreach ($this->survey->getQuestionGroups() as $question_group) {
            $questions = [];
            foreach ($question_group->getQuestions() as $q) {
                $questions[] = $this->factory->create($q);
            }
            $retr[] = (object)[
                'group' => $question_group,
                'questions' => $questions
            ];
        }
        return $retr;
    }

    private function getRules()
    {
        $retr = [];
        foreach ($this->traverseQuestionEntities() as $entity) {
            //	There's an issue here because rules can
            //	be associated with multiple questions:
            //	We might add the same rule multiple times.
            //
            //	Therefore when we encounter a rule we check
            //	the first question it's associated with, if
            //	that's us we add a Rule object otherwise we
            //	assume that we either already got it or that
            //	we'll find it later.
            //
            //	NOTE: The assumption is made that when a Rule
            //	entity is associated with multiple Question
            //	entities all those Question entities belong
            //	to the same Survey entity.
            foreach ($entity->getRules() as $r) {
                $qs = $r->getQuestions();
                $rq = $qs[0];
                if ($rq->getId() === $entity->getId()) {
                    $retr[] = $this->rfactory->create($r);
                }
            }
        }
        return $retr;
    }

    public function __construct(
        \FanFerret\QuestionBundle\Entity\Survey $survey,
        \FanFerret\QuestionBundle\Question\QuestionFactoryInterface $factory,
        \FanFerret\QuestionBundle\Rule\RuleFactoryInterface $rfactory,
        \FanFerret\QuestionBundle\Utility\TokenGeneratorInterface $tokens,
        \Twig_Environment $twig,
        \Swift_Mailer $swift
    ) {
        $this->survey = $survey;
        $this->twig = $twig;
        $this->factory = $factory;
        $this->rfactory = $rfactory;
        $this->tokens = $tokens;
        $this->swift = $swift;
        $this->groups = $this->getGroups();
        $this->rules = $this->getRules();
    }

    private function traverseQuestions()
    {
        foreach ($this->groups as $g) {
            foreach ($g->questions as $q) {
                yield $q;
            }
        }
    }

    private function traverseQuestionEntitiesImpl(\FanFerret\QuestionBundle\Entity\Question $q)
    {
        yield $q;
        foreach ($q->getQuestions() as $nested) {
            foreach ($this->traverseQuestionEntitiesImpl($nested) as $result) {
                yield $result;
            }
        }
    }

    private function traverseQuestionEntities()
    {
        foreach ($this->groups as $g) {
            foreach ($g->questions as $q) {
                foreach ($this->traverseQuestionEntitiesImpl($q->getEntity()) as $result) {
                    yield $result;
                }
            }
        }
    }

    public function getEntity()
    {
        return $this->survey;
    }

    public function addToFormBuilder(\Symfony\Component\Form\FormBuilderInterface $fb)
    {
        foreach ($this->traverseQuestions() as $q) {
            $q->addToFormBuilder($fb);
        }
    }

    public function getAnswers(\FanFerret\QuestionBundle\Entity\SurveySession $session, array $data)
    {
        //	Collect answers and attach them to the
        //	SurveySession
        $questions = [];
        foreach ($this->traverseQuestions() as $q) {
            foreach ($q->getAnswers($data) as $ans) {
                $entity = $ans->getQuestion();
                $entity->addQuestionAnswer($ans);
                $session->addQuestionAnswer($ans);
                $ans->setSurveySession($session);
                //  TODO: Testimonial handling if applicable
                $questions[$entity->getId()] = $ans;
            }
        }
        //	Process all rules
        foreach ($this->rules as $r) {
            $r->evaluate($questions);
        }
    }

    private function getTemplate($default, $obj = null)
    {
        $str = $this->getOptionalString('template',$obj);
        return is_null($str) ? $default : $str;
    }

    private function getGroupTemplate(\FanFerret\QuestionBundle\Entity\QuestionGroup $group)
    {
        return $this->getTemplate('FanFerretQuestionBundle:Group:default.html.twig',$group->getParams());
    }

    private function getSurveyTemplate()
    {
        return $this->getTemplate('FanFerretQuestionBundle:Survey:default.html.twig');
    }

    private function getStylesheets()
    {
        $retr = $this->getOptionalStringArray('stylesheets');
        if (is_null($retr)) return [];
        return $retr;
    }

    private function getBaseContext()
    {
        return [
            'survey' => $this->survey,
            'stylesheets' => $this->getStylesheets()
        ];
    }

    public function render(\FanFerret\QuestionBundle\Entity\SurveySession $session, \Symfony\Component\Form\FormView $fv)
    {
        $groups = array_map(function ($group) {
            $ctx = [
                'questions' => $group->questions,
                'group' => $group->group,
                'title' => $this->getString('title',$group->group->getParams())
            ];
            return new \FanFerret\QuestionBundle\Utility\Renderable(
                $this->getGroupTemplate($group->group),
                $ctx,
                $this->twig
            ); 
        },$this->groups);
        $ctx = array_merge($this->getBaseContext(),[
            'groups' => $groups,
            'form' => $fv,
            'session' => $session
        ]);
        return new \FanFerret\QuestionBundle\Utility\Renderable(
            $this->getSurveyTemplate(),
            $ctx,
            $this->twig
        );
    }

    public function renderFinish(\FanFerret\QuestionBundle\Entity\SurveySession $session)
    {
        //	Load answers back into memory/the appropriate
        //	data structure so we can get conditional finish
        //	from the rules
        $qs = [];
        foreach ($session->getQuestionAnswers() as $qa) {
            $qs[$qa->getQuestion()->getId()] = $qa;
        }
        //	Traverse the rules and obtain conditional finish
        //	renderables
        $rs = [];
        //	TODO: We need to order the rules somehow so that
        //	conditional finishes appear in some consistent
        //	order
        foreach ($this->rules as $r) {
            $rs = array_merge($r->getConditionalFinish($qs),$rs);
        }
        $ctx = array_merge($this->getBaseContext(),[
            'conditional' => $rs,
            'session' => $session
        ]);
        return new \FanFerret\QuestionBundle\Utility\Renderable(
            'FanFerretQuestionBundle:Survey:finish.html.twig',
            $ctx,
            $this->twig
        );
    }

    public function renderStyles()
    {
        $styles = $this->getOptionalString('styles');
        if (is_null($styles)) $styles = '';
        $ctx = [
            'styles' => $styles
        ];
        return new \FanFerret\QuestionBundle\Utility\Renderable(
            'FanFerretQuestionBundle:Survey:styles.css.twig',
            $ctx,
            $this->twig
        );
    }

    private function isNiceTime()
    {
        $now = new \DateTime();
        $now->setTimezone($this->getTimezone());
        $h = intval($now->format('G'));
        //  Only send emails between the hours of 9AM and
        //  5PM local time
        if ($h < 9) return false;
        if ($h >= 17) return false;
        return true;
    }
    
    public function sendAdminNotification(\FanFerret\QuestionBundle\Entity\SurveySession $session, $num, $force = false ) {
        if (!$force && !$this->isNiceTime($session)) return null;
        $surveyParams = $session->getSurvey()->getParams();
        $subject = (empty($surveyParams->notifications->subject->completed)) ? 'Survey Completed' : $surveyParams->notifications->subject->completed; 
        $to  = (empty($surveyParams->notifications->to)) ? 'info@fanferret.com' : $surveyParams->notifications->to;
        $content_type = 'text/html';
        $from = $this->getEmailArray('from');       
        $fname = $session->getFirstName();
        $lname = $session->getLastName();
        if (!is_null($fname) && !is_null($lname)) $to->name = sprintf('%s %s',$fname,$lname);
        $replyto = $this->getOptionalEmailArray('replyto');
        $msg = new \Swift_Message();
        $msg->setCharset('UTF-8');
        $msg->setFrom($this->toSwiftAddressArray($from));
        
        $msg->setTo((object)$this->toSwiftAddressArray($to));
        $msg->setBcc('info@fanferret.com');
        $msg->setReplyTo($this->toSwiftAddressArray($replyto));
        $msg->setContentType($content_type);
        $msg->setSubject($subject);
        $retr = new \FanFerret\QuestionBundle\Entity\SurveyNotification();
        $retr->setSurveySession($session);
        $retr->setSent(new \DateTime());
        $retr->setToken($this->tokens->generate());
        $retr->setSubject($subject);
        $retr->setContentType($content_type);
        $session->addSurveyNotification($retr);
        $body = $this->twig->render('FanFerretQuestionBundle:Notification:admin_notification.html.twig',[
            'session' => $session,
            'notification' => $retr
        ]);
        $retr->setBody($body);
        $msg->setBody($body);
        $rs = $this->swift->send($msg);
        if ($rs === 0) throw new \RuntimeException('Failed to send email');
        return $retr;        
    }

  
    
    public function sendNotification(\FanFerret\QuestionBundle\Entity\SurveySession $session, $num, $force = false)
    {
        if (!$force && !$this->isNiceTime($session)) return null;
        $template = ($num == 1) ? 'FanFerretQuestionBundle:Notification:notification.html.twig' : 'FanFerretQuestionBundle:Notification:notification2.html.twig';
        $surveyParams = $session->getSurvey()->getParams();
        $notify1 = (empty($surveyParams->notifications->subject->notification1)) ? 'Thank you for your stay' : $surveyParams->notifications->subject->notification1;
        $notify2 = (empty($surveyParams->notifications->subject->notification2)) ? 'Guest Comment Card' : $surveyParams->notifications->subject->notification2; 
       
        $subject = ($num == 1) ? $notify1 : $notify2; 
        $content_type = 'text/html';
        $from = $this->getEmailArray('from');
        $to = (object)['address' => $session->getEmail()];
        $fname = $session->getFirstName();
        $lname = $session->getLastName();
        if (!is_null($fname) && !is_null($lname)) $to->name = sprintf('%s %s',$fname,$lname);
        $replyto = $this->getOptionalEmailArray('replyto');
        $msg = new \Swift_Message();
        $msg->setCharset('UTF-8');
        $msg->setFrom($this->toSwiftAddressArray($from));
        $msg->setTo($this->toSwiftAddressArray($to));
        $msg->setBcc('info@fanferret.com');
        $msg->setReplyTo($this->toSwiftAddressArray($replyto));
        $msg->setContentType($content_type);
        $msg->setSubject($subject);
        $retr = new \FanFerret\QuestionBundle\Entity\SurveyNotification();
        $retr->setSurveySession($session);
        $retr->setSent(new \DateTime());
        $retr->setToken($this->tokens->generate());
        $retr->setSubject($subject);
        $retr->setContentType($content_type);
        $session->addSurveyNotification($retr);
        $body = $this->twig->render($template,[
            'session' => $session,
            'last_notification_date' => $session->getSurveyNotifications()->first()->getSent(),
            'notification' => $retr
        ]);
        $retr->setBody($body);
        $msg->setBody($body);
        $rs = $this->swift->send($msg);
        if ($rs === 0) throw new \RuntimeException('Failed to send email');
        return $retr;
    }
}
