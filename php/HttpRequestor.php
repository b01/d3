<?php
namespace d3; // Diablo 3 Character Builder

class HttpRequestor
{
	protected
		$requestInfo,
		$responseText,
		$url;

	/**
	* Constructor
	*/
	public function __construct( $p_url )
	{
		$this->requestInfo = NULL;
		$this->responseText = NULL;
		$this->url = $p_url;
	}

	/**
	* Destructor
	*/
	public function __destruct()
	{
		unset(
			$this->requestInfo,
			$this->responseText,
			$this->url
		);
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
	public function getJson( $p_url )
	{
		$returnValue = NULL;
		if ( isString($p_url) )
		{
			$this->url = $p_url;
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
		if ( isArray($this->requestInfo) && array_key_exists("http_code", $this->requestInfo) )
		{
			return $this->requestInfo[ "http_code" ];
		}
		return NULL;
	}
	
	/**
	* Send an HTTP request
	* @return string HTTP response.
	*/
	public function send( $body = NULL )
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
	* Set the URL of the request.
	*/
	public function setUrl( $p_url )
	{
		$this->url = $p_url;
	}
}
?>