<?php namespace kshabazz\d3a\test\Model;
/**
 * Created by PhpStorm.
 * User: Khalifah
 * Date: 1/3/14
 * Time: 7:54 AM
 */

use kshabazz\d3a\BattleNet_Hero;
use kshabazz\d3a\BattleNet_Requestor;
use kshabazz\d3a\BattleNet_Sql;
use kshabazz\d3a\Model_GetHero;

/**
 * Class GetHeroTest
 * @package kshabazz\d3a\test\Model
 */
class GetHeroTest extends \PHPUnit_Framework_TestCase
{
	private
		$attributeMap,
		$bnr,
		$bnrHero,
		$heroId,
		$sql;

	/**
	 * Setup
	 */
	public function setUp()
	{
		$this->heroId = 36131726;
		$this->bnr = new BattleNet_Requestor( 'msuBREAKER#1374' );
		$this->sql = new BattleNet_Sql();
		$this->attributeMap = \kshabazz\d3a\loadAttributeMap( \kshabazz\d3a\ATTRIBUTE_MAP_FILE );
		$this->bnrHero = new BattleNet_Hero( $this->heroId, $this->bnr, $this->sql, FALSE );
		$_SESSION = [];
		$_SESSION[ 'hero-' . $this->heroId ] = time();
	}

	/**
	 * Simple test to construct the object.
	 */
	public function test_initializing_a_get_hero_object()
	{
		$hero = new Model_GetHero( $this->bnrHero, $this->attributeMap, $this->bnr, $this->sql );

		$this->assertTrue( $hero instanceof Model_GetHero, 'Could not initialize Model_GetHero object.' );
	}

	/**
	 * Test retrieving the hero property.
	 */
	public function test_retrieving_hero_property()
	{
		$getHero = new Model_GetHero( $this->bnrHero, $this->attributeMap, $this->bnr, $this->sql );
		$hero = $getHero->hero();
		$this->assertEquals( $this->heroId, $hero->id, 'Invalid hero returned from Model_GetHero::hero property.' );
	}

