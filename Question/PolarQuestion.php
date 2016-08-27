<?php

namespace FanFerret\QuestionBundle\Question;

/**
 *	Represents a polar question.
 */
class PolarQuestion extends Question
{
	private $negative;

	public function __construct(\FanFerret\QuestionBundle\Entity\Question $q)
	{
		parent::__construct($q);
		$this->negative = $this->getBoolean('negative');
	}

	public function addToFormBuilder(\Symfony\Component\Form\FormBuilderInterface $fb)
	{
		$fb->add(
			$this->getName(),
			\Symfony\Component\Form\Extension\Core\Type\HiddenType::class
		);
	}

	public function getAnswer(array $data)
	{
		$retr = new \FanFerret\QuestionBundle\Entity\QuestionAnswer();
		$retr->setQuestion($this->getEntity());
		$retr->setValue($data[$this->getName()] ? 'true' : 'false');
		return $retr;
	}
}
