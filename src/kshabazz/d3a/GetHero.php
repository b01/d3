<?php namespace kshabazz\d3a;
/**
* Take inventory of all stats on each item equipped.
*
* @var array $p_items A hash array of items, by which the keys indicate where the items are placed
*	on the hero.
*/
class GetHero
{
	/**
	 * Constructor
	 *
	 * @param BattleNet_Hero $bnrHero
	 * @param array $pAttributeMap
	 * @param BattleNet_Requestor $pBnr
	 * @param BattleNet_Sql $pSql
	 */
	public function __construct( BattleNet_Hero $bnrHero, array & $pAttributeMap, BattleNet_Requestor $pBnr, BattleNet_Sql $pSql )
	{
		$this->attributeMap = $pAttributeMap;
		$this->bnr = $pBnr;
		$this->bnrHero = $bnrHero;
		$this->sql = $pSql;
		$this->stats = [];
		$this->requestTime = $_SERVER[ 'REQUEST_TIME_FLOAT' ];
		$this->battleNetUrlSafeId = $this->bnr->battleNetUrlSafeId();
		$this->init();
		$this->renderSetup();
	}
}
?>