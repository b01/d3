<?php
namespace d3;
/**
* Get the users item from Battle.Net and present it to the user; store it locally in a database behind the scenes.
* The item will only be updated after a few ours of retrieving it.
*
*/

use \d3\BattleNetDqi;
use \d3\Sql;

/**
* var $p_itemHash string User BattleNet ID.
* var $p_dqi object Data Query Interface.
* var $p_sql object SQL.
*/
class Item
{
	protected 
		$column,
		$dqi,
		$hash,
		$id,
		$info,
		$json,
		$loadedFromBattleNet,
		$sql;
	
	/**
	* Constructor
	*/
	public function __construct( $p_hash, $p_column, BattleNetDqi $p_dqi, Sql $p_sql )
	{
		$this->column = $p_column;
		$this->dqi = $p_dqi;
		$this->hash = $p_hash;
		$this->id = NULL;
		$this->info = NULL;
		$this->json = NULL;
		$this->loadedFromBattleNet = FALSE;
		$this->sql = $p_sql;
		$this->pullJson()
			->processJson();
	}
	
	/**
	* Destructor
	*/
	public function __destruct()
	{
		unset(
			$this->dqi,
			$this->info,
			$this->itemHash,
			$this->json,
			$this->loadedFromBattleNet,
			$this->sql
		);
	}
	
	/**
	* Get item data from local database.
	* @return $this Chainable.
	*/
	protected function pullJsonFromDb()
	{
		$returnValue = NULL;
		if ( $this->hash !== NULL )
		{
			$query = sprintf( Sql::SELECT_ITEM, DB_NAME, $this->column );
			$result = $this->sql->getData( $query, [
				"selectValue" => [ $this->hash, \PDO::PARAM_STR ]
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
			$json = $this->dqi->getItem( $this->hash );
			$responseCode = $this->dqi->responseCode();
			$url = $this->dqi->getUrl();
			// Log the request.
			$this->sql->addRequest( $this->dqi->getBattleNetId(), $url );
			if ( $responseCode == 200 )
			{
				$this->loadedFromBattleNet = TRUE;
				$this->json = $json;
			}
		}
		
		return $this;
	}
	
	/**
	* Get raw JSON data returned from Battle.net.
	*/
	public function json()
	{
		return $this->json;
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
			$this->hash = substr( $this->info[ 'tooltipParams' ], 5 );
			$this->id = $this->info[ 'id' ];
			if ( $this->loadedFromBattleNet )
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
		$utcTime = gmdate( "Y-m-d H:i:s" );
		$array = [
			"hash" => [ $this->hash, \PDO::PARAM_STR ],
			"id" => [ $this->id, \PDO::PARAM_STR ],
			"name" => [ $this->name, \PDO::PARAM_STR ],
			"itemType" => [ $this->type['id'], \PDO::PARAM_STR ],
			"json" => [ $this->json, \PDO::PARAM_STR ],
			"ipAddress" => [ $this->sql->ipAddress(), \PDO::PARAM_STR ],
			"lastUpdate" => [ $utcTime, \PDO::PARAM_STR ],
			"dateAdded" => [ $utcTime, \PDO::PARAM_STR ]
		];
		return $this->sql->save( Sql::INSERT_ITEM, $array );
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