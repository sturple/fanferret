<?php

namespace FanFerret\QuestionBundle\Tests\Interop;

class CsvReportEmailExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testMimeTypes()
    {
        $extractor = new \FanFerret\QuestionBundle\Interop\CsvReportEmailExtractor(0,1,'');
        $types = $extractor->getMimeTypes();
        $this->assertCount(2,$types);
        $this->assertTrue(in_array('text/csv',$types,true));
        $this->assertTrue(in_array('application/vnd.ms-excel',$types,true));
    }

    public function testEmpty()
    {
        $extractor = new \FanFerret\QuestionBundle\Interop\CsvReportEmailExtractor(0,1,'');
        $result = $extractor->extract('text/csv','');
        $this->assertNull($result->getStart());
        $this->assertNull($result->getEnd());
        $this->assertCount(0,$result->getEmails());
    }

    public function testNoHeaders()
    {
        $extractor = new \FanFerret\QuestionBundle\Interop\CsvReportEmailExtractor(0,1,'Y/m/d H:i:s',null,0);
        $result = $extractor->extract('text/csv','rleahy@rleahy.ca,1970/01/01 00:00:00' . "\r\n" . 'rleahy@fifthgeardev.com,1970/01/01 00:00:01');
        $start = $result->getStart();
        $this->assertNotNull($start);
        $this->assertSame(0,$start->getTimestamp());
        $end = $result->getEnd();
        $this->assertNotNull($end);
        $this->assertSame(1,$end->getTimestamp());
        $emails = $result->getEmails();
        $this->assertCount(2,$emails);
        $this->assertSame('rleahy@rleahy.ca',$emails[0]);
        $this->assertSame('rleahy@fifthgeardev.com',$emails[1]);
    }

    public function testHeaders()
    {
        $extractor = new \FanFerret\QuestionBundle\Interop\CsvReportEmailExtractor(0,1,'Y/m/d H:i:s');
        $result = $extractor->extract('text/csv','Email,Date' . "\r\n" . 'rleahy@rleahy.ca,1970/01/01 00:00:00');
        $start = $result->getStart();
        $this->assertNotNull($start);
        $this->assertSame(0,$start->getTimestamp());
        $end = $result->getEnd();
        $this->assertNotNull($end);
        $this->assertSame(0,$end->getTimestamp());
        $emails = $result->getEmails();
        $this->assertCount(1,$emails);
        $this->assertSame('rleahy@rleahy.ca',$emails[0]);
    }

    public function testTimezone()
    {
        $extractor = new \FanFerret\QuestionBundle\Interop\CsvReportEmailExtractor(0,1,'Y/m/d H:i:s',new \DateTimeZone('America/Vancouver'),0);
        $result = $extractor->extract('text/csv','rleahy@rleahy.ca,1970/01/01 00:00:00');
        $start = $result->getStart();
        $this->assertNotNull($start);
        $ts = 60 * 8 * 60;
        $this->assertSame($ts,$start->getTimestamp());
        $end = $result->getEnd();
        $this->assertNotNull($end);
        $this->assertSame($ts,$end->getTimestamp());
        $emails = $result->getEmails();
        $this->assertCount(1,$emails);
        $this->assertSame('rleahy@rleahy.ca',$emails[0]);
    }

    public function testInvalidDateTime()
    {
        $extractor = new \FanFerret\QuestionBundle\Interop\CsvReportEmailExtractor(0,1,'Y/m/d H:i:s',null,0);
        $this->expectException(\FanFerret\QuestionBundle\Exception\ReportEmailExtractorException::class);
        $extractor->extract('text/csv','rleahy@rleahy.ca,aoeu');
    }
}
