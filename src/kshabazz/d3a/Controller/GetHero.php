<?php namespace kshabazz\d3a\Controller;
use \kshabazz\d3a;
/**
 * Diablo 3 Assistant License is under The MIT License (MIT)
 * [OSI Approved License]. Please read LICENSE.txt, included with this
 * software for the full licensing information. If no LICENSE.txt accompanied
 * this software, then no license is granted.
 *
 * @package kshabazz\d3a\Controller
 * @copyright (c) 2012-2013 Khalifah K. Shabazz
 */
/**
 * Class GetHero
 * @package kshabazz\d3a\Controller
 */
class GetHero extends \kshabazz\d3a\Abstracts\Controller
{
	protected
		$battleNetUrlSafeId,
		$bnr,
		$id,
		$fromCache,
		$items,
		$bnrHero,
		$hero,
		$heroItems,
		$model,
		$sessionCacheInfo,
		$supers,
		$view;

	/**
	 * Controller actions go here.
	 * @param \kshabazz\d3a\SuperGlobals $pSuper
	 */
	public function __construct( d3a\SuperGlobals $pSuper )
	{
		$this->supers = $pSuper;
		$this->battleNetId = $this->supers->getParam( 'battleNetId', NULL, 'string', 'GET' );
		$this->id = $this->supers->getParam( 'heroId', NULL, 'string', 'GET' );
		$this->fromCache = $this->supers->getParam( 'cache', NULL, 'bool', 'GET' );
		$this->items = NULL;
		$this->bnrHero = NULL;
		$this->dqi = NULl;
		$this->sql = NULL;
		$this->bnrHero = NULL;
		$this->hero = NULL;
		$this->heroItems = [];
		// call methods
		$this->setupModel();
	}

	/**
	 * BATTLENET_D3_API_PROFILE_URL
	 *
	 * @param null $pModel
	 * @return mixed
	 */
	public function getModel( $pModel = NULL )
	{
		return $this->model;
	}

	public function setupModel()
	{
		if ( \kshabazz\d3a\isString($this->battleNetId) && \kshabazz\d3a\isString($this->id) )
		{
			// Check if the cache has expired for the hero JSON.
//			$this->sessionCacheInfo = \kshabazz\d3a\getSessionExpireInfo( 'heroTime', $this->fromCache );
			$this->sessionCacheInfo = \kshabazz\d3a\getSessionExpireInfo( 'hero-' . $this->id, $this->fromCache );
			// Build the view model.
			$this->bnr = new \kshabazz\d3a\BattleNet_Requestor( $this->battleNetId );
			$this->sql = new \kshabazz\d3a\BattleNet_Sql(
				\kshabazz\d3a\DSN,
				\kshabazz\d3a\DB_USER,
				\kshabazz\d3a\DB_PSWD,
				\kshabazz\d3a\USER_IP_ADDRESS
			);
			$this->bnrHero = new \kshabazz\d3a\BattleNet_Hero(
				$this->id,
				$this->bnr,
				$this->sql,
				$this->sessionCacheInfo[ 'loadFromBattleNet' ]
			);

			$attributeMap = \kshabazz\d3a\loadJsonFile( \kshabazz\d3a\ATTRIBUTE_MAP_FILE );

			$this->hero = new \kshabazz\d3a\Hero( $this->bnrHero->json() );
			$this->model = new \kshabazz\d3a\Model_GetHero( $this->bnrHero, $attributeMap, $this->bnr, $this->sql );
			$this->model->setHero( $this->hero );
		}
	}
}
// DO NOT WRITE BELOW THIS LINE, NOT EVEN WHITE-SPACE CHARS.
?>