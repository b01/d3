<?php namespace Kshabazz\Tests\BattleNet\D3\Connections;

use Kshabazz\BattleNet\D3\Connections\Sql;

/**
 * Class SqlTest
 *
 * @package Kshabazz\Tests\BattleNet\D3\Connections
 */
class SqlTest extends \PHPUnit_Framework_TestCase
{
	private
		$battleNetId,
		$ipAddress,
		$pdo;

	public function setUp()
	{
		$this->battleNetId = 'msuBREAKER#1374';
		$this->ipAddress = '0.0.0.0';
		$updDir = DIRECTORY_SEPARATOR . '..';
		$pdoPath = realpath(
			__DIR__ . $updDir . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'Pdo.php'
		);
		$this->pdo = include $pdoPath;
	}

	public function test_sql()
	{
		$sql = new Sql( $this->battleNetId, $this->pdo, $this->ipAddress );
		$this->assertInstanceOf( 'Kshabazz\BattleNet\D3\Connections\Sql', $sql, 'Could not instantiate Sql object' );
	}
}
