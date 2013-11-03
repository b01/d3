<?php namespace kshabazz\d3a;

use kshabazz\d3a\SuperGlobals;

/**
* Put the battle net id in the form field if present in the session, or a
* defaults to msuBREAKER#1374
*
* @var string $battleNetId
* @var string $urlSafeBattleNetId
*	on the hero.
*/
class Model_Index
{
    const DEFAULT_BATTLE_NET_ID = 'msuBREAKER#1374';
	public
		$battleNetId,
		$urlSafeBattleNetId;

	/**
	* Constructor
	*
	* @param Application $d3a
	*/
	public function __construct( SuperGlobals $pSuper )
	{
		$this->battleNetId = $pSuper->getParam( 'battleNetId', self::DEFAULT_BATTLE_NET_ID, 'string', 'POST' );
	}
}
?>