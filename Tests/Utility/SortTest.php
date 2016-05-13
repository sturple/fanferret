<?php

namespace FanFerret\QuestionBundle\Tests\Utility;

use FanFerret\QuestionBundle\Utility\Sort as Sort;

class SortTest extends \PHPUnit_Framework_TestCase
{
    
    public function testStable()
    {
        $arr=[2,3,1];
        Sort::stable($arr);
        $this->assertSame(3,count($arr));
        $this->assertSame(1,$arr[0]);
        $this->assertSame(2,$arr[1]);
        $this->assertSame(3,$arr[2]);
        $arr=[
            (object)['num' => 1,'key' => 'quux'],
            (object)['num' => 0,'key' => 'foo'],
            (object)['num' => 0,'key' => 'bar']
        ];
        Sort::stable($arr,function ($a, $b) {   return $a->num-$b->num; });
        $this->assertSame(3,count($arr));
        $c=$arr[0];
        $this->assertSame('foo',$c->key);
        $this->assertSame(0,$c->num);
        $c=$arr[1];
        $this->assertSame('bar',$c->key);
        $this->assertSame(0,$c->num);
        $c=$arr[2];
        $this->assertSame('quux',$c->key);
        $this->assertSame(1,$c->num);
    }
    
}