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
		$baseWepaondamage,
		$bodyMap,
		$classDamage,
		$criticalHitChanceData,
		$criticalHitChance,
		$criticalHitDamage,
		$duelWield,
		$items;

	/**
	* Constructor
	*/
	public function __construct( array $p_items )
	{
		$this->attackSpeed = 0.0;
		$this->baseWepaondamage = 0.00;
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
		$this->classDamage = 0.0;
		$this->criticalHitChance = 0.0;
		$this->criticalHitChanceData = [];
		$this->criticalHitDamage = 0.0;
		$this->duelWield = FALSE;
		$this->items = $p_items;
		
		$this->init();
	}
	
	/**
	* Descructor
	*/
	public function __destruct()
	{
		unset(
			$this->attackSpeed,
			$this->baseWepaondamage,
			$this->bodyMap,
			$this->classDamage,
			$this->criticalHitChance,
			$this->criticalHitDamage,
			$this->duelWield,
			$this->items
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
		// Transfer the items from an array to properties.
		if ( isArray($this->items) )
		{
			foreach ( $this->items as $placement => $item )
			{
				$this->$placement = $item;
				// compute some things.
				$this->tallyAttackSpeed( $placement, $item );
				$this->tallyCriticalHitChance( $placement, $item );
			}
		}
	}
	
	/**
	* Base Weapon Damage
	* This calculation is take from: http://eu.battle.net/d3/en/forum/topic/4903361857
	* @return 
	*/
	public function baseDamage()
	{
		// BASE X SPEED x CRIT x SKILL x CARAC = your total dps
		if ( is_object($this->mainHand) )
		{
			// DPS Value of weapon (as shown on tooltip) / weapon attack spead : this is your BASE weapon damage. 
			if ( $this->duelWield )
			{
				// Add in the average damage of your offhand (min + max) / 2.
				$this->baseWepaondamage =   ( (float) $this->offHand->dps['min'] + (float) $this->offHand->dps['max'] ) / 2;
			}
			else
			{
				$this->baseWepaondamage = ( float ) $this->mainHand->dps['max'];
			}
		}
		return $this->baseWepaondamage;
	}
	
	/**
	* Detect use of two weapons.
	* This calculation is take from:http://eu.battle.net/d3/en/forum/topic/4903361857
	* @return 
	*/
	public function dualWields()
	{
		return $this->duelWield;
	}
	
	/**
	* Dampage Per Second
	* This calculation is take from:http://eu.battle.net/d3/en/forum/topic/4903361857
	* @return 
	*/
	public function dps()
	{
		// BASE X SPEED x CRIT x SKILL x CARAC = your total dps
		return $this->baseWepaondamage / $this->attackSpeed;
	}
	
	/**
	* Critical hit chance.
	* @return float
	*/
	public function getCriticalHitChance()
	{
		return $this->criticalHitChance;
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
	* Tally speed for an item.
	* @return float
	*/
	public function tallyAttackSpeed( $p_placement, $p_item )
	{
		if ( array_key_exists("Attacks_Per_Second_Item_Percent", $p_item->attributesRaw) )
		{
			$value = ( float ) $p_item->attributesRaw[ 'Attacks_Per_Second_Item_Percent' ][ 'max' ] * 100;
			$this->attackSpeedData[ $p_placement ] = $value;
			$this->attackSpeed += $value;
		}
		return $this->attackSpeed;
	}
	
	/**
	* Tally critical hit chance.
	* @return float
	*/
	protected function tallyCriticalHitChance( $p_placement, $p_item )
	{
		if ( array_key_exists("Crit_Percent_Bonus_Capped", $p_item->attributesRaw) )
		{
			$value = ( float ) $p_item->attributesRaw[ 'Crit_Percent_Bonus_Capped' ][ 'max' ] * 100;
			$this->criticalHitChanceData[ $p_placement ] = $value;
			$this->criticalHitChance += $value;
		}
		return $this;
	}
	
	/**
	* Total Dampage Per Second, after taking all item stats into account.
	* This calculation is take from:http://eu.battle.net/d3/en/forum/topic/4903361857
	* @return 
	*/
	public function totalDps()
	{
		$this->dps();
		// BASE X SPEED x CRIT x SKILL x CARAC = your total dps
		return $this->baseWepaondamage * $this->attackSpeed & $this->criticalHitDamage * $this->classDamage;
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
}
?>