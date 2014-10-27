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
		$ipAddress;

	public function setUp()
	{
		$this->battleNetId = 'msuBREAKER#1374';
		$this->ipAddress = '0.0.0.0';
	}

	public function test_sql()
	{
		$pdo = $this->getPdoMock();
		$sql = new Sql( $this->battleNetId, $pdo, $this->ipAddress );
		$this->assertInstanceOf( '\\Kshabazz\\BattleNet\\D3\\Connections\\Sql', $sql, 'Could not instantiate Sql object' );
	}

	public function test_getHero()
	{
		$heroJson = file_get_contents( FIXTURES_PATH . DIRECTORY_SEPARATOR . 'hero-3955832-no-items.json' );
		$return = [ 0 => ['json' => $heroJson] ];
		$pdoMock = $this->getPdoMock( $return );
		$retriever = new Sql( $this->battleNetId, $pdoMock, $this->ipAddress );
		$json = $retriever->getHero( 3955832 );
		$hero = json_decode( $json );
		$this->assertEquals( 3955832, $hero->id );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage Hero ID should be an integer.
	 */
	public function test_getHero_with_invalid_id()
	{
		$pdoMock = $this->getPdoMock();
		$retriever = new Sql( $this->battleNetId, $pdoMock, $this->ipAddress );
		$retriever->getHero( 'test' );
	}

	public function test_getHero_addRequest()
	{
		$pdoMock = $this->getPdoMock();
		$retriever = new Sql( $this->battleNetId, $pdoMock, $this->ipAddress );
		$this->assertTrue( $retriever->addRequest('test') );
	}

	/**
	 * @return \PDO
	 */
	private function getPdoMock( $pReturn = NULL, $pReturn2 = TRUE )
	{
		require_once FIXTURES_PATH . DIRECTORY_SEPARATOR . 'PdoMock.php';
		$pdoMock = $this->getMock( '\\PdoMock', ['prepare'] );
		$pdoMock->method( 'prepare' )
			->willReturn( new \PDOStatementMock($pReturn, $pReturn2) );
		return $pdoMock;
	}
}
