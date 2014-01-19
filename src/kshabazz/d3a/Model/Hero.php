<?php namespace kshabazz\d3a\Model;

/**
 * Diablo 3 Assistant License is under The MIT License (MIT) [OSI Approved License]
 * Please read LICENSE.txt, included with this software for the full licensing information.
 * If no LICENSE.txt accompanied this software, then no license is granted.
 * @package kshabazz\d3a\Model
 *
 * @copyright (c) 2012-2013 diablo-3-assistant by Khalifah K. Shabazz
 * timestamp: 11/23/13:8:21 AM
 */
 /**
 * Class Hero
 * @package kshabazz\d3a\Model
 */
class Hero
{
	const
		APS_DUAL_WIELD_BONUS = 0.15,
		CRITICAL_HIT_CHANCE_BONUS = 0.08,
		CRITICAL_HIT_DAMAGE_BONUS = 0.05;

	protected
		$armor,
		$battleNet,
		$class,
		$coldResist,
		$criticalHitChance,
		$criticalHitDamage,
		$dexterity,
		$dodgeChance,
		$dualWield,
		$fireResist,
		$gender,
		$hardcore,
		$id,
		$intelligence,
		$items,
		$json,
		$lastUpdated,
		$level,
		$lightingResist,
		$multiplierDex,
		$multiplierInt,
		$multiplierStr,
		$name,
		$noItemsStats,
		$paragonLevel,
		$physicalResist,
		$poisonResist,
		$primaryAttribute,
		$progress,
		$skills,
		$stats,
		$strength,
		$vitality;

	/**
	 * Constructor
	 *
	 * @param string $pJson
	 */
	public function __construct( $pJson )
	{
		$this->dualWield = FALSE;
		$this->json = $pJson;
		// base data every hero starts with.
		$this->armor = 7;
		$this->criticalHitChance = 0.05;
		$this->criticalHitDamage = 0.50;
		$this->dexterity = 7;
		$this->dodgeChance = 0.01;
		$this->dualWield = FALSE;
		$this->intelligence = 7;
		$this->strength = 7;
		$this->vitality = 7;
		$this->coldResist = 1;
		$this->fireResist = 1;
		$this->lightingResist = 1;
		$this->poisonResist = 1;
		$this->physicalResist = 1;
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
		$this->init()
			 ->levelUpBonuses();

		$this->noItemsStats[ $this->primaryAttribute ][ 'muliplier' ] = 3;
		$this->noItemsStats[ $this->primaryAttribute ][ 'primary' ] = TRUE;
	}

