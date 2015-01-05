<?php namespace Kshabazz\Tests\BattleNet\D3;

use
	\Kshabazz\BattleNet\D3\Connections\Http,
	\Kshabazz\BattleNet\D3\Hero,
	\Kshabazz\Interception\StreamWrappers\Http as HttpWrapper,
	\Kshabazz\Slib\HttpClient;

/**
 * @class HeroTest
 * @package Kshabazz\Tests\BattleNet\D3
 */
class HeroTest extends \PHPUnit_Framework_TestCase
{
	private
		/** @var string Directory where fixtures live. */
		$fixturesDir,
		/** @var int */
		$heroId,
		/** @var \Kshabazz\BattleNet\D3\Hero */
		$heroNoItems,
		/** @var string. */
		$json;

	/**
	 * Setup
	 */
	public function setUp()
	{
		$this->fixturesDir = FIXTURES_PATH . DIRECTORY_SEPARATOR;
		$this->heroId = 36131726;
		$this->json = \file_get_contents( $this->fixturesDir . 'hero-36131726.json' );

		$heroFixture = $this->fixturesDir . 'hero-3955832-no-items.json';
		$noItemsJson = \file_get_contents( $heroFixture );
		$this->heroNoItems = new Hero( $noItemsJson );

	}

	public function test_getting_the_hero_id()
	{
		$hero = new Hero( $this->json );
		$id = $hero->id();
		$this->assertEquals( $this->heroId, $id, 'Invalid hero id returned.' );
	}

	/**
	 * Should throw an error when a property is not found.
	 *
	 * @expectedException \Exception
	 * @expectedExceptionMessage Hero has no property test123
	 */
	public function test_throwing_an_error_on_an_undefined_property()
	{
		$hero = new Hero( $this->json );
		$hero->get( 'test123' );
	}

	public function test_retrieving_json()
	{
		$hero = new Hero( $this->json );
		$json = $hero->json();
		$data = \json_decode( $json, TRUE );
		$this->assertEquals( $this->heroId, $data['id'], 'Invalid hero JSON returned.' );
	}

	public function test_retrieving_lastUpdated()
	{
		$hero = new Hero( $this->json );
		$this->assertEquals( 1387045578, $hero->lastUpdated(), 'Invalid hero lastUpdated date returned.' );
	}

	public function test_retrieving_name()
	{
		$hero = new Hero( $this->json );
		$name = $hero->name();
		$this->assertEquals( 'Kashara', $name, 'Invalid hero name returned.' );
	}

	public function test_retrieving_progression()
	{
		$hero = new Hero( $this->json );
		$progress = $hero->highestProgression();
		$this->assertEquals(
			'Highest completed: inferno act2 BetrayeroftheHoradrim',
			$progress,
			'Invalid hero progression returned.'
		);
	}

	public function test_retrieving_skills()
	{
		$hero = new Hero( $this->json );
		$skills = $hero->skills();
		$this->assertArrayHasKey( 'active', $skills, 'Invalid hero skills returned.' );
	}

	public function test_preCalculatedStats()
	{
		$hero = new Hero( $this->json );
		$stats = $hero->preCalculatedStats();
		$this->assertEquals(53479, $stats['life']);
	}

	/**
	 * Test retrieving items.
	 */
	public function test_retrrieving_items()
	{
		$hero = new Hero( $this->json );
		$tooltipParams = $hero->items();
		$itemParams = [
			'head' => 'item/CmAI76DVrAQSBwgEFSMZlIsdlD3juR1bToDJHc9FH8sdo9Ya6B0SXPT8HYYCY-owCTjTAkAAUBJg1wJqJQoMCAAQ9fHWhoSAgKADEhUIxKuTpwgSBwgEFfee2KswDTgAQAEYuZvfxg5QBlgC',
		];
		$this->assertEquals($itemParams['head'], $tooltipParams['head']['tooltipParams'],
			'Invalid head item returned from Model_GetHero::itemHashes property.'
		);
	}

	/**
	 * @vcr hero.yml
	 */
	public function test_highest_progression()
	{
		$hero = new Hero( $this->json );
		$progression = $hero->highestProgression();
		$this->assertEquals(
			'Highest completed: inferno act2 BetrayeroftheHoradrim',
			$progression,
			'Invalid progression value.'
		);
	}

	public function test_primary_attribute()
	{
		$hero = new Hero( $this->json );
		$primaryAttribute = $hero->primaryAttribute();
		$this->assertEquals(
			'Intelligence_Item',
			$primaryAttribute,
			'Incorrect primary attribute returned.'
		);
	}

	public function test_is_dead()
	{
		$hero = new Hero( $this->json );
		$isDead = $hero->isDead();
		$this->assertFalse( $isDead, 'isDead returned unexpected value.' );
	}

	public function test_itemsHashesBySlot_when_items_equipped()
	{
		$hero = new Hero( $this->json );
		$itemHashes = $hero->itemsHashesBySlot();
		// verify at least one key has an exact value.
		$this->assertArrayHasKey( 'torso', $itemHashes );
		$this->assertEquals(
			'item/CjkIxYL_7AISBwgEFRaF-hsdtwRpuh2V-O2WHTIlWp4dElbV7h1X8eDWHYNFzIwwCTixAkAAUBJgtQIYrJTUvwNQBlgC',
			$itemHashes['torso']
		);
	}

