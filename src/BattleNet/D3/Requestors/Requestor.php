<?php
/**
 * Created by PhpStorm.
 * User: Khalifah
 * Date: 7/4/14
 * Time: 8:14 AM
 */

namespace kshabazz\d3a\BattleNet\Requestors;

/**
 * Interface Resource
 *
 * @package kshabazz\d3a\BattleNet\Resources
 */
interface Requestor
{
	/**
	 * Constructor
	 *
	 * @param $pBattleNetId
	 */
	public function __construct( $pBattleNetId );

	/**
	 * Request Hero JSON from Battle.Net.
	 * ex: http://us.battle.net/api/d3/profile/<battleNetIdName>-<battleNetIdNumber>/hero/<hero-id>
	 * Note: Leave off the trailing '/' when setting
	 *
	 * @param $pHeroId
	 * @return string|null
	 * @throws \InvalidArgumentException
	 */
	public function getHero( $pHeroId );

	/**
	 * Get item JSON from Battle.Net D3 API.
	 * ex: http://us.battle.net/api/d3/data/item/COGHsoAIEgcIBBXIGEoRHYQRdRUdnWyzFB2qXu51MA04kwNAAFAKYJMD
	 *
	 * @param $pItemId
	 * @return string|null
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 */
	public function getItem( $pItemId );

	/**
	 * ex: http://us.battle.net/api/d3/profile/<battleNetIdName>-<battleNetIdNumber>/
	 *
	 * @return string|null
	 * @throws \Exception
	 */
	public function getProfile();
}