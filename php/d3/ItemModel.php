<?php
namespace d3;
/**
* Get the users item from Battle.Net and present it to the user; store it locally in a database
* behind the scenes. The item will only be updated after a few ours of retrieving it.
*
*/

/**
* var $p_itemHash string User BattleNet ID.
* var $p_dqi object Data Query Interface.
* var $p_sql object SQL.
* var $p_userIp string User IP address.
*/
class ItemModel extends BattleNetModel 
{
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
	*/
	public function __construct( $p_json )
	{
		parent::__construct( $p_json );

		// if ( isset($this->attacksPerSecond) )
		if ( isWeapon($this) )
		{
			$this->calculateDamage();
		}
		$this->getEffects();

	}

	/** BEGIN GETTER/SETTER **/

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
	*
	* @return ItemModel Chainable
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