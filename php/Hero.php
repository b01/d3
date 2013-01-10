<?php
/**
* Get the users item from Battle.Net and present it to the user; store it locally in a database behind the scenes.
* The item will only be updated after a few ours of retrieving it.
*
*/
namespace d3;

/**
* var $p_heroId string User BattleNet ID.
* var $p_dqi object Data Query Interface.
* var $p_sql object SQL.
*/
class Hero
{
	protected 
		$characterClass,
		$dqi,
		$heroId,
		$info,
		$items,
		$json,
		$loadedFromBattleNet,
		$sql,
		$stats;
	
	
	/**
	* Constructor
	*/
	public function __construct( $p_heroId, \d3\BattleNetDqi $p_dqi, \d3\Sql $p_sql )
	{
		$this->characterClass = NULL;
		$this->dqi = $p_dqi;
		$this->heroId = $p_heroId;
		$this->info = NULL;
		$this->items = NULL;
		$this->json = NULL;
		$this->loadedFromBattleNet = FALSE;
		$this->sql = $p_sql;
		$this->stats = NULL;
		
		$this->init();
	}
	
	/**
	* Destructor
	*/
	public function __destruct()
	{
		unset(
			$this->dqi,
			$this->characterClass,
			$this->heroId,
			$this->info,
			$this->items,
			$this->json,
			$this->loadedFromBattleNet,
			$this->sql,
			$this->stats
		);
	}
	
	/**
	* Character class
	*
	* @return string
	*/
	public function getCharacterClass()
	{
		return $this->characterClass;
	}
	
	/**
	* Get the item, first check the local DB, otherwise pull from Battle.net.
	*
	* @return array
	*/
	public function getItems()
	{
		return $this->items;
	}
	
	/**
	* Get character stats.
	*
	* @return array
	*/
	public function getStats()
	{
		return $this->stats;
	}
	
	/**
	* Get the item, first check the local DB, otherwise pull from Battle.net.
	*
	* @return string JSON item data.
	*/
	public function getJson()
	{
		
		$tenMinutesPassed = sessionTimeExpired( "heroTime", MINUTES_10 );
		if ( $tenMinutesPassed )
		{
			$_SESSION[ 'heroTime' ] = time();
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
		}
		else
		{
			// Get the item from local database.
			$this->info = $this->getHero( $this->heroId );
			if ( isArray($this->info) )
			{
				$this->json = $this->info[0]['json'];
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
	protected function init()
	{
		// Get the hero.
		$this->getJson();
		// Convert the JSON to an associative array.
		if ( isString($this->json) )
		{
			$hero = parseJson( $this->json );
			if ( isArray($hero) )
			{
				$this->items = $hero['items'];
				$this->characterClass = $hero['class'];
				if ( $this->loadedFromBattleNet )
				{
					$this->save();
				}
			}
		}
		return $this;
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