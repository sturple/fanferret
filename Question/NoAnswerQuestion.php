<?php

namespace FanFerret\QuestionBundle\Question;

/**
 * A convenience base class for questions which
 * do not generate an answer.
 */
abstract class NoAnswerQuestion extends Question
{
    public function addToFormBuilder(\Symfony\Component\Form\FormBuilderInterface $fb)
    {
    }

    public function getAnswers(array $data)
    {
        return [];
    }
}
