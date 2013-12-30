<?php
/**
* Test for: \kshabazz\d3a\Controller\GetHero class.
*/
class GetHeroTest extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
		$this->super = new \kshabazz\d3a\SuperGlobals();
	}
	public function test_hero()
	{
		$controller = new \kshabazz\d3a\Controller\GetHero( $this->super );
		$this->assertTrue( $controller instanceof kshabazz\d3a\Controller\GetHero, 'GetHero is NULL.' );
	}
}
?>