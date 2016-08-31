<?php

namespace FanFerret\QuestionBundle\Question;

/**
 * Provides an abstract mechanism for creating Question
 * objects from Question entities.
 */
interface QuestionFactoryInterface
{
    /**
     * Creates a Question object for a Question
     * entity.
     *
     * @param $question
     *  The Question entity.
     *
     * @return
     *  A Question object.
     */
    public function create(\FanFerret\QuestionBundle\Entity\Question $question);
}
