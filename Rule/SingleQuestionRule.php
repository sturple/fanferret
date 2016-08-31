<?php

namespace FanFerret\QuestionBundle\Rule;

abstract class SingleQuestionRule extends Rule
{
    private $q;

    public function __construct(\FanFerret\QuestionBundle\Entity\Rule $rule, $expected_type)
    {
        parent::__construct($rule);
        $qs = $rule->getQuestions();
        $c = count($qs);
        if ($c !== 1) throw new \InvalidArgumentException(
            'SingleQuestionRule associated with multiple Question entities'
        );
        $this->q = $qs[0];
        $type = $this->q->getType();
        if ($type !== $expected_type) throw new \InvalidArgumentException(
            sprintf(
                'Expected Question entity with type "%s", got "%s"',
                $expected_type,
                $type
            )
        );
    }

    protected function getQuestion()
    {
        return $this->q;
    }

    protected function getAnswer(array $questions, \FanFerret\QuestionBundle\Entity\Question $q = null)
    {
        if (is_null($q)) $q = $this->q;
        return parent::getAnswer($questions,$q);
    }
}
