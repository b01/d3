<?php namespace kshabazz\d3a\Controller;
use \kshabazz\d3a;
use \kshabazz\d3a\BattleNet_Requestor;
use \kshabazz\d3a\Model_GetProfile;
use \kshabazz\d3a\BattleNet_Sql;
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
* Controller for the home page.
*/
class GetProfile
{
	protected
		$battleNetId,
		$dqi,
		$model,
		$sql,
		$supers;

	/**
	* Controller actions go here.
	*/
	public function __construct( d3a\SuperGlobals $pSupers )
	{
		$this->supers = $pSupers;
		$this->dqi = NULL;
		$this->sql = NULL;
		$this->battleNetId = $this->supers->getParam( 'battleNetId', NULL, 'string', 'POST' );

		$this->setup();
	}

	public function setup()
	{
		$this->dqi = new BattleNet_Requestor( $this->battleNetId );
		$this->sql = new BattleNet_Sql( \kshabazz\d3a\USER_IP_ADDRESS );
		$this->model = new Model_GetProfile( $this->supers, $this->dqi, $this->sql );
	}

	/**
	* Set BattleNet_Requestor object
	*/
	public function setBnr( BattleNet_Requestor $pBnr )
	{
		// Set a valid BattleNet_Requestor object or throw an exception.
		if ( $pBnr instanceof BattleNet_Requestor )
		{
			$this->dqi = $pBnr;
			return $this;
		}

		throw new Exception( 'Must be a valid BattleNet_Requestor object, no other values are excepted, not even NULL.' );
	}

	/**
	* Set BattleNet_Sql object
	*/
	public function setSql( \kshabazz\d3a\BattleNet_Sql $pSql)
	{
		// Set a valid BattleNet_Sql object or throw an exception.
		if ( $pSql instanceof \kshabazz\d3a\BattleNet_Sql )
		{
			$this->sql = $pSql;
			return $this;
		}

		throw new Exception( 'Must be a valid BattleNet_Sql object, no other values are excepted, not even NULL.' );
	}

	public function getModel()
	{
		return $this->model;
	}
}
?>