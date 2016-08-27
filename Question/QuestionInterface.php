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
     * @param array $data
     *  An associative array of data from the submitted
     *  form.
     *
     * @return
     *  A QuestionAnswer entity.
     */
    public function getAnswer(array $data);
}
