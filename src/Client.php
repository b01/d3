<?php namespace Kshabazz\BattleNet\D3;
/**
 * Reduces line of code to get Battle.Net data.
 */

use Kshabazz\BattleNet\D3\Connections\Http;
use Kshabazz\Slib\HttpClient;

/**
 * Class Stream
 *
 * @package Kshabazz\BattleNet\D3
 */
class Client
{
	/**
	 * @var string Mashery D3 AAPI key.
	 */
	private $apiKey;

	/**
	 * @var string
	 */
	private $battleNetTag;


	/**
	 * Get a Diablo 3 Http client.
	 *
	 * @param $pApiKey
	 * @param $pBattleNetTag
	 * @return \Kshabazz\BattleNet\D3\Connections\Http
	 */
	static public function getD3_Client( $pApiKey, $pBattleNetTag )
	{
		// Get an HTTP client.
		$httpClient = new HttpClient();

		// Initialize a Diablo 3 battle.net HTTP client.
		return $d3Client = new Http( $pApiKey, $pBattleNetTag, $httpClient );
	}

	/**
	 * I need a hero!
	 *
	 * @param $pApiKey
	 * @param $pBattleNetTag
	 * @param $pHeroId
	 * @return \Kshabazz\BattleNet\D3\Hero
	 */
	static public function heroFactory( $pApiKey, $pBattleNetTag, $pHeroId )
	{
		$d3Client = static::getD3_Client( $pApiKey, $pBattleNetTag, new HttpClient() );

		// Get the Diablo 3 Hero (this will be the raw JSON).
		$json = $d3Client->getHero( $pHeroId );

		// Pass the hero JSON into a Hero model for accessing common properties.
		return new Hero( $json );
	}

	/**
	 * Get an item from battle.net.
	 *
	 * @param $pApiKey
	 * @param $pBattleNetTag
	 * @param $pItemHash
	 * @return \Kshabazz\BattleNet\D3\Item
	 */
	static public function itemFactory( $pApiKey, $pBattleNetTag, $pItemHash )
	{
		// Get an HTTP client.
		$httpClient = new HttpClient();

		// Initialize a Diablo 3 battle.net HTTP client.
		$bnClient = new Http( $pApiKey, $pBattleNetTag, $httpClient );

		// Get the item from Battle.net.
		$itemJson = $bnClient->getItem( $pItemHash );

		// Put the JSON into a more usable state.
		return new Item( $itemJson );
	}

	/**
	 * Get a hero profile from Battle.Net.
	 *
	 * @param string $pApiKey
	 * @param string $pBattleNetTag
	 * @return \Kshabazz\BattleNet\D3\Profile
	 */
	static public function profileFactory( $pApiKey, $pBattleNetTag )
	{
		// Get an HTTP client, currently only my custom HTTP client works.
		$httpClient = new HttpClient();

		// Initialize a battle.net HTTP client.
		$bnClient = new Http( $pApiKey, $pBattleNetTag, $httpClient );

		// Get the profile for the Battle.net tag (this will be the raw JSON).
		$profileJson = $bnClient->getProfile();

		// Pass the profile JSON into a Profile model for accessing common properties.
		return new Profile( $profileJson );
	}

	/**
	 * Constructor
	 *
	 * @param $pApiKey
	 * @param $pBattleNetTag
	 */
	public function __construct( $pApiKey, $pBattleNetTag )
	{
		$this->apiKey = $pApiKey;
		$this->battleNetTag = $pBattleNetTag;
	}

	/**
	 * Get a hero profile from Battle.Net.
	 *
	 * @return \Kshabazz\BattleNet\D3\Profile
	 */
	public function getProfile()
	{
		// Pass the profile JSON into a Profile model for accessing common properties.
		return self::profileFactory( $this->apiKey, $this->battleNetTag );
	}

	/**
	 * Get a hero from Battle.Net.
	 *
	 * @param int|string Hero ID
	 * @return \Kshabazz\BattleNet\D3\Hero
	 */
	public function getHero( $pHeroId )
	{
		// Pass the profile JSON into a Profile model for accessing common properties.
		return self::heroFactory( $this->apiKey, $this->battleNetTag, $pHeroId );
	}

	/**
	 * Get an item from Battle.Net.
	 *
	 * @param string $pItemHash
	 * @return \Kshabazz\BattleNet\D3\Item
	 */
	public function getItem( $pItemHash )
	{
		// Pass the profile JSON into a Profile model for accessing common properties.
		return self::itemFactory( $this->apiKey, $this->battleNetTag, $pItemHash );
	}
}
?>