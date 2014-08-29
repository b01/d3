<?php namespace Kshabazz\Tests\BattleNet\D3\Handlers;

/**
 * Class ProfileTest
 *
 * @package Kshabazz\Tests\BattleNet\D3\Handlers
 */
class ProfileTest extends \PHPUnit_Framework_TestCase
{
	private
		$http,
		$urlSafeBattleNetId;

	public function setUp()
	{
		$this->urlSafeBattleNetId = 'msuBREAKER-1374';
		$this->http = new \Kshabazz\BattleNet\D3\Connections\Http( 'msuBREAKER#1374' );

	}

	/**
	 * @vcr profile.yml
	 */
	public function test_retrieving_profile_json()
	{
		$profileHandler = new \Kshabazz\BattleNet\D3\Handlers\Profile( $this->urlSafeBattleNetId );
		$profileJson = $profileHandler->getJson( $this->http );
		$profile = json_decode( $profileJson );
		$this->assertEquals( 3955832, $profile->heroes[0]->id, 'Unexpected profile ID.' );
	}
}
?>