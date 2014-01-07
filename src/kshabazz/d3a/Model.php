<?php namespace kshabazz\d3a;
/**
* Get the users item from Battle.Net and present it to the user; store it locally in a database
* behind the scenes. The item will only be updated after a few ours of retrieving it.
*
*/

/**
* var $p_itemHash string User BattleNet ID.
* var $pDqi object Data Query Interface.
* var $pSql object SQL.
* var $p_userIp string User IP address.
*/
abstract class Model implements \JsonSerializable
{
	use Shared;
	protected
		$effectsMap,
		$forcePropertyType,
		$json;

	/**
	* Constructor
	*/
	public function __construct( $p_json )
	{
		$this->json = $p_json;
		$this->forcePropertyType = [
			"attributes" => "array",
			"attributesRaw" => "array",
			"bonusAffixes" => "int",
			"displayColor" => "string",
			"flavorText" => "string", // optional
			"gems" => "array",
			"id" => "string",
			"icon" => "string",
			"itemLevel" => "int",
			"name" => "string",
			"requiredLevel" => "int",
			"salvage" => "array",
			"set" => "array", // optional
			"socketEffects" => "array", // optional
			"tooltipParams" => "string",
			"type" => "array",
			"typeName" => "string"
		];
		$this->effectsMap = [
			"" => "armor",
			"" => "poison"
		];
		$this->init();
	}

	/**
	* Get property
	*/
	public function __get( $p_name )
	{
		if ( isset($this->$p_name) )
		{
			return $this->$p_name;
		}

		$trace = debug_backtrace();
		trigger_error(
			'Undefined property: ' . $p_name .
			' in ' . $trace[0]['file'] .
			' on line ' . $trace[0]['line'],
			E_USER_NOTICE
		);

		return NULL;
	}

	/**
	* Convert this object to a string.
	* @return string
	*/
	public function __toString()
	{
		return json_encode( $this, JSON_PRETTY_PRINT );
	}

	/**
	* Determine if a variable is set.
	* @return bool
	*/
	public function __isset( $p_property )
	{
		return isset( $this->$p_property );
	}

	/**
	* Initialize all the properties for this object.
	*/
	protected function init()
	{
		$jsonArray = json_decode( $this->json, TRUE );
		if ( isArray($jsonArray) )
		{
			foreach ( $jsonArray as $name => $value )
			{
				if ( array_key_exists($name, $this->forcePropertyType) )
				{
					if ( setType($value, $this->forcePropertyType[$name]) )
					{
						$this->$name = $value;
					}
				}
				else
				{
					$this->$name = $value;
				}
			}
		}
		else
		{
			$exception = new \Exception( "Tried to initialize ItemModel with invalid JSON." );
			//logError( $exception, "Tried to initialize ItemModel with invalid JSON.", "An application error has occured. Please try again later" );
			logError( $exception, "Tried to initialize ItemModel with invalid JSON." );
		}
	}

	/** BEGIN GETTER/SETTER SECTION **/

	/**
	* Get the item, first check the local DB, otherwise pull from Battle.net.
	*
	* @return string JSON item data.
	*/
	public function json()
	{
		return $this->json;
	}

	/** END GETTER/SETTER SECTION **/

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
?>