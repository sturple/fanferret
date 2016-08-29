<?php

namespace FanFerret\QuestionBundle\Utility;

/**
 * Binds together a template and a context into
 * an object upon which render may be called
 * with no arguments.
 */
class Renderable
{
    private $twig;
    private $template;
    private $context;

    public function __construct($template, array $context, \Twig_Environment $twig)
    {
        $this->twig = $twig;
        $this->template = $template;
        $this->context = $context;
    }

    public function render()
    {
        return $this->twig->render($this->template,$this->context);
    }
}
