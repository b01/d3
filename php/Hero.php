<?php
/**
* Get the users item from Battle.Net and present it to the user; store it locally in a database behind the scenes.
* The item will only be updated after a few ours of retrieving it.
*
*/
namespace d3;

use \d3\Tool;

/**
* var $p_heroId string User BattleNet ID.
* var $p_dqi object Data Query Interface.
* var $p_sql object SQL.
*/
class Hero
{
	protected 
		$dqi,
		$info,
		$items,
		$hero,
		$heroId,
		$json,
		$loadedFromBattleNet,
		$sql;
	
	
	/**
	* Constructor
	*/
	public function __construct( $p_heroId, \d3\BattleNetDqi $p_dqi, \d3\Sql $p_sql )
	{
		$this->heroId = $p_heroId;
		$this->dqi = $p_dqi;
		$this->sql = $p_sql;
		$this->hero = NULL;
		$this->items = NULL;
		$this->info = NULL;
		$this->json = NULL;
		$this->loadedFromBattleNet = FALSE;
		$this->load();
	}
	
	/**
	* Destructor
	*/
	public function __destruct()
	{
		unset(
			$this->dqi,
			$this->info,
			$this->items,
			$this->hero,
			$this->heroId,
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
	public function getItems()
	{
		return $this->items;
	}
	
	/**
	* Get the item, first check the local DB, otherwise pull from Battle.net.
	*
	* @return string JSON item data.
	*/
	protected function getJson()
	{
		// Get the item from local database.
		$this->info = $this->getHero( $this->heroId );
		if ( isArray($this->info) )
		{
			$this->json = $this->info[0]['json'];
		}
		// If that fails, then try to get it from Battle.net.
		if ( !isString($this->json) )
		{
			// Request the hero from BattleNet.
			$json = $this->dqi->getHero( $this->heroId );
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
	* Get hero data from local database.
	*/
	protected function getHero()
	{
		if ( $this->heroId !== NULL )
		{
			return $this->sql->getData( Sql::SELECT_HERO, [
				"id" => [ $this->heroId, \PDO::PARAM_STR ]
			]);
		}
		return NULL;
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
	* Load the users hero into this class
	*/
	protected function load()
	{
		// Get the hero.
		$this->getJson();
		// Convert the JSON to an associative array.
		if ( isString($this->json) )
		{
			$hero = parseJson( $this->json );
			if ( isArray($hero) )
			{
				$this->hero = $hero;
				$this->items = $hero['items'];
				if ( $this->loadedFromBattleNet )
				{
					$this->save();
				}
			}
		}
		
		return $this->hero;
	}
	
	/**
	* Save the users hero in a local database.
	* @return bool Indicates success (TRUE) or failure (FALSE).
	*/
	protected function save()
	{
		$timeStamp = date( "Y-m-d H:i:s" );
		return $this->sql->save( Sql::INSERT_HERO, [
			"heroId" => [ $this->heroId, \PDO::PARAM_STR ],
			"battleNetId" => [ $this->dqi->getBattleNetId(), \PDO::PARAM_STR ],
			"json" => [ $this->json, \PDO::PARAM_STR ],
			"ipAddress" => [ $this->sql->getIpAddress(), \PDO::PARAM_STR ],
			"lastUpdated" => [ $timeStamp, \PDO::PARAM_STR ],
			"dateAdded" => [ $timeStamp, \PDO::PARAM_STR ]
		]);
	}
}
?>