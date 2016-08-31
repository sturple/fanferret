<?php

namespace FanFerret\QuestionBundle\Rule;

abstract class RatingThresholdRule extends SingleQuestionRule
{
    private $threshold;
    private $condition;

    public function __construct(\FanFerret\QuestionBundle\Entity\Rule $rule, $expected_type)
    {
        parent::__construct($rule,$expected_type);
        $this->threshold = $this->getInteger('threshold');
        if (($this->threshold < 1) || ($this->threshold > 5)) throw new \InvalidArgumentException(
            sprintf(
                'Threshold %d out of range',
                $this->threshold
            )
        );
        $this->condition = $this->getString('condition');
        switch ($this->condition) {
            case '=':
            case '>':
            case '<>':
            case '<':
            case '<=':
            case '>=':
                break;
            default:
                throw new \InvalidArgumentException(
                    sprintf(
                        'Unrecognized condition "%s"',
                        $this->condition
                    )
                );
        }
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
        switch ($this->condition) {
            default:
                break;
            case '=':
                return $val === $this->threshold;
            case '>':
                return $val > $this->threshold;
            case '<':
                return $val < $this->threshold;
            case '<=':
                return $val <= $this->threshold;
            case '>=':
                return $val >= $this->threshold;
            case '<>':
                return $val !== $this->threshold;
        }
        //  This should never be reached (see ctor)
        return false;
    }
}
