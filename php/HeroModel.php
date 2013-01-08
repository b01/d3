<?php
/**
* Get the users item from Battle.Net and present it to the user; store it locally in a database behind the scenes.
* The item will only be updated after a few ours of retrieving it.
*
*/
namespace d3;

/**
* var $p_heroId string User BattleNet ID.
* var $p_dqi object Data Query Interface.
* var $p_sql object SQL.
*/
class HeroModel extends BattleNetModel
{
	/**
	* Constructor
	*/
	public function __construct( $p_json )
	{
		parent::__construct( $p_json );
		// Top-level properties required of the JSON returned from battle.net.
		$this->attributeMap = [
			"class", "string",
			"items", "array",
			"name", "string",
			"stats", "array"
		];
		$this->__init();
	}
	
	/**
	* Character class
	*
	* @return string
	*/
	public function getCharacterClass()
	{
		return $this->characterClass;
	}
	
	/**
	* Get the item, first check the local DB, otherwise pull from Battle.net.
	*
	* @return array
	*/
	public function getItems()
	{
		return $this->items;
	}
	
	/**
	* Get character stats.
	*
	* @return array
	*/
	public function getStats()
	{
		return $this->stats;
	}
}
?>