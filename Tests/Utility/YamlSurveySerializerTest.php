<?php

namespace FanFerret\QuestionBundle\Tests\Utility;

class YamlQuestionSerializerTest extends \PHPUnit_Framework_TestCase
{
    private $se;
    private $ex;
    
    protected function setUp()
    {
        $this->se=new \FanFerret\QuestionBundle\Utility\YamlSurveySerializer();
        $this->ex=[
            'questionGroup' => [
                [
                    'id' => 'test',
                    'slug' => 'test',
                    'title' => 'Test',
                    'params' => [
                        'quux' => 'corge'
                    ]
                ]
            ],
            'questions' => [
                'test' => [
                    [
                        'type' => 'group',
                        'set' => 'foo',
                        'title' => 'Foo'
                    ],
                    [
                        'type' => 'group',
                        'set' => 'bar',
                        'title' => 'Bar',
                        'params' => [
                            'baz' => 'bar'
                        ]
                    ]
                ],
                'foo' => [
                    [
                        'type' => 'polar',
                        'email' => ['quux' => 'corge']
                    ],
                    [
                        'type' => 'rating',
                        'order' => 1,
                        'rules' => [
                            [
                                'type' => 'email'
                            ],
                            [
                                'type' => 'bar'
                            ]
                        ]
                    ]
                ],
                'bar' => [
                    [
                        'type' => 'rating',
                        'order' => 16
                    ],
                    [
                        'type' => 'polar',
                        'order' => 2
                    ]
                ]
            ]
        ];
    }
    
    private function yaml()
    {
        return \Symfony\Component\Yaml\Yaml::dump($this->ex);
    }
    
    private function fromString()
    {
        return $this->se->fromString($this->yaml());
    }
    
    private function assertThrows()
    {
        $this->expectException(\RuntimeException::class);
        $this->fromString();
    }
    
    public function testFromStringBadYaml()
    {
        $this->expectException(\Symfony\Component\Yaml\Exception\ParseException::class);
        $this->se->fromString('extrasquiggle: {Scratch that} squigs can go at the beginning also!');
    }
    
    public function testFromStringNonArrayRoot()
    {
        $this->expectException(\RuntimeException::class);
        $this->se->fromString('hello');
    }
    
    public function testFromStringNoQuestionGroup()
    {
        unset($this->ex['questionGroup']);
        $this->assertThrows();
    }
    
    public function testFromStringNonArrayQuestionGroup()
    {
        $this->ex['questionGroup']='hi';
        $this->assertThrows();
    }
    
    public function testFromStringNoQuestions()
    {
        unset($this->ex['questions']);
        $this->assertThrows();
    }
    
    public function testFromStringNonArrayQuestions()
    {
        $this->ex['questions']='foo';
        $this->assertThrows();
    }
    
