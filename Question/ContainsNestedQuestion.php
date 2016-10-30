<?php

namespace FanFerret\QuestionBundle\Question;

/**
 * A convenience base class for questions which
 * contain nested questions.
 */
abstract class ContainsNestedQuestion extends Question
{
    private $qs;

    /**
     * Creates a ContainsNestedQuestion object.
     *
     * @param Question $question
     *  The Question entity to wrap.
     * @param QuestionFactoryInterface $factory
     *  The factory which shall be used to create
     *  the Question objects for the nested entities.
     * @param TranslatorInterface $translator
     *  An optional translator for internationalizing
     *  strings.  Defaults to null.
     */
    public function __construct(\FanFerret\QuestionBundle\Entity\Question $question, QuestionFactoryInterface $factory, \FanFerret\QuestionBundle\Internationalization\TranslatorInterface $translator = null)
    {
        parent::__construct($question,$translator);
        $this->qs = [];
        foreach ($question->getQuestions() as $nested) {
            $this->qs[] = $factory->create($nested);
        }
    }

    public function addToFormBuilder(\Symfony\Component\Form\FormBuilderInterface $fb)
    {
        foreach ($this->qs as $q) $q->addToFormBuilder($fb);
    }

    public function getAnswers(array $data)
    {
        $retr = [];
        foreach ($this->qs as $q) $retr = array_merge($retr,$q->getAnswers($data));
        return $retr; 
    }

    protected function getQuestions()
    {
        return $this->qs;
    }
}
