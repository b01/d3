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
	*/
	public function __construct( $pBattleNetId = NULL )
	{
		parent::__construct( '' );
		if ( isString($pBattleNetId) )
		{
			$this->setBattleNetId( $pBattleNetId );
		}
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
	* Set BattleNet ID
	*
	* @return string BattleNet ID
	*/
	public function setBattleNetId( $pBattleNetId )
	{
		$this->battleNetId = $pBattleNetId;
		$this->battleNetUrlSafeId = str_replace( '#', '-', $this->battleNetId );
		return $this;
	}

	/**
	* Example:
	* url ::= <host> "/api/d3/data/item/" <item-data>
	* GET /api/d3/data/item/COGHsoAIEgcIBBXIGEoRHYQRdRUdnWyzFB2qXu51MA04kwNAAFAKYJMD
	* Note: Leave off the trailing '/' when setting
	*	/api/d3/profile/<battleNetIdName>-<battleNetIdNumber>
	*/
	public function getHero( $p_heroId )
	{
		$returnValue = NULL;
		if ( isString($p_heroId) )
		{
			$this->url = sprintf( BATTLENET_D3_API_HERO_URL, $this->battleNetUrlSafeId(), $p_heroId );
			$returnValue = $this->send();
		}
		else
		{
			throw new \Exception( "Hero '{$p_heroId}' not found." );
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
	* @param $p_battleNetId string Battle.Net ID with the "#code"
	*/
	public function getItem( $p_itemId )
	{
		$returnValue = NULL;
		if ( isString($p_itemId) )
		{
			$this->url = sprintf( BATTLENET_D3_API_ITEM_URL, $p_itemId );;
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
	* @param $p_battleNetId string Battle.Net ID with the "#code"
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