<?php

namespace FanFerret\QuestionBundle\Internationalization;

/**
 * Performs translation according to IETF language tags.
 */
class Translator implements TranslatorInterface
{
    private $tag;
    private $subtags;

    /**
     * Creates a Translator.
     *
     * @param string $tag
     *  The IETF language tag for the language to which
     *  the newly-created object shall translate to.
     */
    public function __construct($tag)
    {
        $this->tag = $tag;
        $this->subtags = explode('-',$tag);
    }

    private function getDefault($obj)
    {
        if (isset($obj->{'default'})) return $obj->{'default'};
        return null;
    }

    private function translateImpl($obj, $i = 0)
    {
        if (is_string($obj)) return $obj;
        if (!is_object($obj)) throw new \InvalidArgumentException('Expected either object or string');
        if ($i === count($this->subtags)) return $this->getDefault($obj);
        $subtag = $this->subtags[$i];
        if (!isset($obj->$subtag)) return $this->getDefault($obj);
        $retr = $this->translateImpl($obj->$subtag,$i + 1);
        if (is_null($retr)) return $this->getDefault($obj);
        return $retr;
    }

    public function translate($obj)
    {
        $retr = $this->translateImpl($obj);
        if (is_null($retr)) throw new NoTranslationException(
            sprintf(
                'No default translation or translation for language "%s"',
                $this->tag
            )
        );
        return $retr;
    }
}
