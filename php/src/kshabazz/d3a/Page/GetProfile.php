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

use kshabazz\d3a\Abstracts\aPage;
use \kshabazz\d3a\Application;
use \kshabazz\d3a\BattleNet_Requestor;
use \kshabazz\d3a\BattleNet_Sql;

/**
 * Class GetProfile
 * @package kshabazz\d3a\Page
 */
class GetProfile extends aPage
{
	protected
		$battleNetId,
		$clearCache,
		$dqi,
		$loadFromDb,
		$sql,
		$supers,
		$view;

	/**
	* Controller actions go here.
	*/
	public function __construct( Application $pSystem )
	{
		parent::__constructor( $pSystem );
		$this->supers = $pSystem->superGlobals();
		$this->dqi = NULL;
		$this->sql = NULL;
		$this->battleNetId = $this->supers->getParam( 'battleNetId', NULL, 'string', 'GET' );
		$this->clearCache = $this->supers->getParam( 'clearCache', FALSE, 'bool', 'GET' );

		$this->load();
	}

	public function load()
	{
		$this->bnr = new BattleNet_Requestor( $this->battleNetId );
		$this->sql = new BattleNet_Sql( \kshabazz\d3a\USER_IP_ADDRESS );
		$this->view = new \kshabazz\d3a\View\GetProfile([
			'battleNetId' => $this->battleNetId,
			'clearCache' => $this->clearCache,
			'bnr' => $this->bnr,
			'sql' => $this->sql,
			'supers' => $this->supers,
		]);
	}
}
?>