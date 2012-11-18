<?php
namespace d3cb;
/**
* Get the users item from Battle.Net and present it to the user; store it locally in a database behind the scenes.
* The item will only be updated after a few ours of retrieving it.
*
*/

require( "php/classes/Item.php" );
use \d3cb\Tool;
use \d3cb\BattleNetDqi;
use \d3cb\Sql;

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
	public function __construct( $p_itemHash, BattleNetDqi $p_dqi, Sql $p_sql )
	{
		$this->itemHash = $p_itemHash;
		$this->dqi = $p_dqi;
		$this->sql = $p_sql;
		$this->item = NULL;
		$this->info = NULL;
		$this->json = NULL;
		$this->loadedFromBattleNet = FALSE;
		$this->load( $p_itemHash );
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
		if ( !\d3cb\isString($p_value) && !\d3cb\isString($p_column) )
		{
			throw new \Exception( "Invalid data used to retrieve an item." );
		}
		
		$this->info = $this->sql->getItem( $p_value, $p_column );
		if ( \d3cb\isArray($this->info) )
		{
			$this->json = $this->info['json'];
		}
		// If that fails, then try to get it from Battle.net.
		if ( !\d3cb\isString($this->json) )
		{
			// Request the item from BattleNet.
			$json = $this->dqi->getItem( $this->itemHash );
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
	protected function load( $p_id, $p_column = "hash" )
	{
		$returnValue = FALSE;
		// Get the item.
		$this->getJson( $p_id, $p_column );
		// Convert the JSON to an associative array.
		if ( \d3cb\isString($this->json) )
		{
			$item = new ItemModel( $this->json );
			if ( $item instanceof \d3cb\ItemModel )
			{
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