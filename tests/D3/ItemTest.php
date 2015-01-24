<?php namespace Kshabazz\Tests\BattleNet\D3;

use \Kshabazz\BattleNet\D3\Item,
	\Kshabazz\BattleNet\D3\Connections\Http,
	\Kshabazz\Slib\HttpClient;

/**
 * Class ItemTest
 *
 * @package Kshabazz\Tests\BattleNet\D3
 */
class ItemTest extends \PHPUnit_Framework_TestCase
{
	private
		$apiKey,
		$bnrHttp,
		$fixturesDir,
		$itemHash1,
		$itemHash2,
		$itemHash3;

	public function setUp()
	{
		$this->apiKey = \D3_TEST_API_KEY;
		$client = new HttpClient();
		$this->bnrHttp = new Http( $this->apiKey, 'msuBREAKER#1374', $client );
		$this->itemHash1 = 'item/Cj0I-bvTgAsSBwgEFdosyssdb2mxyh10HmzAHfKS3AgdcIt38CILCAEVbEIDABgWICAwiQI4_AJAAFAMYJUDGMvMrsMGUABYAg';
		$this->itemHash2 = 'item/ChoIqvDNpwMSBwgEFScYtUkwiQI4kANAAGCQAxjO4KibCVAIWAI';
		$this->itemHash3 = 'item/CioI4YeygAgSBwgEFcgYShEdhBF1FR2dbLMUHape7nUwDTiTA0AAUApgkwMYkOPQlAI';
		$this->fixturesDir = FIXTURES_PATH . DIRECTORY_SEPARATOR;
	}

	public function test_item_has_recipe()
	{
		$fixtureFile = $this->fixturesDir . 'item-hash-3.json';
		$itemJson = \file_get_contents( $fixtureFile );
		$item = new Item( $itemJson );
		$actualRecipe = $item->recipe();
		$this->assertEquals( 'T09_Weapon_MightyWeapon_1H', $actualRecipe->id, 'Recipe not found!' );
	}

	public function test_item_has_no_recipe()
	{
		$fixtureFile = $this->fixturesDir . 'item-hash-1.json';
		$itemJson = \file_get_contents( $fixtureFile );
		$item = new Item( $itemJson );
		$actualRecipe = $item->recipe();
		$this->assertEquals( NULL, $actualRecipe, 'Recipe found!' );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function test_invalid_json()
	{
		$item = new Item( NULL );
	}

	public function test_is_weapon()
	{
		$fixtureFile = $this->fixturesDir . 'item-hash-3.json';
		$itemJson = \file_get_contents( $fixtureFile );
		$item = new Item( $itemJson );
		$this->assertTrue( $item->isWeapon() );
	}

	public function test_is_not_a_weapon()
	{
		$fixtureFile = $this->fixturesDir . 'item-hash-1.json';
		$itemJson = \file_get_contents( $fixtureFile );
		$item = new Item( $itemJson );
		$this->assertFalse( $item->isWeapon() );
	}

	public function test_armor()
	{
		$fixtureFile = $this->fixturesDir . 'item-Unique_Helm_006_x1.json';
		$itemJson = \file_get_contents( $fixtureFile );
		$item = new Item( $itemJson );
		$this->assertEquals( 741, $item->armor->min );
	}

	public function test_flavorText()
	{
		$fixtureFile = $this->fixturesDir . 'item-Unique_Helm_006_x1.json';
		$itemJson = \file_get_contents( $fixtureFile );
		$item = new Item( $itemJson );
		$this->assertNotNull( $item->flavorText() );
	}
}
?>