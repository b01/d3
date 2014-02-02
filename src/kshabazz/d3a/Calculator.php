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
		$dexterity,
		$dexterityData,
		$dps,
		$damagePerSecondData,
		$dualWield,
		$gems,
		$hero,
		$increasedAttackSpeed,
		$intelligence,
		$intelligenceData,
		$itemDamage,
		$itemDamageData,
		$items,
		$primaryAttributeDamage,
		$primaryAttributeDamageData,
		$primaryAttributeTotal,
		$primaryAttributeTotalData,
		$primaryAttributes,
		$primaryAttributesData,
		$scramData,
		$strength,
		$strengthData,
		$totalMaxDamage,
		$totalMinDamage,
		$vitality,
		$vitalityData,
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
		$this->dexterity = 0.0;
		$this->dexterityData = [];
		$this->dualWield = FALSE;
		$this->gems = [];
		$this->increasedAttackSpeed = 0.0;
		$this->intelligence = 0.0;
		$this->intelligenceData = [];
		$this->itemDamage = 0.0;
		$this->itemDamageData = [];
		$this->totalMaxDamage = 0.0;
		$this->totalMinDamage = 0.0;
		$this->primaryAttributeTotal = 0.0;
		$this->primaryAttributeTotalData = [];
		$this->scramData = [];
		$this->strength = 0.0;
		$this->strengthData = [];
		$this->vitality = 0.0;
		$this->vitalityData = [];
		$this->weaponAttacksPerSecond = 0.0;

		$this->attackSpeed = 0.0;
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
	* Attack speed, or Attacks per Second (APS) simply means how often your character can use its skills. Taken from
	* tool-tip.
	*
	* @return $this
	*/
	protected function computeAttacksPerSecond()
	{
		$attackSpeed = 0.0;
		// Set some properties for easy access.
		if ( array_key_exists("Attacks_Per_Second_Item_Percent", $this->attributeTotals) )
		{
			$attackSpeed = $this->attributeTotals[ 'Attacks_Per_Second_Item_Percent' ];
			$this->attackSpeedData = $this->attributeSlots[ 'Attacks_Per_Second_Item_Percent' ];
		}
		// Attack speed continued.
		if ( array_key_exists("Attacks_Per_Second_Percent", $this->attributeTotals) )
		{
			$attackSpeed += $this->attributeTotals[ 'Attacks_Per_Second_Percent' ];
			$this->attackSpeedData = array_merge(
				$this->attackSpeedData,
				$this->attributeSlots[ 'Attacks_Per_Second_Percent' ]
			);
		}
		// Attack speed continued.
		if ( array_key_exists("Attacks_Per_Second_Item", $this->attributeTotals) )
		{
			$attackSpeed += $this->attributeTotals[ 'Attacks_Per_Second_Item' ];
			$this->attackSpeedData = array_merge(
				$this->attackSpeedData,
				$this->attributeSlots[ 'Attacks_Per_Second_Item' ]
			);
		}

		$this->attackSpeed = round( $attackSpeed, 2 );

		$this->debug[ 'attack per second' ] = $this->attackSpeed;
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
			$this->tallyItemDamage( $name, $item );
		}

		$this->averageDamage = ( $this->totalMinDamage + $this->totalMaxDamage ) / 2;
		$this->debug[ 'totalMinDamage' ] = $this->totalMinDamage;
		$this->debug[ 'totalMaxDamage' ] = $this->totalMaxDamage;

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
	protected function computePrimaryAttributeTotal()
	{
		$primaryAttribute = $this->hero->primaryAttribute();
		$this->primaryAttributeTotal += $this->attributeTotals[ $primaryAttribute ];
		$this->primaryAttributeTotalData = $this->attributeSlots[ $primaryAttribute ];
		if ($primaryAttribute === 'Dexterity_Item' )
		{
			$this->primaryAttributeTotal += $this->hero->dexterity();
			$this->primaryAttributeTotalData[ 'levelBonus_dexterity' ] = $this->hero->dexterity();
		}
		if ($primaryAttribute === 'Intelligence_Item' )
		{
			$this->primaryAttributeTotal += $this->hero->intelligence();
			$this->primaryAttributeTotalData[ 'levelBonus_intelligence' ] = $this->hero->intelligence();
		}
		if ($primaryAttribute === 'Strength_Item' )
		{
			$this->primaryAttributeTotal += $this->hero->strength();
			$this->primaryAttributeTotalData[ 'levelBonus_strength' ] = $this->hero->strength();
		}

		return $this;
	}

	/**
	 * Primary Attribute Damage
	 *
	 * @return Calculator
	 */
	protected function computePrimaryAttributeTotals()
	{
		$this->processPrimaryAtribute( 'dexterity' );
		$this->processPrimaryAtribute( 'intelligence' );
		$this->processPrimaryAtribute( 'strength' );
		$this->processPrimaryAtribute( 'vitality' );

		return $this;
	}

	protected  function processPrimaryAtribute( $pAttribute )
	{
		$attribute = ucfirst( $pAttribute );
		$levelBonus = $this->hero->{ $pAttribute }();
		// add level bonus with the sum from each item.
		$this->{ $pAttribute } = $this->attributeTotals[ $attribute . '_Item' ] + $levelBonus;
		// get all contributing items.
		$property = $pAttribute . 'Data';
		$this->{ $property } = $this->attributeSlots[ $attribute . '_Item' ];
		$this->{ $property }[ 'levelBonus_' . $pAttribute ] = $levelBonus;
	}

	/**
	 * List of items that contribute to the  primary attributes totals.
	 * @return array
	 */
	public function primaryAttributesData()
	{
		return $this->primaryAttributesData;
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
	 * Dexterity computed from level and items.
	 * @return array
	 */
	public function dexterity()
	{
		return $this->dexterity;
	}

	/**
	 * Items that contribute to dexterity.
	 * @return array
	 */
	public function dexterityData()
	{
		return $this->dexterityData;
	}

	/**
	 * Initialize this object.
	 */
	protected function init()
	{
		$this->processRawAttributes();
		$this->isDualWielding();

		$this->computeAverageDamage()
			->computeAttacksPerSecond()
			->computeCriticalHitChance()
			->computeCriticalDamage()
			->computePrimaryAttributeTotal()
			->computeIncreasedAttackSpeed()
			->computeSkillDamage();
		$this->computePrimaryAttributeTotals();
	}

	/**
	 * Intelligence computed from level and items.
	 * @return float
	 */
	public function intelligence()
	{
		return $this->intelligence;
	}

	/**
	 * Items that contribute to intelligence.
	 * @return array
	 */
	public function intelligenceData()
	{
		return $this->intelligenceData;
	}

	/**
	 * Determine if a hero is brandishing a weapon in each hand.
	 */
	protected function isDualWielding()
	{
		// TODO: test!
		if ( array_key_exists('mainHand', $this->items)
			 && !$this->items['mainHand']->type['twoHanded']
			 && array_key_exists('offHand', $this->items) )
		{
			$this->dualWield = isWeapon( $this->items['mainHand'] )
				&& isWeapon( $this->items['offHand'] );
		}

		return $this->dualWield;
	}

	/**
	 * @return int
	 */
	public function itemDamage()
	{
		return $this->itemDamage;
	}

	/**
	 * List of items that contribute to damage.
	 * @return int
	 */
	public function itemDamageData()
	{
		return $this->itemDamageData;
	}

	/**
	 * Primary attribute is the sum of all items, with that attribute, and the level bonus.
	 * @return float
	 */
	public function primaryAttributeTotal()
	{
		return $this->primaryAttributeTotal;
	}

	/**
	 * List of items that compose the primary attribute total
	 * @return array
	 */
	public function primaryAttributeTotalData()
	{
		return $this->primaryAttributeTotalData;
	}

	/**
	 * Loop through raw attributes for every item.
	 */
	protected function processRawAttributes()
	{
		if ( isArray($this->items) )
		{
			foreach ( $this->items as $placement => $item )
			{
//				var_dump($item);
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
	 * Calculate Damage using SCRAM.
	 * @return float
	 */
	public function scram()
	{
		$s = ( $this->primaryAttributeTotal * 0.01 ) + 1;
		$this->scramData[ 'S = ' . $this->hero->primaryAttribute() ] = $this->primaryAttributeTotal;

		$c = ( $this->criticalHitChance * $this->criticalHitDamage ) + 1;
		$this->scramData[ 'C = (criticalHitChance * criticalHitDamage) + 1' ] = $c;

		$r = $this->attackSpeed;
		$this->scramData[ 'R = attackSpeed' ] = $this->attackSpeed;

		$a = ( $this->totalMinDamage + $this->totalMaxDamage ) / 2;
		$this->scramData[ 'A = (totalMinDamage + totalMaxDamage) / 2' ] = $a;


		$m = 1; //$this->skillDamageBonus;
		$this->scramData[ 'M = skillModifiers' ] = $m;

		return $s * $c * $r * $a * $m;
	}

	/**
	 * Parts used to calculate SCRAM.
	 * @return mixed
	 */
	public function scramData()
	{
		return $this->scramData;
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
	 * Strength
	 * @return float
	 */
	public function strength()
	{
		return $this->strength;
	}

	/**
	 * List of items that contribute to strength.
	 * @return float
	 */
	public function strengthData()
	{
		return $this->strengthData;
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
			// A break-down of an attributes total. An item can have multiple types of the same attribute
			// use a combination of the slot and attribute name to keep them from replacing the previous value.
			$this->attributeSlots[ $attribute ][ $pSlot . '_' . $attribute ] = $value;
		}
		return $this;
	}

	/**
	 * Tally raw gem attributes.
	 *
	 * @param array $pGems
	 * @param string $pSlot
	 * @return $this
	 */
	protected function tallyGemAttributes( array $pGems, $pSlot )
	{
		for ( $i = 0; $i < count($pGems); $i++ )
		{
			$gem = $pGems[ $i ];
			if ( isArray($gem) )
			{
				$this->tallyAttributes( $gem['attributesRaw'], "{$pSlot} gem slot {$i}" );
			}
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
		if ( isset($item->damage) && is_array($item->damage) )
		{
			$minDamage += $item->damage[ 'min' ];
			$maxDamage += $item->damage[ 'max' ];
		}

//		if ( isset($item->attributesRaw) )
//		{
//			foreach ( $item->attributesRaw as $key => $value )
//			{
//				if ( strpos($key, "Damage_") > -1 )
//				{
//					$this->debug[ $key ] = $value['min'];
//					if ( strpos($key, "_Reduction") )
//					{
//					}
//					else if ( strpos($key, "_Percent") )
//					{
//						// $minDamage += $value[ 'min' ];
//					}
//					else if ( strpos($key, "_Min#") )
//					{
//						$minDamage += $value[ 'min' ];
//					}
//					else
//					{
//						$maxDamage += $value[ 'min' ];
//					}
//
//					$keys .= $key . ',';
//				}
//			}
//		}
//
//		if ( isset($item->gems) )
//		{
//			foreach ( $item->gems as $gem )
//			{
//				foreach ( $gem['attributesRaw'] as $key => $value )
//				{
//					if ( strpos($key, "Damage_") > -1 )
//					{
//						if ( strpos($key, "_Min#") )
//						{
//							$minDamage += $value[ 'min' ];
//						}
//						else
//						{
//							$maxDamage += $value[ 'min' ];
//						}
//					}
//				}
//			}
//		}

		if ( $minDamage > 0 || $maxDamage > 0 )
		{
			$this->totalMinDamage += $minDamage;
			$this->totalMaxDamage += $maxDamage;
			$this->itemDamage = $minDamage + $maxDamage;
			$this->itemDamageData[ $pItemName ] = "{$minDamage} - {$maxDamage}";
		}
		return $this;
	}

	/**
	 * Vitality computed from level and items.
	 * @return float
	 */
	public function vitality()
	{
		return $this->vitality;
	}

	/**
	 * List of items that contribute to vitality.
	 * @return array
	 */
	public function vitalityData()
	{
		return $this->vitalityData;
	}
}
?>