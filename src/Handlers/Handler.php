<?php namespace Kshabazz\BattleNet\D3\Handlers;// TODO: move to 'namespace Kshabazz\BattleNet\D3';
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
	 * Constructor
	 *
	 * @param string $pKey
	 * @param Handler $pHandler
	 */
	public function __construct( $pKey, Handler $pHandler );

	/**
	 * Get the JSON data from somewhere like Battle.net or a DB.
	 *
	 * @return string
	 */
	public function json();

	/**
	 * Get a fresh copy of the JSON from the {@link Handler}
	 *
	 * @return $this
	 */
	public function pullJson();

	/**
	 * Save data (usually JSON pulled from the API) to a local cache.
	 *
	 * @param \Kshabazz\BattleNet\D3\Requestors\Requestor $pResource
	 * @return bool Indicates TRUE on success or FALSE when skipped or a failure occurs.
	 */
	public function save( \Kshabazz\BattleNet\D3\Requestors\Requestor $pResource );
}
?>