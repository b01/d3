<?php namespace kshabazz\d3a;
/**
 * Get the users item from Battle.Net and present it to the user; store it locally in a database behind the scenes.
 * The item will only be updated after a few ours of retrieving it.
 */
/**
 * Class BattleNet_Item
 *
 * @package kshabazz\d3a
 */
class BattleNet_Item extends BattleNet_Model
{
	protected
		$column,
		$hash,
		$id;

	/**
	 * Constructor
	 *
	 * @param string $pHash
	 * @param string $pColumn
	 * @param BattleNet_Requestor $pBnr
	 * @param BattleNet_Sql $pSql
	 * @param bool $fromDb
	 */
	public function __construct( $pHash, $pColumn, BattleNet_Requestor $pBnr, BattleNet_Sql $pSql, $fromDb = FALSE )
	{
		$this->column = $pColumn;
		$this->id = NULL;
		$this->info = NULL;
		parent::__construct( $pHash, $pBnr, $pSql, $fromDb );
	}

	/**
	 * Get item data from local database.
	 * @return $this
	 */
	protected function pullJsonFromDb()
	{
		$returnValue = NULL;
		if ( $this->key !== NULL )
		{
			$query = sprintf( BattleNet_Sql::SELECT_ITEM, $this->column );
			$result = $this->sql->getData( $query, ['selectValue' => [$this->key, \PDO::PARAM_STR]] );
			if ( isArray($result) )
			{
				$this->info = $result[ 0 ];
				$this->json = $this->info[ 'json' ];
			}
		}
		return $this;
	}

	/**
	 * Get the item JSON from Battle.net.
	 * @return $this
	 */
	protected function requestJsonFromApi()
	{
		// Request the item from BattleNet.
		$json = $this->bnr->getItem( $this->key );
		$requestSuccessful = ( $this->bnr->responseCode() === 200 );
		// Log the request.
		$url = $this->bnr->url();
		$this->sql->addRequest( $this->bnr->battleNetId(), $url );
		// Set the property.
		if ( $requestSuccessful )
		{
			$this->json = $json;
		}
		return $this;
	}

	/**
	 * Save the users item locally, in this case a database.
	 * @return bool
	 */
	protected function save()
	{
		// There is no need to save what was loaded from the database.
		if ( $this->loadFromDb )
		{
			return FALSE;
		}
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
		return $this->sql->save( BattleNet_Sql::INSERT_ITEM, $params );
	}

	/**
	 * Convert this object to a string.
	 * @return string
	 */
	public function __toString()
	{
		return json_encode( $this, JSON_PRETTY_PRINT );
	}
}
?>