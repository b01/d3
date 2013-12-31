<?php namespace kshabazz\d3a\Model;

/**
 * Diablo 3 Assistant License is under The MIT License (MIT) [OSI Approved License]
 * Please read LICENSE.txt, included with this software for the full licensing information.
 * If no LICENSE.txt accompanied this software, then no license is granted.
 * @package kshabazz\d3a\Model
 *
 * @copyright (c) 2012-2013 diablo-3-assistant by Khalifah K. Shabazz
 * timestamp: 12/29/13:11:52 PM
 */
/**
 * Class Profile
 * @package kshabazz\d3a\Model
 */
class Profile implements \JsonSerializable
{
	protected
		$data,
		$heroes,
		$json;

	public function __construct( $pJson )
	{
		$this->json = $pJson;
		$this->init();
	}

	/**
	 * Get property
	 */
	public function get( $pProperty, $pType = 'string' )
	{
		if ( isset($this->$pProperty) )
		{
			return $this->$pProperty;
		}

		if ( array_key_exists($pProperty, $this->data) )
		{
			$value = $this->data[ $pProperty ];
			if ( setType($value, $pType) )
			{
				return $this->$pProperty = $value;
			}
		}

		$trace = debug_backtrace();
		trigger_error(
			'Undefined property: ' . $pProperty .
			' in ' . $trace[0]['file'] .
			' on line ' . $trace[0]['line'],
			E_USER_NOTICE
		);
	}

	/**
	 * Get Hero(s) data.
	 *
	 * @param $pHeroByName string Optional name to specify a single hero to return.
	 * @return mixed Heroes(s) data as an array, or null if none.
	 */
	public function heroes( $pHeroByName = NULL )
	{
		$returnValue = NULL;
		if ( \kshabazz\d3a\isArray($this->heroes) )
		{
			if ( $pHeroByName !== NULL && array_key_exists($pHeroByName, $this->heroes) )
			{
				$returnValue = $this->heroes[ $pHeroByName ];
			}
			else
			{
				$returnValue = $this->heroes;
			}
		}

		return $returnValue;
	}

	/**
	 * Initialize all the properties for this object.
	 *
	 * @return $this
	 */
	protected function init()
	{
		$jsonArray = json_decode( $this->json, TRUE );
		if ( \kshabazz\d3a\isArray($jsonArray) )
		{
			$this->data = $jsonArray;
		}
		else
		{
			$exception = new \Exception( "Tried to initialize ItemModel with invalid JSON." );
			logError(
				$exception,
				"Tried to initialize ItemModel with invalid JSON.",
				"An application error has occured. Please try again later"
			);
		}

		return $this;
	}

	/**
	 * Get raw JSON data returned from Battle.net.
	 */
	public function json()
	{
		return $this->json;
	}

	/**
	 * Specify how this object is to be used with json_encode.
	 * @return array
	 */
	public function jsonSerialize()
	{
		$returnValue = [];
		foreach ( $this as $property => $value )
		{
			if ( array_key_exists($property, $this->forcePropertyType) )
			{
				$returnValue[ $property ] = [ gettype($value), $value ];
			}
			else
			{
				$returnValue[ $property ] = [ gettype($value), $value ];
			}
		}
		return $returnValue;
	}
}
// Writing below this line can cause headers to be sent before intended
?>