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
class ItemModel
{
	private
		$_array;

	protected
		$dateAdded,
		$ipAddress,
		$json,
		$lastUpdated;

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
			$this->gems
		);
	}
}
?>