<?php

namespace FanFerret\QuestionBundle\Survey;

class Survey implements SurveyInterface
{
	private $survey;
	private $twig;
	private $groups;

	public function __construct(\FanFerret\QuestionBundle\Entity\Survey $survey, \FanFerret\QuestionBundle\Question\QuestionFactoryInterface $factory, \Twig_Environment $twig)
	{
		$this->survey = $survey;
		$this->twig = $twig;
		$this->groups = [];
		foreach ($this->survey->getQuestionGroups() as $qg) {
			$qs = [];
			foreach ($qg->getQuestions() as $q) {
				$qs[] = $factory->create($q);
			}
			$this->groups[] = (object)[
				'group' => $qg,
				'questions' => $qs
			];
		}
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
		foreach ($this->traverseQuestions() as $q) {
			$ans = $q->getAnswer($data);
			$q->getEntity()->addQuestionAnswer($ans);
			$session->addQuestionAnswer($ans);
			$ans->setSurveySession($session);
			//	TODO: Testimonial handling
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
