<?php

namespace FanFerret\QuestionBundle\Rule;

abstract class RatingThresholdRule extends SingleQuestionRule
{
    private $condition;

    public function __construct(\FanFerret\QuestionBundle\Entity\Rule $rule, $expected_type)
    {
        parent::__construct($rule,$expected_type);
        $threshold = $this->getInteger('threshold');
        if (($threshold < 1) || ($threshold > 5)) throw new \InvalidArgumentException(
            sprintf(
                'Threshold %d out of range',
                $threshold
            )
        );
        $condition = $this->getString('condition');
        $this->condition = new \FanFerret\QuestionBundle\Utility\Condition($threshold,$condition);
    }

    /**
     * Sees if the answer meets the condition of the
     * rule.
     *
     * @param array $questions
     *  The map of Question entity IDs to QuestionAnswer
     *  entities.
     *
     * @return
     *  \em true if the rule is satisfied, \em false
     *  otherwise.
     */
    protected function check(array $questions)
    {
        $ans = $this->getAnswer($questions);
        $val = intval($ans->getValue());
        return $this->condition->check($val);
    }
}
