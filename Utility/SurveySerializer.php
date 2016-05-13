<?php

namespace FanFerret\QuestionBundle\Utility;

/**
 * Serializes Survey entities to and from strings.
 */
interface SurveySerializer
{
    
    /**
     * Attempts to retrieve a collection of Survey
     * entities from a string.
     *
     * @param string $str
     *  The string to deserialize.
     *
     * @return array
     *  An array of Survey entities extracted from
     *  \em str.
     */
    public function fromString($str);
    
}
