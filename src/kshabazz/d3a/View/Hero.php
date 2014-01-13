<?php namespace kshabazz\d3a\View;
/**
 * Diablo 3 Assistant License is under The MIT License (MIT) [OSI Approved License]
 * Please read LICENSE.txt, included with this software for the full licensing information.
 * If no LICENSE.txt accompanied this software, then no license is granted.
 * @package kshabazz\d3a\View
 *
 * @copyright (c) 2012-2014 diablo-3-assistant by Khalifah K. Shabazz
 * timestamp: 1/11/14:8:54 AM
 */

use kshabazz\d3a\Model\Hero as HeroModel;

/**
 * Class Hero
 * @package kshabazz\d3a\View
 */
class Hero
{
	public function __construct( HeroModel $pModel )
	{
		$this->hero = $pModel;
	}

	/**
	 * Get the items.
	 *
	 * @return array
	 */
	public function getItemModels()
	{
		if ( !isset($this->itemModels) && $this->hero instanceof HeroModel )
		{
			$this->itemModels = [];
			$this->itemHashes = [];
			$this->items = $this->hero->items();
			// It is valid that the bnrHero may not have any items equipped.
			if ( isArray($this->items) )
			{
				foreach ( $this->items as $slot => $item )
				{
					$hash = str_replace( "item/", '', $item['tooltipParams'] );
					$bnItem = new BattleNet_Item( $hash, "hash", $this->bnr, $this->sql );
					$this->itemModels[ $slot ] = new Item( $bnItem->json() );
					// for output to JavaScript variable.
					$this->itemHashes[ $slot ] = $hash;
				}
			}
		}

		return $this->itemModels;
	}

	/**
	 * Get Hero, used by template engine.
	 *
	 * @return array
	 */
	public function hero()
	{
		return $this->hero;
	}

	/**
	 * Initialize this object.
	 */
	protected function init()
	{
		$this->time = microtime( TRUE ) - $_SERVER[ 'REQUEST_TIME_FLOAT' ];
		$this->hero = new HeroModel( $this->bnrHero->json() );
		$this->getItemModels();
		$this->calculator = new Calculator( $this->hero, $this->attributeMap, $this->hero->itemModels() );
	}

	/**
	 * Render setup
	 * @return $this
	 */
	public function renderSetup()
	{
		$this->name = $this->hero->name();
		$this->hardcore = ( $this->hero->hardcore ) ? 'Hardcore ' : '';
		$this->deadText = '';
		if ( $this->hero->dead )
		{
			$this->deadText = "This {$this->hardcore}hero fell on " . date( 'm/d/Y', $this->hero->{'last-updated'} ) . ' :(';
		}

		$this->sessionCacheInfo = getSessionExpireInfo( 'hero-' . $this->hero->id );
		$this->sessionTimeLeft = displaySessionTimer( $this->sessionCacheInfo['timeLeft'] );
		$this->progress = $this->hero->progress();
		$this->heroItemHashes = json_encode( $this->itemHashes );
		$this->items = $this->itemModels;
		$this->heroJson = $this->hero->json();

		return $this;
	}

	/**
	 * Set Hero
	 *
	 * @param Hero $pHero
	 * @return $this
	 */
	public function setHero( Hero $pHero )
	{
		$this->hero = $pHero;
		return $this;
	}
}
// Writing below this line can cause headers to be sent before intended ?>