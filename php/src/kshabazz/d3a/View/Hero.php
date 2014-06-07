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

use function kshabazz\d3a\getSessionExpireInfo, kshabazz\d3a\displaySessionTimer;

/**
 * Class Hero
 * @package kshabazz\d3a\View
 */
class Hero
{
	private
		$hardcore;

	/**
	 * @param array $pModels
	 */
	public function __construct( array $pModels )
	{
		$this->calculator = $pModels[ 'calculator' ];
		$this->hero = $pModels[ 'hero' ];
		$this->items = $pModels[ 'items' ];
		$this->battleNetUrlSafeId = $pModels[ 'battleNetUrlSafeId' ];
	}

	/**
	 * Render setup
	 * @return $this
	 */
	public function render()
	{
		$this->hardcore = ( $this->hero->hardcore() ) ? 'Hardcore' : '';
		$data = [
			'battleNetUrlSafeId' => $this->battleNetUrlSafeId,
			'calculator' => $this->calculator,
			'hero' => $this->hero,
			'items' => $this->items,
			'name' => $this->hero->name(),
			'hardcore' => $this->hardcore,
			'sessionCacheInfo' => getSessionExpireInfo( 'hero-' . $this->hero->id() ),
			'sessionTimeLeft' => displaySessionTimer( $this->sessionCacheInfo['timeLeft'] ),
			'heroJson'=> $this->hero->json(),
			'progress' => $this->hero->progression(),
			'heroItemHashes' => \json_encode( $this->itemHashes ),
			'time' => \microtime( TRUE ) - $_SERVER[ 'REQUEST_TIME_FLOAT' ],
            'pageTitle' => 'Hero'
		];

		return $data;
	}

	protected function getDeadText()
	{
		$returnValue = '';
		if ( $this->hero->dead )
		{
			$returnValue = "This {$this->hardcore} hero fell on " . date( 'm/d/Y', $this->hero->lastUupdated() ) . ' :(';
		}

		return $returnValue;
	}
}
// Writing below this line can cause headers to be sent before intended ?>