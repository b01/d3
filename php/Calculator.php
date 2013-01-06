<?php namespace d3;
/**
* Perform all D3 calculations.
*
* @var array $p_items A hash array of items, by which the keys indicate where the items are placed
*	on the hero.
*/
class Calculator
{
	protected
		$attackSpeed,
		$attackSpeedData,
		$bodyMap,
		$criticalHitChance,
		$criticalHitChanceData,
		$criticalHitDamage,
		$criticalHitDamageData,
		$dps,
		$dpsData,
		$duelWield,
		$gems,
		$heroClass,
		$items,
		$primaryAttribute,
		$primaryAttributeDamage,
		$weaponDamage;

	/**
	* Constructor
	*/
	public function __construct( array $p_items, $heroClass )
	{
		$this->attackSpeed = 0.0;
		$this->attackSpeedData = [];
		$this->bodyMap = [
			"bracers",
			"feet",
			"hands",
			"head",
			"leftFinger",
			"legs",
			"mainHand",
			"neck",
			"offHand",
			"rightFinger",
			"shoulders",
			"special",
			"torso",
			"waist"
		];
		$this->bodyMap = [];
		$this->criticalDamage = 0.0;
		$this->criticalHitChance = 0.05;
		$this->criticalHitChanceData = [];
		$this->criticalHitDamage = 0.50;
		$this->criticalHitDamageData = [];
		$this->dps = 0.0;
		$this->dpsData = [];
		$this->duelWield = FALSE;
		$this->gems = [];
		$this->heroClass = $heroClass;
		$this->items = $p_items;
		$this->primaryAttribute = NULL;
		$this->primaryAttributeDamage = 0.0;
		$this->weaponDamage = 0.00;
		
		$this->init();
	}
	
	/**
	* Descructor
	*/
	public function __destruct()
	{
		unset(
			$this->attackSpeed,
			$this->attackSpeedData,
			$this->bodyMap,
			$this->criticalHitChance,
			$this->criticalHitChanceData,
			$this->criticalHitDamage,
			$this->criticalHitDamageData,
			$this->dps,
			$this->dpsData,
			$this->duelWield,
			$this->gems,
			$this->heroClass,
			$this->items,
			$this->primaryAttribute,
			$this->primaryAttributeDamage,
			$this->weaponDamage
		);
	}
	
	/**
	* Total attack speed for all items equipped.
	* @return float
	*/
	public function attackSpeed()
	{
		return $this->attackSpeed;
	}
	
	/**
	* Attack speed for each items equipped.
	* @return array
	*/
	public function attackSpeedData()
	{
		return $this->attackSpeedData;
	}
	
	/**
	* Initialize this object.
	*/
	protected function init()
	{
		$primaryAttribute = $this->determinePrimaryAttribute();
		// Transfer the items from an array to properties.
		if ( isArray($this->items) )
		{
			foreach ( $this->items as $placement => $item )
			{
				// Build a map of positions available to the hero.
				$this->bodyMap[] = $placement;
				$this->$placement = $item;
				// Collect the gems for later calculations.
				$this->gems[ $placement ] = $item->gems;
				// Compute some things.
				$this->tallyAttribute( $placement, $item, "Attacks_Per_Second_Item_Percent", "attackSpeed" );
				$this->tallyAttribute( $placement, $item, "Crit_Percent_Bonus_Capped", "criticalHitChance" );
				$this->tallyAttribute( $placement, $item, "Crit_Damage_Percent", "criticalHitDamage" );
				$this->tallyAttribute( $placement, $item, $primaryAttribute, "primaryAttributeDamage" );
			}
		}
		// Transfer the items from an array to properties.
		if ( isArray($this->gems) )
		{
			foreach ( $this->gems as $placement => $gem )
			{
				if ( isArray($gem) )
				{
					// Compute some things.
					$this->tallyGemAttribute( $placement, $gem, "Attacks_Per_Second_Item_Percent", "attackSpeed" );
					$this->tallyGemAttribute( $placement, $gem, "Crit_Percent_Bonus_Capped", "criticalHitChance" );
					$this->tallyGemAttribute( $placement, $gem, "Crit_Damage_Percent", "criticalHitDamage" );
				}
			}
		}
		
		$this->weaponDamage();
		$this->dps();
	}
	
	/**
	* Based on class.
	* @return float
	*/
	protected function determinePrimaryAttribute()
	{
		switch( $this->heroClass )
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
		return $this->primaryAttribute;
	}
	
	/**
	* Detect use of two weapons.
	* This calculation is take from:http://eu.battle.net/d3/en/forum/topic/4903361857
	* @return 
	*/
	public function duelWields()
	{
		return $this->duelWield;
	}
	
