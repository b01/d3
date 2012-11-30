<?php
namespace d3cb;
/**
* Get the users item from Battle.Net and present it to the user; store it locally in a database
* behind the scenes. The item will only be updated after a few ours of retrieving it.
*
*/

/**
* var $p_itemHash string User BattleNet ID.
* var $p_dqi object Data Query Interface.
* var $p_sql object SQL.
* var $p_userIp string User IP address.
*/
class ItemModel implements \JsonSerializable 
{
	private
		$_array;

	protected
		$dateAdded,
		$ipAddress,
		$json,
		$lastUpdated,
		$effects = [
			"Hitpoints_Max_Percent_Bonus_Item" => "<span class=\"value\">%+.0f%s%%</span> Life",
			"Crit_Percent_Bonus_Capped" => "Critical Hit Chance Increased by <span class=\"value\">%.1f%%</span>",
			"Resistance#Fire" => "<span class=\"value\">%+d</span> Fire Resistance",
			"Intelligence_Item" => "<span class=\"value\">%+d</span> Intelligence",
			"Armor_Bonus_Item" => "<span class=\"value\">%+d</span> Armor"
		];

	// Seperated because they are the top-level properties of the JSON returned from battle.net.
	protected
		$id, // string
		$name, // string
		$icon, // string
		$displayColor, // string
		$tooltipParams, // string
		$requiredLevel, // int
		$itemLevel, // int
		$bonusAffixes, // int
		$typeName, // string
		$type, // associative array
		$armor, // associative array
		$attributes, // array
		$attributesRaw, // associative array
		$socketEffects, // array
		$salvage, // array
		$gems; // array
	
	/**
	* I named this cast in the hopes that PHP will implement bug: https://bugs.php.net/bug.php?id=46128
	*/
	static public function __cast( \stdClass $p_object )
	{
		$instance = new ItemModel( NULL );
		$instance->id = ( string ) $p_object->id;
		$instance->name = ( string ) $p_object->name;
		$instance->icon = ( string ) $p_object->icon;
		$instance->displayColor = ( string ) $p_object->displayColor;
		$instance->tooltipParams = ( string ) $p_object->tooltipParams;
		$instance->requiredLevel = ( int ) $p_object->requiredLevel;
		$instance->itemLevel = ( int ) $p_object->itemLevel;
		$instance->bonusAffixes = ( int ) $p_object->bonusAffixes;
		$instance->typeName = ( string ) $p_object->typeName;
		$instance->type = ( array ) $p_object->type;
		$instance->armor = ( array ) $p_object->armor;
		$instance->attributes = ( array ) $p_object->attributes;
		$instance->attributesRaw = ( array ) $p_object->attributesRaw;
		$instance->socketEffects = ( array ) $p_object->socketEffects;
		$instance->salvage = ( array ) $p_object->salvage;
		$instance->gems = ( array ) $p_object->gems;
		return $instance;
	}
	
	/**
	* Constructor
	*/
	public function __construct( $p_json )
	{
		$this->_array = json_decode( $p_json, TRUE );
		if ( \d3cb\isArray($this->_array) )
		{
			$this->json = $p_json;
			$this->__init();
		}
		else
		{
			throw new \Exception( "Tried to initialize ItemModel with invalid JSON." );
		}
	}
	
	/**
	* Destructor
	*/
	public function __destruct()
	{
		unset(
			$this->_array,
			$this->dateAdded,
			$this->ipAddress,
			$this->json,
			$this->lastUpdated,
			$this->id,
			$this->name,
			$this->icon,
			$this->displayColor,
			$this->tooltipParams,
			$this->requiredLevel,
			$this->itemLevel,
			$this->bonusAffixes,
			$this->typeName,
			$this->type,
			$this->armor,
			$this->attributes,
			$this->attributesRaw,
			$this->socketEffects,
			$this->salvage,
			$this->gems,
			$this->effects
		);
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
			'Undefined property via __get(): ' . $name .
			' in ' . $trace[0]['file'] .
			' on line ' . $trace[0]['line'],
			E_USER_NOTICE
		);
		
		return NULL;
	}
	
	/**
	* Initialize this object.
	*/
	private function __init()
	{
		$this->id = ( string ) $this->_array['id'];
		$this->name = ( string ) $this->_array['name'];
		$this->icon = ( string ) $this->_array['icon'];
		$this->displayColor = ( string ) $this->_array['displayColor'];
		$this->tooltipParams = ( string ) $this->_array['tooltipParams'];
		$this->requiredLevel = ( int ) $this->_array['requiredLevel'];
		$this->itemLevel = ( int ) $this->_array['itemLevel'];
		$this->bonusAffixes = ( int ) $this->_array['bonusAffixes'];
		$this->typeName = ( string ) $this->_array['typeName'];
		$this->type = ( array ) $this->_array['type'];
		$this->armor = ( array ) $this->_array['armor'];
		$this->attributes = ( array ) $this->_array['attributes'];
		$this->attributesRaw = ( array ) $this->_array['attributesRaw'];
		$this->socketEffects = ( array ) $this->_array['socketEffects'];
		$this->salvage = ( array ) $this->_array['salvage'];
		$this->gems = ( array ) $this->_array['gems'];
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
	* @param $p_effect Name of an effect (a.k.a attribute).
	* @return string
	*/
	public function getEffect( $p_effect, $p_min, $p_max )
	{
		$returnValue = '';
		if ( array_key_exists($p_effect, $this->effects) )
		{
			$mapValue = $this->effects[ $p_effect ];
			// Convert some decimals to percents.
			if ( $p_min < 1 ) {
				$p_min = $p_min * 100;
				$p_max = $p_max * 100;
			}
			$max = ( $p_min === $p_max ) ? '' : '-' . $p_max;
			$returnValue = sprintf( $mapValue, $p_min, $max );
		}
		return $returnValue;
	}
	

	/**
	* Specify how this object is to be used with json_encode.
	* @return array
	*/
	public function jsonSerialize()
	{
		return [
			"dateAdded" => $this->dateAdded,
			"ipAddress" => $this->ipAddress,
			"json" => $this->json,
			"lastUpdated" => $this->lastUpdated,
			"id" => $this->id,
			"name" => $this->name,
			"icon" => $this->icon,
			"displayColor" => $this->displayColor,
			"tooltipParams" => $this->tooltipParams,
			"requiredLevel" => $this->requiredLevel,
			"itemLevel" => $this->itemLevel,
			"bonusAffixes" => $this->bonusAffixes,
			"typeName" => $this->typeName,
			"type" => $this->type,
			"armor" => $this->armor,
			"attributes" => $this->attributes,
			"attributesRaw" => $this->attributesRaw,
			"socketEffects" => $this->socketEffects,
			"salvage" => $this->salvage,
			"gems" => $this->gems
		];
	}

	// public function offsetSet( $p_offset, $p_value )
	// {
		// $trace = debug_backtrace();
		// trigger_error(
			// "Attempting to set a read-only property via array indices []: " . $p_offset .
			// " in  {$trace[0]['file']} on line {$trace[0]['line']}" . E_USER_NOTICE
		// );
		// return NULL;
	// }
	
	// public function offsetExists( $p_offset )
	// {
		// return isset($this->container[$p_offset]);
	// }
	// public function offsetUnset($p_offset)
	// {
		// unset($this->container[$p_offset]);
	// }
	// public function offsetGet($p_offset)
	// {
		// return isset($this->container[$p_offset]) ? $this->container[$p_offset] : null;
	// }
}
?>