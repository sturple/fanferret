<?php

namespace FanFerret\QuestionBundle\Tests\Question;

class ChecklistQuestionTest extends QuestionTestCase
{
    private function create()
    {
        return new \FanFerret\QuestionBundle\Question\ChecklistQuestion(
            $this->createEntity(),
            $this->createTwig()
        );
    }

    private function assertAnswer(\FanFerret\QuestionBundle\Entity\QuestionAnswer $ans, $option, $other)
    {
        $entity = $ans->getQuestion();
        $this->assertNotNull($entity);
        $val = $ans->getValue();
        $this->assertNotNull($val);
        $obj = json_decode($val);
        $this->assertTrue(is_object($obj));
        $vars = array_keys(get_object_vars($obj));
        $this->assertTrue(in_array('option',$vars,true));
        $this->assertSame(in_array('other',$vars,true),$entity->getParams()->hasOther);
        $this->assertSame($option,$obj->option);
        if (isset($other)) $this->assertSame($other,$obj->other);
    }

    public function testNoOptions()
    {
        $this->params->hasOther = false;
        $this->expectException(\InvalidArgumentException::class);
        $this->create();
    }

    public function testOptionsNotArray()
    {
        $this->params->hasOther = false;
        $this->params->options = 'foo';
        $this->expectException(\InvalidArgumentException::class);
        $this->create();
    }

    public function testOptionsNotStringArray()
    {
        $this->params->hasOther = false;
        $this->params->options = [2];
        $this->expectException(\InvalidArgumentException::class);
        $this->create();
    }

    public function testNoHasOther()
    {
        $this->params->options = ['foo'];
        $this->expectException(\InvalidArgumentException::class);
        $this->create();
    }

    public function testHasOtherNotBoolean()
    {
        $this->params->hasOther = 12;
        $this->params->options = [2];
        $this->expectException(\InvalidArgumentException::class);
        $this->create();
    }

    public function testGetAnswer()
    {
        $this->params->hasOther = false;
        $this->params->options = ['foo','bar'];
        $data = ['1_2' => '0'];
        $q = $this->create();
        $ans = $q->getAnswer($data);
        $this->assertAnswer($ans,0,null);
    }

    public function testGetAnswerOther()
    {
        $this->params->hasOther = true;
        $this->params->options = ['foo'];
        $data = ['1_2' => null,'1_2_other' => 'baz'];
        $q = $this->create();
        $ans = $q->getAnswer($data);
        $this->assertAnswer($ans,null,'baz');
    }

    public function testGetAnswerOtherButNotChosen()
    {
        $this->params->hasOther = true;
        $this->params->options = ['foo'];
        $data = ['1_2' => '0','1_2_other' => null];
        $q = $this->create();
        $ans = $q->getAnswer($data);
        $this->assertAnswer($ans,0,null);
    }

    public function testGetAnswerBad()
    {
        $this->params->hasOther = false;
        $this->params->options = ['foo'];
        $this->expectException(\InvalidArgumentException::class);
        $data = ['1_2' => null];
        $q = $this->create();
        $q->getAnswer($data);
    }
}
