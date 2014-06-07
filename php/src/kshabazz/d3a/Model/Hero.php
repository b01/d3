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
use function \Kshabazz\Slib\isArray;
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
		$multiplierVit,
		$name,
		$paragonLevel,
		$physicalResist,
		$poisonResist,
		$primaryAttribute,
		$primeStats,
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

		$this->init()
			 ->levelUpBonuses();

		// grab these after they have been computed.
		$this->primeStats = [
			'dexterity' => $this->dexterity,
			'intelligence' => $this->intelligence,
			'strength' => $this->strength,
			'vitality' => $this->vitality
		];
	}

	/**
	 * Character class
	 * @return string
	 */
	public function characterClass()
	{
		return $this->class;
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
	 * Dexterity
	 * @return int
	 */
	public function dexterity()
	{
		return $this->dexterity;
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
	 * @return int
	 */
	public function id()
	{
		return $this->id;
	}

	/**
	 * Initialize this object.
	 * @return $this
	 * @throws \Exception
	 */
	protected function init()
	{
		// decode battle.net data to an array.
		$this->battleNet = json_decode( $this->json, TRUE );
		// verify the JSON is legit.
		if (array_key_exists('code', $this->battleNet))
		{
			if (array_key_exists('reason', $this->battleNet))
			{
				$reason = $this->battleNet[ 'reason' ];
			}
			$errorMessage = 'There wan an error with the hero JSON.';
			$errorMessage .= ' ' . $reason;
			throw new \Exception($errorMessage);
		}
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

		return $this;
	}

	/**
	 * Intelligence
	 * @return int
	 */
	public function intelligence()
	{
		return $this->intelligence;
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
	 * Level
	 * @return int
	 */
	public function level()
	{
		return $this->level;
	}

	/**
	 * Paragon level
	 * @return int
	 */
	public function paragonLevel()
	{
		return $this->paragonLevel;
	}

	/**
	 * Add in addition attributes from level bonus.
	 */
	protected function levelUpBonuses()
	{
		$this->multiplierDex = ( $this->primaryAttribute === 'Dexterity_Item' ) ? 3 : 1;
		$this->multiplierInt = ( $this->primaryAttribute === 'Intelligence_Item' ) ? 3 : 1;
		$this->multiplierStr = ( $this->primaryAttribute === 'Strength_Item' ) ? 3 : 1;
		$this->multiplierVit = ( $this->primaryAttribute === 'Vitality_Item' ) ? 3 : 1;

		// These totals are based on level, all increment by 1 per level, except the primary, which increments by 3.
		// based on hero class.
		$totalLevel = $this->level + $this->paragonLevel;
		$this->dexterity += ( $totalLevel * $this->multiplierDex );
		$this->intelligence += ( $totalLevel * $this->multiplierInt );
		$this->strength += ( $totalLevel * $this->multiplierStr );
		$this->vitality += ( $totalLevel * $this->multiplierStr );

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
	 * @return string
	 */
	public function primaryAttribute()
	{
		return $this->primaryAttribute;
	}

	/**
	 * Prime stats
	 * @return array
	 */
	public function primeStats()
	{
		return $this->primeStats;
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
	 * Strength
	 * @return int
	 */
	public function strength()
	{
		return $this->strength;
	}

	/**
	 * Vitality
	 * @return int
	 */
	public function vitality()
	{
		return $this->vitality;
	}
}
// Writing below this line can cause headers to be sent before intended ?>