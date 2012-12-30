<?php
namespace d3;
/**
* Get the users item from Battle.Net and present it to the user; store it locally in a database behind the scenes.
* The item will only be updated after a few ours of retrieving it.
*
*/

use \d3\Tool;
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
		$dqi,
		$info,
		$itemHash,
		$json,
		$loadedFromBattleNet,
		$sql;
	
	/**
	* Constructor
	*/
	public function __construct( $p_id, $p_column, BattleNetDqi $p_dqi, Sql $p_sql )
	{
		$this->dqi = $p_dqi;
		$this->sql = $p_sql;
		$this->info = NULL;
		$this->json = NULL;
		$this->loadedFromBattleNet = FALSE;
		$this->load( $p_id,  $p_column );
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
	* Get hero data from local database.
	*/
	protected function getItem()
	{
		if ( $this->id !== NULL )
		{
			return $this->sql->getData( Sql::SELECT_ITEM, [
				"itemPrimaryValue" => [ $this->id, \PDO::PARAM_STR ]
			]);
		}
		return NULL;
	}
	
	/**
	* Get the item, first check the local DB, otherwise pull from Battle.net.
	*
	* @return string JSON item data.
	*/
	protected function getJson( $p_value, $p_column )
	{
		// Get the item info locally from the database.
		// if ( !isString($p_value) && !isString($p_column) )
		// {
			// throw new \Exception( "Invalid data used to retrieve an item." );
		// }
		
		// $this->info = $this->getItem();
		// if ( isArray($this->info) )
		// {
			// $this->json = $this->info['json'];
		// }
		// // If that fails, then try to get it from Battle.net.
		// if ( !isString($this->json) )
		// {
			// Request the item from BattleNet.
			$json = $this->dqi->getItem( $p_value );
			$responseCode = $this->dqi->responseCode();
			$url = $this->dqi->getUrl();
			// Log the request.
			$this->sql->addRequest( $this->dqi->getBattleNetId(), $url );
			if ( $responseCode == 200 )
			{
				$this->json = $json;
				$this->loadedFromBattleNet = TRUE;
			}
		// }
		
		return $this->json;
	}
	
	/**
	* Get raw JSON data returned from Battle.net.
	*/
	public function getRawData()
	{
		if ( $this->json !== NULL )
		{
			return $this->json;
		}
		return NULL;
	}
	
	/**
	* Load the users item into this class
	*/
	public function load( $p_id, $p_column = "hash" )
	{
		$returnValue = FALSE;
		// Get the item.
		$this->getJson( $p_id, $p_column );
		// Convert the JSON to an associative array.
		if ( isString($this->json) )
		{
			$this->itemHash = $p_id;
			$this->column = $p_column;
			if ( $this->loadedFromBattleNet )
			{
				// $this->save( "item" );
				$returnValue = TRUE;
			}
		}
		
		return $returnValue;
	}
	
	/**
	* Save the users item locally, in this case a database
	*/
	protected function save()
	{
		$timeStamp = date( "Y-m-d H:i:s" );
		return $this->sql->save( self::INSERT_ITEM, [
			":hash" => $p_itemHash, \PDO::PARAM_STR,
			":id" => $p_item->id, \PDO::PARAM_STR,
			":name" => $p_item->name, \PDO::PARAM_STR,
			":itemType" => $p_item->type['id'], \PDO::PARAM_STR,
			":json" => $p_itemJson, \PDO::PARAM_STR,
			":ipAddress" => $this->ipAddress, \PDO::PARAM_STR,
			":lastUpdate" => $timeStamp, \PDO::PARAM_STR,
			":dateAdded" => $timeStamp, \PDO::PARAM_STR
		]);
		// OBSOLETE, use above instead.
		// return $this->sql->saveItem( $this->itemHash, $this->item, $this->json );
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