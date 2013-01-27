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
	}
	
	/**
	* Based on class.
	* @return float
	*/
	protected function determinePrimaryAttribute()
	{
		switch( $this->{"class"} )
		{
			case "monk":
			case "demon hunter":
				$this->primaryAttribute = "Dexterity_Item";
				break;
			case "barbarian":
				$this->primaryAttribute = "Strength_Item";
				break;
			case "wizard":
			case "shaman":
				$this->primaryAttribute = "Intelligence_Item";
				break;
			default:
				$trace = debug_backtrace();
				trigger_error(
					'Undefined property: ' . $p_placement .
					' in ' . $trace[0]['file'] .
					' on line ' . $trace[0]['line'],
					E_USER_NOTICE
				);
		}
		return $this;
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
	
	/**
	* Detect use of two weapons.
	* This calculation is take from:http://eu.battle.net/d3/en/forum/topic/4903361857
	* @return 
	*/
	public function duelWields()
	{
		return $this->dualWield;
	}
}
?>