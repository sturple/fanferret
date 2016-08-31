<?php

namespace FanFerret\QuestionBundle\Rule;

/**
 * A convenience base class which provides utilities
 * for implementing Rule objects.
 */
abstract class Rule implements RuleInterface
{
    use \FanFerret\QuestionBundle\Utility\HasObject;

    private $entity;

    public function __construct(\FanFerret\QuestionBundle\Entity\Rule $rule)
    {
        $this->entity = $rule;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function evaluate(array $questions)
    {
    }

    public function getConditionalFinish(array $questions)
    {
        return [];
    }

    /**
     * May be overriden by a derived class which actually
     * provides a Translator, default implementation simply
     * throws an exception.
     */
    protected function getTranslator()
    {
        throw new \LogicException('No Translator');
    }

    private function getDefaultObject()
    {
        return $this->entity->getParams();
    }

    protected function getAnswer(array $questions, \FanFerret\QuestionBundle\Entity\Question $q)
    {
        $id = $q->getId();
        if (!isset($questions[$id])) throw new \InvalidArgumentException(
            sprintf(
                'No QuestionAnswer entity for Question entity with ID %d',
                $id
            )
        );
        return $questions[$id];
    }
}
