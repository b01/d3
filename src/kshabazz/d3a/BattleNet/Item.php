<?php namespace kshabazz\d3a;
/**
* Get the users item from Battle.Net and present it to the user; store it locally in a database behind the scenes.
* The item will only be updated after a few ours of retrieving it.
*
*/

/**
* var $p_itemHash string User BattleNet ID.
* var $pDqi object Data Query Interface.
* var $pSql object SQL.
*/
class BattleNet_Item extends BattleNet_Model
{
	protected
		$column,
		$hash,
		$id,
		$info;

	/**
	 * Constructor
	 *
	 * @param string $pHash
	 * @param string $pColumn
	 * @param BattleNet_Requestor $pDqi
	 * @param BattleNet_Sql $pSql
	 * @param bool $force
	 */
	public function __construct( $pHash, $pColumn, BattleNet_Requestor $pDqi, BattleNet_Sql $pSql, $force = FALSE )
	{
		$this->column = $pColumn;
		$this->key = $pHash;
		$this->id = NULL;
		$this->info = NULL;
		parent::__construct( $pHash, $pDqi, $pSql, $force );
	}

	/**
	* Get item data from local database.
	* @return $this Chainable.
	*/
	protected function pullJsonFromDb()
	{
		$returnValue = NULL;
		if ( $this->key !== NULL )
		{
			$query = sprintf( BattleNet_Sql::SELECT_ITEM, $this->column );
			$result = $this->sql->getData( $query, [
				'selectValue' => [ $this->key, \PDO::PARAM_STR ]
			]);

			if ( isArray($result) )
			{
				$this->info = $result[ 0 ];
				$this->json = $this->info[ 'json' ];
			}
		}
		return $this;
	}

	/**
	* Get the item, first check the local DB, otherwise pull from Battle.net.
	*
	* @return string JSON item data.
	*/
	protected function pullJson()
	{
		// Attempt to get it from the local DB.
		$this->pullJsonFromDb();
		// If that fails, then try to get it from Battle.net.
		if ( !isString($this->json) )
		{
			// Request the item from BattleNet.
			$json = $this->dqi->getItem( $this->key );
			$responseCode = $this->dqi->responseCode();
			$url = $this->dqi->getUrl();
			// Log the request.
			$this->sql->addRequest( $this->dqi->battleNetId(), $url );
			if ( $responseCode == 200 )
			{
				$this->requestSuccessful = TRUE;
				$this->json = $json;
			}
		}

		return $this;
	}

	protected function pullJsonFromBattleNet()
	{
	}

	/**
	* Load properties from the JSON into this object.
	* @return $this Chainable.
	*/
	protected function processJson()
	{
		$this->info = json_decode( $this->json, TRUE );
		if ( isArray($this->info) )
		{
			$this->name = $this->info[ 'name' ];
			$this->type = $this->info[ 'type' ];
			$this->key = substr( $this->info[ 'tooltipParams' ], 5 );
			$this->id = $this->info[ 'id' ];
			if ( $this->requestSuccessful )
			{
				$this->save();
			}
		}
		return $this;
	}

	/**
	* Save the users item locally, in this case a database
	*/
	protected function save()
	{
		$utcTime = gmdate( 'Y-m-d H:i:s' );
		$array = [
			'hash' => [ $this->key, \PDO::PARAM_STR ],
			'id' => [ $this->id, \PDO::PARAM_STR ],
			'name' => [ $this->name, \PDO::PARAM_STR ],
			'itemType' => [ $this->type['id'], \PDO::PARAM_STR ],
			'json' => [ $this->json, \PDO::PARAM_STR ],
			'ipAddress' => [ $this->sql->ipAddress(), \PDO::PARAM_STR ],
			'lastUpdate' => [ $utcTime, \PDO::PARAM_STR ],
			'dateAdded' => [ $utcTime, \PDO::PARAM_STR ]
		];
		return $this->sql->save( BattleNet_Sql::INSERT_ITEM, $array );
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