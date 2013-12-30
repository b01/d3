<?php namespace kshabazz\d3a;
/**
* Perform request to BattleNet
*/
class BattleNet_Requestor extends HttpRequestor
{
	protected
		$battleNetId,
		$battleNetUrlSafeId;

	/**
	 * Constructor
	 *
	 * @param null $pBattleNetId
	 */
	public function __construct( $pBattleNetId = NULL )
	{
		parent::__construct( '' );
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
			$this->requestInfo,
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
	 * Note: Leave off the trailing '/' when setting
	 * ex: http://us.battle.net/api/d3/profile/<battleNetIdName>-<battleNetIdNumber>/hero/<hero-id>
	 *
	 * @param $pHeroId
	 * @return null|string
	 * @throws \Exception
	 */
	public function getHero( $pHeroId )
	{
		$returnValue = NULL;
		if ( isString($pHeroId) )
		{
			$this->url = sprintf( BATTLENET_D3_API_HERO_URL, $this->battleNetUrlSafeId, $pHeroId );
			$returnValue = $this->send();
		}
		else
		{
			throw new \Exception( "Hero '{$pHeroId}' not found." );
		}
		return $returnValue;
	}

	/**
	 * Get item JSON from Battle.Net D3 API.
	 * ex: http://us.battle.net/api/d3/data/item/COGHsoAIEgcIBBXIGEoRHYQRdRUdnWyzFB2qXu51MA04kwNAAFAKYJMD
	 *
	 * @param $pItemId
	 * @return null|string
	 * @throws \InvalidArgumentException
	 */
	public function getItem( $pItemId )
	{
		$returnValue = NULL;
		if ( isString($pItemId) )
		{
			$this->url = sprintf( BATTLENET_D3_API_ITEM_URL, $pItemId );;
			// Return the response text.
			$returnValue = $this->send();
		}
		else
		{
			throw new \InvalidArgumentException(
				"Expects a valid item id, but item id given was: '{$pItemId}'."
			);
			// throw new \Exception( "No item found at '{$this->url}'." );
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
			$this->url = BATTLENET_D3_API_PROFILE_URL . '/' . $this->battleNetUrlSafeId . '/';
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