	/**
	 * Based on the character's class.
	 *
	 * @return Hero
	 */
	protected function determinePrimaryAttribute()
	{
		switch( $this->class )
		{
			case "monk":
			case "demon hunter":
			case "demon-hunter":
				$this->primaryAttribute = "Dexterity_Item";
				break;
			case "crusader":
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
					'There is no hero class ' . $this->class .
					'. Error occurred in ' . $trace[ 0 ][ 'file' ] . ' on line ' . $trace[ 0 ][ 'line' ],
					E_USER_NOTICE
				);
		}
		return $this;
	}

	/**
	 * Get data from the hero JSON data retrieved from Battle.net API.
	 *
	 * @param string $pProperty
	 * @param string $pType
	 * @return mixed
	 * @throws \Exception
	 */
	public function get( $pProperty, $pType = 'string' )
	{
		if ( array_key_exists($pProperty, $this->battleNet) )
		{
			setType( $this->battleNet[$pProperty], $pType );
			return $this->battleNet[ $pProperty ];
		}
		throw new \Exception( 'Class \Kshabazz\d3a\Model\Hero has no property ' . $pProperty );
	}

	/**
	 * @return bool
	 */
	public function hardcore()
	{
		return $this->hardcore;
	}

	/**
	 * Initialize this object.
	 *
	 * @return $this
	 */
	protected function init()
	{
		// decode battle.net data to an array.
		$this->battleNet = json_decode( $this->json, TRUE );
		// shortcuts to some data in the Battle.net JSON.
		$this->class = $this->battleNet[ 'class' ];
		$this->id = ( int ) $this->battleNet[ 'id' ];
		$this->gender = ( int ) $this->battleNet[ 'gender' ];
		$this->hardcore = ( bool ) $this->battleNet[ 'hardcore' ];
		$this->items = $this->battleNet[ 'items' ];
		$this->lastUpdated = ( int ) $this->battleNet[ 'last-updated' ];
		$this->level = ( int ) $this->battleNet[ 'level' ];
		$this->name = $this->battleNet[ 'name' ];
		$this->paragonLevel = ( int ) $this->battleNet[ 'paragonLevel' ];
		$this->progress = $this->battleNet[ 'progress' ];
		$this->skills = $this->battleNet[ 'skills' ];
		$this->stats = $this->battleNet[ 'stats' ];

		$this->determinePrimaryAttribute();

//Should be placed somewhere else.
//		if ( isset($this->itemModels['mainHand']) )
//		{
//			// TODO: test!
//			$this->dualWield = isWeapon( $this->itemModels['mainHand'] )
//							&& isWeapon( $this->itemModels['offHand'] );
//		}

		return $this;
	}

	/**
	 * @return int
	 */
	public function id()
	{
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function items()
	{
		return $this->items;
	}

	/**
	 * @return string JSON from battle.net
	 */
	public function json()
	{
		return $this->json;
	}

	/**
	 * return string
	 */
	public function lastUpdated()
	{
		return $this->lastUpdated;
	}

	/**
	 * Add in addition attributes from level bonus.
	 */
	protected function levelUpBonuses()
	{
		$this->multiplierDex = ( $this->primaryAttribute === "Dexterity_Item" ) ? 3 : 1;
		$this->multiplierInt = ( $this->primaryAttribute === "Intelligence_Item" ) ? 3 : 1;
		$this->multiplierStr = ( $this->primaryAttribute === "Strength_Item" ) ? 3 : 1;

		// Do not remember what this is??? Look like primary attribute bonus based on level.
		// also looks to be incomplete, does not make much sense. Looks like I was reaching.
		$this->dexterity += ( $this->level + $this->paragonLevel ) * $this->multiplierDex;
		$this->intelligence += ( $this->level * $this->multiplierInt );
		$this->strength += ( $this->level * $this->multiplierStr );

		// Looks like the same as above, in some way.
		foreach( $this->noItemsStats as $attribute => &$values )
		{
			$multiplier = $values[ 'muliplier' ];
			$values[ 'value' ] += ( $this->level + $this->paragonLevel ) * $multiplier;
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function name()
	{
		return $this->name;
	}

	/**
	 * @return array
	 */
	public function noItemsStats()
	{
		return $this->noItemsStats;
	}

	/**
	 * @return string
	 */
	public function primaryAttribute()
	{
		return $this->primaryAttribute;
	}

	/**
	 * Determine the highest level completed.
	 *
	 * @return string
	 */
	public function progression()
	{
		$pProgress = $this->progress;
		// Enjoy the flying V!
		$returnValue = '';
		foreach ( $pProgress as $level => $progresssion )
		{
			if ( isArray($progresssion) )
			{
				foreach ( $progresssion as $act => $progress )
				{
					if ( isArray($progress['completedQuests']) )
					{
						$length = count( $progress['completedQuests'] ) - 1;
						$returnValue = "Highest completed: {$level} {$act} {$progress['completedQuests'][ $length ]['name']}";
					}
				}
			}
		}
		return $returnValue;
	}

	/**
	 * Get character skills.
	 *
	 * @return array
	 */
	public function skills()
	{
		return $this->skills;
	}

	/**
	 * Get character stats.
	 *
	 * @return array
	 */
	public function stats()
	{
		return $this->stats;
	}

	/**
	 * Tally raw attributes.
	 * @param $pRawAttribute
	 * @param $pSlot
	 * @return $this
	 */
	protected function tallyAttributes( $pRawAttribute, $pSlot )
	{
		foreach ( $pRawAttribute as $attribute => $values )
		{
			$value = ( float )$values[ 'min' ];
			// Initialize an attribute in the totals array.
			if ( !array_key_exists($attribute, $this->stats) )
			{
				$this->stats[ $attribute ] = 0.0;
				$this->slotStats[ $attribute ] = [];
			}
			// Sum it up.
			$this->stats[ $attribute ] += $value;
			// A break-down of each attribute totals. An item can have multiple types of the same attribute
			// use a combination of the slot and attribute name to keep them from replacing the previous value.
			$this->slotStats[ $attribute ][ $pSlot . '_' . $attribute ] = $value;
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
}
// Writing below this line can cause headers to be sent before intended ?>