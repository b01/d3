<?php namespace Kshabazz\BattleNet\D3\Models;
/**
 * Get the users item from Battle.Net and present it to the user; store it locally in a database
 * behind the scenes. The item will only be updated after a few ours of retrieving it.
 */

/**
 * Class Item
 * http://us.battle.net/api/d3/data/item/Cj0I-bvTgAsSBwgEFdosyssdb2mxyh10HmzAHfKS3AgdcIt38CILCAEVbEIDABgWICAwiQI4_AJAAFAMYJUDGMvMrsMGUABYAg&extra=0&showClose=1
 * @package Kshabazz\BattleNet\D3\Models
 */
class Item
{
	/**
	 * @var bool
	 */
	private $accountBound;

	/**
	 * @var array
	 * example: "attacksPerSecond" : {
	 *  "min" : 1.2999999523162842,
	 *  "max" : 1.2999999523162842
	 * }
	 */
	private $attacksPerSecond;

	/**
	 * @var object
	 * example: "attributes" : {
		"primary" : [ {
		"text" : "+211 Strength",
		"affixType" : "default",
		"color" : "blue"
		}, {
		"text" : "+112 Vitality",
		"affixType" : "default",
		"color" : "blue"
		}, {
		"text" : "2.80% of Damage Dealt Is Converted to Life",
		"affixType" : "default",
		"color" : "blue"
		} ],
		"secondary" : [ ],
		"passive" : [ ]
		}
	 */
	private $attributes;

	/**
	 * @var array
	 * example: "attributesRaw" : {
	 *  "Durability_Max_Before_Reforge" : { "min" : 403.0, "max" : 403.0 },
	 *  ...
	 * }
	 */
	private $attributesRaw;

	/**
	 * @var int
	 */
	private $bonusAffixes;

	/**
	 * @var int
	 */
	private $bonusAffixesMax;

	/**
	 * @var object
	 */
	private $craftedBy;

	/**
	 * @var string
	 */
	private $displayColor;

	/**
	 * @var array
	 * "dps" : {
			"min" : 206.69999241828918,
			"max" : 206.69999241828918
	 *  }
	 */
	private $dps;

	/**
	 * @var array
	 */
	private $gems;

	/**
	 * @var string
	 */
	private $icon;

	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var int
	 */
	private $itemLevel;

	/**
	 * @var array
	 * example: "maxDamage" : {
	 *  "min" : 206.0,
	 *  "max" : 206.0
	 * }
	 */
	private $maxDamage;

	/**
	 * @var array
	 * example: "minDamage" : {
	 *  "min" : 112.0,
	 *  "max" : 112.0
	 * }
	 */
	private $minDamage;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var array
	 */
	private $socketEffects;

	/**
	 * @var array
	 */
	private $randomAffixes;

	/**
	 * @var object
	 */
	private $recipe;

	/**
	 * @var int
	 */
	private $requiredLevel;

	/**
	 * @var string ex: item/CioI4YeygAgSBwgEFcgYShEdhBF1FR2dbLMUHape7nUwDTiTA0AAUApgkwMYkOPQlAI
	 */
	private $tooltipParams;

	/**
	 * @var string
	 */
	private $typeName;

	/**
	 * @var array
	 *  "type" : {
	 *      "twoHanded" : false,
	 *      "id" : "MightyWeapon1H"
	 * }
	 */
	private $type;

	public static $offHandTypes = [
			"offhandother",
			"orb",
			"mojo",
			"quiver",
			"shield"
		],
		$oneHandWeaponTypes = [
			"axe",
			"bow",
			"ceremonialdagger",
			"crossbow",
			"dagger",
			"fistweapon",
			"handxbow",
			"mace",
			"mightyweapon1h",
			"spear",
			"sword",
			"wand"
		],
		$twoHandedWeaponTypes = [
			"combatstaff",
			"mightyweapon2h",
			"sword2h",
			"polearm",
			"mace2h",
			"axe2h",
			"staff",
			"crossbow",
			"bow"
		];

	/**
	 * Constructor
	 *
	 * @param string $pJson
	 * @throws \InvalidArgumentException
	 */
	public function __construct( $pJson )
	{
		$this->json = $pJson;
		$this->data = \json_decode( $this->json );
		if ($this->data === NULL )
		{
			throw new \InvalidArgumentException( 'Invalid item JSON received.' );
		}
		$this->init();
	}

