<?php
namespace d3cb; // Diablo 3 Character Builder


class BattleNetDqi
{
	protected 
		$battleNetId,
		$domain,
		$requestInfo,
		$responseText,
		$url;
	
	
	/**
	* Constructor
	*/
	public function __construct( $p_battleNetId )
	{
		$this->battleNetId = $p_battleNetId;
		$this->domain = BATTLENET_D3_API_DOMAIN;
		$this->requestInfo = NULL;
		$this->url = '';
	}
	
	
	/**
	* Destructor
	*/
	public function __destruct()
	{
		unset(
			$this->battleNetId,
			$this->domain,
			$this->requestInfo,
			$this->responseText,
			$this->url
		);
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
		if ( Tool::isString($p_battleNetId) && substr_count($p_battleNetId, '#') === 1 )
		{
		// Replace the pound sign in the BattleNet id with a dash (I assume for safe URL transport).
			$battleNetId = str_replace( '#', '-', $p_battleNetId );
			$this->url = "http://{$this->domain}/profile/{$battleNetId}/";
			// Return the response text.
			$returnValue = $this->send();
		}
		else
		{
			throw new \Exception( "Invalid BattleNet ID given: '{$p_battleNetId}'; here's a correct example: myBattleNetName#1234" );
		}
		return $returnValue;
	}
	
	/**
	* Get the URL of the request.
	*/
	public function getUrl()
	{
		return $this->url;
	}
	
	/**
	* Get the HTTP response code of the request.
	* @return int HTTP response code.
	*/
	public function responseCode()
	{
		if ( Tool::isArray($this->requestInfo) && array_key_exists("http_code", $this->requestInfo) )
		{
			return $this->requestInfo[ "http_code" ];
		}
		return NULL;
	}
	
	/**
	* Send an HTTP request
	* @return string HTTP response.
	*/
	protected function send( $body = NULL )
	{
		//
		$returnValue = NULL;
		if ( !empty($this->url) )
		{
			$curl = \curl_init();
			\curl_setopt( $curl, CURLOPT_URL, $this->url );
			\curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
			if ( !empty($body) )
			{
				\curl_setopt( $curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8") );

				\curl_setopt( $curl, CURLOPT_POST, 1 );
				\curl_setopt( $curl, CURLOPT_POSTFIELDS, $body );
			}
			// Send the request and get a response.
			$responseText = \curl_exec( $curl );
			// get the status of the call
			$this->requestInfo = curl_getinfo( $curl );
			\curl_close( $curl );
			if ( !empty($responseText) )
			{
				$returnValue = $responseText;
			}
		}
		else
		{
			// Log an error.
		}
		return $returnValue;
	}
	
	/**
	* Set page of search results to retieve.
	*/
	public function setPage( $p_page, $p_pageSize )
	{
		if ( is_numeric($p_page) && is_numeric($p_pageSize) )
		{
			$this->queryParameters['page'] = $p_page;
			$this->queryParameters['pageSize'] = $p_pageSize;
		}
	}
}
?>