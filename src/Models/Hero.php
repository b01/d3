<?php namespace Kshabazz\BattleNet\D3\Models;
/**
 * Diablo 3 Assistant License is under The MIT License (MIT) [OSI Approved License]
 * Please read LICENSE.txt, included with this software for the full licensing information.
 * If no LICENSE.txt accompanied this software, then no license is granted.
 * @package Kshabazz\BattleNet\D3\Models
 *
 * @copyright (c) 2012-2013 diablo-3-assistant by Khalifah K. Shabazz
 * timestamp: 11/23/13:8:21 AM
 */
use \Kshabazz\BattleNet\D3\Models\Item,
	\Kshabazz\BattleNet\D3\Connections\Http;
use function \Kshabazz\Slib\isArray;
 /**
 * Class Hero
 * @package Kshabazz\BattleNet\D3\Models
 */
class Hero
{
	/**
	 * @var string {barbarian|crusader|demon-hunter|monk|witch-doctor|wizard}
	 */
	private $class;

	/**
	 * @var bool
	 */
	private $dead;

	/**
	 * @var array
	 */
	private $followers;

	/**
	 * @var int
	 */
	private $gender;

	/**
	 * @var bool
	 */
	private $hardcore;

	/**
	 * @var int
	 */
	private $id;

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
	private $items;

	/** @var array */
	private $itemModels;

	/**
	 * @var array
	 */
	private $kills;

	/**
	 * @var int
	 */
	private $lastUpdated;

	/**
	 * @var int
	 */
	private $level;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var int
	 */
	private $paragonLevel;

	/**
	 * @var array
	 * "progression" : {
	 *  "act1" : {
	 *      "completed" : false,
	 *      "completedQuests" : [ ]
	 * }, ...
	 */
	private $progression;

	/**
	 * @var array {active|passive}
	 * example:
	 * "skills": {
			"active": [
				{
					"skill" : {
					"slug" : "fists-of-thunder",
					"name" : "Fists of Thunder",
					"icon" : "monk_fistsofthunder",
					"level" : [''];
					"categorySlug" : "primary",
					"tooltipUrl" : "skill/monk/fists-of-thunder",
					"description" : "Generate: 14 Spirit per attack\r\n\r\nTeleport to your target and unleash a series of extremely fast punches that deal 122% weapon damage as Lightning.\r\n\r\nEvery third hit deals 183% weapon damage as Lightning split between all enemies in front of you.",
					"simpleDescription" : "Generate: 14 Spirit per attack\r\n\r\nTeleport to your target and attack it with a series of rapid punches.",
					"skillCalcId" : "a"
				}
			],
			"passive": [
				...
			]
		};
	 */
	private $skills;


	/**
	 * @var object
	 */
	private $stats;

	/**
	 * Constructor
	 *
	 * @param string $pJson
	 */
	public function __construct( $pJson )
	{
		$this->json = $pJson;
		$this->init();
		$this->data = \json_decode( $pJson, TRUE );
		$this->stats = $this->data['stats'];
		$this->kills = $this->data['kills'];
		$this->dead = $this->data['dead'];
		$this->name = $this->data['name'];
		$this->gender = (int) $this->data['gender'];
		$this->level = (int) $this->data['level'];
		$this->followers = $this->data['followers'];
		$this->hardcore = $this->data['hardcore'];
		$this->class = $this->data['class'];
		$this->items = $this->data['items'];
		$this->lastUpdated = (int) $this->data['last-updated'];
		$this->id = (int) $this->data['id'];
		$this->skills = $this->data['skills'];
		// Properties that may be empty, if a new character.
		if ( array_key_exists('progress', $this->data))
		{
			$this->progress = $this->data[ 'progress' ];
		}
		else
		{
			$this->progression = $this->data['progression'];
		}
		$this->itemModels = NULL;
	}

