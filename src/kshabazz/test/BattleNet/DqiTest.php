<?php namespace kshabazz\d3a;

/**
 *
 * @TODO Rename to BattleNet_Request once done with the initial unit test
 */
class BattleNet_DqiTest extends \PHPUnit_Framework_TestCase
{
	public function testConstruct()
	{
		$bnRequest = new \kshabazz\d3a\BattleNet_Dqi( '' );
		$this->assertTrue(
			$bnRequest instanceof \kshabazz\d3a\BattleNet_Dqi,
			'Could not initialize an instance of \kshabazz\d3a\BattleNet_Dqi.'
		);
	}

	/**
	* Get BattleNet ID
	*
	* @return string BattleNet ID
	*/
	public function testBattleNetUrlSafeId()
	{
		$this->markTestIncomplete();
	}

	/**
	* Get BattleNet ID
	*
	* @return string BattleNet ID
	*/
	public function testGetBattleNetId()
	{
		$this->markTestIncomplete();
	}

	/**
	* Set BattleNet ID
	*/
	public function TestSetBattleNetId()
	{
		$bnr = new \kshabazz\d3a\BattleNet_Dqi( '' );
		$bnr->setBattleNetId( 'msuBEAKER#1734' );
		$this->assertEquals( 'msuBREAKER#1734', $bnr->getBattleNetId(), 'Failed to set BattleNet ID.' );
	}

	/**
	* Example:
	* url ::= <host> "/api/d3/data/item/" <item-data>
	* GET /api/d3/data/item/COGHsoAIEgcIBBXIGEoRHYQRdRUdnWyzFB2qXu51MA04kwNAAFAKYJMD
	* Note: Leave off the trailing '/' when setting
	*	/api/d3/profile/<battleNetIdName>-<battleNetIdNumber>
	*/
	public function testGetHero()
	{
		$heroId = '12345';
		//$bnr->testGetHero( $heroId );
		$this->markTestIncomplete();
	}

	/**
	*
	*/
	public function testGetValidItem()
	{
		$bnr = new \kshabazz\d3a\BattleNet_Dqi( 'msuBEAKER#1734' );
		// $bnr->getItem( '' );
		$this->markTestIncomplete();
	}

	/**
	* @expectedException InvalidArgumentException
	*/
	public function testGetInvalidItem()
	{
		$bnr = new \kshabazz\d3a\BattleNet_Dqi( 'msuBEAKER#1734' );
		$bnr->getItem( NULL );
	}

	/**
	* Example:
	* battletag-name ::= <regional battletag allowed characters>
	* battletag-code ::= <integer>
	* url ::= <host> "/api/d3/profile/" <battletag-name> "-" <battletag-code> "/"
	* Note: Add the trailing '/' when setting
	*	/api/d3/profile/<battleNetIdName>-<battleNetIdNumber>/
	* @param $p_battleNetId string Battle.Net ID with the "#code"
	*/
	public function testGetProfile()
	{
		$this->markTestIncomplete();
	}

}
?>