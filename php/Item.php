<?php
namespace d3;
/**
* Get the users item from Battle.Net and present it to the user; store it locally in a database behind the scenes.
* The item will only be updated after a few ours of retrieving it.
*
*/

require( "php/ItemModel.php" );
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
		$item,
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
		$this->item = NULL;
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
			$this->item,
			$this->itemHash,
			$this->json,
			$this->loadedFromBattleNet,
			$this->sql
		);
	}
	
	/**
	* Get the item, first check the local DB, otherwise pull from Battle.net.
	*
	* @return string JSON item data.
	*/
	protected function getJson( $p_value, $p_column )
	{
		// Get the item info locally from the database.
		if ( !\d3\isString($p_value) && !\d3\isString($p_column) )
		{
			throw new \Exception( "Invalid data used to retrieve an item." );
		}
		
		$this->info = $this->sql->getItem( $p_value, $p_column );
		if ( \d3\isArray($this->info) )
		{
			$this->json = $this->info['json'];
		}
		// If that fails, then try to get it from Battle.net.
		if ( !\d3\isString($this->json) )
		{
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
		}
		
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
		if ( \d3\isString($this->json) )
		{
			$item = new ItemModel( $this->json );
			if ( $item instanceof \d3\ItemModel )
			{
				$this->itemHash = $p_id;
				$this->column = $p_column;
				$this->item = $item;
				if ( $this->loadedFromBattleNet )
				{
					$this->save( "item" );
					$returnValue = TRUE;
				}
			}
		}
		
		return $returnValue;
	}
	
	/**
	* Save the users item locally, in this case a database
	*/
	protected function save()
	{
		return $this->sql->saveItem( $this->itemHash, $this->item, $this->json );
	}

	/**
	* Convert this object to a string.
	* @return string
	*/
	public function __toString()
	{
		return json_encode( $this->item, JSON_PRETTY_PRINT );
	}
}
?>