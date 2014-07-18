<?php namespace Kshabazz\Tests\BattleNet\D3;
/**
 * HttpTest test.
 */

/**
 * @class HttpTest
 * @package \Kshabazz\Tests\BattleNet\D3
 */
class HttpTest extends \PHPUnit_Framework_TestCase
{
	private
		$battleNetId,
		$battleNetUrlSafeId,
		$heroId;

	public function setup()
	{
		$this->battleNetId = 'msuBREAKER#1374';
		$this->battleNetUrlSafeId = 'msuBREAKER-1374';
		$this->heroId = 46026639;
	}

	public function test_gettting_url_safe_battleNet_id()
	{
		$bnr = new \Kshabazz\BattleNet\D3\Connections\Http( $this->battleNetId );
		$bnIdUrlSafe = $bnr->battleNetUrlSafeId();
		$this->assertEquals( $this->battleNetUrlSafeId, $bnIdUrlSafe, 'Invalid URL-safe BattelNet ID returned.' );
	}

	/**
	* Get BattleNet ID
	*
	* @return string BattleNet ID
	*/
	public function test_gettting_battleNet_id()
	{
		$bnr = new \Kshabazz\BattleNet\D3\Connections\Http( $this->battleNetId );
		$bnIdUrlSafe = $bnr->battleNetId();
		$this->assertEquals( $this->battleNetId, $bnIdUrlSafe, 'Invalid BattelNet ID returned.' );
	}

	/**
	 * Test getting a hero from Battle.Net
	 * @vcr hero.yml
	 */
	public function test_get_hero()
	{
		$bnr = new \Kshabazz\BattleNet\D3\Connections\Http( $this->battleNetId );
		$heroJson = $bnr->getHero( $this->heroId );
		$hero = json_decode( $heroJson );
		$this->assertEquals( $this->heroId, $hero->id, 'Unable to retrieve Hero from Battle.Net' );
	}

	/**
	 * Test retrieving a valid item from Battle.Net
	 */
	public function test_getting_a_valid_item()
	{
		$bnr = new \Kshabazz\BattleNet\D3\Connections\Http( $this->battleNetId );
		$itemJson = $bnr->getItem( 'item/COGHsoAIEgcIBBXIGEoRHYQRdRUdnWyzFB2qXu51MA04kwNAAFAKYJMD' );
		$item = new \Kshabazz\BattleNet\D3\Models\Item( $itemJson );
		$this->assertEquals( 'MightyWeapon1H_202', $item->id, 'Invalid item returned.' );
	}

	/**
	 * Test retrieving an invalid item from Battle.Net
	 *
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage Expects a valid item id, but was given: ''.
	 */
	public function test_getting_a_invalid_item()
	{
		$bnr = new \Kshabazz\BattleNet\D3\Connections\Http( $this->battleNetId );
		$bnr->getItem( NULL );
	}

	/**
	 * Test retrieving a profile from Battle.Net
	 */
	public function test_getting_a_profile()
	{
		$bnr = new \Kshabazz\BattleNet\D3\Connections\Http( $this->battleNetId );
		$profileJson = $bnr->getProfile();
		$profile = new \Kshabazz\BattleNet\D3\Models\Profile( $profileJson );
		$this->assertEquals(
			'msuBREAKER#1374',
			$profile->get('battleTag'),
			'BattleNet_Requestor return an invalid profile.'
		);
	}
}
?>