    public function testFromStringBasic()
    {
        $arr=$this->fromString();
        $this->assertTrue(is_array($arr));
        $this->assertSame(1,count($arr));
        $s=$arr[0];
        $this->assertTrue($s instanceof \FanFerret\QuestionBundle\Entity\Survey);
        $this->assertSame('test',$s->getSlug());
        $this->assertNull($s->getCompanyId());  //  Should this change?
        $this->assertSame(0,count($s->getSurveySessions()));
        $params=$s->getParams();
        $this->assertTrue(isset($params->quux));
        $this->assertSame('corge',$params->quux);
        $this->assertSame(1,count(get_object_vars($params)));
        //  Check translations
        $ts=$s->getTranslations();
        $this->assertSame(1,count($ts));
        $t=$ts[0];
        $this->assertSame('en',$t->getLanguage());
        $this->assertSame('Test',$t->getText());
        $this->assertSame($s,$t->getSurvey());
        //  Check groups
        $gs=$s->getQuestionGroups();
        $this->assertSame(2,count($gs));
        $foo=$gs[0];
        $params=$foo->getParams();
        $this->assertSame(0,count(get_object_vars($params)));
        $ts=$foo->getTranslations();
        $this->assertSame(1,count($ts));
        $t=$ts[0];
        $this->assertSame('en',$t->getLanguage());
        $this->assertSame('Foo',$t->getText());
        $this->assertSame($foo,$t->getQuestionGroup());
        $this->assertSame(1,$foo->getOrder());
        $this->assertSame($s,$foo->getSurvey());
        $bar=$gs[1];
        $params=$bar->getParams();
        $this->assertTrue(isset($params->baz));
        $this->assertSame('bar',$params->baz);
        $this->assertSame(1,count(get_object_vars($params)));
        $ts=$bar->getTranslations();
        $this->assertSame(1,count($ts));
        $t=$ts[0];
        $this->assertSame('en',$t->getLanguage());
        $this->assertSame('Bar',$t->getText());
        $this->assertSame($bar,$t->getQuestionGroup());
        $this->assertSame(2,$bar->getOrder());
        $this->assertSame($s,$bar->getSurvey());
        //  Check questions
        $qs=$foo->getQuestions();
        $this->assertSame(2,count($qs));
        $q=$qs[0];
        $this->assertSame(1,$q->getOrder());
        $this->assertSame($foo,$q->getQuestionGroup());
        $ps=$q->getParams();
        $this->assertTrue(isset($ps->type));
        $this->assertSame('rating',$ps->type);
        $rs=$q->getRules();
        $this->assertSame(2,count($rs));
        $r=$rs[0];
        $rp=$r->getParams();
        $this->assertTrue(isset($rp->type));
        $this->assertSame('email',$rp->type);
        $this->assertSame(1,count(get_object_vars($rp)));
        $rqs=$r->getQuestions();
        $this->assertSame(1,count($rqs));
        $this->assertSame($q,$rqs[0]);
        $r=$rs[1];
        $rp=$r->getParams();
        $this->assertTrue(isset($rp->type));
        $this->assertSame('bar',$rp->type);
        $this->assertSame(1,count(get_object_vars($rp)));
        $rqs=$r->getQuestions();
        $this->assertSame(1,count($rqs));
        $this->assertSame($q,$rqs[0]);
        $this->assertSame(0,count($q->getQuestionAnswers()));
        $q=$qs[1];
        $this->assertSame(2,$q->getOrder());
        $this->assertSame($foo,$q->getQuestionGroup());
        $ps=$q->getParams();
        $this->assertTrue(isset($ps->type));
        $this->assertSame('polar',$ps->type);
        $rs=$q->getRules();
        $this->assertSame(1,count($rs));
        $r=$rs[0];
        $rp=$r->getParams();
        $this->assertTrue(isset($rp->type));
        $this->assertSame('email',$rp->type);
        $this->assertTrue(isset($rp->quux));
        $this->assertSame('corge',$rp->quux);
        $this->assertSame(2,count(get_object_vars($rp)));
        $rqs=$r->getQuestions();
        $this->assertSame(1,count($rqs));
        $this->assertSame($q,$rqs[0]);
        $this->assertSame(0,count($q->getQuestionAnswers()));
        $qs=$bar->getQuestions();
        $this->assertSame(2,count($qs));
        $q=$qs[0];
        $this->assertSame(1,$q->getOrder());
        $this->assertSame($bar,$q->getQuestionGroup());
        $ps=$q->getParams();
        $this->assertTrue(isset($ps->type));
        $this->assertSame('polar',$ps->type);
        $this->assertSame(0,count($q->getRules()));
        $this->assertSame(0,count($q->getQuestionAnswers()));
        $q=$qs[1];
        $this->assertSame(2,$q->getOrder());
        $this->assertSame($bar,$q->getQuestionGroup());
        $ps=$q->getParams();
        $this->assertTrue(isset($ps->type));
        $this->assertSame('rating',$ps->type);
        $this->assertSame(0,count($q->getRules()));
        $this->assertSame(0,count($q->getQuestionAnswers()));
    }
    
}
