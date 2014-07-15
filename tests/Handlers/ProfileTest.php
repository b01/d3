<?php namespace Kshabazz\Tests\BattleNet\D3\Handlers;

/**
 * Class ProfileTest
 *
 * @package Kshabazz\Tests\BattleNet\D3\Handlers
 */
class ProfileTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @vcr profile.yml
	 */
	public function test_retrieving_profile_json()
	{
		$profileHandler = new \Kshabazz\BattleNet\D3\Handlers\Profile();
		$bnr = new \Kshabazz\BattleNet\D3\Requestors\Http( 'msuBREAKER#1374' );
		$profileJson = $profileHandler->getJson( $bnr );
		$profile = json_decode( $profileJson );
		$this->assertEquals( 38464947, $profile->heroes[0]->id, 'Unexpected profile ID.' );
	}
}
?>