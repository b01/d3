<?php namespace Kshabazz\BattleNet\D3\Connections;
/**
 * Perform request to BattleNet
 */

use Kshabazz\BattleNet\D3\Models\Item;

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
		D3_API_PROFILE_URL = 'http://us.battle.net/api/d3/profile',
		/** @const string */
		D3_API_HERO_URL = 'http://us.battle.net/api/d3/profile/%s/hero/%d',
		/** @const string */
		D3_API_ITEM_URL = 'http://us.battle.net/api/d3/data/%s';

	private
		/** @var string */
		$battleNetId,
		/** @var string */
		$battleNetUrlSafeId,
		/** @var object */
		$client,
		/** @var string */
		$url;

	/**
	 * Constructor
	 *
	 * @param string $pBattleNetId
	 */
	public function __construct( $pBattleNetId, $pClient )
	{
		$this->client = $pClient;
		$this->battleNetId = $pBattleNetId;
		$this->battleNetUrlSafeId = \str_replace( '#', '-', $this->battleNetId );
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
	 * ex: http://us.battle.net/api/d3/profile/<battleNetIdName>-<battleNetIdNumber>/hero/<hero-id>
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
		$url = \sprintf( self::D3_API_HERO_URL, $this->battleNetUrlSafeId, $pHeroId );
		// Request the hero JSON from BattleNet.
		return $this->makeRequest( $url );
	}

	/**
	 * Get item JSON from Battle.Net D3 API.
	 * ex: http://us.battle.net/api/d3/data/item/COGHsoAIEgcIBBXIGEoRHYQRdRUdnWyzFB2qXu51MA04kwNAAFAKYJMD
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
		$url = \sprintf( self::D3_API_ITEM_URL, $pItemId );
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
	 * ex: http://us.battle.net/api/d3/profile/<battleNetIdName>-<battleNetIdNumber>/
	 *
	 * @return null|string
	 * @throws \Exception
	 */
	public function getProfile()
	{
		// Construct the Battle.net URL.
		$url = self::D3_API_PROFILE_URL . '/' . $this->battleNetUrlSafeId . '/';
		// Return the response text.
		return $this->makeRequest( $url );
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