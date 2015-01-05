<?php namespace Kshabazz\BattleNet\D3;
/**
 * @copyright (c) Khalifah K. Shabazz
 */

use
	\Kshabazz\BattleNet\D3\Connections\Http;

/**
 * Class Hero
 *
 * @package Kshabazz\BattleNet\D3
 */
class Hero
{
	const
		BASE_DUAL_WIELD_IAS_BONUS = 0.15,
		BASE_CRITICAL_HIT_CHANCE_BONUS = 0.05,
		BASE_CRITICAL_HIT_DAMAGE_BONUS = 0.5;

	private
		/** @var array */
		$data,
		/** @var array */
		$followers,
		/**
		 * @var array
		 * example:
		 * "items" : {
		 *      "mainHand" : {
		 *      "id" : "FistWeapon_1H_000",
		 *      "name" : "Worn Knuckles",
		 *      "icon" : "fistweapon_1h_000_demonhunter_male",
		 *      "displayColor" : "white",
		 *      "tooltipParams" : "item/ChoIqvDNpwMSBwgEFScYtUkwiQI4kANAAGCQAxjO4KibCVAIWAI",
		 *      "randomAffixes" : [ ],
		 *      "craftedBy" : [ ]
		 *  }
		 * }
		 */
		$items,
		/** @var array List of hash for the items the hero has equipped. */
		$itemsHashes,
		/**
		 * @var array
		 * "progression" : {
		 *  "act1" : {
		 *      "completed" : false,
		 *      "completedQuests" : [ ]
		 * }, ...
		 */
		$progression,
		/** @var int */
		$arcaneResist,
		/** @var int */
		$armor,
		/** @var float Base attack speed. */
		$attackSpeed,
		$blockAmountMin,
		$blockAmountMax,
		/** @var float */
		$blockChance,
		/** @var int */
		$coldResist,
		/** @var float */
		$critChance,
		/** @var float */
		$critDamage,
		$damageIncrease,
		$damageReduction,
		/** @var int */
		$dexterity,
		/** @var int */
		$fireResist,
		/** @var float */
		$goldFind,
		/** @var int */
		$intelligence,
		/** @var int */
		$life,
		$lifeOnHit,
		$lifePerKill,
		$lifeSteal,
		/** @var int */
		$lightningResist,
		$magicFind,
		$primaryResource,
		/** @var int */
		$physicalResist,
		/** @var int */
		$poisonResist,
		/** @var float A punch can do about 1 - 4 damage, that's an average of 2.5. */
		$punchDamage,
		$secondaryResource,
		/** @var int Base of core stats. */
		$statBase,
		/** @var int */
		$strength,
		/** @var int */
		$vitality,
		$thorns;

	/**
	 * Constructor
	 *
	 * @param string $pJson
	 */
	public function __construct( $pJson )
	{
		$this->json = $pJson;
		$this->init( $pJson );
		$this->followers = $this->data[ 'followers' ];
		$this->items = $this->data[ 'items' ];
		// Properties that may be empty, if a new character.
		// This value changed in RoS.
		if ( \array_key_exists('progress', $this->data))
		{
			$this->progression = $this->data[ 'progress' ];
		}
		else
		{
			$this->progression = $this->data[ 'progression' ];
		}
		$this->itemModels = NULL;

		// Stats
		$this->arcaneResist = 0;
		$this->armor = 0;
		$this->attackSpeed = 1.00;
		$this->blockAmountMin = 0.0;
		$this->blockAmountMax = 0;
		$this->blockChance = 0.0;
		$this->coldResist = 0;
		$this->critChance = 0.05;
		$this->critDamage = 0.5;
		$this->damageIncrease = 0.0;
		$this->damageReduction = 0.0;
		$this->fireResist = 0;
		$this->goldFind = 0.0;
		$this->intelligence = 0;
		$this->life = 0;
		$this->lifeOnHit = 0.0;
		$this->lifePerKill = 0.0;
		$this->lifeSteal = 0.0;
		$this->lightningResist = 0;
		$this->magicFind = 0.0;
		$this->physicalResist = 0;
		$this->poisonResist = 0;
		$this->primaryResource = 0;
		$this->punchDamage = 2.5;
		$this->secondaryResource = 0;
		$this->statBase = 7;
		$this->strength = 0;
		$this->thorns = 0.0;
	}


	/**
	 * Get armor stat.
	 *
	 * @return int
	 */
	public function armor()
	{
		$levelBonus = $this->level() * 3;
		$this->armor = $this->statBase + $levelBonus + $this->strength();
		return $this->armor;
	}

	/**
	 * Get attack speed.
	 *
	 * @return float
	 */
	public function attackSpeed()
	{
		return $this->attackSpeed;
	}