	/**
	 * Use the hero's class to determine the primary attribute.
	 *
	 * @return string
	 */
	private function determinePrimaryAttribute()
	{
		$primaryAttribute = NULL;
		switch( $this->class )
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
					'There is no hero class ' . $this->class .
					'. Error occurred in ' . $trace[ 0 ][ 'file' ] . ' on line ' . $trace[ 0 ][ 'line' ],
					E_USER_NOTICE
				);
		}
		return $primaryAttribute;
	}

	/**
	 * Item in the main hand.
	 *
	 * @param Http $pHttp
	 * @param string $pKey
	 * @return \Kshabazz\BattleNet\D3\Models\Item|null
	 */
	private function getItem( Http $pHttp, $pKey )
	{
		if ( array_key_exists($pKey, $this->items) )
		{
			$itemHash = $this->items[ $pKey ][ 'tooltipParams' ];
			if ( !empty($itemHash) )
			{
				$itemJson = $pHttp->getItem( $itemHash );
				return new Item( $itemJson );
			}
		}
		return NULL;
	}

	/**
	 * For each item the hero has equipped construct an Model\Item and return them as an array.
	 * This is costly, it make a HTTP request for each item on the hero.
	 *
	 * @param Http $pBnr
	 * @param bool $pForceRefresh
	 * @return array|null
	 * @throws \InvalidArgumentException
	 */
	public function getItemsAsModels( Http $pBnr, $pForceRefresh = FALSE )
	{
		if ( $this->itemModels === NULL || $pForceRefresh )
		{
			// It is valid that the hero may not have any items equipped (new character).
			if ( isArray($this->items) )
			{
				foreach ( $this->items as $slot => $item )
				{
					$hash = $item[ 'tooltipParams' ];
					$itemJson = $pBnr->getItem( $hash );
					$this->itemModels[ $slot ] = new Item( $itemJson );
				}
			}
		}

		return $this->itemModels;
	}

	/**
	 * @return $this
	 * @throws \Exception
	 */
	private function init()
	{
		// decode battle.net data to an array.
		$this->data = \json_decode( $this->json, TRUE );
		// verify the JSON is legit.
		if ( \array_key_exists('code', $this->data))
		{
			$reason = '';
			if ( \array_key_exists('reason', $this->data))
			{
				$reason = $this->data[ 'reason' ];
			}
			$errorMessage = 'There wan an error with the hero JSON.';
			$errorMessage .= ' ' . $reason;
			throw new \Exception($errorMessage);
		}

		return $this;
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
	 * Get data from the hero JSON data retrieved from Battle.net API.
	 *
	 * @param string $pProperty
	 * @param string $pType
	 * @return mixed
	 * @throws \Exception
	 */
	public function get( $pProperty, $pType = 'string' )
	{
		if ( array_key_exists($pProperty, $this->data) )
		{
			setType( $this->data[$pProperty], $pType );
			return $this->data[ $pProperty ];
		}
		throw new \Exception( 'Hero has no property ' . $pProperty );
	}

	/**
	 * @return bool
	 */
	public function hardcore()
	{
		return $this->hardcore;
	}

	/**
	 * Get the highest level completed.
	 *
	 * @return string
	 */
	public function highestProgression()
	{
		if ( !\is_array($this->progress) )
		{
			return '';
		}
		// Enjoy the flying V!
		$returnValue = '';
		foreach ( $this->progress as $level => $progression )
		{
			// When the level was not skipped.
			if ( isArray($progression) )
			{
				foreach ( $progression as $act => $progress )
				{
					// When the quest is completed.
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
	 * @return int
	 */
	public function id()
	{
		return $this->id;
	}

	/**
	 * Indicates whether the Hero has fallen.
	 *
	 * @return bool
	 */
	public function isDead()
	{
		return (bool) $this->dead;
	}

	/**
	 * Determine if a hero is brandishing a weapon in each hand.
	 *
	 * @return bool
	 */
	public function isDualWielding()
	{
//		// TODO: test!
//		if ( array_key_exists('mainHand', $this->items)
//			&& !$this->items['mainHand']->type['twoHanded']
//			&& array_key_exists('offHand', $this->items) )
//
		return $this->mainHand()->isWeapon() && $this->offHand()->isWeapon();
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
		return $this->lastUpdated;
	}

	/**
	 * Get level.
	 *
	 * @return int
	 */
	public function level()
	{
		return $this->level;
	}

	/**
	 * Item in the main hand.
	 *
	 * @param Http $pHttp
	 * @return Item|null
	 */
	public function mainHand( Http $pHttp )
	{
		return $this->getItem( $pHttp, 'mainHand' );
	}

	/**
	 * Get name.
	 *
	 * @return string
	 */
	public function name()
	{
		return $this->name;
	}

	/**
	 * Get off-hand item.
	 *
	 * @param Http $pHttp
	 * @return Item|null
	 */
	public function offHand( Http $pHttp )
	{
		return $this->getItem( $pHttp, 'offHand' );
	}

	/**
	 * Get paragon level.
	 *
	 * @return int
	 */
	public function paragonLevel()
	{
		return $this->paragonLevel;
	}

	/**
	 * Get primary attribute.
	 *
	 * @return string
	 */
	public function primaryAttribute()
	{
		if (!isset($this->primaryAttribute) )
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
}
// Writing below this line can cause headers to be sent before intended ?>