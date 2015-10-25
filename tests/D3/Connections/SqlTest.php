<?php namespace Kshabazz\Tests\BattleNet\D3\Connections;

use Kshabazz\BattleNet\D3\Connections\Sql;
use Kshabazz\BattleNet\D3\Item;

/**
 * Class SqlTest
 *
 * @package Kshabazz\Tests\BattleNet\D3\Connections
 * @coversDefaultClass \Kshabazz\BattleNet\D3\Connections\Sql
 */
class SqlTest extends \PHPUnit_Framework_TestCase
{
	private
		$battleNetId,
		$fixturesDir,
		$ipAddress;

	public function setUp()
	{
		$this->battleNetId = 'msuBREAKER#1374';
		$this->ipAddress = '0.0.0.0';
		$this->fixturesDir = FIXTURES_PATH . DIRECTORY_SEPARATOR;
	}

	public function test_sql()
	{
		$pdo = $this->getPdoMock();
		$sql = new Sql( $this->battleNetId, $pdo, $this->ipAddress );
		$this->assertInstanceOf( '\\Kshabazz\\BattleNet\\D3\\Connections\\Sql', $sql, 'Could not instantiate Sql object' );
	}

	public function test_getHero()
	{
		$heroJson = \file_get_contents( FIXTURES_PATH . DIRECTORY_SEPARATOR . 'hero-3955832-no-items.json' );
		$return = [ 0 => ['json' => $heroJson] ];
		$pdoMock = $this->getPdoMock( $return );
		$retriever = new Sql( $this->battleNetId, $pdoMock, $this->ipAddress );
		$json = $retriever->getHero( 3955832 );
		$hero = \json_decode( $json );
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
	 * @covers ::saveItem
	 */
	public function test_saveItem()
	{
		$pdoMock = $this->getPdoMock();
		$sql = new Sql( $this->battleNetId, $pdoMock, $this->ipAddress );
		$item = $this->loadItem();
		$actual = $sql->saveItem(
			$item->name(),
			$item->type()->id,
			$item->tooltipParams(),
			$item->json()
		);
		$this->assertTrue( $actual );
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

	/**
	 * Load an item for testing.
	 *
	 * @return \Kshabazz\BattleNet\D3\Item
	 */
	public function loadItem()
	{
		$fixtureFile = $this->fixturesDir . 'item-Unique_Helm_006_x1.json';
		$itemJson = \file_get_contents( $fixtureFile );
		return $item = new Item( $itemJson );
	}
}
