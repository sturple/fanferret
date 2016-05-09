<?php

namespace FanFerret\QuestionBundle\Utility;

/**
 * Contains static helper methods for encoding
 * and decoding JSON.
 */
class Json {
    
    private static function errorCheck()
    {
        $c=json_last_error();
        if ($c===JSON_ERROR_NONE) return;
        $msg=json_last_error_msg();
        if (!is_string($msg)) $msg='';
        throw new \RuntimeException($msg,$c);
    }
    
    /**
     * Encodes a PHP value as JSON.
     *
     * @param mixed $value
     *  The value to encode.
     *
     * @return string
     *  A JSON encoding of \em value.
     */
    public static function encode($value)
    {
        $str=json_encode($value,JSON_PRESERVE_ZERO_FRACTION);
        self::errorCheck();
        return $str;
    }
    
    /**
     * Decodes a string to a PHP value.
     *
     * @param string $str
     *  The JSON string to decode.
     *
     * @return mixed
     *  A PHP value as represented by \em str.
     */
    public static function decode($str)
    {
        $retr=json_decode($str);
        self::errorCheck();
        return $retr;
    }
    
}
