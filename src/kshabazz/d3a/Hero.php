<?php namespace kshabazz\d3a;
/**
* Get the users item from Battle.Net and present it to the user; store it locally in a database behind the scenes.
* The item will only be updated after a few ours of retrieving it.
*
* @var $p_heroId string User BattleNet ID.
* @var $pDqi object Data Query Interface.
* @var $pSql object SQL.
*/
class Hero extends Model
{
	protected
		$armor,
		$dexterity,
		$dualWield,
		$intelligence,
		$itemModels,
		$noItemsStats,
		$primaryAttribute,
		$strength,
		$vitality;

	/**
	 * Constructor
	 * @param string $p_json Hero JSON from BattleNet query.
	 */
	public function __construct( $p_json )
	{
		parent::__construct( $p_json );
		$this->itemModels = [];
		// Increase to 8% above level 59
		if ($this->level > 59 )
		{
			$this->criticalHitChance = 0.08;
		}
		$this->levelUpBonuses();
	}

	/** BEGIN	GETTER/SETTER **/

	/**
	* Character class
	*
	* @return string
	*/
	public function getCharacterClass()
	{
		return $this->characterClass;
	}

	/**
	* Get the item, first check the local DB, otherwise pull from Battle.net.
	*
	* @return array
	*/
	public function getItemModels()
	{
		return $this->itemModels;
	}

	/**
	* Get base dexterity.
	* @return int
	*/
	public function dexterity()
	{
		return $this->dexterity;
	}

	/**
	* Indicates use of two-hand weapons.
	*
	* @return
	*/
	public function dualWield()
	{
		if ( !isset($this->dualWield) )
		{
			$this->dualWield = $this->items[ 'mainHand' ]->type[ 'twoHanded' ];
		}
		return $this->dualWield;
	}

	/**
	*  Get stats when no items are equipped attribute.
	* @return string
	*/
	public function noItemsStats()
	{
		return $this->noItemsStats;
	}

	/**
	*  Get primary attribute.
	* @return string
	*/
	public function primaryAttribute()
	{
		return $this->primaryAttribute;
	}

	/** END	GETTER/SETTER **/
}
?>