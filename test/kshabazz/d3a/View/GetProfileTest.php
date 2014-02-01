<?php namespace kshabazz\d3a\test\View;
use kshabazz\d3a\BattleNet_Requestor;
use kshabazz\d3a\BattleNet_Sql;
use kshabazz\d3a\SuperGlobals;

/**
 * Created by PhpStorm.
 * User: Khalifah
 * Date: 1/21/14
 * Time: 6:26 PM
 */

class GetProfileTest extends \PHPUnit_Framework_TestCase
{
	private
		$battleNetId,
		$bnr,
		$bnSql,
		$supers;

	public function setUp()
	{
		$this->battleNetId = 'msuBREAKER#1374';
		$this->bnr = new BattleNet_Requestor( $this->battleNetId );
		$this->bnSql = new BattleNet_Sql( \kshabazz\d3a\USER_IP_ADDRESS );
		$this->supers = new SuperGlobals();
		$_SESSION = [];
	}

	public function test_initialization()
	{
		$view = new \kshabazz\d3a\View\GetProfile([
			'battleNetId' => $this->battleNetId,
			'clearCache' => TRUE,
			'dqi' => $this->bnr,
			'sql' => $this->bnSql,
			'supers' => $this->supers,
		]);

		$this->assertInstanceOf('\\kshabazz\\d3a\\View\\GetProfile', $view, 'Failed to initialize GetProfile view.' );
	}
}
