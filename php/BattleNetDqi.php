<?php
namespace d3; // Diablo 3 Character Builder

class BattleNetDqi extends HttpRequestor
{
	protected 
		$battleNetId,
		$battleNetUrlSafeId;
	
	/**
	* Constructor
	*/
	public function __construct( $p_battleNetId )
	{
		$this->battleNetId = $p_battleNetId;
		$this->requestInfo = NULL;
		$this->url = '';
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
			$this->responseText,
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
			throw new \Exception( "Invalid item ID (hash) given: '{$p_itemId}'; here's a correct example: COGHsoAIEgcIBBXIGEoRHYQRdRUdnWyzFB2qXu51MA04kwNAAFAKYJMD" );
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
	public function getProfile( $p_battleNetId )
	{
		$returnValue = NULL;
		if ( isString($p_battleNetId) && substr_count($p_battleNetId, '#') === 1 )
		{
			// Replace the pound sign in the BattleNet id with a dash (I assume for safe URL transport).
			$battleNetId = str_replace( '#', '-', $p_battleNetId );
			$this->url = sprintf( BATTLENET_D3_API_PROFILE_URL, $p_battleNetId );
			// Return the response text.
			$returnValue = $this->send();
		}
		else
		{
			throw new \Exception( "Invalid BattleNet ID given: '{$p_battleNetId}'; here's a correct example: myBattleNetName#1234" );
		}
		return $returnValue;
	}
}
?>