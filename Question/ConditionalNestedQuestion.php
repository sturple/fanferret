<?php

namespace FanFerret\QuestionBundle\Question;

/**
 * Represents a conditional nested question.
 */
class ConditionalNestedQuestion extends ContainsNestedQuestion
{
    private $negative;
    private $twig;

    public function __construct(\FanFerret\QuestionBundle\Entity\Question $q, QuestionFactoryInterface $f, \FanFerret\QuestionBundle\Internationalization\TranslatorInterface $t = null, \Twig_Environment $twig)
    {
        parent::__construct($q,$f,$t);
        $this->negative = $this->getBoolean('negative');
        $this->twig = $twig;
    }

    public function addToFormBuilder(\Symfony\Component\Form\FormBuilderInterface $fb)
    {
        $fb->add(
            $this->getName(),
            \Symfony\Component\Form\Extension\Core\Type\HiddenType::class
        );
        parent::addToFormBuilder($fb);
    }

    public function getAnswers(array $data)
    {
        $retr = $this->getAnswer();
        $val = !!$data[$this->getName()];
        $retr->setValue(\FanFerret\QuestionBundle\Utility\Json::encode($val));
        if ($val === $this->negative) return [$retr];
        return array_merge([$retr],parent::getAnswers($data));
    }

    public function render()
    {
        return $this->twig->render(
            'FanFerretQuestionBundle:Question:conditionalnested.html.twig',
            $this->getRenderContext([
                'negative' => $this->negative,
                'nested' => $this->getQuestions()
            ])
        );
    }
}
