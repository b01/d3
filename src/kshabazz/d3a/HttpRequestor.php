<?php namespace kshabazz\d3a;
/**
 * Generic methods for retrieving HTML pages.
 *
 */

use \Kshabazz\Slib;

/**
 * Class HttpRequestor
 * @package kshabazz\d3a
 */
class HttpRequestor
{
	protected
		$options,
		$requestInfo,
		$responseText,
		$url;

	/**
	* Constructor
	*/
	public function __construct( $pUrl, array $pOptions = NULL )
	{
		$this->requestInfo = NULL;
		$this->responseText = NULL;
		$this->url = $pUrl;
		$this->options = ( $pOptions !== NULL ) ? $pOptions : [ "Content-Type: application/json; charset=utf-8" ];
	}

	/**
	* Destructor
	*/
	public function __destruct()
	{
		unset(
			$this->options,
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
			throw new Exception( "There was a problem getting a response from '{$p_url}'" );
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
	 * Process any headers passed into the request.
	 *
	 * @parm \curl $pCurl
	 * @return bool
	 */
	protected function processHeaders( $pCurl )
	{
		if ( isArray($this->options) && $pCurl !== NULL )
		{
			curl_setopt_array( $pCurl, $this->options );
		}
		return TRUE;
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
			$curl = curl_init( $this->url );
			\curl_setopt( $curl, \CURLOPT_RETURNTRANSFER, TRUE );
			if ( !empty($body) )
			{
				\curl_setopt( $curl, \CURLOPT_HTTPHEADER, ["Content-Type: application/json; charset=utf-8"] );
				\curl_setopt( $curl, \CURLOPT_POST, TRUE );
				\curl_setopt( $curl, \CURLOPT_POSTFIELDS, $body );
			}
			$this->processHeaders( $curl );
			// Send the request and get a response.
			$responseText = \curl_exec( $curl );
			// get the status of the call
			$this->requestInfo = \curl_getinfo( $curl );
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