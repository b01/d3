<?php namespace Kshabazz\BattleNet\D3\Connections;
/**
 * Connections for retrieving things such as JSON for hero profiles.
 */

/**
 * Interface Resource
 *
 * @package \Kshabazz\BattleNet\D3\Resources
 */
interface Connection
{
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
	 * For each item the hero in the array construct an Model\Item and return them as an array.
	 *
	 * @param array $pItemHashes List of item hashes.
	 * @return array|null
	 * @throws \InvalidArgumentException
	 */
	public function getItemsAsModels( array $pItemHashes );

	/**
	 * ex: http://us.battle.net/api/d3/profile/<battleNetIdName>-<battleNetIdNumber>/
	 *
	 * @return string|null
	 * @throws \Exception
	 */
	public function getProfile();
}
?>