<?php

namespace FanFerret\QuestionBundle\Question;

class CheckboxQuestion extends Question
{
    private $options;
    private $hasOther;
    private $twig;

    public function __construct(\FanFerret\QuestionBundle\Entity\Question $q, \FanFerret\QuestionBundle\Internationalization\TranslatorInterface $t, \Twig_Environment $twig)
    {
        parent::__construct($q,$t);
        $this->options = $this->getTranslatableStringArray('options');
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
        if (!is_numeric($option)) {
            $array = explode(',',$option);
            if (is_array($array) and count($array) > 0 ){
                $i = $array;
            }
            else {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Option "%s" is not integer or cannot be converted to array',
                        $option
                    )
                );
            }
                
            
        }
        else {
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
        }

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
            }
        }
        $option = $data[$name];
        $retr->option = $this->getOption($option);
        return $retr;
    }

    public function getAnswers(array $data)
    {
        $retr = $this->getAnswer();
        $retr->setValue(json_encode($this->getAnswerObject($data)));
        return [$retr];
    }

    public function render()
    {
        return $this->twig->render(
            'FanFerretQuestionBundle:Question:checkbox.html.twig',
            $this->getRenderContext([
                'options' => $this->options,
                'hasOther' => $this->hasOther
            ])
        );
    }
}
