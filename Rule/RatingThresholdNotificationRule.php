<?php

namespace FanFerret\QuestionBundle\Rule;

class RatingThresholdNotificationRule extends RatingThresholdRule
{
    public function __construct(\FanFerret\QuestionBundle\Entity\Rule $rule)
    {
        parent::__construct($rule,'rating');
        //  TODO
    }

    public function evaluate(array $questions)
    {
        if (!$this->check($questions)) return;
        //  TODO: Actually send an e-mail
        throw new \LogicException('Unacceptable');
    }
}
