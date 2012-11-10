<?php
/**
* Get the users item from Battle.Net and present it to the user; store it locally in a database
* behind the scenes. The item will only be updated after a few ours of retrieving it.
*
*/
namespace d3cb;

use \d3cb\Tool;

/**
* var $p_itemHash string User BattleNet ID.
* var $p_dqi object Data Query Interface.
* var $p_sql object SQL.
* var $p_userIp string User IP address.
*/
class Item // TODO: implements BattleNetObject
{
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
		
	// static public function __cast( $p_object )
	// {
		// $instance = new self();
		// switch ( var_type($p_object) )
		// {
			// case "boolean":
				// break;
			// default:
				// $newObject->
				// break;
		// }
		// return $newObject;
	// }
	
	/**
	* I named this cast in the hopes that PHP will implement bug: https://bugs.php.net/bug.php?id=46128
	*/
	public function __cast( $p_jsonArray )
	{
		$this->id = ( string ) $p_jsonArray['id'];
		$this->name = ( string ) $p_jsonArray['name'];
		$this->icon = ( string ) $p_jsonArray['icon'];
		$this->displayColor = ( string ) $p_jsonArray['displayColor'];
		$this->tooltipParams = ( string ) $p_jsonArray['tooltipParams'];
		$this->requiredLevel = ( int ) $p_jsonArray['requiredLevel'];
		$this->itemLevel = ( int ) $p_jsonArray['itemLevel'];
		$this->bonusAffixes = ( int ) $p_jsonArray['bonusAffixes'];
		$this->typeName = ( string ) $p_jsonArray['typeName'];
		$this->type = ( array ) $p_jsonArray['type'];
		$this->armor = ( array ) $p_jsonArray['armor'];
		$this->attributes = ( array ) $p_jsonArray['attributes'];
		$this->attributesRaw = ( array ) $p_jsonArray['attributesRaw'];
		$this->socketEffects = ( array ) $p_jsonArray['socketEffects'];
		$this->salvage = ( array ) $p_jsonArray['salvage'];
		$this->gems = ( array ) $p_jsonArray['gems'];
	}
	
	/**
	* Constructor
	*/
	public function __construct( $p_json )
	{
		$jsonArray = json_decode( $p_json, TRUE );
		if ( Tool::isArray($jsonArray) )
		{
			$this->json = $p_json;
			$this->__cast( $jsonArray );
		}
	}
	
	/**
	* Destructor
	*/
	public function __destruct()
	{
		unset(
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