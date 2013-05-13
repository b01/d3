<?php namespace D3;
/**
* Perform all D3 calculations.
*
* @var array $p_items A hash array of items, by which the keys indicate where the items are placed
*	on the hero.
*/
class Calculator
{
	const
		APS_DUAL_WIELD_BONUS = 0.15,
		CRITICAL_HIT_CHANCE_BONUS = 0.08,
		CRITICAL_HIT_DAMAGE_BONUS = 0.05;
	protected
		$attackSpeed,
		$attackSpeedData,
		$attributeMap,
		$attributeSlots,
		$attributeTotals,
		$averageDamage,
		$averageDamageData,
		$criticalHitChance,
		$criticalHitChanceData,
		$criticalHitDamage,
		$criticalHitDamageData,
		$dps,
		$damagePerSecondData,
		$dualWield,
		$gems,
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
	public function __construct( array $pItems, $pHero )
	{
		$this->attackSpeedData = [];
		$this->attributeMap = $GLOBALS[ 'settings' ][ 'ATTRIBUTE_MAP' ];
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
		$this->hero = $pHero;
		$this->items = $pItems;
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

		$this->init();
		// Collect unique attributes into the attributes map file.
		saveAttributeMap( $this->attributeMap );
	}

	/**
	* Descructor
	*/
	public function __destruct()
	{
		unset(
			$this->attackSpeed,
			$this->attackSpeedData,
			$this->attributeMap,
			$this->attributeSlots,
			$this->attributeTotals,
			$this->averageDamage,
			$this->averageDamageData,
			$this->criticalHitChance,
			$this->criticalHitChanceData,
			$this->criticalHitDamage,
			$this->criticalHitDamageData,
			$this->dps,
			$this->damagePerSecondData,
			$this->dualWield,
			$this->gems,
			$this->items,
			$this->increasedAttackSpeed,
			$this->maxDamage,
			$this->minDamage,
			$this->primaryAttribute,
			$this->primaryAttributeDamage,
			$this->baseWeaponDamage,
			$this->weaponAttacksPerSecond
		);
	}

	/**
	* Attack speed, or Attacks per Second (APS) simply means how often your character can use its skills. Taken from
	* tool-tip.
	*
	* @return Calculator
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
		echo "<div>attack per second = {$this->attackSpeed}</div>";
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

		echo "<div>attack speed = {$this->attackSpeed}</div>";
		return $this;
	}

	/*
	* Average Damage
	*
	* Computed by taking the minumum sum and maximum sum from all items and calculating the average between the two.
	*
	* @return Calculator
	*/
	protected function computeAverageDamage()
	{
	// print_r( $this->items[ 'mainHand' ] );
		echo "<table>";
		echo "<thead><tr><th>Item</th><th>minimum</th><th>maximum</th></tr></thead>";
		foreach ( $this->items as $name => $item )
		{
			if ($name === 'rightFinger')
			{
				// print_r($item);
			}
			$this->tallyItemDamage( $name, $item );
		}
		echo "</table>";

		return $this;
	}

	/**
	* Tally an items minimum and maximum damage.
	*
	* @return Calculator
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
					echo "<div>{$key} = {$value['min']}</div>";
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
			echo "<tr><td>{$pItemName}</td><td>{$minDamage}</td><td>{$maxDamage}</td><td>{$keys}</td></tr>";
		}
		return $this;
	}

	/** CALCULATORS BEGIN **/

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
			echo "<div>off-hand dps = {$this->offHandDps}</div>";
		}

		echo "<div>base weapon damage = {$this->baseWeaponDamage}</div>";
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

		echo "<div>critical hit chance = {$this->criticalHitChance}</div>";
		return $this;
	}

	/**
	* Critical Dampage
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

		echo "<div>critical damage = {$this->criticalHitDamage}</div>";
		return $this;
	}

	/**
	* Dampage Per Second
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

		echo "<div>damage_per_second = {$this->damagePerSecond}</div>";
		return $this;
	}


	/*
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

		echo "<div>increased attack speed = {$this->increasedAttackSpeed}</div>";
		return $this;
	}


	/*
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

		echo "<div>primary attribute damage = {$this->primaryAttributeDamage}</div>";
		return $this;
	}

	/*
	* Skill Damage
	*
	* @return Calculator
	*/
	protected function computeSkillDamage()
	{
		$this->skillDamage = 1;

		echo "<div>skill damage = {$this->skillDamage}</div>";
		return $this;
	}

	/*
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

	/*
	* Weapon Attacks Per Second
	*
	* ???
	*
	* @return Calculator
	*/
	protected function computeWeaponAttacksPerSecond()
	{
		$this->weaponAttacksPerSecond = ( float ) $this->items[ 'mainHand' ]->attacksPerSecond[ 'min' ];

		echo "<div>weapon attacks per second = {$this->weaponAttacksPerSecond}</div>";
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

		echo "<div>weapon damage = {$this->weaponDamage}</div>";
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

		echo '<div class="debug-info">';
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
		echo '</div>';
	}

	/**
	* Dampage Per Second
	* This calculation is take from:http://eu.battle.net/d3/en/forum/topic/4903361857
	* 	dual wield dps = 1.15 * (main-hand damage + off-hand damage) /
	*		( (1 / main-hand attacks per second) + (1 / off-hand attacks per second))
	* @return
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
	* Tally raw attributes.
	* @return float
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

			// if ( $pSlot === 'mainHand' || $pSlot === 'rightFinger' )
			// {
				// echo "<div>$pSlot</div>";
				// echo "<div>$attribute = $value</div>";
			// }
			// if ( $attribute === 'Attacks_Per_Second_Item' || $attribute === 'Attacks_Per_Second_Item_Percent' )
			// {
				// echo "<div>$pSlot</div>";
				// echo "<div>$attribute = $value</div>";
				// echo "<div>totals::$attribute = {$this->attributeTotals[ $attribute ]}</div>";
			// }

			// Add the attribute to the map collection.
			if ( !array_key_exists($attribute, $this->attributeMap) )
			{
				$this->attributeMap[ $attribute ] = '';
			}
		}
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