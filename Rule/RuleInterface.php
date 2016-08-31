<?php

namespace FanFerret\QuestionBundle\Rule;

/**
 * An interface for rules.
 *
 * Rules are evaluated on QuestionAnswer
 * entities when a SurveySession entity is
 * completed.
 */
interface RuleInterface
{
    /**
     * Evaluates the rule.
     *
     * @param array $questions
     *  An array of QuestionAnswer entities.  There
     *  shall be an entry in this array for each
     *  Question entity in the associated Survey
     *  entity.  This array shall be keyed such that
     *  each QuestionAnswer entity's key is the ID
     *  of its Question entity.
     */
    public function evaluate(array $questions);

    /**
     *  Retrieves the conditional finish for this
     *  rule, if any.
     *
     * @param array $questions
     *  An array of QuestionAnswer entities.  There
     *  shall be an entry in this array for each
     *  Question entity in the associated Survey
     *  entity.  This array shall be keyed such that
     *  each QuestionAnswer entity's key is the ID
     *  of its Question entity.
     *
     * @return
     *  An array of Renderable objects representing
     *  the conditional finish items for this rule,
     *  if any (if none return the empty array).
     */
    public function getConditionalFinish(array $questions);
}
