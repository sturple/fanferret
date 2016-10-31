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

    private function getDefaultObject()
    {
        return $this->entity->getParams();
    }

    protected function getAnswer(array $questions, \FanFerret\QuestionBundle\Entity\Question $q)
    {
        $id = $q->getId();
        if (!isset($questions[$id])) return null;
        return $questions[$id];
    }

    protected function getSurvey()
    {
        $qs = $this->entity->getQuestions();
        if (count($qs) === 0) throw new \InvalidArgumentException('No questions');
        $q = $qs[0];
        while (is_null($q->getQuestionGroup())) $q = $q->getQuestion();
        $qg = $q->getQuestionGroup();
        return $qg->getSurvey();
    }

    private function getOptionalInheritedEmailArray($property)
    {
        $local = $this->getOptionalEmailArray($property);
        if (!is_null($local)) return $local;
        $s = $this->getSurvey();
        return $this->getOptionalEmailArray($property,$s->getParams());
    }

    private function getInheritedEmailArray($property)
    {
        $retr = $this->getOptionalInheritedEmailArray($property);
        if (is_null($retr)) throw new \InvalidArgumentException(
            sprintf(
                'No property "%s"',
                $property
            )
        );
        return $retr;
    }

    protected function getMessage()
    {
        $retr = new \Swift_Message();
        $retr->setCharset('UTF-8');
        $from = $this->getInheritedEmailArray('from');
        $to = $this->getInheritedEmailArray('to');
        $cc = $this->getOptionalInheritedEmailArray('cc');
        $bcc = $this->getOptionalInheritedEmailArray('bcc');
        $reply = $this->getOptionalInheritedEmailArray('replyto');
        $retr->setFrom($this->toSwiftAddressArray($from));
        $retr->setTo($this->toSwiftAddressArray($to));
        $retr->setCc($this->toSwiftAddressArray($cc));
        $retr->setBcc($this->toSwiftAddressArray($bcc));
        $retr->setReplyTo($this->toSwiftAddressArray($reply));
        return $retr;
    }
}
