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
use \kshabazz\d3a\Application;
use \kshabazz\d3a\Abstracts\aPage;
use \kshabazz\d3a\BattleNet_Item;
use \kshabazz\d3a\Calculator;
use \kshabazz\d3a\Item;
use \kshabazz\d3a\View\Hero;
/**
 * Class GetHero
 * @package kshabazz\d3a\Controller
 */
class GetHero extends aPage
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
		$itemHashes,
		$itemModels,
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
	 * @param Application $pSystem
	 */
	public function __construct( Application $pSystem )
	{
		$this->system = $pSystem;
		$this->supers = $this->system->superGlobals();
		$this->battleNetId = $this->supers->getParam( 'battleNetId', NULL, 'string', 'GET' );
		$this->id = $this->supers->getParam( 'heroId', NULL, 'string', 'GET' );
		$this->fromCache = $this->supers->getParam( 'cache', NULL, 'bool', 'GET' );
		$this->load();
	}

	/**
	 * Get the items.
	 *
	 * @return array
	 */
	protected function processHeroItems()
	{
		if ( !isset($this->itemModels) && $this->hero !== NULL )
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
				var_dump($this->items);
				var_dump($this->itemHashes);

			}
		}

		return $this->itemModels;
	}

	/**
	 * Setup any models needed for the page view.
	 */
	public function load()
	{
		// call methods
		$this->getHeroModel();
	}

	public function getHeroModel()
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
			$this->processHeroItems();
			$this->calculator = new Calculator( $this->hero, $this->attributeMap, $this->itemModels );
		}
	}

	public function getView()
	{
		return new Hero([
			'hero' => $this->hero,
			'items' => $this->itemModels,
			'calculator' => $this->calculator
		]);
	}
}
// Writing below this line can cause headers to be sent before intended ?>