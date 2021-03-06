<?php namespace Kshabazz\Tests\BattleNet\D3;

use
	\Kshabazz\BattleNet\D3\Profile;

/**
 * Class ProfileTest
 *
 * @package Kshabazz\Tests\BattleNet\D3
 * @coversDefaultClass \Kshabazz\BattleNet\D3\Profile
 */
class ProfileTest extends \PHPUnit_Framework_TestCase
{
	private
		/** @var string */
		$fixturesDir,
		$battleNetTag;

	public function setUp()
	{
		$this->battleNetTag = 'msuBREAKER#1374';
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

	public function test_profile_battleTag()
	{
		$filename = $this->fixturesDir . 'profile-msuBREAKER-1374.json';
		$profileJson = \file_get_contents( $filename );
		$profile = new Profile( $profileJson );
		$this->assertContains( $this->battleNetTag, $profile->battleTag() );
	}

	public function test_profile_heroes()
	{
		$filename = $this->fixturesDir . 'profile-msuBREAKER-1374.json';
		$profileJson = \file_get_contents( $filename );
		$profile = new Profile( $profileJson );
		$heroes = $profile->heroes();
		$this->assertEquals( 11, count($heroes) );
	}

	public function test_profile_getHero()
	{
		$filename = $this->fixturesDir . 'profile-msuBREAKER-1374.json';
		$profileJson = \file_get_contents( $filename );
		$profile = new Profile( $profileJson );
		$hero = $profile->getHero( 'Khalil' );
		$this->assertEquals( 3955832, $hero->id );
	}

	/**
	 * @expectedException \Exception
	 * @expectedExceptionMessage Tried to initialize ItemModel with invalid JSON
	 */
	public function test_profile_invalid_json()
	{
		$profile = new Profile( 'test' );
		$hero = $profile->getHero( 'Khalil' );
		$this->assertEquals( 3955832, $hero->id );
	}

	/**
	 * @expectedException \Exception
	 * @expectedExceptionMessage lame reason
	 */
	public function test_profile_code_reason()
	{
		$profile = new Profile( '{"code":"test","reason":"lame reason"}' );
		$hero = $profile->getHero( 'Khalil' );
		$this->assertEquals( 3955832, $hero->id );
	}

	public function test_profile_json()
	{
		$filename = $this->fixturesDir . 'profile-msuBREAKER-1374.json';
		$profileJson = \file_get_contents( $filename );
		$profile = new Profile( $profileJson );
		$heroJson = $profile->json();
		$heroStdObject = \json_decode( $heroJson );
		$this->assertEquals( $heroStdObject->battleTag, $profile->battleTag() );
	}

	public function test_profile_serialize()
	{
		$filename = $this->fixturesDir . 'profile-msuBREAKER-1374.json';
		$profileJson = \file_get_contents( $filename );
		$profile = new Profile( $profileJson );
		$heroJson = \json_encode( $profile );
		$heroStdObject = \json_decode( $heroJson );
		$this->assertEquals( $heroStdObject->battleTag, $profile->battleTag() );
	}

	public function test_converting_profile_to_a_string()
	{
		$filename = $this->fixturesDir . 'profile-msuBREAKER-1374.json';
		$profileJson = \file_get_contents( $filename );
		$profile = new Profile( $profileJson );
		$profile2 = new Profile( (string)$profile );
		$this->assertInstanceOf( '\\Kshabazz\\BattleNet\\D3\\Profile', $profile2 );
	}

	public function test_profile_get()
	{
		$filename = $this->fixturesDir . 'profile-msuBREAKER-1374.json';
		$profileJson = \file_get_contents( $filename );
		$profile = new Profile( $profileJson );
		$actual = $profile->get( 'battleTag' );
		$this->assertEquals( $this->battleNetTag, $actual );
		return $profile;
	}

	/**
	 * @depends test_profile_get
	 */
	public function test_profile_get_when_isset( $profile )
	{
		$actual = $profile->get( 'battleTag' );
		$this->assertEquals( $this->battleNetTag, $actual );
		return $profile;
	}

	/**
	 * @depends test_profile_get
	 * @expectedException \Exception
	 * @expectedExceptionMessage Undefined property: invalidProperty in
	 */
	public function test_profile_get_undefined_property( $profile )
	{
		$actual = $profile->get( 'invalidProperty' );
		$this->assertEquals($this->battleNetTag , $actual );
		return $profile;
	}
}
?>