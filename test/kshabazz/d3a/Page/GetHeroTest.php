<?php
/**
* Test for: \kshabazz\d3a\Page\GetHero class.
*/
class GetHeroTest extends PHPUnit_Framework_TestCase
{
	private
		$system;

	public function setUp()
	{
		$this->system = new \kshabazz\d3a\Application( new \kshabazz\d3a\SuperGlobals() );
	}
	public function test_hero()
	{
		$controller = new \kshabazz\d3a\Page\GetHero( $this->system );
		$this->assertInstanceOf( '\\kshabazz\\d3a\\Page\\GetHero', $controller, 'GetHero is NULL.' );
	}
}
?>