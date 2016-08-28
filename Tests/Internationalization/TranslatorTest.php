<?php

namespace FanFerret\QuestionBundle\Tests\Internationalization;

class TranslatorTest extends \PHPUnit_Framework_TestCase
{
	private function create($tag)
	{
		return new \FanFerret\QuestionBundle\Internationalization\Translator($tag);
	}

	public function testString()
	{
		$t = $this->create('en');	//	English
		$this->assertSame('foo',$t->translate('foo'));
	}

	public function testObject()
	{
		$obj = (object)[
			'en' => (object)[
				'US' => 'color',
				'GB' => 'colour'
			],
			'default' => 'corge'
		];
		$t = $this->create('en-US');	//	English as spoken in the U.S.
		$this->assertSame('color',$t->translate($obj));
	}

	public function testObjectDefault()
	{
		$obj = (object)[
			'en' => (object)[
				'US' => 'color',
				'GB' => 'colour'
			],
			'default' => 'corge'
		];
		$t = $this->create('es');	//	Spanish
		$this->assertSame('corge',$t->translate($obj));
	}

	public function testInvalid()
	{
		$t = $this->create('en');
		$this->expectException(\InvalidArgumentException::class);
		$t->translate(2);
	}

	public function testNoTranslation()
	{
		$obj = (object)[
			'en' => 'foo'
		];
		$t = $this->create('es');
		$this->expectException(\FanFerret\QuestionBundle\Internationalization\NoTranslationException::class);
		$t->translate($obj);
	}
}