	public function test_itemsHashesBySlot_when_no_items_equipped()
	{
		$heroFixture = $this->fixturesDir . 'hero-3955832-no-items.json';
		$heroJson = \file_get_contents( $heroFixture );
		$hero = new Hero( $heroJson );
		$items = $hero->items();
		$this->assertEquals( 0, \count($items) );
	}

	public function test_when_hero_is_duel_wielding()
	{
		$json = \file_get_contents( $this->fixturesDir . 'hero-46026639-dual-wield.json' );
		$itemJson = \file_get_contents( $this->fixturesDir . 'item-FistWeapon_1H_000.json' );
		$httpMock = $this->getMock(
			'\\Kshabazz\\BattleNet\\D3\\Connections\\Http',
			['getItem'],
			[],
			'',
			FALSE
		);
		$httpMock->expects( $this->exactly(2) )
			->method( 'getItem' )
			->willReturn( $itemJson );
		$hero = new Hero( $json );
		// Since this method will make a network call we need to set a save name.
		HttpWrapper::setSaveFilename( 'item-FistWeapon_1H_000.rsd' );
		$actual = $hero->isDualWielding( $httpMock );
		$this->assertTrue( $actual );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage Invalid JSON. Please verify the string is valid JSON.
	 */
	public function test_constructing_with_invalid_json()
	{
		$hero = new Hero( '1234' );
	}

	public function test_hero_characterClass()
	{
		$hero = new Hero( $this->json );
		$actual = $hero->characterClass();
		$this->assertEquals( 'witch-doctor', $actual );
	}

	public function test_itemsHashesBySlot_return_null()
	{
		$heroFixture = $this->fixturesDir . 'hero-3955832-no-items.json';
		$heroJson = \file_get_contents( $heroFixture );
		$hero = new Hero( $heroJson );
		$this->assertNull( $hero->itemsHashesBySlot() );
	}

	public function test_hero_level()
	{
		$hero = new Hero( $this->json );
		$actual = $hero->level();
		$this->assertEquals( 60, $actual );
	}

	public function test_hero_paragonLevel()
	{
		$hero = new Hero( $this->json );
		$actual = $hero->paragonLevel();
		$this->assertEquals( 2, $actual );
	}

	public function test_hero_progression()
	{
		$hero = new Hero( $this->json );
		$actual = $hero->progression();
		$this->assertArrayHasKey( 'normal' , $actual );
	}

	public function test_hero_get()
	{
		$hero = new Hero( $this->json );
		$actual = $hero->get( 'id' );
		$this->assertEquals( '36131726' , $actual );
	}

	public function test_dual_wielding()
	{
		$apiKey = \D3_TEST_API_KEY;
		$httpClient = new HttpClient();
		$httpClient = new Http($apiKey, 'msuBREAKER#1374', $httpClient);
		$heroFixture = $this->fixturesDir . 'hero-3955832-no-items.json';
		$heroJson = \file_get_contents( $heroFixture );
		$hero = new Hero( $heroJson );
		$actual = $hero->isDualWielding( $httpClient );
		$this->assertFalse( $actual );
	}

	/**
	 * @expectedException \Exception
	 * @expectedExceptionMessage Just testing
	 */
	public function test_hero_code_error()
	{
		( new Hero( '{"code":"test", "reason": "Just testing."}' ) );
	}

	public function test_dexterity()
	{
		$actual = $this->heroNoItems->dexterity();
		$this->assertEquals( 217, $actual );
	}

	public function test_intelligence()
	{
		$actual = $this->heroNoItems->intelligence();
		$this->assertEquals( 77, $actual );
	}

	public function test_strength()
	{
		$actual = $this->heroNoItems->strength();
		$this->assertEquals( 77, $actual );
	}

	public function test_vitality()
	{
		$actual = $this->heroNoItems->vitality();
		$this->assertEquals( 147, $actual );
	}

	public function test_primary_resource_bonus()
	{
		$actual = $this->heroNoItems->primaryAttributeBonus();
		$this->assertEquals( 3.17, $actual );
	}

	public function test_isHardcore()
	{
		$actual = $this->heroNoItems->isHardcore();
		$this->assertFalse( $actual );
	}

	public function test_isSeasonal()
	{
		$actual = $this->heroNoItems->isSeasonal();
		$this->assertFalse( $actual );
	}

	public function test_eliteKills()
	{
		$actual = $this->heroNoItems->eliteKills();
		$this->assertEquals( 4798, $actual );
	}

	public function test_gender()
	{
		$actual = $this->heroNoItems->gender();
		$this->assertEquals( 0, $actual );
	}

	public function test_attackSpeed()
	{
		$actual = $this->heroNoItems->attackSpeed();
		$this->assertEquals( 1, $actual );
	}

	public function test_armor()
	{
		$actual = $this->heroNoItems->armor();
		$this->assertEquals( 294, $actual );
	}

	public function test_criticalHitChance()
	{
		$actual = $this->heroNoItems->criticalHitChance();
		$this->assertEquals( 0.05, $actual );
	}

	public function test_criticalHitDamage()
	{
		$actual = $this->heroNoItems->criticalHitDamage();
		$this->assertEquals( 0.5, $actual );
	}

	public function test_punchDamage()
	{
		$actual = $this->heroNoItems->punchDamage();
		$this->assertEquals( 2.5, $actual );
	}
}
?>