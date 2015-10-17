<?php namespace Kshabazz\BattleNet\D3\Tests;

use Kshabazz\BattleNet\D3\Client as D3_Client;

use const \Kshabazz\BattleNet\D3\Tests\API_KEY;

/**
 * Class ClientTest
 *
 * @package Kshabazz\BattleNet\D3\Tests
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{

	private
		/** @var string Battle.Net gamer tag. */
		$battleNetTag,
		/** @var \Kshabazz\BattleNet\D3\Client Battle.Net HTTP client. */
		$client,
		/** @var int Diablo 3 hero ID. */
		$heroId;

	public function setUp()
	{
		$this->battleNetTag = 'msuBREAKER#1374';
		$this->heroId = 3955832;
		$this->client = new D3_Client( API_KEY, $this->battleNetTag );

	}

	public function tearDown()
	{
		unset( $this->client );
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
		$actual = $this->client->getHero( $this->heroId );
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

	/**
	 * @interception client-getHero
	 */
	public function test_heroFactory()
	{
		$actual = D3_Client::heroFactory( API_KEY, $this->battleNetTag, $this->heroId );
		$this->assertInstanceOf( '\\Kshabazz\\BattleNet\\D3\\Hero', $actual );
	}

	/**
	 * @depends test_getHero
	 * @interception client-getItem
	 */
	public function test_itemFactory($itemHashes)
	{
		$actual = D3_Client::itemFactory(
			API_KEY,
			$this->battleNetTag,
			$itemHashes['mainHand']
		);

		$this->assertInstanceOf('\\Kshabazz\\BattleNet\\D3\\Item', $actual);
	}

	/**
	 * @interception client-getProfile
	 */
	public function test_profileFactory()
	{
		$actual = D3_Client::profileFactory(
			API_KEY,
			$this->battleNetTag
		);

		$this->assertInstanceOf('\\Kshabazz\\BattleNet\\D3\\Profile', $actual);
	}
}
?>