	/**
	* Dampage Per Second
	* This calculation is take from:http://eu.battle.net/d3/en/forum/topic/4903361857
	* @return 
	*/
	protected function dps()
	{	
		if ( $this->weaponDamage > 0.0 )
		{
			$this->dpsData[ 'weaponDamage' ] = $this->weaponDamage;
			$this->dps = $this->weaponDamage;
		}
		if ( $this->criticalHitChance > 0.0 && $this->criticalHitDamage > 0.0 )
		{
			// Calculate Critial damage
			$criticalDamageMultiplier = 1 + ( $this->criticalHitChance * $this->criticalHitDamage );
			$this->dpsData[ 'criticalDamage' ] = $criticalDamageMultiplier;
			$this->dps *= $criticalDamageMultiplier;
		}
		if ( $this->attackSpeed > 0.0 )
		{
			$attackSpeedMultiplier = 1 + $this->attackSpeed;
			$this->dpsData[ 'attackSpeed' ] = $attackSpeedMultiplier;
			$this->dps *= $attackSpeedMultiplier;
		}
		if ( $this->primaryAttributeDamage > 0.0 )
		{
			$primaryAttributeDamageMultiplier = 1 + ( $this->primaryAttributeDamage / 100 );
			$this->dpsData[ 'primaryAttributeDamage' ] = $primaryAttributeDamageMultiplier;
			$this->dps *= $primaryAttributeDamageMultiplier;
		}
		// BASE X SPEED x CRIT x SKILL x CARAC = your total dps
		return $this->dps;
	}
	
	/**
	* Critical hit chance percent.
	* @return float
	*/
	public function getCriticalHitChance()
	{
		return $this->criticalHitChance * 100;
	}
	
	/**
	* Critical hit chance data.
	* @return float
	*/
	public function getCriticalHitChanceData()
	{
		return $this->criticalHitChanceData;
	}
	
	/**
	* Critical hit damage percent.
	* @return float
	*/
	public function getCriticalHitDamage()
	{
		return $this->criticalHitDamage * 100;
	}
	
	/**
	* Critical hit damage data.
	* @return array
	*/
	public function getCriticalHitDamageData()
	{
		return $this->criticalHitDamageData;
	}
	
	/**
	* Damage per second.
	* @return float
	*/
	public function getDps()
	{
		return $this->dps;
	}
	
	/**
	* Damage per second data.
	* @return float
	*/
	public function getDpsData()
	{
		return $this->dpsData;
	}
	
	/**
	* Primary attribute damage percent.
	* @return float
	*/
	public function getPrimaryAttributeDamage()
	{
		return $this->primaryAttributeDamage;
	}
	
	/**
	* Primary attribute damage data.
	* @return float
	*/
	public function getPrimaryAttributeDamageData()
	{
		return $this->primaryAttributeDamageData;
	}
	
	/**
	* Primary attribute.
	* @return float
	*/
	public function getWeaponDamage()
	{
		return $this->weaponDamage;
	}
	
	/**
	* Primary attribute.
	* @return float
	*/
	public function getWeaponDamageData()
	{
		return $this->weaponDamageData;
	}
	
	/**
	* Primary attribute.
	* @return float
	*/
	public function getPrimaryAttribute()
	{
		return $this->primaryAttribute;
	}
	
	/**
	* Tally speed for an item.
	* @return float
	*/
	protected function tallyAttribute( $p_placement, $p_item, $p_key, $p_property )
	{
		if ( array_key_exists($p_key, $p_item->attributesRaw) )
		{
			$value = ( float ) $p_item->attributesRaw[ $p_key ][ 'max' ];
			$this->{$p_property . "Data"}[ $p_placement ] = $value;
			$this->$p_property += $value;
		}
		return $this;
	}
	
	/**
	* Tally speed for an item.
	* @return float
	*/
	protected function tallyGemAttribute( $p_placement, $p_gem, $p_key, $p_property )
	{
		$gem = $p_gem[ 0 ];
		if ( array_key_exists($p_key, $gem['attributesRaw']) )
		{
			$value = ( float ) $gem['attributesRaw'][ $p_key ][ 'max' ];
			$this->{$p_property . "Data"}[ $p_placement ] = $value;
			$this->$p_property += $value;
		}
		return $this;
	}
	
	/**
	* Add an item, one at a time.
	* @return bool
	*/
	public function updateEquippedItem( $p_item, $p_placement )
	{
		if ( array_key_exists($p_placement, $this->bodyMap) && is_object($p_item) )
		{
			$this->$p_placement = $p_item;
			return TRUE;
		}
		// Else, bad items should NOT be getting passed in.
		$trace = debug_backtrace();
		trigger_error(
			'Undefined property: ' . $p_placement .
			' in ' . $trace[0]['file'] .
			' on line ' . $trace[0]['line'],
			E_USER_NOTICE
		);
	}
	
	/**
	* Base Weapon Damage
	* This calculation is take from: http://eu.battle.net/d3/en/forum/topic/4903361857
	* @return 
	*/
	public function weaponDamage()
	{
		if ( is_object($this->mainHand) )
		{
			if ( isWeapon($this->offHand) )
			{
				$this->duelWield = TRUE;
			}
			// DPS Value of weapon (as shown on tooltip) / weapon attack spead : this is your BASE weapon damage. 
			$this->weaponDamage = ( float ) $this->mainHand->dps['max'];
			$this->weaponDamageData[ 'mainHand' ] = $this->weaponDamage;
			if ( $this->duelWield )
			{
				$offHandDamage = ( (float) $this->offHand->dps['min'] + (float) $this->offHand->dps['max'] ) / 2;
				// Add in the average damage of your offhand (min + max) / 2.
				$this->weaponDamage = $offHandDamage;
				$this->weaponDamageData[ 'offHand' ] = $offHandDamage;
			}
			else
			{
			}
		}
		return $this->weaponDamage;
	}
}
?>