	/**
	 * Get any property that isset.
	 *
	 * @param $pName
	 * @return mixed
	 * @thows \Exception
	 */
	public function __get( $pName )
	{
		return $this->{$pName};
	}

	/**
	 * What to do when this object is converting to a string.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->json;
	}

	/**
	 * Compute [min, max] damage for the tool-tip.
	 *
	 * Damage the item can do, if weapon.
	 * @return mixed
	 */
	public function damage()
	{
		$returnValue = [ 'min' => 0.0, 'max' => 0.0 ];
		foreach ( $this->attributesRaw as $attribute => $value )
		{
			if ( strpos($attribute, "Damage_Weapon_Min") !== FALSE )
			{
				$returnValue[ 'min' ] += ( float ) $value[ 'min' ];
				$returnValue[ 'max' ] += ( float ) $value[ 'min' ];
			}
			if ( strpos($attribute, "Damage_Weapon_Delta") !== FALSE )
			{
				$returnValue[ 'max' ] += ( float ) $value[ 'min' ];
			}
		}
		return $returnValue;
	}

	/**
	 *  Get name of an items special effects.
	 * Get list string of item effect.
	 * @return string
	 */
	public function effects()
	{
		$returnValue = '';
		if ( array_key_exists("Damage_Weapon_Min#Poison", $this->attributesRaw) )
		{
			$returnValue .= " poison";
		}
		if ( array_key_exists("Armor_Item", $this->attributesRaw) )
		{
			$returnValue .= " armor";
		}
		return $returnValue;
	}

	/**
	 * Get item ID.
	 *
	 * @return mixed
	 */
	public function id()
	{
		return $this->id;
	}

	/**
	 * Initialize all the properties for this object.
	 */
	private function init()
	{
		$this->accountBound = $this->data->accountBound;
		$this->attributes = $this->data->attributes;
		$this->attributesRaw = $this->data->attributesRaw;
		$this->bonusAffixes = $this->data->bonusAffixes;
		$this->bonusAffixesMax = $this->data->bonusAffixesMax;
		$this->craftedBy = $this->data->craftedBy;
		$this->displayColor = $this->data->displayColor;
		$this->gems = $this->data->gems;
		$this->icon = $this->data->icon;
		$this->id = $this->data->id;
		$this->itemLevel = $this->data->itemLevel;
		$this->name = $this->data->name;
		$this->socketEffects = $this->data->socketEffects;
		$this->requiredLevel = ( int ) $this->data->requiredLevel;
		$this->tooltipParams = $this->data->tooltipParams;
		$this->typeName = $this->data->typeName;
		$this->type = $this->data->type;
		// Fields that may NOT be on every item.
		$this->attacksPerSecond = isset( $this->data->attacksPerSecond ) ? $this->data->attacksPerSecond : NULL;
		$this->dps = isset( $this->data->dps ) ? $this->data->dps : NULL;
		$this->maxDamage = isset( $this->data->maxDamage ) ? $this->data->maxDamage : NULL;
		$this->minDamage = isset( $this->data->minDamage ) ? $this->data->minDamage : NULL;
		$this->recipe = isset( $this->data->recipe ) ? $this->data->recipe : NULL;
		$this->randomAffixes = isset($this->data->randomAffixes) ? $this->data->randomAffixes : NULL;
	}

	/**
	 * Check if an items type is a weapon.
	 *
	 * @return bool
	 */
	function isWeapon()
	{
		$itemType = strtolower( $this->type->id );
		return in_array( $itemType, self::$oneHandWeaponTypes );
	}

	/**
	 * Get JSON use to initialize this object.
	 *
	 * @return string
	 */
	public function json()
	{
		return $this->json;
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
	 * Get recipe for constructing the item at the forge.
	 *
	 * @return object
	 */
	public function recipe()
	{
		return $this->recipe;
	}

	/**
	 * Get web HASH.
	 * @return string
	 */
	public function tooltipParams()
	{
		return $this->tooltipParams;
	}

	/**
	 * Get the item type.
	 * @return string
	 */
	public function type()
	{
		return $this->type;
	}
}
?>