<?php namespace Kshabazz\BattleNet\D3\Handlers;
/**
 * Get the users item from Battle.Net and present it to the user; store it locally in a database behind the scenes.
 * The item will only be updated after a few ours of retrieving it.
 */

/**
 * Class Handler
 *
 * @package Kshabazz\BattleNet
 */
interface Handler
{
	/**
	 * Get a fresh copy of the JSON from the {@link \Kshabazz\BattleNet\D3\Requestors\Requestor}
	 *
	 * @param \Kshabazz\BattleNet\D3\Requestors\Http $pResource
	 * @return {string|NULL}
	 */
	public function getJson( \Kshabazz\BattleNet\D3\Requestors\Http $pResource  );

	/**
	 * Get profile JSON from the database.
	 *
	 * @param \Kshabazz\BattleNet\D3\Requestors\Sql $pSql
	 * @param string $pKey Primary key value.
	 * @return null
	 */
	public function getJsonFromDb( \Kshabazz\BattleNet\D3\Requestors\Sql $pSql, $pKey );

	/**
	 * Save data (usually JSON pulled from the API) to a local cache.
	 *
	 * @param \Kshabazz\BattleNet\D3\Requestors\Sql $pResource
	 * @return bool Indicates TRUE on success or FALSE when skipped or a failure occurs.
	 */
	public function save( \Kshabazz\BattleNet\D3\Requestors\Sql $pResource );
}
?>