<?php

namespace FanFerret\QuestionBundle\Rule;

class RatingThresholdNotificationRule extends RatingThresholdRule
{
    private $twig;
    private $swift;

    public function __construct(\FanFerret\QuestionBundle\Entity\Rule $rule, \Twig_Environment $twig, \Swift_Mailer $swift)
    {
        parent::__construct($rule,'rating');
        $this->twig = $twig;
        $this->swift = $swift;
    }

    public function evaluate(array $questions)
    {
        if (!$this->check($questions)) return;
        //  TODO: Setup context
        $q = $this->getQuestion();
        $a = $this->getAnswer($questions);
        $ctx = [
            'question' => $q,
            'answer' => $a,
            'survey' => $this->getSurvey(),
            'session' => $a->getSurveySession()
        ];
        $body = $this->twig->render('FanFerretQuestionBundle:Rule:ratingthresholdnotification.txt.twig',$ctx);
        $msg = $this->getMessage();
        $msg->setContentType('text/plain');
        $msg->setBody($body);
        $msg->setSubject('Unacceptable Answer Notification');
        $rs = $this->swift->send($msg);
        if ($rs === 0) throw new \RuntimeException('Failed to send email');
    }
}
