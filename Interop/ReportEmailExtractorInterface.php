<?php

namespace FanFerret\QuestionBundle\Interop;

/**
 * Represents a strategy for extracting email
 * addresses from a report.
 */
interface ReportEmailExtractorInterface
{
    /**
     * Returns an array of MIME types which the
     * extractor can handle.
     *
     * @return array
     */
    public function getMimeTypes();

    /**
     * Parses a file and extracts the email addresses
     * therefrom.
     *
     * @param string $mime
     *  A string containing the MIME type of the file
     *  to parse.
     * @param string $str
     *  A string containing the raw binary contents of
     *  the file to parse.
     *
     * @return ReportEmailExtractorResultInterface
     */
    public function extract($mime, $str);
}
