<?php namespace Kshabazz\Tests\BattleNet\D3\Handlers;
/**
 * Created by PhpStorm.
 * User: Khalifah
 * Date: 7/16/14
 * Time: 6:49 AM
 */

/**
 * Class HeroTest
 *
 * @package Kshabazz\Tests\BattleNet\D3\Handlers
 */
class HeroTest extends \PHPUnit_Framework_TestCase
{
	private
		$heroId,
		$http;

	public function setUp()
	{
		$this->heroId = 46026639;
		$this->http = new \Kshabazz\BattleNet\D3\Requestors\Http( 'msuBREAKER#1374' );
	}

	/**
	 * @vcr hero.yml
	 */
	public function test_retrieving_hero_json()
	{
		$heroHanlder = new \Kshabazz\BattleNet\D3\Handlers\Hero( $this->heroId );
		$heroJson = $heroHanlder->getJson( $this->http );
		$hero = json_decode( $heroJson, TRUE );
		$this->assertEquals( $this->heroId, $hero['id'], 'Coulld not retrive HERO.' );
	}
}
