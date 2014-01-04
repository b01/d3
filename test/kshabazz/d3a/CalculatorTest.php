<?php namespace kshabazz\test;
/**
 * Created by PhpStorm.
 * User: Khalifah
 * Date: 11/7/13
 * Time: 7:26 AM
 */

use kshabazz\d3a\BattleNet_Hero;
use kshabazz\d3a\BattleNet_Requestor;
use kshabazz\d3a\BattleNet_Sql;
use kshabazz\d3a\Calculator;
use kshabazz\d3a\Hero;
use kshabazz\d3a\Model_GetHero;

/**
 * Class CalculatorTest
 * @package kshabazz\test
 */
class CalculatorTest extends \PHPUnit_Framework_TestCase
{
	private
		$attackSpeed,
		$attributeMap,
		$hero,
		$heroId,
		$json,
		$items;
	/**
	 * Setup hero, attribute-map, and items models.
	 *
	 */
	public function setUp()
	{
		$this->heroId = 36131726;
		$bnr = new BattleNet_Requestor( 'msuBREAKER#1374' );
		$sql = new BattleNet_Sql();
		$this->attributeMap = \kshabazz\d3a\loadAttributeMap( \kshabazz\d3a\ATTRIBUTE_MAP_FILE );
		$hero = new BattleNet_Hero( $this->heroId, $bnr, $sql, FALSE );
		$_SESSION = [];
		$_SESSION[ 'hero-' . $this->heroId ] = time();
		$controller = new Model_GetHero( $hero, $this->attributeMap, $bnr, $sql );
		$this->items = $controller->getItemModels();
		//
		$this->json = file_get_contents(__DIR__ . '/../../fixture/data/hero.json');
		$this->hero = new Hero( $this->json );
	}

	public function test_attack_speed()
	{
		$this->attackSpeed = 1.6239999723434446;
		$calculator = new Calculator( $this->hero, $this->attributeMap, $this->items );
		$attackSpped = $calculator->attackSpeed();
		$this->assertEquals( 1.6239999723434446, $attackSpped, 'Attack speed calculation is wrong.' );
	}
}
?>