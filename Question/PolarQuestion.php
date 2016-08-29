<?php

namespace FanFerret\QuestionBundle\Question;

/**
 *	Represents a polar question.
 */
class PolarQuestion extends Question
{
	private $negative;
	private $twig;

	public function __construct(\FanFerret\QuestionBundle\Entity\Question $q, \FanFerret\QuestionBundle\Internationalization\TranslatorInterface $t, \Twig_Environment $twig)
	{
		parent::__construct($q,$t);
		$this->negative = $this->getBoolean('negative');
		$this->twig = $twig;
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
		$retr = parent::getAnswer($data);
		$retr->setValue($data[$this->getName()] ? 'true' : 'false');
		return $retr;
	}

	public function render()
	{
		$ctx = [
			'title' => $this->getTranslatableString('title'),
			'negative' => $this->negative,
			'name' => $this->getName(),
			'entity' => $this->getEntity()
		];
		return $this->twig->render('FanFerretQuestionBundle:Question:polar.html.twig',$ctx);
	}
}
