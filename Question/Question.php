<?php

namespace FanFerret\QuestionBundle\Question;

/**
 * A convenience base class for questions.
 */
abstract class Question implements QuestionInterface
{
    private $q;
    private $t;

    /**
     * Creates a Question object.
     *
     * @param $question
     *  The Question entity which the newly-created
     *  object shall represent.
     * @param $translator
     *  A TranslatorInterface object which the newly-created
     *  object my use to obtain localized strings.
     */
    public function __construct(\FanFerret\QuestionBundle\Entity\Question $question, \FanFerret\QuestionBundle\Internationalization\TranslatorInterface $translator)
    {
        $this->q=$question;
        $this->t=$translator;
    }

    public function getEntity()
    {
        return $this->q;
    }

    public function getAnswer(array $data)
    {
        $retr = new \FanFerret\QuestionBundle\Entity\QuestionAnswer();
        $retr->setQuestion($this->q);
        return $retr;
    }

    protected function getName()
    {
        return sprintf(
            '%d_%d',
            $this->q->getQuestionGroup()->getId(),
            $this->q->getId()
        );
    }

    private function filterObject($obj)
    {
        if (is_null($obj)) return $this->q->getParams();
        if (!is_object($obj)) throw new \InvalidArgumentException('Expected an object');
        return $obj;
    }

    protected function getProperty($property, $obj = null)
    {
        $obj = $this->filterObject($obj);
        if (!isset($obj->$property)) throw new \InvalidArgumentException(
            sprintf(
                'No property "%s"',
                $property
            )
        );
        return $obj->$property;
    }

    protected function getString($property, $obj = null)
    {
        $val = $this->getProperty($property,$obj);
        if (!is_string($val)) throw new \InvalidArgumentException(
            sprintf(
                'Property "%s" not string',
                $property
            )
        );
        return $val;
    }

    protected function getTranslatableString($property, $obj = null)
    {
        $val = $this->getProperty($property,$obj);
        return $this->t->translate($val);
    }

    protected function getBoolean($property, $obj = null)
    {
        $val = $this->getProperty($property,$obj);
        if (!is_bool($val)) throw new \InvalidArgumentException(
            sprintf(
                'Property "%s" not boolean',
                $property
            )
        );
        return $val;
    }

    protected function getArray($property, $obj = null)
    {
        $val = $this->getProperty($property,$obj);
        if (!is_array($val)) throw new \InvalidArgumentException(
            sprintf(
                'Property "%s" not array',
                $property
            )
        );
        return $val;
    }

    protected function getStringArray($property, $obj = null)
    {
        $val = $this->getArray($property,$obj);
        foreach ($val as $str) if (!is_string($str)) throw new \InvalidArgumentException(
            sprintf(
                'Property "%s" is not string array',
                $property
            )
        );
        return $val;
    }

    protected function getTranslatableStringArray($property, $obj = null)
    {
        $val = $this->getArray($property,$obj);
        return array_map(function ($obj) {  return $this->t->translate($obj);   },$val);
    }

    protected function getRenderContext(array $ctx = [])
    {
        return array_merge(
            [
                'title' => $this->getTranslatableString('title'),
                'entity' => $this->q,
                'name' => $this->getName()
            ],
            $ctx
        );
    }
}