	/**
	 * Character class
	 *
	 * @return string (barbarian|crusader|demon-hunter|monk|witch-doctor|wizard)
	 */
	public function characterClass()
	{
		return $this->data[ 'class' ];
	}

	/**
	 * @return float
	 */
	public function criticalHitChance()
	{
		return $this->critChance;
	}

	/**
	 * @return float
	 */
	public function criticalHitDamage()
	{
		return $this->critDamage;
	}

	/**
	 * Get dexterity.
	 *
	 * @return int
	 */
	public function dexterity()
	{
		$primaryResource = $this->primaryAttribute();
		$multiplier = ( $primaryResource === 'Dexterity_Item' ) ? 3 : 1;
		$this->baseAttributeLevelBonus( 'dexterity', $multiplier );
		return $this->dexterity;
	}

	/**
	 * Get number of elites killed.
	 *
	 * @return int
	 */
	public function eliteKills()
	{
		return (int) $this->data[ 'kills' ][ 'elites' ];
	}

	/**
	 * Get gender.
	 *
	 * @return int 0 for male, 1 for woman.
	 */
	public function gender()
	{
		return (int) $this->data[ 'gender' ];
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
		if ( \array_key_exists($pProperty, $this->data) )
		{
			setType( $this->data[$pProperty], $pType );
			return $this->data[ $pProperty ];
		}
		throw new \Exception( 'Hero has no property ' . $pProperty );
	}

	/**
	 * Get the highest level completed.
	 *
	 * @return string
	 */
	public function highestProgression()
	{
		if ( empty($this->progression) )
		{
			return '';
		}
		// Enjoy the flying V!
		$returnValue = '';
		foreach ( $this->progression as $level => $progression )
		{
			// When the level was not skipped.
			if ( \is_array($progression) && \count($progression) > 0 )
			{
				foreach ( $progression as $act => $progress )
				{
					// When the quest is completed.
					$completedQuests = $progress['completedQuests'];
					if ( \is_array($completedQuests) && \count($completedQuests) > 0 )
					{
						$length = \count( $progress['completedQuests'] ) - 1;
						$returnValue = "Highest completed: {$level} {$act} {$progress['completedQuests'][ $length ]['name']}";
					}
				}
			}
		}
		return $returnValue;
	}

	/**
	 * @return int
	 */
	public function id()
	{
		return (int) $this->data[ 'id' ];
	}

	/**
	 * Get intelligence.
	 *
	 * @return int
	 */
	public function intelligence()
	{
		$primaryResource = $this->primaryAttribute();
		$multiplier = ( $primaryResource === 'Intelligence_Item' ) ? 3 : 1;
		$this->baseAttributeLevelBonus( 'intelligence', $multiplier );
		return $this->intelligence;
	}

	/**
	 * Indicates whether the Hero has fallen.
	 *
	 * @return bool
	 */
	public function isDead()
	{
		return (bool) $this->data[ 'dead' ];
	}

	/**
	 * Determine if a hero is brandishing a weapon in each hand.
	 *
	 * @param Http $pHttp
	 * @return bool
	 */
	public function isDualWielding( Http $pHttp )
	{
		// We can have a weapon in each hand if one of them has nothing.
		if ( !\array_key_exists('mainHand', $this->items) || !\array_key_exists('offHand', $this->items) )
		{
			return FALSE;
		}
		$itemHashes[ 'mainHand' ] = $this->items[ 'mainHand' ];
		$itemHashes[ 'offHand' ] = $this->items[ 'offHand' ];
		$itemModels = $pHttp->getItemsAsModels( $itemHashes );
		$mainHand = $itemModels[ 'mainHand' ];
		$offHand = $itemModels[ 'offHand' ];
		return ( $mainHand!== NULL && $mainHand->isWeapon() && !(bool)$mainHand->type->twoHanded )
			 && ( $offHand!== NULL && $offHand->isWeapon() );
	}

	/**
	 * Get hardcore flag.
	 *
	 * @return bool
	 */
	public function isHardCore()
	{
		return (bool) $this->data[ 'hardcore' ];
	}

	/**
	 * Get seasonal flag.
	 *
	 * @return bool
	 */
	public function isSeasonal()
	{
		return (bool) $this->data[ 'seasonal' ];
	}

	/**
	 * Get items.
	 *
	 * @return array
	 */
	public function items()
	{
		return $this->items;
	}

	/**
	 * Get a list of hashes for each item the hero has equipped.
	 *
	 * @return array|null Null when the hero has no items equipped.
	 */
	public function itemsHashesBySlot()
	{
		if ( !\is_array($this->items) || \count($this->items) < 1 )
		{
			return NULL;
		}

		foreach ( $this->items as $slot => $item )
		{
			$this->itemsHashes[ $slot ] = $item[ 'tooltipParams' ];
		}

		return $this->itemsHashes;
	}

