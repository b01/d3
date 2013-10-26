<?php
/**
* Unit test for: namespace kshabazz\d3a\Hero class.
*
*/
class HeroTest extends PHPUnit_Framework_TestCase
{
	/**
	* Constructor
	*/
	public function setup()
	{
		$this->armor = 7;
		$this->criticalHitChance = 0.05;
		$this->criticalHitDamage = 0.50;
		$this->dexterity = 7;
		$this->dodgeChance = 0.01;
		$this->dualWield = FALSE;
		$this->intelligence = 7;
		$this->itemModels = [];
		$this->strength = 7;
		$this->vitality = 7;
		$this->coldResist = 1;
		$this->fireResist = 1;
		$this->lightingResist = 1;
		$this->poisonResist = 1;
		$this->physicalResist = 1;
		$this->characterClass = $this->{'class'};
		$this->noItemsStats = [
			"Dexterity_Item" => [
				"value" => 7,
				"muliplier" => 1
			],
			"Intelligence_Item" => [
				"value" => 7,
				"muliplier" => 1
			],
			"Strength_Item" => [
				"value" => 7,
				"muliplier" => 1
			]
		];

		$this->determinePrimaryAttribute();
		$this->noItemsStats[ $this->primaryAttribute ][ 'muliplier' ] = 3;
		$this->noItemsStats[ $this->primaryAttribute ][ 'primary' ] = TRUE;
		// Increase to 8% above level 59
		if ($this->level > 59 )
		{
			$this->criticalHitChance = 0.08;
		}
		$this->levelUpBonuses();
	}

	/**
	* Based on the character's class.
	*
	* @return HeroModel
	*/
	protected function determinePrimaryAttribute()
	{
		switch( $this->characterClass )
		{
			case "monk":
			case "demon hunter":
			case "demon-hunter":
				$this->primaryAttribute = "Dexterity_Item";
				break;
			case "barbarian":
				$this->primaryAttribute = "Strength_Item";
				break;
			case "wizard":
			case "witch-doctor":
			case "witch doctor":
			case "shaman":
				$this->primaryAttribute = "Intelligence_Item";
				break;
			default:
				$trace = debug_backtrace();
				trigger_error(
					'Undefined property: ' . $this->primaryAttribute .
					' in ' . $trace[ 0 ][ 'file' ] .
					' on line ' . $trace[ 0 ][ 'line' ],
					E_USER_NOTICE
				);
		}
		return $this;
	}

	/**
	* Add in addition attributes from level bonus.
	*/
	protected function levelUpBonuses()
	{
		$dexMultiplier = ( $this->primaryAttribute === "Dexterity_Item" ) ? 3 : 1;
		$intMultiplier = ( $this->primaryAttribute === "Intelligence_Item" ) ? 3 : 1;
		$strMultiplier = ( $this->primaryAttribute === "Strength_Item" ) ? 3 : 1;
		$this->dexterity += ( $this->level + $this->paragonLevel ) * $dexMultiplier;
		$this->intelligence += ( $this->level * $intMultiplier );
		$this->strength += ( $this->level * $strMultiplier );

		foreach( $this->noItemsStats as $attribute => &$values )
		{
			$multiplier = $values[ 'muliplier' ];
			$values[ 'value' ] += ( $this->level + $this->paragonLevel ) * $multiplier;
		}

		return $this;
	}

	/** BEGIN	GETTER/SETTER **/

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
	public function getItemModels()
	{
		return $this->itemModels;
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
	* Get base dexterity.
	* @return int
	*/
	public function dexterity()
	{
		return $this->dexterity;
	}

	/**
	* Indicates use of two-hand weapons.
	*
	* @return
	*/
	public function dualWield()
	{
		if ( !isset($this->dualWield) )
		{
			$this->dualWield = $this->items[ 'mainHand' ]->type[ 'twoHanded' ];
		}
		return $this->dualWield;
	}

	/**
	*  Get stats when no items are equipped attribute.
	* @return string
	*/
	public function noItemsStats()
	{
		return $this->noItemsStats;
	}

	/**
	*  Get primary attribute.
	* @return string
	*/
	public function primaryAttribute()
	{
		return $this->primaryAttribute;
	}

	/** END	GETTER/SETTER **/
}
?>