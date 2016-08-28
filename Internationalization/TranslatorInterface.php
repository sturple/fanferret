<?php

namespace FanFerret\QuestionBundle\Internationalization;

/**
 * An interface which may be implemented to translate
 * text.
 */
interface TranslatorInterface
{
    /**
     * Obtains a translation.
     *
     * @param $obj
     *  A value representing the translations
     *  to choose from.  This may take on many forms
     *  and therefore no checking should be done by
     *  the caller.
     *
     * @return string
     *  The translated string.
     */
    public function translate($obj);
}
