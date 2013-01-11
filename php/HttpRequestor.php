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
	* Shortcut: Set the URL and returns a response.
	* @param string $p_url
	* @return string HTTP response.
	*/
	public function get( $p_url )
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
			throw new \Exception( "There was a problem getting a response from '{$p_url}'" );
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