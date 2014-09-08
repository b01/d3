<?php namespace Kshabazz\Tests\BattleNet\D3\Models;

use Kshabazz\BattleNet\D3\Models\Hero;

/**
 * @class HeroTest
 * @package Kshabazz\BattleNet\test\Model
 */
class HeroTest extends \PHPUnit_Framework_TestCase
{
	private
		$heroId,
		$itemParams,
		$json;

	/**
	 * Setup
	 */
	public function setUp()
	{
		$this->heroId = 36131726;
		$this->json = \file_get_contents( FIXTURES_PATH . 'hero.json' );
		$this->itemParams = [
			'head' => 'item/CmAI76DVrAQSBwgEFSMZlIsdlD3juR1bToDJHc9FH8sdo9Ya6B0SXPT8HYYCY-owCTjTAkAAUBJg1wJqJQoMCAAQ9fHWhoSAgKADEhUIxKuTpwgSBwgEFfee2KswDTgAQAEYuZvfxg5QBlgC',
			'torso' => 'item/CjkIxYL_7AISBwgEFRaF-hsdtwRpuh2V-O2WHTIlWp4dElbV7h1X8eDWHYNFzIwwCTixAkAAUBJgtQIYrJTUvwNQBlgC',
			'feet' => 'item/CjgIr-38GxIHCAQVZ1aC6B1l3mdxHTlvIsUd1WaKlB1xwgPgHdlbmuEdCupQCDAJOKsDQABQEmCvAxixpu33AlAGWAI',
			'hands' => 'item/CkgIrYP_xQYSBwgEFcNbkqQd0HHzPB11sezwHQ5rf_0dSJ3LyB0kKgFuHQ5c9PwiCwgBFW9CAwAYJCAKMAk44QRAAEgPUBBg5QQY-ZDttgtQBlgC',
			'shoulders' => 'item/Cj4IiLvghAgSBwgEFcz4yBUdY-q9Cx03dgOEHQ4XgMkdx9LzZSILCAAVrUQDABgEIBQwCTjgBEAASA5QDGDkBBiY48fcAlAGWAI',
			'legs' => 'item/CpYBCNKgjvMHEgcIBBWbktWlHY8m_qsdQADiBh2vU8M2HbcEabodbNBu8h2mk8T2IgsIARXCRAMAGAAgCjAJOKQFQABID1AQYKgFaiUKDAgAEJry1oaEgICgAxIVCIGh-f0HEgcIBBX3ntirMAk4AEABaiUKDAgAEKny1oaEgICgAxIVCKD4-PQEEgcIBBX3ntirMAk4AEABGMm106ILUAZYAg',
			'bracers' => 'item/CkEI5bLrsgwSBwgEFdgsyssd_Wwdqh3tzHZQHaCBE64di_E_-x2sLjBMIgsIABW2_gEAGBogAjAJOJ0DQABQDGChAxjPzK7DBlAGWAI',
			'mainHand' => 'item/Cm0IkPmI8gkSBwgEFbiWItodElz0_B2UvzSKHejoLjwdufHsGh31-f9_HceNbR0iCwgBFchEAwAYBCAEMAk4lANAAFAOYJgDaiUKDAgAEJ6E1oaEgICgAxIVCK_MwLQOEgcIBBUalq6YMA04AEABGI-l690EUAZYAg',
			'offHand' => 'item/CkYImaWDjwQSBwgEFSph2PcdnmyzFB3jb1GTHX3gy3YduPHsGh26MEgIHRFc9PwiCwgBFXJCAwAYGiAKMAk44QJAAFAQYOUCGKv7vIIBUAZYAg',
			'waist' => 'item/CkEI_OqP_gkSBwgEFb6nQtQdoIETrh1j6r0LHTwrb_EdBVbV7h3QNNiIIgsIARW1RAMAGBQgJjAJOKADQABQDmCkAxiD4eq7BVAGWAI',
			'rightFinger' => 'item/Cm0In6jiiwoSBwgEFTJVXUQd7TrCNh3_ppcRHb0K8EwdZxfBMR0vYyJsHcfS82UiCwgAFar-AQAYHCAeMAk4vwNAAFAQYMMDaiUKDAgAEMm1wo6GgICgKhIVCLCljpgGEgcIBBW2XbZ6MA04AEABGOTU6sUIUAZYAg',
			'leftFinger' => 'item/CmgIxdPigAsSBwgEFTNVXUQdtwRpuh0GfZZBHe06wjYdzGx7gx00_I9rIgsIABWq_gEAGDYgEDAJOOUCQABQDGDpAmolCgwIABCxtcKOhoCAoCoSFQjz7cvDDBIHCAQVtl22ejANOABAARjm1OrFCFAGWAI',
			'neck' => 'item/CkYI0Jiv6g4SBwgEFa3FRGQdD1z0_B3jxCtvHT6kUAcduPHsGh2GAmPqHZZGDL0iCwgBFYpCAwAYKCACMAk4tANAAFAQYLgDGNqWpsQMUAZYAg'
		];
	}

	public function test_getting_the_hero_id()
	{
		$hero = new Hero( $this->json );
		$id = $hero->id();
		$this->assertEquals( $this->heroId, $id, 'Invalid hero id returned.' );
	}

	/**
	 * Should throw an error when a property is not found.
	 * @expectedException \Exception
	 * @expectedExceptionMessage Hero has no property test123
	 */
	public function test_throwing_an_error_on_an_undefined_property()
	{
		$hero = new Hero( $this->json );
		$hero->get( 'test123' );
	}

	/**
	 * Test retrieving the hero property.
	 */
	public function test_retrieving_hero_hardcore()
	{
		$hero = new Hero( $this->json );
		$this->assertInternalType( 'bool', $hero->hardcore(), 'Hardcore is not a boolean as expected.' );
	}

