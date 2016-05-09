<?php

namespace FanFerret\QuestionBundle\Tests\Entity;

class QuestionTest extends \PHPUnit_Framework_TestCase
{
    private $entity;
    private $params;
    
    protected function setUp()
    {
        $this->entity=new \FanFerret\QuestionBundle\Entity\Question();
        $r=new \ReflectionClass($this->entity);
        $this->params=$r->getProperty('params');
        $this->params->setAccessible(true);
    }
    
    public function testGetParamsBadJson()
    {
        $this->params->setValue($this->entity,'foo');
        $this->expectException(\RuntimeException::class);
        $this->entity->getParams();
    }
    
    public function testGetParamsNonObject()
    {
        $this->params->setValue($this->entity,'"hello"');
        $this->expectException(\LogicException::class);
        $this->entity->getParams();
    }
    
    public function testGetParamsNull()
    {
        $this->params->setValue($this->entity,'null');
        $this->expectException(\LogicException::class);
        $this->entity->getParams();
    }
    
    public function testGetParams()
    {
        $this->params->setValue($this->entity,'{}');
        $obj=$this->entity->getParams();
        $this->assertTrue(is_object($obj),'Not an object');
        $this->assertSame(0,count(get_object_vars($obj)),'Not an empty object');
    }
    
    public function testSetParamsNonObject()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->entity->setParams('test');
    }
    
    public function testSetParamsNull()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->entity->SetParams(null);
    }
    
    public function testSetParams()
    {
        $obj=new \stdClass();
        $obj->test='foo';
        $this->entity->setParams($obj);
        $this->assertSame('{"test":"foo"}',$this->params->getValue($this->entity),'Incorrect JSON');
    }
}
