<?php

namespace FanFerret\QuestionBundle\Question;

class QuestionFactory implements QuestionFactoryInterface
{
    private $twig;
    private $tokens;
    private $translator;

    public function __construct(\FanFerret\QuestionBundle\Internationalization\TranslatorInterface $translator, \Twig_Environment $twig, \FanFerret\QuestionBundle\Utility\TokenGeneratorInterface $tokens)
    {
        $this->twig = $twig;
        $this->tokens = $tokens;
        $this->translator = $translator;
    }

    public function create(\FanFerret\QuestionBundle\Entity\Question $question)
    {
        $type = $question->getType();
        if ($type === 'open') return new OpenQuestion(
            $question,
            $this->translator,
            $this->twig,
            $this->tokens
        );
        if ($type === 'polar') return new PolarQuestion(
            $question,
            $this->translator,
            $this->twig
        );
        if ($type === 'checklist') return new ChecklistQuestion(
            $question,
            $this->translator,
            $this->twig
        );
        if ($type === 'checkbox') return new CheckboxQuestion(
            $question,
            $this->translator,
            $this->twig
        );
        if ($type === 'radio') return new RadioQuestion(
            $question,
            $this->translator,
            $this->twig
        );
        if ($type === 'rating') return new RatingQuestion(
            $question,
            $this->translator,
            $this->twig
        );
        if ($type === 'title') return new TitleQuestion(
            $question,
            $this->twig
        );
        if ($type === 'template') return new TemplateQuestion(
            $question,
            $this->twig
        );
        if ($type === 'html') return new HtmlQuestion($question);
        if ($type === 'conditionalnested') return new ConditionalNestedQuestion(
            $question,
            $this,
            $this->translator,
            $this->twig
        );
        throw new \InvalidArgumentException(
            sprintf(
                'Unrecognized question type "%s"',
                $type
            )
        );
    }
}
