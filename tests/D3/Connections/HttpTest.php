<?php namespace Kshabazz\Tests\BattleNet\D3\Connections;
/**
 * HttpTest test.
 */

use \Kshabazz\BattleNet\D3\Connections\Http,
	\Kshabazz\Slib\HttpClient;

/**
 * Class HttpTest
 *
 * @package \Kshabazz\Tests\BattleNet\D3
 * @coversDefaultClass \Kshabazz\BattleNet\D3\Connections\Http
 */
class HttpTest extends \PHPUnit_Framework_TestCase
{
	private
		$apiKey,
		$battleNetId,
		$battleNetUrlSafeId,
		$fixturesDir,
		$heroId;

	public function setup()
	{
		$this->battleNetId = 'msuBREAKER#1374';
		$this->battleNetUrlSafeId = 'msuBREAKER-1374';
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
	}

	public function test_getting_a_url_safe_battleNet_id()
	{
		$httpClient = new HttpClient();
		$bnrClient = new Http( $this->apiKey, $this->battleNetId, $httpClient );
		$bnIdUrlSafe = $bnrClient->battleNetUrlSafeId();
		$this->assertEquals( $this->battleNetUrlSafeId, $bnIdUrlSafe );
	}

	public function test_gettting_battleNet_id()
	{
		$httpClient = new HttpClient();
		$bnr = new Http( $this->apiKey, $this->battleNetId, $httpClient );
		$bnIdUrlSafe = $bnr->battleNetId();
		$this->assertEquals( $this->battleNetId, $bnIdUrlSafe, 'Invalid BattelNet ID returned.' );
	}

	/**
	 * @interception hero-3955832
	 */
	public function test_getting_a_hero_from_battle_net()
	{
		$httpClient = new HttpClient();
		$bnr = new Http( $this->apiKey, $this->battleNetId, $httpClient );
		$heroJson = $bnr->getHero( $this->heroId );
		$hero = \json_decode( $heroJson );
		$this->assertEquals( $this->heroId, $hero->id, 'Unable to retrieve Hero from Battle.Net' );
	}

	/**
	 * @interception item-MightyWeapon1H_202
	 */
	public function test_getting_a_valid_item()
	{
		$httpClient = new HttpClient();
		$bnr = new Http( $this->apiKey, $this->battleNetId, $httpClient );
		$itemJson = $bnr->getItem( 'item/COGHsoAIEgcIBBXIGEoRHYQRdRUdnWyzFB2qXu51MA04kwNAAFAKYJMD' );
		$item =\json_decode( $itemJson );
		$this->assertEquals( 'MightyWeapon1H_202', $item->id, 'Invalid item returned.' );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage Expects a valid item id, but was given: ''.
	 */
	public function test_getting_a_invalid_item()
	{
		$httpClient = new HttpClient();
		$bnr = new Http( $this->apiKey, $this->battleNetId, $httpClient );
		$bnr->getItem( NULL );
	}

	/**
	 * @interception profile-msuBREAKER-1374
	 */
	public function test_getting_a_profile()
	{
		$httpClient = new HttpClient();
		$bnr = new Http( $this->apiKey, $this->battleNetId, $httpClient );
		$profileJson = $bnr->getProfile();
		$profile = \json_decode( $profileJson );
		$this->assertEquals( 'msuBREAKER#1374', $profile->battleTag );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function test_invalid_argument_with_getHero()
	{
		$httpClient = new HttpClient();
		$bnr = new Http( $this->apiKey, $this->battleNetId, $httpClient );
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
		$httpClient = new HttpClient();
		$http = new Http( $this->apiKey, $this->battleNetId, $httpClient );
		$items = $http->getItemsAsModels( $itemHashes );
		$this->assertEquals( 'Unique_Helm_006_x1', $items['head']->id() );
	}

	/**
	 * @interception profile-msuBREAKER-1374
	 */
	public function test_url()
	{
		$httpClient = new HttpClient();
		$bnrClient = new Http( $this->apiKey, $this->battleNetId, $httpClient );
		$bnrClient->getProfile();
		$this->assertContains( $this->battleNetUrlSafeId, $bnrClient->url() );
	}

	/**
	 * @interception profile-msuBREAKER-1374
	 */
	public function test_setting_the_region()
	{
		$httpClient = new HttpClient();
		$bnrClient = new Http( $this->apiKey, $this->battleNetId, $httpClient );
		$bnrClient->setRegion( 'uk' );
		$bnrClient->getProfile();
		$this->assertContains( 'uk', $bnrClient->url() );
	}

	/**
	 * @interception response-404
	 */
	public function test_http_status_code_not_200()
	{
		$httpClient = new HttpClient();
		$bnrClient = new Http( $this->apiKey, $this->battleNetId, $httpClient );
		$actual = $bnrClient->getItem( '4321' );
		$this->assertNull( $actual );
	}
}
?>