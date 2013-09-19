<?php namespace kshabazz\d3a;
/**
* Take inventory of all stats on each item equipped.
*
* @var array $p_items A hash array of items, by which the keys indicate where the items are placed
*	on the hero.
*/
class Model_Hero
{
	const
		APS_DUAL_WIELD_BONUS = 0.15,
		CRITICAL_HIT_CHANCE_BONUS = 0.08,
		CRITICAL_HIT_DAMAGE_BONUS = 0.05;

	protected
		$hero,
		$items,
		$slotStats,
		$stats;

	/**
	* Constructor
	*/
	public function __construct( Hero $pHero, array & $attributeMap, array $pItems )
	{
		$this->hero = $pHero;
		$this->items = $pItems;
		$this->stats = [];
		$this->attributeMap = $attributeMap;

		$this->init();
	}

	/**
	* Initialize this object.
	*/
	protected function init()
	{
		$this->processRawAttributes();

		var_dump($this->stats);
		$this->primaryAttribute = $this->hero->primaryAttribute();

		if ( isset($this->items['mainHand']) )
		{
			$this->dualWield = $this->items[ 'mainHand' ]->type[ 'twoHanded' ];
		}
	}

	/**
	* Loop through raw attributes for every item.
	* @return float
	*/
	protected function processRawAttributes()
	{
		// Transfer the items from an array to properties.
		if ( isArray($this->items) )
		{
			foreach ( $this->items as $placement => $item )
			{
				// Compute some things.
				$this->tallyAttributes( $item->attributesRaw, $placement );
				// Tally gems.
				if ( isArray($item->gems) )
				{
					for ( $i = 0; $i < count($item->gems); $i++ )
					{
						$gem = $item->gems[ $i ];
						if ( isArray($gem) )
						{
							$this->tallyAttributes( $gem['attributesRaw'], "{$placement} gem slot {$i}" );
						}
					}
				}
			}
		}
	}

	/**
	* Tally raw attributes.
	* @return float
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

			// Add the attribute to the map collection.
			if ( !array_key_exists($attribute, $this->attributeMap) )
			{
				$this->attributeMap[ $attribute ] = '';
			}
		}
		return $this;
	}
}
?>