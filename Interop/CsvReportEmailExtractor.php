<?php

namespace FanFerret\QuestionBundle\Interop;

/**
 * Extracts email addresses from a CSV report.
 */
class CsvReportEmailExtractor implements ReportEmailExtractorInterface
{
    private $email_col;
    private $date_col;
    private $headers;
    private $date_fmt;
    private $tz;

    public function __construct($email_col, $date_col, $date_fmt, \DateTimeZone $tz = null, $headers = 1)
    {
        $this->email_col = $email_col;
        $this->date_col = $date_col;
        $this->headers = $headers;
        $this->date_fmt = $date_fmt;
        if (is_null($tz)) $tz = new \DateTimeZone('UTC');
        $this->tz = $tz;
    }

    public function getMimeTypes()
    {
        return ['text/csv','application/vnd.ms-excel'];
    }

    private function getMax(\DateTime $a = null, \DateTime $b = null)
    {
        if (is_null($a)) return $b;
        if (is_null($b)) return $a;
        if ($a->getTimestamp() > $b->getTimestamp()) return $a;
        return $b;
    }

    private function getMin(\DateTime $a = null, \DateTime $b = null)
    {
        if (is_null($a)) return $b;
        if (is_null($b)) return $a;
        if ($a->getTimestamp() < $b->getTimestamp()) return $a;
        return $b;
    }

    public function extract($mime, $str)
    {
        $reader = \League\Csv\Reader::createFromString($str);
        $i = 0;
        $start = null;
        $end = null;
        $emails = [];
        foreach ($reader->fetch() as $row) {
            //  Skip headers
            if ($this->headers > $i) {
                ++$i;
                continue;
            }
            if (!isset($row[$this->email_col])) throw new \InvalidArgumentException('No email');
            if (!isset($row[$this->date_col])) throw new \InvalidArgumentException('No date');
            $date_str = $row[$this->date_col];
            $date = \DateTime::createFromFormat($this->date_fmt,$date_str,$this->tz);
            if ($date === false) throw new \FanFerret\QuestionBundle\Exception\ReportEmailExtractorException(
                sprintf(
                    '"%s" cannot be converted to DateTime using format string "%s"',
                    $date_str,
                    $this->date_fmt
                )
            );
            $start = $this->getMin($date,$start);
            $end = $this->getMax($date,$end);
            $emails[] = $row[$this->email_col];
        }
        return new BasicReportEmailExtractorResult($emails,$start,$end);
    }
}
