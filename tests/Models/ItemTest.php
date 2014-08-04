<?php namespace Kshabazz\Tests\BattleNet\D3\Models;

use \Kshabazz\BattleNet\D3\Models\Item,
	\Kshabazz\BattleNet\D3\Connections\Http as BnrHttp;

/**
 * Class ItemTest
 *
 * @package Kshabazz\Tests\BattleNet\D3\Models
 */
class ItemTest extends \PHPUnit_Framework_TestCase
{
	private
		$bnrHttp,
		$itemHash1,
		$itemHash2,
		$itemHash3;

	public function setUp()
	{
		$this->bnrHttp = new BnrHttp( 'msuBREAKER#1374' );
		$this->itemHash1 = 'item/Cj0I-bvTgAsSBwgEFdosyssdb2mxyh10HmzAHfKS3AgdcIt38CILCAEVbEIDABgWICAwiQI4_AJAAFAMYJUDGMvMrsMGUABYAg';
		$this->itemHash2 = 'item/ChoIqvDNpwMSBwgEFScYtUkwiQI4kANAAGCQAxjO4KibCVAIWAI';
		$this->itemHash3 = 'item/CioI4YeygAgSBwgEFcgYShEdhBF1FR2dbLMUHape7nUwDTiTA0AAUApgkwMYkOPQlAI';
	}

	/**
	 * @vcr item-hash-3.yml
	 */
	public function test_item_has_recipe()
	{
		$itemJson = $this->bnrHttp->getItem( $this->itemHash3 );
		$item = new Item( $itemJson );
		$actualRecipe = $item->recipe();
		$this->assertEquals( 'T09_Weapon_MightyWeapon_1H', $actualRecipe->id, 'Recipe not found!' );
	}

	/**
	 * @vcr item-hash-1.yml
	 */
	public function test_item_has_no_recipe()
	{
		$itemJson = $this->bnrHttp->getItem( $this->itemHash1 );
		$item = new Item( $itemJson );
		$actualRecipe = $item->recipe();
		$this->assertEquals( NULL, $actualRecipe, 'Recipe found!' );
	}
}