	/**
	 * Test retrieving items.
	 */
	public function test_retrrieving_items()
	{
		$getHero = new Model_GetHero( $this->bnrHero, $this->attributeMap, $this->bnr, $this->sql );
		$tooltipParams = $getHero->itemHashes();
		$fixtures = array(
			'head' => 'CmAI76DVrAQSBwgEFSMZlIsdlD3juR1bToDJHc9FH8sdo9Ya6B0SXPT8HYYCY-owCTjTAkAAUBJg1wJqJQoMCAAQ9fHWhoSAgKADEhUIxKuTpwgSBwgEFfee2KswDTgAQAEYuZvfxg5QBlgC',
			'torso' => 'CjkIxYL_7AISBwgEFRaF-hsdtwRpuh2V-O2WHTIlWp4dElbV7h1X8eDWHYNFzIwwCTixAkAAUBJgtQIYrJTUvwNQBlgC',
			'feet' => 'CjgIr-38GxIHCAQVZ1aC6B1l3mdxHTlvIsUd1WaKlB1xwgPgHdlbmuEdCupQCDAJOKsDQABQEmCvAxixpu33AlAGWAI',
			'hands' => 'CkgIrYP_xQYSBwgEFcNbkqQd0HHzPB11sezwHQ5rf_0dSJ3LyB0kKgFuHQ5c9PwiCwgBFW9CAwAYJCAKMAk44QRAAEgPUBBg5QQY-ZDttgtQBlgC',
			'shoulders' => 'Cj4IiLvghAgSBwgEFcz4yBUdY-q9Cx03dgOEHQ4XgMkdx9LzZSILCAAVrUQDABgEIBQwCTjgBEAASA5QDGDkBBiY48fcAlAGWAI',
			'legs' => 'CpYBCNKgjvMHEgcIBBWbktWlHY8m_qsdQADiBh2vU8M2HbcEabodbNBu8h2mk8T2IgsIARXCRAMAGAAgCjAJOKQFQABID1AQYKgFaiUKDAgAEJry1oaEgICgAxIVCIGh-f0HEgcIBBX3ntirMAk4AEABaiUKDAgAEKny1oaEgICgAxIVCKD4-PQEEgcIBBX3ntirMAk4AEABGMm106ILUAZYAg',
			'bracers' => 'CkEI5bLrsgwSBwgEFdgsyssd_Wwdqh3tzHZQHaCBE64di_E_-x2sLjBMIgsIABW2_gEAGBogAjAJOJ0DQABQDGChAxjPzK7DBlAGWAI',
			'mainHand' => 'Cm0IkPmI8gkSBwgEFbiWItodElz0_B2UvzSKHejoLjwdufHsGh31-f9_HceNbR0iCwgBFchEAwAYBCAEMAk4lANAAFAOYJgDaiUKDAgAEJ6E1oaEgICgAxIVCK_MwLQOEgcIBBUalq6YMA04AEABGI-l690EUAZYAg',
			'offHand' => 'CkYImaWDjwQSBwgEFSph2PcdnmyzFB3jb1GTHX3gy3YduPHsGh26MEgIHRFc9PwiCwgBFXJCAwAYGiAKMAk44QJAAFAQYOUCGKv7vIIBUAZYAg',
			'waist' => 'CkEI_OqP_gkSBwgEFb6nQtQdoIETrh1j6r0LHTwrb_EdBVbV7h3QNNiIIgsIARW1RAMAGBQgJjAJOKADQABQDmCkAxiD4eq7BVAGWAI',
			'rightFinger' => 'Cm0In6jiiwoSBwgEFTJVXUQd7TrCNh3_ppcRHb0K8EwdZxfBMR0vYyJsHcfS82UiCwgAFar-AQAYHCAeMAk4vwNAAFAQYMMDaiUKDAgAEMm1wo6GgICgKhIVCLCljpgGEgcIBBW2XbZ6MA04AEABGOTU6sUIUAZYAg',
			'leftFinger' => 'CmgIxdPigAsSBwgEFTNVXUQdtwRpuh0GfZZBHe06wjYdzGx7gx00_I9rIgsIABWq_gEAGDYgEDAJOOUCQABQDGDpAmolCgwIABCxtcKOhoCAoCoSFQjz7cvDDBIHCAQVtl22ejANOABAARjm1OrFCFAGWAI',
			'neck' => 'CkYI0Jiv6g4SBwgEFa3FRGQdD1z0_B3jxCtvHT6kUAcduPHsGh2GAmPqHZZGDL0iCwgBFYpCAwAYKCACMAk4tANAAFAQYLgDGNqWpsQMUAZYAg'
		);

		$this->assertEquals($fixtures['head'], $tooltipParams['head'],
			'Invalid head item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($fixtures['torso'], $tooltipParams['torso'],
			'Invalid torso item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($fixtures['feet'], $tooltipParams['feet'],
			'Invalid feet item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($fixtures['hands'], $tooltipParams['hands'],
			'Invalid hands item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($fixtures['shoulders'], $tooltipParams['shoulders'],
			'Invalid shoulders item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($fixtures['legs'], $tooltipParams['legs'],
			'Invalid legs item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($fixtures['bracers'], $tooltipParams['bracers'],
			'Invalid bracers item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($fixtures['mainHand'], $tooltipParams['mainHand'],
			'Invalid mainHand item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($fixtures['offHand'], $tooltipParams['offHand'],
			'Invalid offHand item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($fixtures['waist'], $tooltipParams['waist'],
			'Invalid waist item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($fixtures['rightFinger'], $tooltipParams['rightFinger'],
			'Invalid rightFinger item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($fixtures['leftFinger'], $tooltipParams['leftFinger'],
			'Invalid leftFinger item returned from Model_GetHero::itemHashes property.'
		);

		$this->assertEquals($fixtures['neck'], $tooltipParams['neck'],
			'Invalid neck item returned from Model_GetHero::itemHashes property.'
		);
	}
}
