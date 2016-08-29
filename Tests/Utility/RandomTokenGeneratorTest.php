<?php

namespace FanFerret\QuestionBundle\Tests\Utility;

class RandomTokenGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testBitsNegative()
    {
        $this->expectException(\LogicException::class);
        new \FanFerret\QuestionBundle\Utility\RandomTokenGenerator(-1);
    }

    public function testBitsZero()
    {
        $this->expectException(\LogicException::class);
        new \FanFerret\QuestionBundle\Utility\RandomTokenGenerator(0);
    }

    public function testBitsNotDivisibleByEight()
    {
        $this->expectException(\LogicException::class);
		new \FanFerret\QuestionBundle\Utility\RandomTokenGenerator(9);
    }

    public function testGenerate()
    {
        $g=new \FanFerret\QuestionBundle\Utility\RandomTokenGenerator(128);
        $a=$g->generate();
        $b=$g->generate();
        $this->assertNotSame($a,$b);
        $preg='/^[a-f0-9]{32}$/u';
        $this->assertSame(1,preg_match($preg,$a));
        $this->assertSame(1,preg_match($preg,$b));
    }
}
