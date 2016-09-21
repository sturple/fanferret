<?php

namespace FanFerret\QuestionBundle\Question;

/**
 * Represents an HTML question.
 */
class HtmlQuestion extends NoAnswerQuestion
{
    private $html;

    public function __construct(\FanFerret\QuestionBundle\Entity\Question $question)
    {
        parent::__construct($question);
        $this->html = $this->getString('content');
    }

    public function render()
    {
        return $this->html;
    }
}
