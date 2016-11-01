<?php

namespace FanFerret\QuestionBundle\Interop;

/**
 * Contains the results of a ReportEmailExtractorInterface
 * operation by providing basic getters and setters.
 */
class BasicReportEmailExtractorResult implements ReportEmailExtractorResultInterface
{
    private $emails;
    private $start;
    private $end;

    /**
     * Creates a new BasicReportEmailExtractorResult.
     *
     * @param array $emails
     * @param DateTime $start
     * @param DateTime $end
     */
    public function __construct (array $emails, \DateTime $start = null, \DateTime $end = null)
    {
        $this->emails = $emails;
        $this->start = $start;
        $this->end = $end;
    }

    public function getEmails()
    {
        return $this->emails;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function getEnd()
    {
        return $this->end;
    }
}
