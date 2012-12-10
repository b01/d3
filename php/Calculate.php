<?php namespace d3;
/**
* Perform all D3 calculations.
*
* @var array $p_items A hash array of items, by which the keys indicate where the items are placed
*	on the hero.
*/
class Calculate
{
	protected
		$attackSpeed,
		$baseWepaondamage,
		$classDamage,
		$criticalHitDamage,
		$duelWield;

	/**
	* Constructor
	*/
	public __construct( array $p_items )
	{
		$this->items = $p_items;
		$this->baseWepaondamage = 0.0;
		$this->attackSpeed = 0.0;
		$this->criticalHitDamage = 0.0;
		$this->classDamage = 0.0;
		$this->duelWield = FALSE;
	}
	
	/**
	* Descructor
	*/
	public __destruct()
	{
		unset(
			$this->attackSpeed,
			$this->baseWepaondamage,
			$this->classDamage,
			$this->criticalHitDamage,
			$this->duelWield
		);
	}
	
	/**
	* Base Weapon Damage
	* This calculation is take from: http://eu.battle.net/d3/en/forum/topic/4903361857
	* @return 
	*/
	public function base()
	{
		// DPS Value of weapon (as shown on tooltip) / weapon attack spead : this is your BASE weapon damage. 
		if ( $this->duelWield )
		{
			// Add in the average damage of your offhand (min + max) / 2.
			$this->baseWepaondamage = ( $this->offHand->dps['min'] + $this->offHand->dps['min'] ) / 2;
		}
		else
		{
			$this->baseWepaondamage = $this->hand1->[min];
		}
	}
	
	/**
	* Dampage Per Second
	* This calculation is take from:http://eu.battle.net/d3/en/forum/topic/4903361857
	* @return 
	*/
	public function dps()
	{
		// BASE X SPEED x CRIT x SKILL x CARAC = your total dps
		$this->baseWepaondamage * $this->attackSpeed & $this->criticalHitDamage * $this->classDamage;
	}
	
	/**
	* Tally speed
	* @return null
	*/
	public function tallySpeed()
	{
		// Speed is based on the following:
		for ( $this->items as $item )
		{
			if ( $item )
			{
				$this->attackSpeed = 0.0;
			}
		}
	}
	
	/**
	* Prep dampage attributes
	* @return null
	*/
	public function PrepDps()
	{
		// BASE X SPEED x CRIT x SKILL x CARAC = your total dps
		$this->baseWepaondamage = $this->items['mainHand']['max'];
	}
}
?>