<?php

namespace FanFerret\QuestionBundle\Question;

class ChecklistQuestion extends Question
{
    private $options;
    private $hasOther;
    private $twig;

    public function __construct(\FanFerret\QuestionBundle\Entity\Question $q, \Twig_Environment $twig)
    {
        parent::__construct($q);
        $this->options = $this->getStringArray('options');
        $this->hasOther = $this->getBoolean('hasOther');
        $this->twig = $twig;
    }

    public function addToFormBuilder(\Symfony\Component\Form\FormBuilderInterface $fb)
    {
        $name = $this->getName();
        $fb->add(
            $name,
            \Symfony\Component\Form\Extension\Core\Type\HiddenType::class
        );
        if ($this->hasOther) $fb->add(
            $name . '_other',
            \Symfony\Component\Form\Extension\Core\Type\HiddenType::class
        );
    }

    private function getOption($option)
    {
        if (!is_numeric($option)) throw new \InvalidArgumentException(
            sprintf(
                'Option "%s" is not numeric',
                $option
            )
        );
        $i = intval($option);
        if ($i != floatval($option)) throw new \InvalidArgumentException(
            sprintf(
                'Option "%s" is not integer',
                $option
            )
        );
        if (($i<0) || ($i>=count($this->options))) throw new \InvalidArgumentException(
            sprintf(
                'Option index %d out of range',
                $i
            )
        );
        return $i;
    }

    private function getAnswerObject(array $data)
    {
        $name = $this->getName();
        $retr = (object)['option' => null];
        if ($this->hasOther) {
            $retr->other = null;
            $key = $name . '_other';
            $other = $data[$key];
            if ($other) {
                $retr->other = $other;
                return $retr;
            }
        }
        $option = $data[$name];
        $retr->option = $this->getOption($option);
        return $retr;
    }

    public function getAnswer(array $data)
    {
        $retr = new \FanFerret\QuestionBundle\Entity\QuestionAnswer();
        $retr->setQuestion($this->getEntity());
        $retr->setValue(json_encode($this->getAnswerObject($data)));
        return $retr;
    }

    public function render()
    {
        $ctx = [
            'options' => $this->options,
            'hasOther' => $this->hasOther,
            'title' => $this->getString('title'),
            'name' => $this->getName()
        ];
        return $this->twig->render('FanFerretQuestionBundle:Question:checklist.html.twig',$ctx);
    }
}
