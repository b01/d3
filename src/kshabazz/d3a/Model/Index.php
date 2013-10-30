<?php namespace kshabazz\d3a;

use kshabazz\d3a\Application;

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
	public
		$battleNetId,
		$urlSafeBattleNetId;

	/**
	* Constructor
	*
	* @param Application $d3a
	*/
	public function __construct( Application $d3a )
	{
		$this->battleNetId = $d3a->getParam( 'battleNetId', 'msuBREAKER#1374', 'string', 'POST' );
	}
}
?>