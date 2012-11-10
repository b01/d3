<?php
/**
* Get the users item from Battle.Net and present it to the user; store it locally in a database behind the scenes.
* The item will only be updated after a few ours of retrieving it.
*
*/
namespace d3cb\Api;

use \d3cb\Tool;

/**
* var $p_heroId string User BattleNet ID.
* var $p_dqi object Data Query Interface.
* var $p_sql object SQL.
* var $p_userIp string User IP address.
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
		$sql,
		$userIp;
	
	
	/**
	* Constructor
	*/
	public function __construct( $p_heroId, \d3cb\BattleNetDqi $p_dqi, \d3cb\Sql $p_sql, $p_userIp )
	{
		$this->heroId = $p_heroId;
		$this->dqi = $p_dqi;
		$this->sql = $p_sql;
		$this->userIp = $p_userIp;
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
			$this->sql,
			$this->userIp
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
		$this->info = NULL;//$this->sql->getHero( $this->heroId );
		if ( isArray($this->info) )
		{
			$this->json = $this->info['hero_json'];
		}
		// If that fails, then try to get it from Battle.net.
		if ( !isString($this->json) )
		{
			// Request the hero from BattleNet.
			$json = $this->dqi->getHero( $this->heroId );
			$responseCode = $this->dqi->responseCode();
			$url = $this->dqi->getUrl();
			// Log the request.
			$this->sql->addRequest( $this->dqi->getBattleNetId(), $url, $this->userIp );
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
	* Save the users hero locally, in this case a database
	*/
	protected function save()
	{
		// return $this->sql->saveItem( $this->heroId, $this->hero, $this->json, $this->userIp );
	}
}
?>