<?php namespace Kshabazz\Tests\BattleNet\D3;
/**
 * HttpTest test.
 */

use \Kshabazz\BattleNet\D3\Connections\Http,
	\Kshabazz\BattleNet\D3\Models\Item,
	\Kshabazz\BattleNet\D3\Models\Profile,
	\Kshabazz\BattleNet\D3\Models\Hero;

/**
 * Class HttpTest
 *
 * @package Kshabazz\Tests\BattleNet\D3
 */
class HttpTest extends \PHPUnit_Framework_TestCase
{
	private
		$battleNetId,
		$battleNetUrlSafeId,
		$fixturesDir,
		$heroId;

	public function setup()
	{
		$this->battleNetId = 'msuBREAKER#1374';
		$this->battleNetUrlSafeId = 'msuBREAKER-1374';
		$this->heroId = 46026639;
		$this->fixturesDir = \FIXTURES_PATH . DIRECTORY_SEPARATOR;
	}

	public function test_getting_a_url_safe_battleNet_id()
	{
		$bnr = new Http( $this->battleNetId );
		$bnIdUrlSafe = $bnr->battleNetUrlSafeId();
		$this->assertEquals( $this->battleNetUrlSafeId, $bnIdUrlSafe );
	}

	/**
	* Get BattleNet ID
	*
	* @return string BattleNet ID
	*/
	public function test_gettting_battleNet_id()
	{
		$bnr = new Http( $this->battleNetId );
		$bnIdUrlSafe = $bnr->battleNetId();
		$this->assertEquals( $this->battleNetId, $bnIdUrlSafe, 'Invalid BattelNet ID returned.' );
	}

	public function test_getting_a_hero_from_battle_net()
	{
		$bnr = new Http( $this->battleNetId );
		$heroJson = $bnr->getHero( $this->heroId );
		$hero = \json_decode( $heroJson );
		$this->assertEquals( $this->heroId, $hero->id, 'Unable to retrieve Hero from Battle.Net' );
	}

	/**
	 * Test retrieving a valid item from Battle.Net
	 */
	public function test_getting_a_valid_item()
	{
		$bnr = new Http( $this->battleNetId );
		$itemJson = $bnr->getItem( 'item/COGHsoAIEgcIBBXIGEoRHYQRdRUdnWyzFB2qXu51MA04kwNAAFAKYJMD' );
		$item = new Item( $itemJson );
		$this->assertEquals( 'MightyWeapon1H_202', $item->id(), 'Invalid item returned.' );
	}

	/**
	 * Test retrieving an invalid item from Battle.Net
	 *
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage Expects a valid item id, but was given: ''.
	 */
	public function test_getting_a_invalid_item()
	{
		$bnr = new Http( $this->battleNetId );
		$bnr->getItem( NULL );
	}

	/**
	 * Test retrieving a profile from Battle.Net
	 */
	public function test_getting_a_profile()
	{
		$bnr = new Http( $this->battleNetId );
		$profileJson = $bnr->getProfile();
		$profile = new Profile( $profileJson );
		$this->assertEquals(
			'msuBREAKER#1374',
			$profile->get( 'battleTag' ),
			'BattleNet_Requestor return an invalid profile.'
		);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function test_invalid_argument_with_getHero()
	{
		$bnr = new Http( $this->battleNetId );
		$bnr->getHero( 'test' );
	}

	public function test_getItemsAsModels()
	{
		$itemHashes = [
			'head' => [
				'id' => 'Unique_Helm_006_x1',
				'tooltipParams' => 'item/CnIInND3jAQSBwgEFVml7RMdS7X5Sx0_8gnYHTsnbyQdZiMGUB1-dlWhHcn6vKAwiwI4qwFAAFASWARggAJqKwoMCAAQuemrwYCAgKA-EhsIt-yauQYSBwgEFdVdtnowjwI4AEABWASQAQCAAUa1AX_5Tl0YspXtvgJQCFgA'
		    ]
		];
		$itemJson = \file_get_contents( FIXTURES_PATH . 'item-hash-1.json' );
		$mockHttp = $this->getMock(
			'\\Kshabazz\\BattleNet\\D3\\Connections\\Http',
			[ 'getItem' ],
			[ 'msuBREAKER#1374' ]
		);
		$mockHttp->expects( $this->exactly(1) )
			->method( 'getItem' )
			->willReturn( $itemJson );

		$mockHttp->getItemsAsModels( $itemHashes );
	}
}
?>