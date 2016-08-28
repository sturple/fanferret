<?php

namespace FanFerret\QuestionBundle\Question;

/**
 *	Represents an open response question.
 */
class OpenQuestion extends Question
{
    private $testimonial;
    private $twig;

    public function __construct(\FanFerret\QuestionBundle\Entity\Question $q, \Twig_Environment $twig)
    {
        parent::__construct($q);
        $this->testimonial = $this->getBoolean('testimonial');
        $this->twig = $twig;
    }

    public function addToFormBuilder(\Symfony\Component\Form\FormBuilderInterface $fb)
    {
        $name = $this->getName();
        $fb->add(
            $name,
            \Symfony\Component\Form\Extension\Core\Type\HiddenType::class
        );
        if ($this->testimonial) $fb->add(
            $name . '_testimonial',
            \Symfony\Component\Form\Extension\Core\Type\HiddenType::class
        );
    }

    public function getAnswer(array $data)
    {
        $retr = new \FanFerret\QuestionBundle\Entity\QuestionAnswer();
        $retr->setQuestion($this->getEntity());
        $name = $this->getName();
        $value = $data[$name];
        if (is_null($value)) $value = '';
        $retr->setValue($value);
        if ($this->testimonial && $data[$name . '_testimonial']) {
            $t = new \FanFerret\QuestionBundle\Entity\Testimonial();
            $t->setQuestionAnswer($retr);
            $t->setApproved(false);
            $t->setText($value);
            //  TODO: Generate & set token
            $t->setQuestionAnswer($retr);
            $retr->setTestimonial($t);
        }
        return $retr;
    }

    public function render()
    {
        $ctx = [
            'name' => $this->getName(),
            'testimonial' => $this->testimonial,
            'title' => $this->getString('title')
        ];
        return $this->twig->render('FanFerretQuestionBundle:Question:open.html.twig',$ctx);
    }
}
