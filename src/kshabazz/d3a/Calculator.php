<?php namespace kshabazz\d3a;
/**
 * Perform all D3 calculations.
 *
 * @var array $p_items A hash array of items, by which the keys indicate where the items are placed
 *	on the hero.
 */

/**
 * Class Calculator
 * @package kshabazz\d3a
 */
class Calculator
{
	use Shared;

	const
		APS_DUAL_WIELD_BONUS = 0.15,
		CRITICAL_HIT_CHANCE_BONUS = 0.05,
		CRITICAL_HIT_DAMAGE_BONUS = 0.5;

	protected
		$attackSpeed,
		$attackSpeedData,
		$attributeSlots,
		$attributeTotals,
		$averageDamage,
		$averageDamageData,
		$armor,
		$armorData,
		$criticalHitChance,
		$criticalHitChanceData,
		$criticalHitDamage,
		$criticalHitDamageData,
		$debug,
		$dps,
		$damagePerSecondData,
		$dualWield,
		$gems,
		$hero,
		$increasedAttackSpeed,
		$items,
		$maxDamage,
		$minDamage,
		$primaryAttribute,
		$primaryAttributeDamage,
		$baseWeaponDamage,
		$weaponAttacksPerSecond;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->attackSpeedData = [];
		$this->attributeSlots = [];
		$this->attributeTotals = [];
		$this->averageDamage = 0.0;
		$this->averageDamageData = [];
		$this->criticalHitChance = self::CRITICAL_HIT_CHANCE_BONUS;
		$this->criticalHitChanceData = [];
		$this->criticalHitDamage = self::CRITICAL_HIT_DAMAGE_BONUS;
		$this->criticalHitDamageData = [];
		$this->damagePerSecondData = [];
		$this->dualWield = FALSE;
		$this->gems = [];
		$this->increasedAttackSpeed = 0.0;
		$this->maxDamage = 0.0;
		$this->minDamage = 0.0;
		$this->primaryAttribute = NULL;
		$this->primaryAttributeDamage = 0.0;
		$this->weaponAttacksPerSecond = 0.0;

