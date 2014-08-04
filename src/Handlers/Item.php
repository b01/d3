<?php namespace Kshabazz\BattleNet\D3\Handlers;
/**
 * Get the users item from Battle.Net and present it to the user; store it locally in a database behind the scenes.
 * The item will only be updated after a few ours of retrieving it.
 */

/**
 * Class BattleNet_Item
 *
 * @package Kshabazz\BattleNet
 */
class Item implements Handler
{
	protected
		$column,
		$itemHash,
		$info;

	/**
	 * Constructor
	 *
	 * @param string $pItemHash
	 */
	public function __construct( $pItemHash )
	{
		$this->info = NULL;
		$this->itemHash = $pItemHash;
	}

	/**
	 * Get item data from local database.
	 *
	 * @deprecated {@see \Kshabazz\BattleNet\D3\Connections\Sql::getItem}
	 * @param \Kshabazz\BattleNet\D3\Connections\Sql $pSql
	 * @return null
	 */
	public function getJsonFromDb( \Kshabazz\BattleNet\D3\Connections\Sql $pSql )
	{
		$this->column = 'hash';
		$hashValue = str_replace( 'item/', '', $this->itemHash );
		$query = sprintf( \Kshabazz\BattleNet\D3\Connections\Sql::SELECT_ITEM, $this->column );
		$result = $pSql->pdoQueryBind( $query, ['selectValue' => [$hashValue, \PDO::PARAM_STR]] );
		if ( \Kshabazz\Slib\isArray($result) )
		{
			$this->info = $result[ 0 ];
			return $this->info[ 'json' ];
		}
		return NULL;
	}

	/**
	 * Get the item JSON from Battle.net.
	 *
	 * @deprecated {@see \Kshabazz\BattleNet\D3\Connections\Http::getItem}
	 * @param \Kshabazz\BattleNet\D3\Connections\Http $pHttp
	 * @return string|null
	 */
	public function getJson( \Kshabazz\BattleNet\D3\Connections\Http $pHttp )
	{
		// Request the item from BattleNet.
		$json = $pHttp->getItem( $this->itemHash );
		$requestSuccessful = ( $pHttp->responseCode() === 200 );
		// Log the request.
//		$this->sql->addRequest( $this->bnr->battleNetId(), $pHttp->url() );
		// Set the property.
		if ( $requestSuccessful )
		{
			return $json;
		}
		return NULL;
	}

	/**
	 * Save the users item locally, in this case a database.
	 *
	 * @deprecated {@see \Kshabazz\BattleNet\D3\Connections\Sql::saveItem}
	 * @param \Kshabazz\BattleNet\D3\Connections\Sql $pSql
	 * @return bool
	 */
	public function save( \Kshabazz\BattleNet\D3\Connections\Sql $pSql )
	{
		$itemName = $this->info[ 'name' ];
		$itemType = $this->info[ 'type' ];
		$id = $this->info[ 'id' ];
		$utcTime = gmdate( 'Y-m-d H:i:s' );
		$params = [
			'hash' => [ $this->key, \PDO::PARAM_STR ],
			'id' => [ $id, \PDO::PARAM_STR ],
			'name' => [ $itemName, \PDO::PARAM_STR ],
			'itemType' => [ $itemType['id'], \PDO::PARAM_STR ],
			'json' => [ $this->json, \PDO::PARAM_STR ],
			'ipAddress' => [ $this->sql->ipAddress(), \PDO::PARAM_STR ],
			'lastUpdate' => [ $utcTime, \PDO::PARAM_STR ],
			'dateAdded' => [ $utcTime, \PDO::PARAM_STR ]
		];
		return $this->sql->pdoQueryBind( \Kshabazz\BattleNet\D3\Connections\Sql::INSERT_ITEM, $params );
	}
}
?>