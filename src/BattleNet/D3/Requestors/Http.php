<?php namespace Kshabazz\BattleNet\D3\Requestors;
/**
* Perform request to BattleNet
*/
use function \Kshabazz\Slib\isString,
			 \Kshabazz\Slib\isArray;
/**
 * Class Http
 *
 * @package Kshabazz\BattleNet
 */
class Http extends \Kshabazz\Slib\HttpRequester implements Requestor
{
	const
		D3_API_PROFILE_URL = 'http://us.battle.net/api/d3/profile',
		D3_API_HERO_URL = 'http://us.battle.net/api/d3/profile/%s/hero/%d',
		D3_API_ITEM_URL = 'http://us.battle.net/api/d3/data/%s';

	private
		$battleNetId,
		$battleNetUrlSafeId;

	/**
	 * Constructor
	 *
	 * @param string $pBattleNetId
	 */
	public function __construct( $pBattleNetId )
	{
		parent::__construct( NULL );
		$this->battleNetId = $pBattleNetId;
		$this->battleNetUrlSafeId = str_replace( '#', '-', $this->battleNetId );
	}

	/**
	 * Destructor
	 */
	public function __destruct()
	{
		unset(
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
		$returnValue = NULL;
		// todo: validate with regex.
		if ( !isString($pHeroId) )
		{
			throw new \InvalidArgumentException( "Hero '{$pHeroId}' not found." );
		}
		$this->url = sprintf( self::D3_API_HERO_URL, $this->battleNetUrlSafeId, $pHeroId );
		$returnValue = $this->send();
		return $returnValue;
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
		$returnValue = NULL;
		if ( !isString($pItemId) )
		{
			throw new \InvalidArgumentException(
				"Expects a valid item id, but was given: '{$pItemId}'."
			);
		}
		try
		{
			// retrieve the item JSON from the at the constructed URL
			$this->url = sprintf( self::D3_API_ITEM_URL, $pItemId );
			// Return the response text.
			$returnValue = $this->send();
		}
		catch( \Exception $pError )
		{
			throw new \Exception( "An error occurred trying to retrieve the item at '{$this->url}'." );
		}
		return $returnValue;
	}

	/**
	 * ex: http://us.battle.net/api/d3/profile/<battleNetIdName>-<battleNetIdNumber>/
	 *
	 * @return null|string
	 * @throws \Exception
	 */
	public function getProfile()
	{
		$returnValue = NULL;
		try
		{
			$this->url = self::D3_API_PROFILE_URL . '/' . $this->battleNetUrlSafeId . '/';
			// Return the response text.
			$returnValue = $this->send();
		}
		catch ( \Exception $p_error )
		{
			throw new \Exception( "No profile found at '{$this->url}'." );
		}
		return $returnValue;
	}
}
?>