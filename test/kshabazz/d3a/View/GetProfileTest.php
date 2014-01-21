<?php namespace kshabazz\d3a\test\View;
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
		$dqi,
		$sql,
		$supers;

	public function setUp()
	{
		$this->battleNetId = 'msuBREAKER#1374';
		$this->dqi = new BattleNet_Requestor( $this->battleNetId );
		$this->sql = new BattleNet_Sql( \kshabazz\d3a\USER_IP_ADDRESS );
		$this->supers = new SuperGlobals();
	}

	public function test_initialization()
	{
		$view = new \kshabazz\d3a\View\GetProfile([
			'battleNetId' => $this->battleNetId,
			'clearCache' => $this->clearCache,
			'dqi' => $this->dqi,
			'sql' => $this->sql,
			'supers' => $this->supers,
		]);

		$this->assertInstanceOf('\\kshabazz\\d3a\\View\\GetProfile', $view, 'Failed to initialize GetProfile view.' );
	}
}
