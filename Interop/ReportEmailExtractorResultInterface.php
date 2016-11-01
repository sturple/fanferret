<?php

namespace FanFerret\QuestionBundle\Interop;

/**
 * Represents the result of parsing a report and
 * extracting the emails therefrom.
 */
interface ReportEmailExtractorResultInterface
{
    /**
     * Retrieves all email addresses from the parsed
     * report.
     *
     * @return array
     */
    public function getEmails();

    /**
     * Retrieves the earliest entry in the parsed
     * report.
     *
     * @return DateTime|null
     *  Null shall only be returned if there were no
     *  entries in the parsed report.
     */
    public function getStart();

    /**
     * Retrieves the timestamp of the latest entry
     * in the parsed report.
     *
     * @return DateTime|null
     *  Null shall only be returned if there were no
     *  entries in the parsed report.
     */
    public function getEnd();
}
