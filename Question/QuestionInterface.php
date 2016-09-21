<?php

namespace FanFerret\QuestionBundle\Question;

interface QuestionInterface
{
    /**
     * Retrieves the Question entity which
     * this object represents.
     *
     * @return
     *  A Question entity.
     */
    public function getEntity();

    /**
     * Adds appropriate fields to a FormBuilder such
     * that the form the FormBuilder generates may
     * capture the information necessary to answer
     * this question.
     *
     * @param $fb
     *  A FormBuilder.
     */
    public function addToFormBuilder(\Symfony\Component\Form\FormBuilderInterface $fb);

    /**
     * Gets a QuestionAnswer entity representing the
     * answer to this question.
     *
     * This method shall link the QuestionAnswer to
     * the Question entity this object represents,
     * but shall not form the relationship the other
     * way.  This is to ensure that should the caller
     * decide not to use the returned QuestionAnswer
     * entity the wrapped Question remains unaffected.
     *
     * @param array $data
     *  An associative array of data from the submitted
     *  form.
     *
     * @return QuestionAnswer|null
     *  A QuestionAnswer entity or null if the question
     *  does not generate an answer.
     */
    public function getAnswer(array $data);

    /**
     * Renders the HTML for the question.
     *
     * @return string
     *  A string containing the raw HTML for the question.
     */
    public function render();
}
