<?php namespace kshabazz\d3a;

use \kshabazz\d3a\BattleNet_Requestor;
use \kshabazz\d3a\BattleNet_Sql;

/**
* Take inventory of all stats on each item equipped.
*
* @var array $p_items A hash array of items, by which the keys indicate where the items are placed
*	on the hero.
*/
class Model_GetProfile
{
	const
		HERO_URL = '/get-hero.php?battleNetId=%s&heroId=';

	public
		$battleNetId,
		$battleNetUrlSafeId,
		$heroes,
		$heroUrl,
		$profile,
		$timeLeft;

	/**
	* Constructor
	*/
	public function __construct( SuperGlobals $pSuper, BattleNet_Requestor $pDqi, BattleNet_Sql $pSql )
	{
		$this->dqi = $pDqi;
		$this->sql = $pSql;
		$this->superGlobals = $pSuper;
		$this->heroes = NULL;
		$this->cache = $this->superGlobals->getParam( 'cache', TRUE, 'bool', 'GET' );
		$this->battleNetId = $pDqi->battleNetId();

		$this->setup();
	}

	protected function setup()
	{
		if ( isString($this->battleNetId) )
		{
			$sessionCacheInfo = getSessionExpireInfo( "profileTime", $this->cache );
			$this->timeLeft = $sessionCacheInfo[ 'timeLeft' ];
			$this->profile = new BattleNet_Profile(
				$this->battleNetId,
				$this->dqi,
				$this->sql,
				$sessionCacheInfo[ 'loadFromBattleNet' ]
			);
			$this->heroes = $this->profile->heroes();
			$this->battleNetUrlSafeId = \str_replace( '#', '-', $this->battleNetId );
			$this->heroUrl = sprintf(self::HERO_URL, $this->battleNetUrlSafeId);
		}
	}
}
?>