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

    /**
     * Converts a value into an object representing an email
     * address.
     *
     * @param mixed $obj
     *  The value to convert.
     *
     * @return object
     *  An object with a "name" and "address" property.
     */
    protected function toEmail($obj)
    {
        if (is_string($obj)) return (object)[
            'address' => $obj,
            'name' => null
        ];
        if (!is_object($obj)) throw new \InvalidArgumentException(
            'Expected either string or object'
        );
        return (object)[
            'address' => $this->getString('address',$obj),
            'name' => $this->getOptionalString('name',$obj)
        ];
    }

    protected function toEmailArray($obj)
    {
        if (!is_array($obj)) return [$this->toEmail($obj)];
        return array_map(function ($obj) {
            return $this->toEmail($obj);
        },$obj);
    }

    protected function getEmail($property)
    {
        $retr = $this->getOptionalProperty($property);
        if (!is_null($retr)) return $this->toEmail($retr);
        $s = $this->getSurvey();
        $retr = $this->getProperty($property,$s->getParams());
        return $this->toEmail($retr);
    }

    private function getEmailArrayImpl($property)
    {
        $retr = $this->getOptionalProperty($property);
        if (!is_null($retr)) return $this->toEmailArray($retr);
        $s = $this->getSurvey();
        $retr = $this->getOptionalProperty($property,$s->getParams());
        if (is_null($retr)) return [];
        return $this->toEmailArray($retr);
    }

    protected function getEmailArray($property, $allow_empty = true)
    {
        $retr = $this->getEmailArrayImpl($property);
        if (!$allow_empty && (count($retr) === 0)) throw new \InvalidArgumentException(
            sprintf(
                'No emails associated with property "%s"',
                $property
            )
        );
        return $retr;
    }

    protected function getSurvey()
    {
        $qs = $this->entity->getQuestions();
        if (count($qs) === 0) throw new \InvalidArgumentException('No questions');
        $q = $qs[0];
        $qg = $q->getQuestionGroup();
        return $qg->getSurvey();
    }

    private function toSwiftAddressArray(array $arr)
    {
        $retr = [];
        foreach ($arr as $obj) {
            if (isset($obj->name)) $retr[$obj->name] = $obj->address;
            else $retr[] = $obj->address;
        }
        return $retr;
    }

    protected function getMessage()
    {
        $retr = new \Swift_Message();
        $retr->setCharset('UTF-8');
        $from = $this->getEmailArray('from',false);
        $to = $this->getEmailArray('to',false);
        $cc = $this->getEmailArray('cc');
        $bcc = $this->getEmailArray('bcc');
        $reply = $this->getEmailArray('replyto');
        $retr->setFrom($this->toSwiftAddressArray($from));
        $retr->setTo($this->toSwiftAddressArray($to));
        $retr->setCc($this->toSwiftAddressArray($cc));
        $retr->setBcc($this->toSwiftAddressArray($bcc));
        $retr->setReplyTo($this->toSwiftAddressArray($reply));
        return $retr;
    }
}
