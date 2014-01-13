<?php namespace kshabazz\d3a\Page;
/**
 * Diablo 3 Assistant License is under The MIT License (MIT)
 * [OSI Approved License]. Please read LICENSE.txt, included with this
 * software for the full licensing information. If no LICENSE.txt accompanied
 * this software, then no license is granted.
 *
 * @package kshabazz\d3a\Controller
 * @copyright (c) 2012-2013 Khalifah K. Shabazz
 */
use \kshabazz\d3a;
/**
 * Class GetHero
 * @package kshabazz\d3a\Controller
 */
class GetHero extends d3a\Abstracts\Page
{
	protected
		$attributeMap,
		$battleNetUrlSafeId,
		$bnr,
		$bnrHero,
		$calculator,
		$id,
		$fromCache,
		$items,
		$heroItems,
		$hero,
		$requestTime,
		$sessionCacheInfo,
		$sql,
		$supers,
		$time,
		$view;

	/**
	 * Controller actions go here.
	 * @param d3a\Application $pSystem
	 */
	public function __construct( d3a\Application $pSystem )
	{
		$this->system = $pSystem;
		$this->supers = $this->system->superGlobals();
		$this->battleNetId = $this->supers->getParam( 'battleNetId', NULL, 'string', 'GET' );
		$this->id = $this->supers->getParam( 'heroId', NULL, 'string', 'GET' );
		$this->fromCache = $this->supers->getParam( 'cache', NULL, 'bool', 'GET' );
		$this->load();
	}

	/**
	 * Setup any models needed for the page view.
	 */
	public function load()
	{
		// call methods
		$this->setupModel();
	}

	/**
	 *
	 *
	 * @return {BattleNet_Hero|NULL}
	 */
	public function getModel()
	{
		return $this->hero;
	}

	public function setupModel()
	{
		if ( isString($this->battleNetId) && isString($this->id) )
		{
			// Check if the cache has expired for the hero JSON.
			$this->sessionCacheInfo = \kshabazz\d3a\getSessionExpireInfo( 'hero-' . $this->id, $this->fromCache );
			// Build the view model.
			$this->bnr = new \kshabazz\d3a\BattleNet_Requestor( $this->battleNetId );
			$this->sql = new \kshabazz\d3a\BattleNet_Sql( \kshabazz\d3a\USER_IP_ADDRESS );
			$this->bnrHero = new \kshabazz\d3a\BattleNet_Hero(
				$this->id,
				$this->bnr,
				$this->sql,
				$this->sessionCacheInfo[ 'loadFromBattleNet' ]
			);

			$this->attributeMap = loadJsonFile( \kshabazz\d3a\ATTRIBUTE_MAP_FILE );

			$this->hero = new \kshabazz\d3a\Model\Hero( $this->bnrHero->json() );
		}
	}
}
// Writing below this line can cause headers to be sent before intended ?>