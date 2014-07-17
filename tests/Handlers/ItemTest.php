<?php namespace Kshabazz\Tests\BattleNet\D3\Handlers;
/**
 * Created by PhpStorm.
 * User: Khalifah
 * Date: 7/16/14
 * Time: 9:28 AM
 */

class ItemTest extends \PHPUnit_Framework_TestCase
{
	private
		$itemHash,
		$http;

	public function setUp()
	{
		//http://us.battle.net/api/d3/data/item/COGHsoAIEgcIBBXIGEoRHYQRdRUdnWyzFB2qXu51MA04kwNAAFAKYJMD
		$this->itemHash = 'item/COGHsoAIEgcIBBXIGEoRHYQRdRUdnWyzFB2qXu51MA04kwNAAFAKYJMD';
		$this->http = new \Kshabazz\BattleNet\D3\Requestors\Http( 'msuBREAKER#1374' );
	}

	/**
	 * @vcr item-1.yml
	 */
	public function test_retrieving_hero_json()
	{
		$itemHandler = new \Kshabazz\BattleNet\D3\Handlers\Item( $this->itemHash );
		$itemJson = $itemHandler->getJson( $this->http );
		$item = json_decode( $itemJson, TRUE );
		$this->assertEquals( 'MightyWeapon1H_202', $item['id'], 'Could not retrieve item.' );
	}
}
?>