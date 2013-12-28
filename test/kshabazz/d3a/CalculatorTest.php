<?php namespace kshabazz\test;
use kshabazz\d3a\BattleNet_Hero;
use kshabazz\d3a\BattleNet_Requestor;
use kshabazz\d3a\BattleNet_Sql;
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
		$items;
	/**
	 * Setup hero, attribute-map, and items models.
	 *
	 */
	public function setup()
	{
		$bnr = new BattleNet_Requestor( 'msuBREAKER#1374' );
		$sql = new BattleNet_Sql(
			'mysql:host=127.0.0.1;dbname=kshabazz;charset=utf8',
			'd3appuser',
			'n0tAn3a5yPa55'
		);
		$this->attributeMap = \kshabazz\d3a\loadAttributeMap( \kshabazz\d3a\ATTRIBUTE_MAP_FILE );
		$bnrHero = new BattleNet_Hero( 'id', $bnr, $sql, FALSE );
		$controller = new Model_GetHero( $bnrHero, $this->attributeMap, $bnr, $sql );
		$this->items = $controller->getItemModels();
		$this->hero = new Hero( $controller->json() );
	}


	public function test_attack_speed()
	{
		$this->markTestIncomplete('Incomplete.');
	}
}
?>