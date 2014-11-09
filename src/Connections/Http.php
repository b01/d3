<?php namespace Kshabazz\BattleNet\D3\Connections;
/**
 * Perform request to BattleNet
 */

use \Kshabazz\BattleNet\D3\Models\Item;

use function \Kshabazz\Slib\isString,
			 \Kshabazz\Slib\isArray;

/**
 * Class Http
 *
 * @package \Kshabazz\BattleNet
 */
class Http implements Connection
{
	const
		/** @const string */
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
		/** @var string */
		$apiKey,
		/** @var string */
		$battleNetId,
		/** @var string */
		$battleNetUrlSafeId,
		/** @var \Kshabazz\Slib\HttpClient */
		$client,
		/** @var string */
		$locale,
		/** @var string */
		$region,
		/** @var string */
		$url;

	/**
	 * Constructor
	 *
	 * @param string $pApiKey
	 * @param string $pBattleNetId
	 * @param \Kshabazz\Slib\HttpClient $pClient
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
		unset(
			$this->client,
			$this->battleNetId,
			$this->battleNetUrlSafeId,
			$this->url
		);
	}

	/**
	 * Get BattleNet ID
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
	 * ex: https://us.api.battle.net/d3/profile/<battleNetIdName>-<battleNetIdNumber>/hero/<hero-id>?locale=<string>&apikey=<>
     * Note: Leave off the trailing '/' when setting
	 *
	 * @param $pHeroId
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
	 * Get item JSON from Battle.Net D3 API.
	 * ex: https://us.battle.net/api/d3/data/item/COGHsoAIEgcIBBXIGEoRHYQRdRUdnWyzFB2qXu51MA04kwNAAFAKYJMD
	 *
	 * @param $pItemId
	 * @return mixed|null
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 */
	public function getItem( $pItemId )
	{
		if ( !isString($pItemId) )
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
	 * This is costly, it make a HTTP request for each item on the hero.
	 *
	 * @param array $pItemHashes List of item hashes.
	 * @return array|null
	 * @throws \InvalidArgumentException
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
	 * ex: https://us.api.battle.net/d3/profile/<battleNetIdName>-<battleNetIdNumber>/
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