		$this->attackSpeed = 0.0;
		$this->baseWeaponDamage = 0.0;
		$this->criticalDamage = 0.0;
		$this->damagePerSecond = 0.0;
		$this->increasedAttackSpeed = 0.0;
		$this->weaponDamage = 0.0;
		$this->debug = [];
	}

	/**
	 * Calculate armor.
	 *
	 * @return float
	 */
	public function armor()
	{
		if (!isset($this->armor) )
		{
			// Add more as they are found.
			$armorAttributes = [
				'Armor_Item',
				'Strength_Item',
				'Armor_Bonus_Item',
				'Block_Amount_Item_Min'
			];
			$this->attributeComputer( 'armor', $armorAttributes, 0.0 );
		}
		return $this->armor;
	}

	/**
	 * @return array
	 */
	public function armorData()
	{
		if ( !isset($this->armorData) )
		{
			$this->armor();
		}

		return $this->armorData;
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
	 * Take the sum of an list of attributes.
	 *
	 * @param $pName
	 * @param array $pAttributes
	 * @param $pInitValue
	 * @return $this
	 */
	public function attributeComputer( $pName, array $pAttributes, $pInitValue )
	{
		if ( isArray($pAttributes) )
		{
			$property = $pInitValue;
			$data = [];

			foreach ( $pAttributes as $attribute )
			{
				if ( array_key_exists($attribute, $this->attributeTotals) )
				{
					$property += $this->attributeTotals[ $attribute ];
//					$data[ $attribute ] = array_merge( $data, $this->attributeSlots[$attribute] );
					$data = array_merge( $data, $this->attributeSlots[$attribute] );
				}
			}

			$this->{ $pName } = $property;
			$this->{ $pName . 'Data' } = $data;
		}
		return $this;
	}

	/**
	 * @return array
	 */
	public function attributeTotals()
	{
		return $this->attributeTotals;
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

	/**
	* Attack speed, or Attacks per Second (APS) simply means how often your character can use its skills. Taken from
	* tool-tip.
	*
	* @return $this
	*/
	protected function computeAttacksPerSecond()
	{
		// Set some properties for easy access.
		if ( array_key_exists("Attacks_Per_Second_Item_Percent", $this->attributeTotals) )
		{
			$this->attackSpeed = $this->attributeTotals[ 'Attacks_Per_Second_Item_Percent' ];
			$this->attackSpeedData = $this->attributeSlots[ 'Attacks_Per_Second_Item_Percent' ];
		}
		// Attack speed continued.
		if ( array_key_exists("Attacks_Per_Second_Percent", $this->attributeTotals) )
		{
			$this->attackSpeed += $this->attributeTotals[ 'Attacks_Per_Second_Percent' ];
			$this->attackSpeedData = array_merge(
				$this->attackSpeedData,
				$this->attributeSlots[ 'Attacks_Per_Second_Percent' ]
			);
		}
		// Attack speed continued.
		if ( array_key_exists("Attacks_Per_Second_Item", $this->attributeTotals) )
		{
			$this->attackSpeed += $this->attributeTotals[ 'Attacks_Per_Second_Item' ];
			$this->attackSpeedData = array_merge(
				$this->attackSpeedData,
				$this->attributeSlots[ 'Attacks_Per_Second_Item' ]
			);
		}
		$this->debug[ 'attack per second' ] = $this->attackSpeed;
		return $this;
	}

	/**
	* Attack speed, or Attacks per Second (APS) simply means how often your character can use its skills. Taken from
	* tool-tip.
	*
	* @return Calculator
	*/
	protected function computeAttackSpeed()
	{
		$dualWieldBonus = 0.0;
		$this->mainHandAps = ( float ) $this->items[ 'mainHand' ]->attacksPerSecond[ 'min' ];

		if ( $this->dualWield )
		{
			$this->offHandAps = ( float ) $this->items[ 'offHand' ]->attacksPerSecond[ 'min' ];
			$dualWieldBonus = self::APS_DUAL_WIELD_BONUS;
		}

		$this->attackSpeed = $this->weaponAttacksPerSecond * ( 1 + $dualWieldBonus + $this->increasedAttackSpeed );

		$this->debug[ 'attack speed ' ] = $this->attackSpeed;
		return $this;
	}

	/**
	 * Average Damage
	 *
	 * Computed by taking the minumum sum and maximum sum from all items and calculating the average between the two.
	 *
	 * @return Calculator
	 */
	protected function computeAverageDamage()
	{
		foreach ( $this->items as $name => $item )
		{
			if ($name === 'rightFinger')
			{
			}
			$this->tallyItemDamage( $name, $item );
		}

		return $this;
	}

	/**
	 * Base Weapon Damage
	 *
	 * Base weapon damage refers to just the damage on a weapon item's tooltip This calculation is take from:
	 * http://eu.battle.net/d3/en/forum/topic/4903361857
	 *
	 * @return Calculation Chainable.
	 */
	protected function computeBaseWeaponDamage()
	{
		// DPS Value of weapon (as shown on tooltip) / weapon attack spead : this is your BASE weapon damage.
		if ( is_object($this->items['mainHand']) )
		{
			$this->mainHandDps = ( float ) $this->items[ 'mainHand' ]->dps[ 'min' ];
			$this->baseWeaponDamageData[ 'mainHand' ] = $this->mainHandDps;
			$this->baseWeaponDamage = $this->mainHandDps;
		}
		// Dual Wield.
		if ( $this->dualWield )
		{
			$this->offHandDps = ( float ) $this->items[ 'offHand' ]->dps[ 'min' ];
			$this->baseWeaponDamageData[ 'mainHand' ] = $this->offHandDps;
			$this->debug .= "<div>off-hand dps = {$this->offHandDps}</div>";
		}

		$this->debug[ 'base weapon damage ' ] = $this->baseWeaponDamage;
		return $this;
	}

	/**
	* Critical Hit Chance
	*
	* @return Calculator
	*/
	protected function computeCriticalHitChance()
	{
		if ( array_key_exists("Crit_Percent_Bonus_Capped", $this->attributeTotals) )
		{
			$this->criticalHitChance += $this->attributeTotals[ 'Crit_Percent_Bonus_Capped' ];
			$this->criticalHitChanceData = $this->attributeSlots[ 'Crit_Percent_Bonus_Capped' ];
			$this->criticalHitChanceData[ 'base' ] = 0.05;
		}

		$this->debug[ 'critical hit chance ' ] = $this->criticalHitChance;
		return $this;
	}

	/**
	 * Critical Damage
	 *
	 * @return Calculator
	 */
	protected function computeCriticalDamage()
	{
		if ( array_key_exists("Crit_Damage_Percent", $this->attributeTotals) )
		{
			$this->criticalHitDamage += $this->attributeTotals[ 'Crit_Damage_Percent' ];
			$this->criticalHitDamageData = $this->attributeSlots[ 'Crit_Damage_Percent' ];
			$this->criticalHitDamageData[ 'base' ] = 0.50;
		}

		$this->debug[ 'critical damage' ] = $this->criticalHitDamage;
		return $this;
	}

	/**
	 * Damage Per Second
	 *
	 * Calculation taken from: http://eu.battle.net/d3/en/forum/topic/4903361857
	 *
	 * @return Calculator
	 */
	protected function computeDamagePerSecond()
	{
		// x CRIT x Primary Skill x Primary Stat = your total dps
		// Weapon Damage X Attac Speed
		$this->damagePerSecond = $this->weaponDamage * $this->attackSpeed;
		$this->damagePerSecondData = [
			'attackSpeed' => $this->attackSpeed,
			'baseWeaponDamage' => $this->baseWeaponDamage,
			'primaryAttributeDamage' => $this->primaryAttributeDamage,
			'weaponDamage' => $this->weaponDamage
		];

		$this->debug[ 'damage_per_second' ] = $this->damagePerSecond;
		return $this;
	}


	/**
	 * Increased Attack Speed
	 *
	 * Sum of all increased attack speed on the heros gear excluding weapons.
	 *
	 * @return Calculator
	 */
	protected function computeIncreasedAttackSpeed()
	{
		foreach ( $this->attackSpeedData as $slot => $speed )
		{
			$apsAttributes = [
				'Attacks_Per_Second_Percent',
				'Attacks_Per_Second_Item_Percent',
				'Attacks_Per_Second_Item'
			];
			// Removed the attribute suffix from the slot name.
			$slotPreffix = str_replace( $apsAttributes, [''], $slot );
			// Skip any weapon IAS, as it will have already factored into weapon attacks per second.
			// if ( $slotPreffix !== "offHand_" && $slotPreffix !== "mainHand_" )
			if ( !($this->dualWield && $slotPreffix == "offHand_") && $slotPreffix !== "mainHand_" )
			{
				$this->increasedAttackSpeed += $speed;
			}
		}

		$this->debug[ 'increased attack speed' ] = $this->increasedAttackSpeed;
		return $this;
	}


	/**
	 * Primary Attribute Damage
	 *
	 * @return Calculator
	 */
	protected function computePrimaryAttributeDamage()
	{
		if ( array_key_exists($this->primaryAttribute, $this->attributeTotals) )
		{
			$attributeFound = array_key_exists( $this->primaryAttribute, $this->hero->noItemsStats() );
			if ( $attributeFound )
			{
				$attributeValue = $this->hero->noItemsStats()[ $this->primaryAttribute ][ 'value' ];
				$this->attributeTotals[ $this->primaryAttribute ] += $attributeValue;
			}
			$this->primaryAttributeDamage = $this->attributeTotals[ $this->primaryAttribute ];
			$this->primaryAttributeDamageData = $this->attributeSlots[ $this->primaryAttribute ];
			$this->primaryAttributeDamageData[ 'levelBonus' ] = $attributeValue;
		}

		$this->debug[ 'primary attribute damage ' ] = $this->primaryAttributeDamage;
		return $this;
	}

	/**
	 * Skill Damage
	 *
	 * @return Calculator
	 */
	protected function computeSkillDamage()
	{
		$this->skillDamage = 1;

		$this->debug[ 'skill damage ' ] = $this->skillDamage;
		return $this;
	}

	/**
	 * Time Between Attacks
	 *
	 * Simply divide the Attack Speed by 1.
	 *
	 * @return Calculator
	 */
	protected function computeTimeBetweenAttacks()
	{
		$this->timeBetweenAttacks = ( $this->attackSpeed > 0.0 ) ? 1 / $this->attackSpeed : 0;
		return $this;
	}

	/**
	 * Weapon Attacks Per Second
	 *
	 * ???
	 *
	 * @return Calculator
	 */
	protected function computeWeaponAttacksPerSecond()
	{
		$this->weaponAttacksPerSecond = ( float ) $this->items[ 'mainHand' ]->attacksPerSecond[ 'min' ];

		$this->debug[ 'weapon attacks per second ' ] = $this->weaponAttacksPerSecond;
		return $this;
	}

	/*
	* Weapon Damage
	*
	*
	*
	* @return Calculator
	*/
	protected function computeWeaponDamage()
	{
		$this->weaponDamage = $this->baseWeaponDamage
			* $this->skillDamage
			* ( 1 + $this->primaryAttributeDamage / 100 );

		$this->debug[ 'weapon damage' ] = $this->weaponDamage;
		return $this;
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
		return $this->damagePerSecond;
	}

	/**
	 * Damage per second data.
	 * @return float
	 */
	public function damagePerSecondData()
	{
		return $this->damagePerSecondData;
	}

	/**
	 * @return string
	 */
	public function  debug()
	{
		return $this->debug;
	}

	/**
	 * Damage Per Second
	 * This calculation is take from:http://eu.battle.net/d3/en/forum/topic/4903361857
	 * 	dual wield dps = 1.15 * (main-hand damage + off-hand damage) /
	 *		( (1 / main-hand attacks per second) + (1 / off-hand attacks per second))
	 *
	 * @return $this
	 */
	protected function dpsOld()
	{
		// BASE X SPEED x CRIT x SKILL x CARAC = your total dps

		$first_damage_bonus = 0;
		$second_other_damage_bonus_etc = 0;

		$monster_vulnerability_bonuses = 0;

		$weapon_damage = $base_weapon_damage * $skill_damage
			* ( 1 + $primary_stat / 100 )
			* ( 1 + $first_damage_bonus )
			* ( 1 + $second_other_damage_bonus_etc );

		$total_damage_per_hit = $base_weapon_damage * $skill_damage
			* ( 1 + $primary_stat / 100 )
			* ( 1 + $first_damage_bonus )
			* ( 1 + $second_other_damage_bonus_etc )
			* ( 1 + $monster_vulnerability_bonuses );
		return $this;
	}

	/**
	 * Initialize this object.
	 */
	protected function init()
	{
		$this->processRawAttributes();

		$this->primaryAttribute = $this->hero->primaryAttribute();

		if ( isset($this->items['mainHand']) )
		{
			$this->dualWield = $this->items[ 'mainHand' ]->type[ 'twoHanded' ];
		}

		$this->computeAverageDamage()
			->computeAttacksPerSecond()
			->computeCriticalHitChance()
			->computeCriticalDamage()
			->computePrimaryAttributeDamage()
			->computeWeaponAttacksPerSecond()
			->computeIncreasedAttackSpeed()
			->computeAttackSpeed() // Requires Increased attack speed.
			->computeTimeBetweenAttacks()
			->computeBaseWeaponDamage() // Requires attack speed to be computed.
			->computeSkillDamage()
			->computeWeaponDamage() // Requires skill damage and primary attribute damage.
			->computeDamagePerSecond(); // Requires just about everything.
	}

	/**
	 * Get the heroes' primary attribute.
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
	* Loop through raw attributes for every item.
	* @return float
	*/
	protected function processRawAttributes()
	{
		// Transfer the items from an array to properties.
		if ( isArray($this->items) )
		{
			foreach ( $this->items as $placement => $item )
			{
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
	}

	/**
	 *  Set hero whom stats to calculate.
	 *
	 * @param Model\Hero $pHero
	 * @param array $pItems
	 * @return $this
	 */
	public function setHero( Model\Hero $pHero, array $pItems )
	{
		$this->__destruct();
		$this->hero = $pHero;
		$this->items = $pItems;
		$this->__construct();
		$this->init();
		return $this;
	}

	/**
	 * Tally raw attributes.
	 *
	 * @param $pRawAttribute
	 * @param $pSlot
	 * @return $this
	 */
	protected function tallyAttributes( $pRawAttribute, $pSlot )
	{
		foreach ( $pRawAttribute as $attribute => $values )
		{
			$value = ( float ) $values[ 'min' ];

			// Initialize an attribute in the totals array.
			if ( !array_key_exists($attribute, $this->attributeTotals) )
			{
				$this->attributeTotals[ $attribute ] = 0.0;
				$this->attributeSlots[ $attribute ] = [];
			}
			// Sum it up.
			$this->attributeTotals[ $attribute ] += $value;
			// A break-down of an attribute's total. An item can have multiple types of the same attribute
			// use a combination of the slot and attribute name to keep them from replacing the previous value.
			$this->attributeSlots[ $attribute ][ $pSlot . '_' . $attribute ] = $value;

//			 if ( $pSlot === 'mainHand' || $pSlot === 'rightFinger' )
//			 {
//				 $this->debug[ $pSlot . $attribute ] = $value;
//			 }
//			 if ( $attribute === 'Attacks_Per_Second_Item' || $attribute === 'Attacks_Per_Second_Item_Percent' )
//			 {
//				 $this->debug[ $pSlot . $attribute ] = $value;
//				 $this->debug[ 'totals_' . $attribute ]  = $this->attributeTotals[ $attribute ];
//			 }
		}
		return $this;
	}

	/**
	 * Tally an items minimum and maximum damage.
	 *
	 * @param $pItemName
	 * @param $pItem
	 * @return $this
	 */
	protected function tallyItemDamage( $pItemName, $pItem )
	{
		$item = $pItem;
		$minDamage = 0.0;
		$maxDamage = 0.0;
		$keys = '';
		// Minimum sum
		if ( isset($item->minDamage) )
		{
			$minDamage += $item->minDamage[ 'min' ];
		}
		// Maximum sum
		if ( isset($item->maxDamage) )
		{
			$maxDamage += $item->maxDamage[ 'min' ];
		}

		if ( isset($item->attributesRaw) )
		{
			foreach ( $item->attributesRaw as $key => $value )
			{
				if ( strpos($key, "Damage_") > -1 )
				{
					$this->debug[ $key ] = $value['min'];
					if ( strpos($key, "_Reduction") )
					{
					}
					else if ( strpos($key, "_Percent") )
					{
						// $minDamage += $value[ 'min' ];
					}
					else if ( strpos($key, "_Min#") )
					{
						$minDamage += $value[ 'min' ];
					}
					else
					{
						$maxDamage += $value[ 'min' ];
					}

					$keys .= $key . ',';
				}
			}
		}

		if ( isset($item->gems) )
		{
			foreach ( $item->gems as $key => $gem )
			{
				foreach ( $gem['attributesRaw'] as $key => $value )
				{
					if ( strpos($key, "Damage_") > -1 )
					{
						if ( strpos($key, "_Min#") )
						{
							$minDamage += $value[ 'min' ];
						}
						else
						{
							$maxDamage += $value[ 'min' ];
						}
					}
				}
			}
		}

		if ( $minDamage > 0 || $maxDamage > 0 )
		{
			$this->averageDamage = ( $minDamage + $maxDamage ) / 2;
			$this->averageDamageData = [ $pItemName, $minDamage, $maxDamage ];
			$this->minDamage += $minDamage;
			$this->maxDamage += $maxDamage;
			$this->debug[ $pItemName ]= "{$minDamage} - {$maxDamage} - {$keys}";
		}
		return $this;
	}
}
?>