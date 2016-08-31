<?php

namespace FanFerret\QuestionBundle\Survey;

class Survey implements SurveyInterface
{
	private $survey;
	private $twig;
	private $factory;
	private $groups;
	private $rules;
	private $rfactory;
	
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

	public function __construct(\FanFerret\QuestionBundle\Entity\Survey $survey, \FanFerret\QuestionBundle\Question\QuestionFactoryInterface $factory, \FanFerret\QuestionBundle\Rule\RuleFactoryInterface $rfactory, \Twig_Environment $twig)
	{
		$this->survey = $survey;
		$this->twig = $twig;
		$this->factory = $factory;
		$this->rfactory = $rfactory;
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
		return $this->twig->render('FanFerretQuestionBundle:Survey:default.html.twig',$ctx);
	}
}
