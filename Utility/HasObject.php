<?php

namespace FanFerret\QuestionBundle\Utility;

/**
 * This trait expects classes which use it to provide
 * the following methods:
 *
 * - getDefaultObject: Retrieves the object to operate
 *   on if one is not supplied
 * - getTranslator: Retrieves a Translator object which
 *   may be used to translate strings.
 */
trait HasObject
{
    private function filterObject($obj)
    {
        if (is_null($obj)) return $this->getDefaultObject();
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
        return $this->getTranslator()->translate($val);
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

    protected function getInteger($property, $obj = null)
    {
        $val = $this->getProperty($property,$obj);
        if (!is_integer($val)) throw new \InvalidArgumentException(
            sprintf(
                'Property "%s" not integer',
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
}
