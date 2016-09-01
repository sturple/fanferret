<?php

namespace FanFerret\QuestionBundle\Rule;

class RuleFactory implements RuleFactoryInterface
{
	private $twig;
	private $swift;

	public function __construct(\Twig_Environment $twig, \Swift_Mailer $swift)
	{
		$this->twig = $twig;
		$this->swift = $swift;
	}

	public function create (\FanFerret\QuestionBundle\Entity\Rule $rule)
	{
		$type = $rule->getType();
		if ($type === 'ratingnotification') return new RatingThresholdNotificationRule($rule,$this->twig,$this->swift);
		throw new \InvalidArgumentException(
			sprintf(
				'Unrecognized rule type "%s"',
				$type
			)
		);
	}
}
