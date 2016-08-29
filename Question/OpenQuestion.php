<?php

namespace FanFerret\QuestionBundle\Question;

/**
 *	Represents an open response question.
 */
class OpenQuestion extends Question
{
    private $testimonial;
    private $twig;
    private $token;

    public function __construct(\FanFerret\QuestionBundle\Entity\Question $q, \FanFerret\QuestionBundle\Internationalization\TranslatorInterface $t, \Twig_Environment $twig, \FanFerret\QuestionBundle\Utility\TokenGeneratorInterface $token)
    {
        parent::__construct($q,$t);
        $this->testimonial = $this->getBoolean('testimonial');
        $this->twig = $twig;
        $this->token = $token;
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
        $retr = parent::getAnswer($data);
        $name = $this->getName();
        $value = $data[$name];
        if (is_null($value)) $value = '';
        $retr->setValue($value);
        if ($this->testimonial && $data[$name . '_testimonial']) {
            $t = new \FanFerret\QuestionBundle\Entity\Testimonial();
            $t->setQuestionAnswer($retr);
            $t->setApproved(false);
            $t->setText($value);
            $t->setToken($this->token->generate());
            $retr->setTestimonial($t);
        }
        return $retr;
    }

    public function render()
    {
        return $this->twig->render(
            'FanFerretQuestionBundle:Question:open.html.twig',
            $this->getRenderContext([
                'testimonial' => $this->testimonial
            ])
        );
    }
}
