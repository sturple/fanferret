<?php

namespace FanFerret\QuestionBundle\Question;

class RatingQuestion extends Question
{
    private $twig;
    private $explain;
    private $explain_prompt;

    public function __construct(\FanFerret\QuestionBundle\Entity\Question $q, \FanFerret\QuestionBundle\Internationalization\TranslatorInterface $t, \Twig_Environment $twig)
    {
        parent::__construct($q,$t);
        $this->twig = $twig;
        $explain = $this->getOptionalObject('explain');
        if (!is_null($explain)) {
            $this->explain_prompt = $t->translate($this->getProperty('prompt',$explain));
            $this->explain = $this->getOptionalConditionObject(1,5,null,$explain);
        }
    }

    public function addToFormBuilder(\Symfony\Component\Form\FormBuilderInterface $fb)
    {
        $name = $this->getName();
        $fb->add(
            $name,
            \Symfony\Component\Form\Extension\Core\Type\HiddenType::class
        );
        if (!is_null($this->explain)) $fb->add(
            $name . '_explain',
            \Symfony\Component\Form\Extension\Core\Type\HiddenType::class
        );
    }

    public function getAnswers(array $data)
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
        $explain = null;
        if (!is_null($this->explain) && $this->explain->check($i)) $explain = $data[$name . '_explain'];
        $retr = $this->getAnswer();
        $retr->setValue(json_encode((object)[
            'rating' => $i,
            'explanation' => $explain
        ]));
        return [$retr];
    }

    public function render()
    {
        $ctx = $this->getRenderContext([
            'explain' => $this->explain,
            'prompt' => $this->explain_prompt
        ]);
        return $this->twig->render('FanFerretQuestionBundle:Question:rating.html.twig',$ctx);
    }
}