	/**
	 * Test retrieving the hero items.
	 */
	public function test_retrieving_hero_items()
	{
		$hero = new Hero( $this->json );
		$items = $hero->items();
		// loop through each item, asserting the expected tooltipParam value.
		foreach ( $items as $slot => $item )
		{
			$this->assertEquals(
				$this->itemParams[ $slot ],
				$item['tooltipParams'],
				'Invalid hero items returned.'
			);
		}
	}

	public function test_retreiving_json()
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

	public function test_retreiving_stats()
	{
		$hero = new Hero( $this->json );
		$stats = $hero->preCalculatedStats();
		$this->assertArrayHasKey( 'arcaneResist', $stats, 'arcaneResist key not found in hero stats.' );
		$this->assertArrayHasKey( 'armor', $stats, 'armor key not found in hero stats.' );
		$this->assertArrayHasKey( 'attackSpeed', $stats, 'attackSpeed key not found in hero stats.' );
		$this->assertArrayHasKey( 'blockAmountMax', $stats, 'blockAmountMax key not found in hero stats.' );
		$this->assertArrayHasKey( 'blockAmountMin', $stats, 'blockAmountMin key not found in hero stats.' );
		$this->assertArrayHasKey( 'blockChance', $stats, 'blockChance key not found in hero stats.' );
		$this->assertArrayHasKey( 'coldResist', $stats, 'coldResist key not found in hero stats.' );
		$this->assertArrayHasKey( 'critChance', $stats, 'critChance key not found in hero stats.' );
		$this->assertArrayHasKey( 'critDamage', $stats, 'critDamage key not found in hero stats.' );
		$this->assertArrayHasKey( 'damage', $stats, 'damage key not found in hero stats.' );
		$this->assertArrayHasKey( 'damageIncrease', $stats, 'damageIncrease key not found in hero stats.' );
		$this->assertArrayHasKey( 'damageReduction', $stats, 'damageReduction key not found in hero stats.' );
		$this->assertArrayHasKey( 'dexterity', $stats, 'dexterity key not found in hero stats.' );
		$this->assertArrayHasKey( 'fireResist', $stats, 'fireResist key not found in hero stats.' );
		$this->assertArrayHasKey( 'goldFind', $stats, 'goldFind key not found in hero stats.' );
		$this->assertArrayHasKey( 'intelligence', $stats, 'intelligence key not found in hero stats.' );
		$this->assertArrayHasKey( 'life', $stats, 'life key not found in hero stats.' );
		$this->assertArrayHasKey( 'lifeOnHit', $stats, 'lifeOnHit key not found in hero stats.' );
		$this->assertArrayHasKey( 'lifePerKill', $stats, 'lifePerKill key not found in hero stats.' );
		$this->assertArrayHasKey( 'lifeSteal', $stats, 'lifeSteal key not found in hero stats.' );
		$this->assertArrayHasKey( 'lightningResist', $stats, 'lightningResist key not found in hero stats.' );
		$this->assertArrayHasKey( 'magicFind', $stats, 'magicFind key not found in hero stats.' );
		$this->assertArrayHasKey( 'physicalResist', $stats, 'physicalResist key not found in hero stats.' );
		$this->assertArrayHasKey( 'poisonResist', $stats, 'poisonResist key not found in hero stats.' );
		$this->assertArrayHasKey( 'primaryResource', $stats, 'primaryResource key not found in hero stats.' );
		$this->assertArrayHasKey( 'secondaryResource', $stats, 'secondaryResource key not found in hero stats.' );
		$this->assertArrayHasKey( 'strength', $stats, 'strength key not found in hero stats.' );
		$this->assertArrayHasKey( 'thorns', $stats, 'thorns key not found in hero stats.' );
		$this->assertArrayHasKey( 'vitality', $stats, 'vitality key not found in hero stats.' );
	}

	/**
	 * Test retrieving items.
	 */
	public function test_retrrieving_items()
	{
		$hero = new Hero( $this->json );
		$tooltipParams = $hero->items();

		$this->assertEquals($this->itemParams['head'], $tooltipParams['head']['tooltipParams'],
			'Invalid head item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($this->itemParams['torso'], $tooltipParams['torso']['tooltipParams'],
			'Invalid torso item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($this->itemParams['feet'], $tooltipParams['feet']['tooltipParams'],
			'Invalid feet item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($this->itemParams['hands'], $tooltipParams['hands']['tooltipParams'],
			'Invalid hands item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($this->itemParams['shoulders'], $tooltipParams['shoulders']['tooltipParams'],
			'Invalid shoulders item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($this->itemParams['legs'], $tooltipParams['legs']['tooltipParams'],
			'Invalid legs item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($this->itemParams['bracers'], $tooltipParams['bracers']['tooltipParams'],
			'Invalid bracers item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($this->itemParams['mainHand'], $tooltipParams['mainHand']['tooltipParams'],
			'Invalid mainHand item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($this->itemParams['offHand'], $tooltipParams['offHand']['tooltipParams'],
			'Invalid offHand item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($this->itemParams['waist'], $tooltipParams['waist']['tooltipParams'],
			'Invalid waist item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($this->itemParams['rightFinger'], $tooltipParams['rightFinger']['tooltipParams'],
			'Invalid rightFinger item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($this->itemParams['leftFinger'], $tooltipParams['leftFinger']['tooltipParams'],
			'Invalid leftFinger item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($this->itemParams['neck'], $tooltipParams['neck']['tooltipParams'],
			'Invalid neck item returned from Model_GetHero::itemHashes property.'
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
}
?>