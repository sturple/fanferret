<?php

namespace FanFerret\QuestionBundle\Utility;

/**
 * Classes which use this trait may provide the following
 * methods:
 *
 * - getDefaultObject: Retrieves the object to operate
 *   on if one is not supplied
 * - getTranslator: Retrieves a Translator object which
 *   may be used to translate strings.
 *
 * This trait supplies default implementations which simply
 * throw.
 */
trait HasObject
{
    protected function getDefaultObject()
    {
        throw new \LogicException('No default object');
    }

    protected function getTranslator()
    {
        throw new \LogicException('No translator');
    }

    private function filterObject($obj)
    {
        if (is_null($obj)) return $this->getDefaultObject();
        if (!is_object($obj)) throw new \InvalidArgumentException('Expected an object');
        return $obj;
    }

    private function noProperty($property)
    {
        throw new \InvalidArgumentException(
            sprintf(
                'No property "%s"',
                $property
            )
        );
    }

    protected function getProperty($property, $obj = null)
    {
        $obj = $this->filterObject($obj);
        if (!isset($obj->$property)) $this->noProperty($property);
        return $obj->$property;
    }

    protected function getOptionalProperty($property, $obj = null)
    {
        $obj = $this->filterObject($obj);
        if (isset($obj->$property)) return $obj->$property;
        return null;
    }

    private function checkString($val, $property)
    {
        if (!is_string($val)) throw new \InvalidArgumentException(
            sprintf(
                'Property "%s" not string',
                $property
            )
        );
    }

    protected function getString($property, $obj = null)
    {
        $val = $this->getProperty($property,$obj);
        $this->checkString($val,$property);
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

    protected function getOptionalArray($property, $obj = null)
    {
        $val = $this->getOptionalProperty($property,$obj);
        if (is_null($val)) return null;
        if (!is_array($val)) throw new \InvalidArgumentException(
            sprintf(
                'Property "%s" not array',
                $property
            )
        );
        return $val;
    }

    protected function getArray($property, $obj = null)
    {
        $val = $this->getOptionalArray($property,$obj);
        if (is_null($val)) $this->noProperty($property);
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

    protected function getOptionalString($property, $obj = null)
    {
        $val = $this->getOptionalProperty($property,$obj);
        if (is_null($val)) return null;
        $this->checkString($val,$property);
        return $val;
    }

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

    protected function getOptionalEmail($property, $obj = null)
    {
        $email = $this->getOptionalProperty($property,$obj);
        if (is_null($email)) return null;
        return $this->toEmail($email);
    }

    protected function getEmail($property, $obj = null)
    {
        $email = $this->getOptionalEmail($property,$obj);
        if (is_null($email)) $this->noProperty($property);
        return $email;
    }

    protected function getOptionalEmailArray($property, $obj = null)
    {
        $emails = $this->getOptionalProperty($property,$obj);
        if (is_null($emails)) return null;
        if (is_array($emails)) return array_map(function ($obj) {
            return $this->toEmail($obj);
        },$emails);
        return [$this->toEmail($emails)];
    }

    protected function getEmailArray($property, $obj = null)
    {
        $emails = $this->getOptionalEmailArray($property,$obj);
        if (is_null($emails)) $this->noProperty($property);
        return $emails;
    }

    protected function toSwiftAddressArray($addrs)
    {
        if (is_null($addrs)) return [];
        if (!is_array($addrs)) $addrs = [$addrs];
        $retr = [];
        foreach ($addrs as $addr) {
            if (isset($addr->name)) $retr[$addr->address] = $addr->name;
            else $retr[] = $addr->address;
        }
        return $retr;
    }
}
