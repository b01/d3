<?php namespace Kshabazz\Tests\BattleNet\D3\Skills;

use \Kshabazz\BattleNet\D3\Hero,
	\Kshabazz\BattleNet\D3\Skills\Passive;

class PassiveTest extends \PHPUnit_Framework_TestCase
{
	private
		/** @var array */
		$skills;

	public function setUp()
	{
		$json = \file_get_contents( FIXTURES_PATH . DIRECTORY_SEPARATOR . 'hero-3955832.json' );
		$hero = new Hero( $json );
		$this->skills = $hero->skills()[ 'passive' ];
	}

	public function test_getting_description()
	{
		$skill = $this->skills[ 0 ][ 'skill' ];
		$passiveSkill = new Passive( $skill );
		$actual = $passiveSkill->description();
		$this->assertContains( 'The Spirit costs of Mantra activation effects are reduced by 50%', $actual) ;
	}
}
?>