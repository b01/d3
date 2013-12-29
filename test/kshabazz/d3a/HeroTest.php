<?php namespace kshabazz\d3a\test;
/**
 * Unit test for: namespace kshabazz\d3a\Hero class.
 *
 * @package \kshabazz\d3a\test
 */
class HeroTest extends \PHPUnit_Framework_TestCase
{
	private
		$heroId,
		$json;

	/**
	 * Load JSON from a file.
	 */
	public function setUp()
	{
		$this->heroId = 36131726;
		$this->json = file_get_contents(__DIR__ . '/../../fixture/data/hero.json');
	}

	public function test_initializing_hero()
	{
		$hero = new \kshabazz\d3a\Hero( $this->json );
		$this->assertEquals( $this->heroId, $hero->id, 'Invalid hero.' );
	}
}
?>