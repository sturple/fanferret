<?php

namespace FanFerret\QuestionBundle\Rule;

/**
 * An interface which may be implemented to
 * provide Rule objects which correspond to
 * a given Rule entity.
 */
interface RuleFactoryInterface
{
    /**
     * Creates a Rule object for a Rule entity.
     *
     * @param $rule
     *  The Rule entity.
     *
     * @return
     *  A Rule object.
     */
    public function create(\FanFerret\QuestionBundle\Entity\Rule $rule);
}
