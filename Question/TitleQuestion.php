<?php

namespace FanFerret\QuestionBundle\Question;

/**
 * Represents a title question.
 */
class TitleQuestion extends NoAnswerQuestion
{
    private $text;
    private $num;
    private $twig;

    public function __construct(\FanFerret\QuestionBundle\Entity\Question $question, \Twig_Environment $twig)
    {
        parent::__construct($question);
        $this->text = $this->getString('title');
        $this->num = $this->getInteger('heading');
        if (($this->num < 1) || ($this->num > 6)) throw new \InvalidArgumentException(
            'Invalid HTML heading number (must be between 1 and 6 inclusive)'
        );
        $this->twig = $twig;
    }

    public function render()
    {
        return $this->twig->render('FanFerretQuestionBundle:Question:title.html.twig',[
            'title' => $this->text,
            'heading' => $this->num
        ]);
    }
}
