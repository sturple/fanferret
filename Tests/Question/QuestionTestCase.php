<?php

namespace FanFerret\QuestionBundle\Tests\Question;

class QuestionTestCase extends \PHPUnit_Framework_TestCase
{
	protected $params;
	protected $templates;
	protected $groupId;
	protected $questionId;

	protected function setUp()
	{
		$this->params = new \stdClass();
		$this->templates = [];
		$this->groupId = 1;
		$this->questionId = 2;
	}

	protected function createEntity()
	{
		$group = new \FanFerret\QuestionBundle\Entity\QuestionGroup();
		$group->setParams(new \stdClass());
		$gr = new \ReflectionClass($group);
		$gid = $gr->getProperty('id');
		$gid->setAccessible(true);
		$gid->setValue($group,$this->groupId);
		$question = new \FanFerret\QuestionBundle\Entity\Question();
		$question->setQuestionGroup($group);
		$question->setParams($this->params);
		$qr = new \ReflectionClass($question);
		$qid = $qr->getProperty('id');
		$qid->setAccessible(true);
		$qid->setValue($question,$this->questionId);
		return $question;
	}

	protected function createTwig()
	{
		$loader = new \Twig_Loader_Array($this->templates);
		return new \Twig_Environment($loader);
	}
}
