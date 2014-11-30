<?php namespace Kshabazz\Tests\BattleNet\D3\Models;

use Kshabazz\BattleNet\D3\Models\Profile;

/**
 * Class ProfileTest
 *
 * @package Kshabazz\Tests\BattleNet\D3\Models
 * @coversDefualtClass Kshabazz\BattleNet\D3\Models\Profile
 */
class ProfileTest extends \PHPUnit_Framework_TestCase
{
	private
		/** @var string */
		$fixturesDir;

	public function setUp()
	{
		$this->fixturesDir = \FIXTURES_PATH . DIRECTORY_SEPARATOR;
	}

	/**
	 * @expectedException \Exception
	 * @expectedExceptionMessage Profile not found
	 */
	public function test_profile__not_found()
	{
		$filename = $this->fixturesDir . 'profile-not-found.json';
		$profileJson = \file_get_contents( $filename );
		new Profile( $profileJson );
	}
}
