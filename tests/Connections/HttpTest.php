<?php namespace Kshabazz\Tests\BattleNet\D3;
/**
 * HttpTest test.
 */

use \Kshabazz\BattleNet\D3\Connections\Http,
	\Kshabazz\BattleNet\D3\Models\Item,
	\Kshabazz\BattleNet\D3\Models\Profile,
	\Kshabazz\BattleNet\D3\Models\Hero,
	\Kshabazz\Interception\StreamWrappers\Http as HttpWrapper;

/**
 * Class HttpTest
 *
 * @package Kshabazz\Tests\BattleNet\D3
 */
class HttpTest extends \PHPUnit_Framework_TestCase
{
	private
		$client,
		$battleNetId,
		$battleNetUrlSafeId,
		$fixturesDir,
		$heroId;

	static public function setUpBeforeClass()
	{
		\stream_wrapper_unregister( 'http' );
		HttpWrapper::setSaveDir( FIXTURES_PATH );

		\stream_register_wrapper(
			'http',
			'\\Kshabazz\\Interception\\StreamWrappers\\Http',
			\STREAM_IS_URL
		);
	}

	static public function tearDownAfterClass()
	{
		stream_wrapper_restore( 'http' );
	}

	public function setup()
	{
		$this->battleNetId = 'msuBREAKER#1374';
		$this->battleNetUrlSafeId = 'msuBREAKER-1374';
		$this->client = new \Kshabazz\Slib\Http();
		$this->heroId = 3955832;
		$this->fixturesDir = \FIXTURES_PATH . DIRECTORY_SEPARATOR;
	}

	public function test_getting_a_url_safe_battleNet_id()
	{
		$bnr = new Http( $this->battleNetId, $this->client );
		$bnIdUrlSafe = $bnr->battleNetUrlSafeId();
		$this->assertEquals( $this->battleNetUrlSafeId, $bnIdUrlSafe );
	}

	public function test_gettting_battleNet_id()
	{
		$bnr = new Http( $this->battleNetId, $this->client );
		$bnIdUrlSafe = $bnr->battleNetId();
		$this->assertEquals( $this->battleNetId, $bnIdUrlSafe, 'Invalid BattelNet ID returned.' );
	}

	public function test_getting_a_hero_from_battle_net()
	{
		HttpWrapper::setSaveFilename( 'hero-' . $this->heroId . '.json' );
		$bnr = new Http( $this->battleNetId, $this->client );
		$heroJson = $bnr->getHero( $this->heroId );
		$hero = \json_decode( $heroJson );
		$this->assertEquals( $this->heroId, $hero->id, 'Unable to retrieve Hero from Battle.Net' );
	}

	public function test_getting_a_valid_item()
	{
		HttpWrapper::setSaveFilename( 'item-MightyWeapon1H_202.json' );
		$bnr = new Http( $this->battleNetId, $this->client );
		$itemJson = $bnr->getItem( 'item/COGHsoAIEgcIBBXIGEoRHYQRdRUdnWyzFB2qXu51MA04kwNAAFAKYJMD' );
		$item = new Item( $itemJson );
		$this->assertEquals( 'MightyWeapon1H_202', $item->id(), 'Invalid item returned.' );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage Expects a valid item id, but was given: ''.
	 */
	public function test_getting_a_invalid_item()
	{
		$bnr = new Http( $this->battleNetId, $this->client );
		$bnr->getItem( NULL );
	}

	/**
	 * Test retrieving a profile from Battle.Net
	 */
	public function test_getting_a_profile()
	{
		HttpWrapper::setSaveFilename( 'profile-msuBREAKER#-1374-10-26-2014-23-20.json' );
		$bnr = new Http( $this->battleNetId, $this->client );
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
		$bnr = new Http( $this->battleNetId, $this->client );
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
		HttpWrapper::setSaveFilename( 'item-Unique_Helm_006_x1.json' );
		$http = new Http( $this->battleNetId, $this->client );
		$items = $http->getItemsAsModels( $itemHashes );
		$this->assertEquals( 'Unique_Helm_006_x1', $items['head']->id() );
	}
}
?>