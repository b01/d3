<?php namespace Kshabazz\BattleNet\D3\Connections;
/**
 * Perform request to BattleNet
 */

use
	\Kshabazz\BattleNet\D3\Models\Item;

/**
 * Class Http
 *
 * @package \Kshabazz\BattleNet
 */
class Http implements Connection
{
	const
		/** @const string URL for obtaining a profile from Battle.net */
		API_PROFILE_URL = 'https://%s.api.battle.net/d3/profile/%s/?locale=%s&apikey=%s',
		/** @const string */
		API_HERO_URL = 'https://%s.api.battle.net/d3/profile/%s/hero/%d?locale=%s&apikey=%s',
		/** @const string */
		API_ITEM_URL = 'https://%s.api.battle.net/d3/data/%s?locale=%s&apikey=%s',
		/** @const string */
		AUTHORIZE_URI = 'https://%s.battle.net/oauth/authorize',
		/** @const string */
		TOKEN_URI = 'https://%s.battle.net/oauth/token';

	private
		/** @var string Key obtained for use with Diablo 3 REST service. */
		$apiKey,
		/** @var string A BattleNet ID.*/
		$battleNetId,
		/** @var string BattleNet ID with pound replace with dash. */
		$battleNetUrlSafeId,
		/** @var \Kshabazz\Slib\HttpClient Client for making HTTP request. */
		$client,
		/** @var string Language locale. */
		$locale,
		/** @var string World region. */
		$region,
		/** @var string Last URL request. */
		$url;

	/**
	 * Constructor
	 *
	 * @param string $pApiKey Key obtained for use with Diablo 3 REST service.
	 * @param string $pBattleNetId BattleNet ID.
	 * @param \Kshabazz\Slib\HttpClient $pClient Client for making HTTP request.
	 * @param string $pLocale
	 */
	public function __construct( $pApiKey , $pBattleNetId, $pClient, $pLocale = 'en_US' )
	{
		$this->apiKey = $pApiKey;
		$this->client = $pClient;
		$this->battleNetId = $pBattleNetId;
		$this->battleNetUrlSafeId = \str_replace( '#', '-', $this->battleNetId );
		$this->locale = $pLocale;
		$this->region = 'us';
		$this->url = NULL;
	}

	/**
	 * Destructor
	 */
	public function __destruct()
	{
		unset( $this->client );
		unset( $this->battleNetId );
		unset( $this->battleNetUrlSafeId );
		unset( $this->url );
	}

	/**
	 * Get BattleNet ID with the pound symbol replaced with a dash.
	 *
	 * @return string BattleNet ID
	 */
	public function battleNetUrlSafeId()
	{
		return $this->battleNetUrlSafeId;
	}

	/**
	 * Get BattleNet ID
	 *
	 * @return string BattleNet ID
	 */
	public function battleNetId()
	{
		return $this->battleNetId;
	}

	/**
     * Request Hero JSON from Battle.Net.
	 *
	 * <code>
	 * <?php
	 * // Make a request to:
	 * // https://us.api.battle.net/d3/profile/<battleNetIdName>-<battleNetIdNumber>/hero/<hero-id>?locale=<string>&apikey=<>
     * // Note: Leave off the trailing '/' when setting
	 * ?>
	 * </code>
	 *
	 * @param int $pHeroId Hero ID.
	 * @return null|string
	 * @throws \InvalidArgumentException
	 */
	public function getHero( $pHeroId )
	{
		if ( !\is_int($pHeroId) )
		{
			throw new \InvalidArgumentException( 'Expected an integer, got a '. \gettype($pHeroId) );
		}
		// Construct the Battle.net URL.
		$url = \sprintf(
			self::API_HERO_URL,
			$this->region,
			$this->battleNetUrlSafeId,
			$pHeroId,
			$this->locale,
			$this->apiKey
		);
		// Request the hero JSON from BattleNet.
		return $this->makeRequest( $url );
	}

	/**
	 * Make a request to the API to get an item (JSON).
	 *
	 * <code>
	 * // Make a request to:
	 * // https://us.battle.net/api/d3/data/item/COGHsoAIEgcIBBXIGEoRHYQRdRUdnWyzFB2qXu51MA04kwNAAFAKYJMD
	 * </code>
	 *
	 * @param string $pItemId Can be obtained from items a hero has equipped.
	 * @return string|null API JSON data.
	 * @throws \InvalidArgumentException
	 */
	public function getItem( $pItemId )
	{
		if ( !\is_string($pItemId) || \strlen($pItemId) < 1 )
		{
			throw new \InvalidArgumentException(
				"Expects a valid item id, but was given: '{$pItemId}'."
			);
		}
		// Construct the Battle.net URL.
		$url = \sprintf(
			self::API_ITEM_URL,
			$this->region,
			$pItemId,
			$this->locale,
			$this->apiKey
		);
		return $this->makeRequest( $url );
	}

	/**
	 * For each item the hero has equipped construct an Model\Item and return them as an array.
	 * This is costly, it makes an HTTP request for each item in the list.
	 *
	 * @param array $pItemHashes List of item hash IDs.
	 * @return array|null Item models
	 */
	public function getItemsAsModels( array $pItemHashes )
	{
		$itemModels = NULL;

		// It is valid that the hero may not have any items equipped (new character).
		foreach ( $pItemHashes as $slot => $item )
		{
			$hash = $item[ 'tooltipParams' ];
			$itemJson = $this->getItem( $hash );
			$itemModels[ $slot ] = new Item( $itemJson );
		}

		return $itemModels;
	}

	/**
	 * Get a profile from Battle.net.
	 * <code>
	 * // Makes a request to: https://us.api.battle.net/d3/profile/<battleNetIdName>-<battleNetIdNumber>/
	 *
	 * </code>
	 *
	 * @return null|string
	 * @throws \Exception
	 */
	public function getProfile()
	{
		// Construct the Battle.net URL.
		$url = sprintf(
			self::API_PROFILE_URL,
			$this->region,
			$this->battleNetUrlSafeId,
			$this->locale,
			$this->apiKey
		);
		// Return the response text.
		return $this->makeRequest( $url );
	}

	/**
	 * Set the region.
	 *
	 * @param $pRegion
	 * @return string
	 */
	public function setRegion( $pRegion )
	{
		$this->region = $pRegion;
		return $this->region;
	}

	/**
	 * @return string
	 */
	public function url()
	{
		return $this->url;
	}

	/**
	 * Make a request to the currently set {@see $this->url}.
	 *
	 * @param string $pUrl
	 * @return string|null
	 * @throws \Exception
	 */
	private function makeRequest( $pUrl )
	{
		$this->url = $pUrl;
		// Request the item from BattleNet.
		$this->client->send( $this->url );
		// When the response is good, return the response text.
		$requestSuccessful = $this->client->responseCode() === 200;
		if ( $requestSuccessful )
		{
			return $this->client->responseBody();
		}
		return NULL;
	}
}
?>