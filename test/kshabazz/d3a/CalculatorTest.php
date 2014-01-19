<?php namespace kshabazz\test;
/**
 * Created by PhpStorm.
 * User: Khalifah
 * Date: 11/7/13
 * Time: 7:26 AM
 */

use kshabazz\d3a\Calculator;
use kshabazz\d3a\Item;
use kshabazz\d3a\Model\Hero;

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
		$heroJson,
		$items;
	/**
	 * Setup hero, attribute-map, and items models.
	 *
	 */
	public function setUp()
	{
		//
		$this->heroJson = file_get_contents( __DIR__ . '/../../fixture/data/hero.json' );
		$this->hero = new Hero( $this->heroJson );
		$this->attributeMap = \kshabazz\d3a\loadAttributeMap( \kshabazz\d3a\ATTRIBUTE_MAP_FILE );

		$this->generateFixtures();

		$itemJsons = [
			'bracers' => file_get_contents( __DIR__ . "/../../fixture/data/item-bracers.json" ),
			'feet' => file_get_contents( __DIR__ . "/../../fixture/data/item-feet.json" ),
			'hands' => file_get_contents( __DIR__ . "/../../fixture/data/item-hands.json" ),
			'head' => file_get_contents( __DIR__ . "/../../fixture/data/item-head.json" ),
			'leftFinger' => file_get_contents( __DIR__ . "/../../fixture/data/item-leftFinger.json" ),
			'legs' => file_get_contents( __DIR__ . "/../../fixture/data/item-legs.json" ),
			'mainHand' => file_get_contents( __DIR__ . "/../../fixture/data/item-mainHand.json" ),
			'neck' => file_get_contents( __DIR__ . "/../../fixture/data/item-neck.json" ),
			'offHand' => file_get_contents( __DIR__ . "/../../fixture/data/item-offHand.json" ),
			'rightFinger' => file_get_contents( __DIR__ . "/../../fixture/data/item-rightFinger.json" ),
			'shoulders' => file_get_contents( __DIR__ . "/../../fixture/data/item-shoulders.json" ),
			'torso' => file_get_contents( __DIR__ . "/../../fixture/data/item-torso.json" ),
			'waist' => file_get_contents( __DIR__ . "/../../fixture/data/item-waist.json" )
		];
		foreach ( $itemJsons as $slot => $itemJson )
		{
			$this->itemModels[ $slot ] = new Item( $itemJson );
		}
	}

	/**
	 * Generates item fixtures by pulling item JSON down from BattleNet API and saving them to files for test.
	 *  - Downloaded files are saved in test/fixture/data
	 *  - Files that already exists are skipped.
	 */
	private function generateFixtures()
	{
		// Get Item models.
		$bnr = new \kshabazz\d3a\BattleNet_Requestor( 'msuBREAKER#1374' );
		$sql = new \kshabazz\d3a\BattleNet_Sql();
		$this->items = $this->hero->items();
		// It is valid that the bnrHero may not have any items equipped.
		if ( isArray($this->items) )
		{
			foreach ( $this->items as $slot => $item )
			{
				$hash = str_replace( "item/", '', $item['tooltipParams'] );
				$bnrItem = new \kshabazz\d3a\BattleNet_Item( $hash, "hash", $bnr, $sql );
				$itemFixturePath = __DIR__ . "/../../fixture/data/item-{$slot}.json";
				if ( !file_exists($itemFixturePath) )
				{
					file_put_contents( $itemFixturePath, $bnrItem->json() );
				}
			}
		}
	}

	public function test_attack_speed()
	{
		$this->attackSpeed = 1.6239999723434446;
		$calculator = new Calculator();
		$calculator->setHero( $this->hero, $this->itemModels );
		$attackSpped = $calculator->attackSpeed();
		$this->assertEquals( 1.6239999723434446, $attackSpped, 'Attack speed calculation is wrong.' );
	}
}
?>