<?php namespace Kshabazz\Tests\BattleNet\D3\Connections;
/**
 * HttpTest test.
 */

use \Kshabazz\BattleNet\D3\Connections\Http,
	\Kshabazz\BattleNet\D3\Models\Item,
	\Kshabazz\BattleNet\D3\Models\Profile,
	\Kshabazz\BattleNet\D3\Models\Hero,
	\Kshabazz\Slib\HttpClient;

/**
 * Class HttpTest
 *
 * @package Kshabazz\Tests\BattleNet\D3
 */
class HttpTest extends \PHPUnit_Framework_TestCase
{
	private
		$apiKey,
		$bnrClient,
		$client,
		$battleNetId,
		$battleNetUrlSafeId,
		$fixturesDir,
		$heroId;

	public function setup()
	{
		$this->battleNetId = 'msuBREAKER#1374';
		$this->battleNetUrlSafeId = 'msuBREAKER-1374';
		$this->client = new HttpClient();
		$this->heroId = 3955832;
		$this->fixturesDir = \FIXTURES_PATH . DIRECTORY_SEPARATOR;
		// Load setting from config.
		$configJson = \file_get_contents(
			TESTS_ROOT
			. DIRECTORY_SEPARATOR . 'config'
			. DIRECTORY_SEPARATOR . 'unit-test.json'
		);
		$config = \json_decode( $configJson );
		$this->apiKey = $config->apiKey;
		$this->bnrClient = new Http( $this->apiKey, $this->battleNetId, $this->client );
	}

	public function test_getting_a_url_safe_battleNet_id()
	{
		$bnIdUrlSafe = $this->bnrClient->battleNetUrlSafeId();
		$this->assertEquals( $this->battleNetUrlSafeId, $bnIdUrlSafe );
	}

	public function test_gettting_battleNet_id()
	{
		$bnr = new Http( $this->apiKey, $this->battleNetId, $this->client );
		$bnIdUrlSafe = $bnr->battleNetId();
		$this->assertEquals( $this->battleNetId, $bnIdUrlSafe, 'Invalid BattelNet ID returned.' );
	}

	/**
	 * @interception 'hero-3955832'
	 */
	public function test_getting_a_hero_from_battle_net()
	{
		$bnr = new Http( $this->apiKey, $this->battleNetId, $this->client );
		$heroJson = $bnr->getHero( $this->heroId );
		$hero = \json_decode( $heroJson );
		$this->assertEquals( $this->heroId, $hero->id, 'Unable to retrieve Hero from Battle.Net' );
	}

	/**
	 * @interception item-MightyWeapon1H_202
	 */
	public function test_getting_a_valid_item()
	{
		$bnr = new Http( $this->apiKey, $this->battleNetId, $this->client );
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
		$bnr = new Http( $this->apiKey, $this->battleNetId, $this->client );
		$bnr->getItem( NULL );
	}

	/**
	 * Test retrieving a profile from Battle.Net
	 *
	 * @interception profile-msuBREAKER-1374
	 */
	public function test_getting_a_profile()
	{
		$bnr = new Http( $this->apiKey, $this->battleNetId, $this->client );
		$profileJson = $bnr->getProfile();
		$profile = new Profile( $profileJson );
		$this->assertEquals( 'msuBREAKER#1374', $profile->battleTag() );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function test_invalid_argument_with_getHero()
	{
		$bnr = new Http( $this->apiKey, $this->battleNetId, $this->client );
		$bnr->getHero( 'test' );
	}

	/**
	 * @interception item-Unique_Helm_006_x1
	 */
	public function test_getItemsAsModels()
	{
		$itemHashes = [
			'head' => [
				'id' => 'Unique_Helm_006_x1',
				'tooltipParams' => 'item/CnIInND3jAQSBwgEFVml7RMdS7X5Sx0_8gnYHTsnbyQdZiMGUB1-dlWhHcn6vKAwiwI4qwFAAFASWARggAJqKwoMCAAQuemrwYCAgKA-EhsIt-yauQYSBwgEFdVdtnowjwI4AEABWASQAQCAAUa1AX_5Tl0YspXtvgJQCFgA'
			]
		];
		$http = new Http( $this->apiKey, $this->battleNetId, $this->client );
		$items = $http->getItemsAsModels( $itemHashes );
		$this->assertEquals( 'Unique_Helm_006_x1', $items['head']->id() );
	}

	/**
	 * @interception item-Unique_Helm_006_x1
	 */
	public function test_url()
	{
		$hash = 'item/CnIInND3jAQSBwgEFVml7RMdS7X5Sx0_8gnYHTsnbyQdZiMGUB1-dlWhHcn6vKAwiwI4qwFAAFASWARggAJqKwoMCAAQuemrwYCAgKA-EhsIt-yauQYSBwgEFdVdtnowjwI4AEABWASQAQCAAUa1AX_5Tl0YspXtvgJQCFgA';
		$http = new Http( $this->apiKey, $this->battleNetId, $this->client );
		$http->getItem( $hash );
		$this->assertContains( $hash, $http->url() );
	}
}
?>