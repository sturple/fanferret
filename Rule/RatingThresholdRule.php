<?php

namespace FanFerret\QuestionBundle\Rule;

abstract class RatingThresholdRule extends SingleQuestionRule
{
    private $condition;

    public function __construct(\FanFerret\QuestionBundle\Entity\Rule $rule, $expected_type)
    {
        parent::__construct($rule,$expected_type);
        $this->condition = $this->getConditionObject(1,5);
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
        $obj = json_decode($ans->getValue());
        return $this->condition->check($this->getInteger('rating',$obj));
    }

    protected function getCondition()
    {
        return $this->condition->getCondition();
    }

    protected function getThreshold()
    {
        return $this->condition->getThreshold();
    }
}
