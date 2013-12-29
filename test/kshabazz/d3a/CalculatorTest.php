<?php namespace kshabazz\test;
use kshabazz\d3a\BattleNet_Hero;
use kshabazz\d3a\BattleNet_Requestor;
use kshabazz\d3a\BattleNet_Sql;
use kshabazz\d3a\Calculator;
use kshabazz\d3a\Hero;
use kshabazz\d3a\Model_GetHero;

/**
 * Created by PhpStorm.
 * User: Khalifah
 * Date: 11/7/13
 * Time: 7:26 AM
 */

class CalculatorTest extends \PHPUnit_Framework_TestCase
{
	private
		$attributeMap,
		$hero,
		$heroId,
		$items;
	/**
	 * Setup hero, attribute-map, and items models.
	 *
	 */
	public function setUp()
	{
		$this->heroId = '36131726';
		$bnr = new BattleNet_Requestor( 'msuBREAKER#1374' );
		$sql = new BattleNet_Sql(
			'mysql:host=127.0.0.1;dbname=kshabazz;charset=utf8',
			'd3appuser',
			'n0tAn3a5yPa55'
		);
		$this->attributeMap = \kshabazz\d3a\loadAttributeMap( \kshabazz\d3a\ATTRIBUTE_MAP_FILE );
		$hero = new BattleNet_Hero( $this->heroId, $bnr, $sql, FALSE );
		$_SESSION = [];
		$_SESSION[ 'hero-' . $this->heroId ] = time();
		$controller = new Model_GetHero( $hero, $this->attributeMap, $bnr, $sql );
		$this->items = $controller->getItemModels();
		$this->hero = new Hero( $controller->json() );
	}


	public function test_attack_speed()
	{
		$calculator = new Calculator( $this->hero, $this->attributeMap, $this->items );
		$attackSpped = $calculator->attackSpeed();
		$this->assertEquals('0.00', $attackSpped, 'Attack speed calculation is wrong.' );
	}
}
?>