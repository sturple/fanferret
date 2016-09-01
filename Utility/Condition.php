<?php

namespace FanFerret\QuestionBundle\Utility;

class Condition
{
    private $threshold;
    private $condition;

    public function __construct($threshold, $condition)
    {
        $this->threshold = $threshold;
        $this->condition = $condition;
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

    public function check($value)
    {
        switch ($this->condition) {
            default:
                break;
            case '=':
                return $value === $this->threshold;
            case '>':
                return $value > $this->threshold;
            case '<':
                return $value < $this->threshold;
            case '<=':
                return $value <= $this->threshold;
            case '>=':
                return $value >= $this->threshold;
            case '<>':
                return $value !== $this->threshold;
        }
        //  This should never be reached (see ctor)
        return false;
    }

    public function getCondition()
    {
        return $this->condition;
    }

    public function getThreshold()
    {
        return $this->threshold;
    }
}
