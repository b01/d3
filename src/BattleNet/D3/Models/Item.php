<?php namespace Kshabazz\BattleNet\D3\Models;
/**
 * Get the users item from Battle.Net and present it to the user; store it locally in a database
 * behind the scenes. The item will only be updated after a few ours of retrieving it.
 */
use Kshabazz\BattleNet\D3\Models;
/**
 * Class Item
 * @package Kshabazz\BattleNet\D3\Models
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
		if ( $this->isWeapon($this->type) )
		{
			$this->calculateDamage();
		}
		$this->getEffects();
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
	 * Check if an items type is a weapon.
	 *
	 * @param array $pItemType
	 * @return bool
	 */
	function isWeapon( array $pItemType )
	{
		$itemType = strtolower( $pItemType['id'] );
		return in_array( $itemType, self::$oneHandWeaponTypes );
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