	/**
	 * Get JSON.
	 *
	 * @return string JSON passed into constructor.
	 */
	public function json()
	{
		return $this->json;
	}

	/**
	 * Get when last updated.
	 *
	 * @return string
	 */
	public function lastUpdated()
	{
		return (int) $this->data[ 'last-updated' ];
	}

	/**
	 * Get level.
	 *
	 * @return int
	 */
	public function level()
	{
		return (int) $this->data['level'];
	}

	/**
	 * Get name.
	 *
	 * @return string
	 */
	public function name()
	{
		return $this->data['name'];
	}

	/**
	 * Get paragon level.
	 *
	 * @return int
	 */
	public function paragonLevel()
	{
		return $this->data[ 'paragonLevel' ];
	}

	/**
	 * Get character stats calculated by Battle.Net.
	 *
	 * @return array
	 */
	public function preCalculatedStats()
	{
		return $this->data[ 'stats' ];
	}

	/**
	 * Get primary attribute.
	 *
	 * @return string
	 */
	public function primaryAttribute()
	{
		if ( !isset($this->primaryAttribute) )
		{
			$this->primaryAttribute = $this->determinePrimaryAttribute();
		}
		return $this->primaryAttribute;
	}

	/**
	 * Get hero act progress.
	 * @return array
	 */
	public function progression()
	{
		return $this->progression;
	}

	/**
	 * Get damage you can do with a single punch.
	 *
	 * @return float
	 */
	public function punchDamage()
	{
		return $this->punchDamage;
	}

	/**
	 * Get character skills.
	 *
	 * @return array
	 */
	public function skills()
	{
		return $this->data[ 'skills' ];
	}

	/**
	 * Get primary stat bonus (bonuses from items not included).
	 *
	 * @return int
	 */
	public function primaryAttributeBonus()
	{
		$primaryResource = \str_replace( '_Item', '', $this->primaryAttribute() );
		$primaryResource = \strtolower( $primaryResource );
		$primaryResourceBonus = 1 + $this->{$primaryResource}() / 100;
		return $primaryResourceBonus;
	}

	/**
	 * Get strength.
	 *
	 * @return int
	 */
	public function strength()
	{
		$primaryResource = $this->primaryAttribute();
		$multiplier = ( $primaryResource === 'Strength_Item' ) ? 3 : 1;
		$this->baseAttributeLevelBonus( 'strength', $multiplier );
		return $this->strength;
	}

	/**
	 * Get vitality.
	 *
	 * @return int
	 */
	public function vitality()
	{
		$this->baseAttributeLevelBonus( 'vitality', 2 );
		return $this->vitality;
	}

	/**
	 * Black box for computing the total for dexterity/intelligence/strength/vitality.
	 *
	 * @param string $pProperty
	 * @param int $pMultiplier
	 */
	private function baseAttributeLevelBonus( $pProperty, $pMultiplier )
	{
		$this->{$pProperty} = 7;
		$totalLevels = $this->level();
		// Compute total based on hero level.
		$this->{$pProperty} += ( $totalLevels * $pMultiplier );
	}

	/**
	 * Use the hero's class to determine the primary attribute.
	 *
	 * @return string
	 */
	private function determinePrimaryAttribute()
	{
		$primaryAttribute = NULL;
		switch( $this->characterClass() )
		{
			case "monk":
			case "demon hunter":
			case "demon-hunter":
				$primaryAttribute = "Dexterity_Item";
				break;
			case "crusader":
			case "barbarian":
				$primaryAttribute = "Strength_Item";
				break;
			case "wizard":
			case "witch-doctor":
			case "witch doctor":
			case "shaman":
				$primaryAttribute = "Intelligence_Item";
				break;
			default:
				$trace = debug_backtrace();
				trigger_error(
					'There is no hero class ' . $this->characterClass() .
					'. Error occurred in ' . $trace[ 0 ][ 'file' ] . ' on line ' . $trace[ 0 ][ 'line' ],
					E_USER_NOTICE
				);
		}
		return $primaryAttribute;
	}

	/**
	 * @return $this
	 * @throws \Exception
	 */
	private function init()
	{
		// decode battle.net data to an array.
		$this->data = \json_decode( $this->json, TRUE );

		if ( !\is_array($this->data) || \count($this->data) < 1 ) {
			throw new \InvalidArgumentException( 'Invalid JSON. Please verify the string is valid JSON.' );
		}

		// verify the JSON is legit.
		if ( \array_key_exists('code', $this->data) )
		{
			$reason = '';
			if ( \array_key_exists('reason', $this->data) )
			{
				$reason = ' Reason: ' . $this->data[ 'reason' ];
			}
			$errorMessage = 'There wan an error with the hero JSON.' . $reason;
			throw new \Exception($errorMessage);
		}

		return $this;
	}
}
?>