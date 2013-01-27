<?php namespace d3;
/**
* Perform all D3 calculations.
*
* @var array $p_items A hash array of items, by which the keys indicate where the items are placed
*	on the hero.
*/
class Calculator
{
	const APS_DUAL_WIELD_BONUS = 0.15;
	protected
		$attackSpeed,
		$attackSpeedData,
		$attributeMap,
		$attributeSlots,
		$attributeTotals,
		$criticalHitChance,
		$criticalHitChanceData,
		$criticalHitDamage,
		$criticalHitDamageData,
		$dps,
		$dpsData,
		$dualWield,
		$gems,
		$heroClass,
		$heroLevel,
		$increasedAttackBonus,
		$items,
		$primaryAttribute,
		$primaryAttributeDamage,
		$baseWeaponDamage,
		$weaponAttacksPerSecond;

	/**
	* Constructor
	*/
	public function __construct( array $p_items, $p_heroClass, $p_heroLevel = 60 )
	{
		$this->attackSpeed = 0.0;
		$this->attackSpeedData = [];
		$this->attributeMap = [
			"Attacks_Per_Second_Item_Percent" => "attackSpeed",
			"Crit_Percent_Bonus_Capped" => "criticalHitChance",
			"Crit_Damage_Percent" => "criticalHitDamage"
		];
		$this->attributeSlots = [];
		$this->attributeTotals = [];
		// $this->bodyMap = [
			// "bracers",
			// "feet",
			// "hands",
			// "head",
			// "leftFinger",
			// "legs",
			// "mainHand",
			// "neck",
			// "offHand",
			// "rightFinger",
			// "shoulders",
			// "special",
			// "torso",
			// "waist"
		// ];
		$this->criticalDamage = 0.0;
		$this->criticalHitChance = 0.08;
		$this->criticalHitChanceData = [];
		$this->criticalHitDamage = 0.50;
		$this->criticalHitDamageData = [];
		$this->dps = 0.0;
		$this->dpsData = [];
		$this->dualWield = FALSE;
		$this->gems = [];
		$this->heroClass = $p_heroClass;
		$this->items = $p_items;
		$this->increasedAttackBonus = 0.0;
		$this->primaryAttribute = NULL;
		$this->primaryAttributeDamage = 0.0;
		$this->baseWeaponDamage = 0.00;
		$this->weaponAttacksPerSecond = 0.0;
		$this->heroLevel = $p_heroLevel;
		
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
			$this->attributeSlots,
			$this->attributeTotals,
			$this->criticalHitChance,
			$this->criticalHitChanceData,
			$this->criticalHitDamage,
			$this->criticalHitDamageData,
			$this->dps,
			$this->dpsData,
			$this->dualWield,
			$this->gems,
			$this->heroClass,
			$this->items,
			$this->increasedAttackBonus,
			$this->primaryAttribute,
			$this->primaryAttributeDamage,
			$this->baseWeaponDamage,
			$this->weaponAttacksPerSecond
		);
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
				// Compute some things.
				$this->tallyAttributes( $item->attributesRaw, $placement );
				// Tally gems.
				if ( isArray($item->gems) )
				{
					for ( $i = 0; $i < count($item->gems); $i++ )
					{
						$gem = $item->gems[ $i ];
						if ( isArray($gem) )
						{
							$this->tallyAttributes( $gem['attributesRaw'], "{$placement} gem slot {$i}" );
						}
					}
				}
			}
		}
		
		$this->determinePrimaryAttribute();

		$this->dualWield = ( isset($this->offHand) && isWeapon($this->offHand) );

		// Set some properties for easy access.
		if ( array_key_exists("Attacks_Per_Second_Item_Percent", $this->attributeTotals) )
		{
			$this->attackSpeed = $this->attributeTotals[ 'Attacks_Per_Second_Item_Percent' ];
			$this->attackSpeedData = $this->attributeSlots[ 'Attacks_Per_Second_Item_Percent' ];
		}
		
		if ( array_key_exists("Crit_Percent_Bonus_Capped", $this->attributeTotals) )
		{
			$this->criticalHitChance += $this->attributeTotals[ 'Crit_Percent_Bonus_Capped' ];
			$this->criticalHitChanceData = $this->attributeSlots[ 'Crit_Percent_Bonus_Capped' ];
			$this->criticalHitChanceData[ 'base' ] = 0.05;
		}
		if ( array_key_exists("Crit_Damage_Percent", $this->attributeTotals) )
		{
			$this->criticalHitDamage += $this->attributeTotals[ 'Crit_Damage_Percent' ];
			$this->criticalHitDamageData = $this->attributeSlots[ 'Crit_Damage_Percent' ];
			$this->criticalHitDamageData[ 'base' ] = 0.50;
		}
		if ( array_key_exists($this->primaryAttribute, $this->attributeTotals) )
		{
			$levelBonus = $this->heroLevel * 3;
			$this->primaryAttributeDamage = $this->attributeTotals[ $this->primaryAttribute ];
			$this->primaryAttributeDamageData = $this->attributeSlots[ $this->primaryAttribute ];
			$this->primaryAttributeDamageData[ 'levelBonus' ] = $levelBonus;
		}
		
		$this->calculateBaseWeaponDamage();
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
		return $this;
	}
	
	/**
	* Dampage Per Second
	* This calculation is take from:http://eu.battle.net/d3/en/forum/topic/4903361857
	* @return 
	*/
	protected function dps()
	{	
		// BASE X SPEED x CRIT x SKILL x CARAC = your total dps
		if ( $this->baseWeaponDamage > 0.0 )
		{
			$this->dpsData[ 'baseWeaponDamage' ] = $this->baseWeaponDamage;
			$this->dps = $this->baseWeaponDamage;
		}
		if ( $this->criticalHitChance > 0.0 && $this->criticalHitDamage > 0.0 )
		{
			// Calculate Critial damage
			$criticalDamageMultiplier = 1 + ( $this->criticalHitChance * $this->criticalHitDamage );
			$this->dpsData[ 'criticalDamage' ] = $criticalDamageMultiplier;
			$this->dps *= $criticalDamageMultiplier;
		}
		
		$this->calculateAttacksPerSecond();
		$this->dpsData[ 'attackSpeed' ] = $this->attackSpeed;
		$this->dps *= $this->attackSpeed;
		
		if ( $this->primaryAttributeDamage > 0.0 )
		{
			$primaryAttributeDamageMultiplier = 1 + ( $this->primaryAttributeDamage / 100 );
			$this->dpsData[ 'primaryAttributeDamage' ] = $primaryAttributeDamageMultiplier;
			$this->dps *= $primaryAttributeDamageMultiplier;
		}
		return $this;
	}
	
	/**
	* Tally raw attributes.
	* @return float
	*/
	protected function tallyAttributes( $p_attributesRaw, $p_slot )
	{
		foreach ( $p_attributesRaw as $attribute => $values )
		{
			$value = $values[ 'min' ];
			if ( !array_key_exists($attribute, $this->attributeTotals) )
			{
				$this->attributeTotals[ $attribute ] = 0.0;
				// A break-down of attributes.
				$this->attributeSlots[ $attribute ] = [];
			}
			$this->attributeTotals[ $attribute ] += ( float ) $value;
			$this->attributeSlots[ $attribute ][ $p_slot ] = $value;
		}
		return $this;
	}
	
	/**
	* Base Weapon Damage
	* This calculation is take from: http://eu.battle.net/d3/en/forum/topic/4903361857
	* @return Calculation Chainable.
	*/
	protected function calculateBaseWeaponDamage()
	{
		// DPS Value of weapon (as shown on tooltip) / weapon attack spead : this is your BASE weapon damage.
		if ( is_object($this->mainHand) )
		{
			$this->baseWeaponDamage = ( float ) $this->mainHand->dps[ 'min' ];
			$this->baseWeaponDamageData[ 'mainHand' ] = $this->baseWeaponDamage;
			$this->weaponAttacksPerSecond = ( float ) $this->mainHand->attacksPerSecond[ 'min' ];
		}
		
		if ( $this->dualWield )
		{
			$offHandDamage = ( (float) $this->offHand->dps['min'] + (float) $this->offHand->dps['max'] ) / 2;
		}
		return $this;
	}
	
	/** CALCULATORS BEGIN **/
	
	protected function calculateAttacksPerSecond()
	{
		$dualWieldBonus = 0.0;
		// Remove any weapon IAS, as that are already factored into weapon attacks per second.
		foreach ( $this->attackSpeedData as $slot => $speed )
		{
			if ( $slot !== "offHand" && $slot !== "mainHand" )
			{
				$this->increasedAttackBonus += $speed;
			}
		}
		if ( $this->dualWield )
		{
			$dualWieldBonus = self::APS_DUAL_WIELD_BONUS;
		}
		
		$this->attackSpeed = $this->weaponAttacksPerSecond * ( 1 + $dualWieldBonus + $this->increasedAttackBonus );
		$this->timeBetweenAttacks = 1 / $this->attackSpeed;
		$this->attackSpeedData[ 'increasedAttackBonus' ] = $this->increasedAttackBonus;
		$this->attackSpeedData[ 'dualWieldBonus' ] = $dualWieldBonus;
		$this->attackSpeedData[ 'weaponAttacksPerSecond' ] = $this->weaponAttacksPerSecond;
		return $this;
	}
	
	/** CALCULATORS END **/
	
	/** PROPERTIES BEGIN **/
	
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
	* Critical hit chance percent.
	* @return float
	*/
	public function criticalHitChance()
	{
		return $this->criticalHitChance * 100;
	}
	
	/**
	* Critical hit chance data.
	* @return float
	*/
	public function criticalHitChanceData()
	{
		return $this->criticalHitChanceData;
	}
	
	/**
	* Critical hit damage percent.
	* @return float
	*/
	public function criticalHitDamage()
	{
		return $this->criticalHitDamage * 100;
	}
	
	/**
	* Critical hit damage data.
	* @return array
	*/
	public function criticalHitDamageData()
	{
		return $this->criticalHitDamageData;
	}
	
	/**
	* Damage per second.
	* @return float
	*/
	public function damagePerSecond()
	{
		return $this->dps;
	}
	
	/**
	* Damage per second data.
	* @return float
	*/
	public function damagePerSecondData()
	{
		return $this->dpsData;
	}
	
	/**
	* Get the heros' primary attribute.
	* @return string
	*/
	public function primaryAttribute()
	{
		return $this->primaryAttribute;
	}
	
	/**
	* Primary attribute damage percent.
	* @return float
	*/
	public function primaryAttributeDamage()
	{
		return $this->primaryAttributeDamage;
	}
	
	/**
	* Primary attribute damage data.
	* @return float
	*/
	public function primaryAttributeDamageData()
	{
		return $this->primaryAttributeDamageData;
	}
	
	/**
	* Primary attribute.
	* @return float
	*/
	public function baseWeaponDamage()
	{
		return $this->baseWeaponDamage;
	}
	
	/**
	* Primary attribute.
	* @return float
	*/
	public function baseWeaponDamageData()
	{
		return $this->baseWeaponDamageData;
	}
	
	/** PROPERTIES END **/
}
?>