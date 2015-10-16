<?php namespace Kshabazz\BattleNet\D3\Tests;

use Kshabazz\BattleNet\D3\Client;

/**
 * Class ClientTest
 *
 * @package Kshabazz\BattleNet\D3\Tests
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
	/** @var \Kshabazz\BattleNet\D3\Client Battle.Net HTTP client. */
	private $client;

	public function setUp()
	{
		$this->client = new Client(
			\Kshabazz\BattleNet\D3\Tests\API_KEY,
			'msuBREAKER#1374'
		);
	}

	/**
	 * @interception client-getProfile
	 */
	public function test_getProfile()
	{
		$actual = $this->client->getProfile();
		$this->assertInstanceOf( '\\Kshabazz\\BattleNet\\D3\\Profile', $actual );
	}

	/**
	 * @interception client-getHero
	 */
	public function test_getHero()
	{
		$actual = $this->client->getHero( 3955832 );
		$this->assertInstanceOf( '\\Kshabazz\\BattleNet\\D3\\Hero', $actual );
		return $actual->itemsHashesBySlot();
	}

	/**
	 * @depends test_getHero
	 * @interception client-getItem
	 */
	public function test_getItem($itemHashes)
	{
		$actual = $this->client->getItem($itemHashes['mainHand']);
		$this->assertInstanceOf( '\\Kshabazz\\BattleNet\\D3\\Item', $actual );
	}
}
?>