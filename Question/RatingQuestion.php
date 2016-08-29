<?php

namespace FanFerret\QuestionBundle\Question;

class RatingQuestion extends Question
{
    private $twig;

    public function __construct(\FanFerret\QuestionBundle\Entity\Question $q, \FanFerret\QuestionBundle\Internationalization\TranslatorInterface $t, \Twig_Environment $twig)
    {
        parent::__construct($q,$t);
        $this->twig = $twig;
    }

    public function addToFormBuilder(\Symfony\Component\Form\FormBuilderInterface $fb)
    {
        $fb->add(
            $this->getName(),
            \Symfony\Component\Form\Extension\Core\Type\HiddenType::class
        );
    }

    public function getAnswer(array $data)
    {
        $name = $this->getName();
        if (!isset($data[$name])) throw new \InvalidArgumentException('No rating');
        $val = $data[$name];
        if (!is_numeric($val)) throw new \InvalidArgumentException(
            sprintf(
                'Rating "%s" not numeric',
                $val
            )
        );
        $i = intval($val);
        if ($i != floatval($val)) throw new \InvalidArgumentException(
            sprintf(
                'Rating "%s" is not integer',
                $val
            )
        );
        if (($i<1) || ($i>5)) throw new \InvalidArgumentException(
            sprintf(
                'Rating %d out of range',
                $i
            )
        );
        $retr = new \FanFerret\QuestionBundle\Entity\QuestionAnswer();
        $retr->setQuestion($this->getEntity());
        $retr->setValue((string)$i);
        return $retr;
    }

    public function render()
    {
        return $this->twig->render(
            'FanFerretQuestionBundle:Question:rating.html.twig',
            $this->getRenderContext()
        );
    }
}
