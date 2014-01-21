<?php namespace kshabazz\d3a\Page;
/**
 * Diablo 3 Assistant License is under The MIT License (MIT) [OSI Approved License]
 * Please read LICENSE.txt, included with this software for the full licensing information.
 * If no LICENSE.txt accompanied this software, then no license is granted.
 * @package kshabazz\d3a\Controller
 *
 * @copyright (c) 2012-2013 diablo-3-assistant by Khalifah K. Shabazz
 * Timestamp: 11/11/13:7:37 AM
 */

use kshabazz\d3a\Abstracts\aPage;
use kshabazz\d3a\Application;
use kshabazz\d3a\BattleNet_Item;
use kshabazz\d3a\BattleNet_Requestor;
use kshabazz\d3a\BattleNet_Sql;

/**
 * Class Item
 * @package kshabazz\d3a\Controller
 */
class Item extends aPage
{
	protected
		$battleNetId,
		$id,
		$idType,
		$item,
		$json,
		$showExtra,
		$tooltipParam;

	/**
	 * Initialize the object.
	 */
	public function __construct( Application $pSystem )
	{
		parent::__constructor( $pSystem );
		$this->supers = $this->system->superGlobals();
		$this->battleNetId = $this->supers->getParam( 'battleNetId', NULL, 'string', 'GET' );
		$this->showExtra = $this->supers->getParam( 'extra', NULL, 'string', 'GET' );
		$this->load();
	}

	public function load()
	{
		$hash = $this->supers->getParam( 'hash', NULL, 'string', 'GET' );
		$id = $this->supers->getParam( 'id', NULL, 'string', 'GET' );
		$name = $this->supers->getParam( 'name', NULL, 'string', 'GET' );
		$tooltipParam = $this->supers->getParam( 'tooltipParam', NULL, 'string', 'GET' );
		if ( isString($tooltipParam) )
		{
			$this->id = $tooltipParam;
			$this->idType = 'hash';
		}
		elseif ( isString($hash) )
		{
			$this->id = str_replace( 'item/', '', $hash );
			$this->idType = 'hash';
		}
		else if ( isString($id) )
		{
			$this->id = $id;
			$this->idType = 'id';
		}
		else if ( isString($name) )
		{
			$this->id = $name;
			$this->idType = 'name';
		}

		if ( isString($this->battleNetId) && isString($this->id) )
		{
			$dqi = new BattleNet_Requestor( $this->battleNetId );
			$sql = new BattleNet_Sql( \kshabazz\d3a\USER_IP_ADDRESS );
			$battleNetItem = new BattleNet_Item( $this->id, $this->idType, $dqi, $sql );
			// Init item as an object.
			if ( is_object($battleNetItem) )
			{
				$this->json = $battleNetItem->json();
				$this->item = new \kshabazz\d3a\Model\Item( $this->json );
				$this->tooltipParam = substr( $this->item->tooltipParams, 5 );
			}
		}

		$this->view = new \kshabazz\d3a\View\Item([
			'item' => $this->item,
			'json' => $this->json
		]);
	}
}
// Writing below this line can cause headers to be sent before intended ?>