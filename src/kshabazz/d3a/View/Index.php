<?php namespace kshabazz\d3a\View;

use kshabazz\d3a\SuperGlobals;

/**
* Put the battle net id in the form field if present in the session, or a
* defaults to msuBREAKER#1374
*
* @var string $battleNetId
* @var string $urlSafeBattleNetId
*	on the hero.
*/
class Index
{
    const DEFAULT_BATTLE_NET_ID = 'msuBREAKER#1374';
	protected
		$battleNetId;

	/**
	 * Constructor
	 *
	 * @param string $pBattleNetId
	 */
	public function __construct( $pBattleNetId )
	{
		if ( $pBattleNetId !== NULL )
		{
			$this->battleNetId = $pBattleNetId;
		}
		else
		{
			$this->battleNetId = self::DEFAULT_BATTLE_NET_ID;
		}
	}

	public function render()
	{
		$data = [
			'battleNetId' => $this->battleNetId
		];
		return $data;
	}
}
?>