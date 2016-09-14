<?php

namespace FanFerret\QuestionBundle\Question;

/**
 *	Represents a polar question.
 */
class PolarQuestion extends Question
{
	private $negative;
	private $twig;
	private $explain;

	public function __construct(\FanFerret\QuestionBundle\Entity\Question $q, \FanFerret\QuestionBundle\Internationalization\TranslatorInterface $t, \Twig_Environment $twig)
	{
		parent::__construct($q,$t);
		$this->negative = $this->getBoolean('negative');
		$this->twig = $twig;
		$this->explain = $this->getOptionalString('explain');
		if (!is_null($this->explain)) switch ($this->explain) {
			case 'positive':
			case 'negative':
				break;
			default:
				throw new \InvalidArgumentException(
					sprintf(
						'Expected "explain" to be "positive" or "negative" got "%s"',
						$this->explain
					)
				);
		}
	}

	public function addToFormBuilder(\Symfony\Component\Form\FormBuilderInterface $fb)
	{
		$name = $this->getName();
		$fb->add(
			$name,
			\Symfony\Component\Form\Extension\Core\Type\HiddenType::class
		);
		if ($this->explain) $fb->add(
			$name . '_explain',
			\Symfony\Component\Form\Extension\Core\Type\HiddenType::class
		);
	}

	public function getAnswer(array $data)
	{
		$retr = parent::getAnswer($data);
		$name = $this->getName();
		$val = !!$data[$name];
		if (!$this->explain) {
			$retr->setValue($val ? 'true' : 'false');
			return $retr;
		}
		$ans = (object)[
			'value' => $val,
			'explanation' => null
		];
		$explain_positive = $this->explain === 'positive';
		$is_positive = $val !== $this->negative;
		if ($explain_positive === $is_positive) {
			$ans->explanation = $data[$name . '_explain'];
		}
		$retr->setValue(json_encode($ans));
		return $retr;
	}

	public function render()
	{
		return $this->twig->render(
			'FanFerretQuestionBundle:Question:polar.html.twig',
			$this->getRenderContext([
				'negative' => $this->negative,
				'explain' => $this->explain
			])
		);
	}
}
