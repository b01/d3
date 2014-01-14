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
use function kshabazz\d3a\getSessionExpireInfo, kshabazz\d3a\displaySessionTimer;

/**
 * Class Hero
 * @package kshabazz\d3a\View
 */
class Hero
{
	/**
	 * @param array $pModels
	 */
	public function __construct( array $pModels )
	{
		$this->calculator = $pModels[ 'calculator' ];
		$this->hero = $pModels[ 'hero' ];
		$this->items = $pModels[ 'items' ];
	}

	/**
	 * Initialize this object.
	 */
	protected function init()
	{
		$this->time = \microtime( TRUE ) - $_SERVER[ 'REQUEST_TIME_FLOAT' ];
		$this->hero = new HeroModel( $this->bnrHero->json() );
		$this->getItemModels();
		$this->calculator = new Calculator( $this->hero, $this->attributeMap, $this->hero->itemModels() );
	}

	/**
	 * Render setup
	 * @return $this
	 */
	public function render()
	{
		$this->name = $this->hero->name();
		$this->hardcore = ( $this->hero->hardcore ) ? 'Hardcore ' : '';
		$this->deadText = '';
		if ( $this->hero->dead )
		{
			$this->deadText = "This {$this->hardcore}hero fell on " . date( 'm/d/Y', $this->hero->{'last-updated'} ) . ' :(';
		}

		$this->sessionCacheInfo = getSessionExpireInfo( 'hero-' . $this->hero->id() );
		$this->sessionTimeLeft = displaySessionTimer( $this->sessionCacheInfo['timeLeft'] );
		$this->heroJson = $this->hero->json();
		$this->progress = $this->hero->progression();
		$this->heroItemHashes = \json_encode( $this->itemHashes );

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