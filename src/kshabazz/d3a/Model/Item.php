<?php namespace kshabazz\d3a\Model;
/**
 * Get the users item from Battle.Net and present it to the user; store it locally in a database
 * behind the scenes. The item will only be updated after a few ours of retrieving it.
 *
 */

use kshabazz\d3a\Model;
use function \kshabazz\d3a\isWeapon;

/**
 * Class Item
 * @package kshabazz\d3a\Model
 */
class Item extends Model
{
    protected
        $effects,
        $item,
        $tooltipParams,
        $type;

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

	protected
		$damage,
		$damageAttributes;

    /**
     * Constructor
     * @param $pJson
     */
    public function __construct( $pJson )
	{
		parent::__construct( $pJson );

		if ( isWeapon($this) )
		{
			$this->calculateDamage();
		}
		$this->getEffects();

		$this->hash = substr( $this->tooltipParams, 5 );
	}

    /**
     * Damage the item can do, if weapon.
     * @return int
     */
    public function damage()
	{
		return $this->damage;
	}

	/**
	* Get list string of item effect.
	* @return string
	*/
	public function effects()
	{
		return $this->effects;
	}

    /**
     * Get web HASH.
     * @return string
     */
    public function hash()
	{
		return $this->hash;
	}

	/**
	* Get the item type.
	* @return string
	*/
	public function type()
	{
		return $this->type;
	}

	/** END	GETTER/SETTER **/

	/**
	* Compute min - max damage range for the tool-tip.
	* @return ItemModel
	*/
	protected function calculateDamage()
	{
		$this->damage = [
			"min" => 0.0,
			"max" => 0.0
		];
		$this->damageAttributes = [];
		foreach ( $this->attributesRaw as $attribute => $value )
		{
			if ( strpos($attribute, "Damage_Weapon_Min") !== FALSE )
			{
				$this->damage[ 'min' ] += ( float ) $value[ 'min' ];
				$this->damage[ 'max' ] += ( float ) $value[ 'min' ];
				$this->damageAttributes[ $attribute ] = $value;
			}
			if ( strpos($attribute, "Damage_Weapon_Delta") !== FALSE )
			{
				$this->damage[ 'max' ] += ( float ) $value[ 'min' ];
				$this->damageAttributes[ $attribute ] = $value;
			}
		}
		return $this;
	}

    /**
     *  Get name of an items special effects.
     * @return $this
     */
    protected function getEffects()
	{
		$effects = '';
		if ( array_key_exists("Damage_Weapon_Min#Poison", $this->attributesRaw) )
		{
			$effects .= " poison";
		}
		if ( array_key_exists("Armor_Item", $this->attributesRaw) )
		{
			$effects .= " armor";
		}
		$this->effects = $effects;
		return $this;
	}
}
?>