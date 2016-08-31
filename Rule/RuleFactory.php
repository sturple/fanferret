<?php

namespace FanFerret\QuestionBundle\Rule;

class RuleFactory implements RuleFactoryInterface
{
	public function create (\FanFerret\QuestionBundle\Entity\Rule $rule)
	{
		$type = $rule->getType();
		if ($type === 'ratingnotification') return new RatingThresholdNotificationRule($rule);
		throw new \InvalidArgumentException(
			sprintf(
				'Unrecognized rule type "%s"',
				$type
			)
		);
	}
}
