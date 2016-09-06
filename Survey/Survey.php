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
	
	private function getGroups()
	{
		$retr = [];
		foreach ($this->survey->getQuestionGroups() as $qg) {
			$qs = [];
			foreach ($qg->getQuestions() as $q) {
				$qs[] = $this->factory->create($q);
			}
			$retr[] = (object)[
				'group' => $qg,
				'questions' => $qs
			];
		}
		return $retr;
	}

	private function getRules()
	{
		$retr = [];
		foreach ($this->traverseQuestions() as $q) {
			$entity = $q->getEntity();
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
		$qs = [];
		foreach ($this->traverseQuestions() as $q) {
			$ans = $q->getAnswer($data);
			$entity = $q->getEntity();
			$entity->addQuestionAnswer($ans);
			$session->addQuestionAnswer($ans);
			$ans->setSurveySession($session);
			//	TODO: Testimonial handling
			$qs[$entity->getId()] = $ans;
		}
		//	Process all rules
		foreach ($this->rules as $r) {
			$r->evaluate($qs);
		}
	}

	private function getGroupTemplate(\FanFerret\QuestionBundle\Entity\QuestionGroup $group)
	{
        $params = $group->getParams();
        if (!isset($params->template)) return 'FanFerretQuestionBundle:Group:default.html.twig';
        if (!is_string($params->template)) throw new \InvalidArgumentException('Expected "template" to be a string');
        return $params->template;
	}

	public function render(\Symfony\Component\Form\FormView $fv)
	{
		$gs = array_map(function ($group) {
			$ctx = [
				'questions' => $group->questions,
				'group' => $group->group
			];
            return new \FanFerret\QuestionBundle\Utility\Renderable(
                $this->getGroupTemplate($group->group),
                $ctx,
                $this->twig
            ); 
		},$this->groups);
		$ctx = [
			'groups' => $gs,
			'form' => $fv
		];
		return new \FanFerret\QuestionBundle\Utility\Renderable(
			'FanFerretQuestionBundle:Survey:default.html.twig',
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
		$ctx = [
			'conditional' => $rs
		];
		return new \FanFerret\QuestionBundle\Utility\Renderable(
			'FanFerretQuestionBundle:Survey:finish.html.twig',
			$ctx,
			$this->twig
		);
	}

	private function isNiceTime()
	{
		//	TODO: Implement this
		return true;
	}

	public function sendNotification(\FanFerret\QuestionBundle\Entity\SurveySession $session, $num)
	{
		if (!$this->isNiceTime()) return null;
		$body = $this->twig->render('FanFerretQuestionBundle:Notification:notification.txt.twig',[]);
		$subject = 'Survey Reminder';
		$content_type = 'text/plain';
		$from = $this->getEmailArray('from');
		$to = $this->getEmailArray('to');
		$msg = new \Swift_Message();
		$msg->setCharset('UTF-8');
		$msg->setFrom($this->toSwiftAddressArray($from));
		$msg->setTo($this->toSwiftAddressArray($to));
		$msg->setBody($body);
		$msg->setContentType($content_type);
		$msg->setSubject($subject);
		$rs = $this->swift->send($msg);
		if ($rs === 0) throw new \RuntimeException('Failed to send email');
		$retr = new \FanFerret\QuestionBundle\Entity\SurveyNotification();
		$retr->setSurveySession($session);
		$retr->setSent(new \DateTime());
		$retr->setBody($body);
		$retr->setToken($this->tokens->generate());
		$retr->setSubject($subject);
		$retr->setContentType($content_type);
		$session->addSurveyNotification($retr);
		return $retr;
	}
}
