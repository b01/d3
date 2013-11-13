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
	public function getBattleNetId()
	{
		return $this->battleNetId;
	}

	/**
	 * Example:
	 * url ::= <host> "/api/d3/data/item/" <item-data>
	 * GET /api/d3/data/item/COGHsoAIEgcIBBXIGEoRHYQRdRUdnWyzFB2qXu51MA04kwNAAFAKYJMD
	 * Note: Leave off the trailing '/' when setting
	 *	/api/d3/profile/<battleNetIdName>-<battleNetIdNumber>
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
	 * Example:
	 * url ::= <host> "/api/d3/data/item/" <item-data>
	 * GET /api/d3/data/item/COGHsoAIEgcIBBXIGEoRHYQRdRUdnWyzFB2qXu51MA04kwNAAFAKYJMD
	 * Host: us.battle.net
	 * Note: Leave off the trailing '/' when setting
	 *	/api/d3/profile/<battleNetIdName>-<battleNetIdNumber>
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
				"Expects an valid item id as a string, item id given was: '{$this->url}'."
			);
			// throw new \Exception( "No item found at '{$this->url}'." );
		}
		return $returnValue;
	}

	/**
	 * Example:
	 * battletag-name ::= <regional battletag allowed characters>
	 * battletag-code ::= <integer>
	 * url ::= <host> "/api/d3/profile/" <battletag-name> "-" <battletag-code> "/"
	 * Note: Add the trailing '/' when setting
	 *	/api/d3/profile/<battleNetIdName>-<battleNetIdNumber>/
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