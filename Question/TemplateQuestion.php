<?php

namespace FanFerret\QuestionBundle\Question;

/**
 * Represents a Template question.
 */
class TemplateQuestion extends NoAnswerQuestion
{
    private $template;
    private $context;
    private $twig;

    public function __construct(\FanFerret\QuestionBundle\Entity\Question $question, \Twig_Environment $twig)
    {
        parent::__construct($question);
        $this->template = $this->getString('template');
        $this->context = $this->getOptionalObject('context');
        if (is_null($this->context)) $this->context = [];
        else $this->context = (array)$this->context;
        $this->twig = $twig;
    }

    public function render()
    {
        return $this->twig->render($this->template,$this->context);
    }
}
