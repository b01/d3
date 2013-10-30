<?php namespace kshabazz\d3a;
/**
* Get the users profile from Battle.Net and present it to the user; store it locally in a database behind the scenes.
* The profile will only be updated after a few ours of retrieving it.
*
*/

/**
* var $p_battleNetId string User BattleNet ID.
* var $pDqi object Data Query Interface.
* var $pSql object SQL.
*/
class BattleNet_Profile extends BattleNet_Model
{
	protected
		$battleNetId,
		$column,
		$profile,
		$sql;


	/**
	* Constructor
	*/
	public function __construct( $pBattleNetId, BattleNet_Requestor $pDqi, BattleNet_Sql $pSql, $pForceLoadFromBattleNet )
	{
		$this->column = "battle_net_id";
		$this->profile = NULL;
		parent::__construct( $pBattleNetId, $pDqi, $pSql, $pForceLoadFromBattleNet );
	}

	/**
	* Destructor
	*/
	public function __destruct()
	{
		unset(
			$this->battleNetId,
			$this->column,
			$this->dqi,
			$this->json,
			$this->loadedFromBattleNet,
			$this->profile,
			$this->sql
		);
	}

	/**
	* Get profile JSON from the database.
	*
	* @return string JSON item data.
	*/
	protected function pullJsonFromDb()
	{
		// Get the profile from local database.
		$result = $this->sql->getProfile( $this->battleNetId );
		if ( isArray($result) )
		{
			$this->json = $result[ 'json' ];
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
		if ( !$this->forceLoadFromBattleNet )
		{
			// Attempt to get it from the local DB.
			$this->pullJsonFromDb();
		}
		// If that fails, then try to get it from Battle.net.
		if ( gettype($this->json) !== "string" )
		{
			$this->pullJsonFromBattleNet();
		}
		return $this;
	}

	/**
	* Example:
	* url ::= <host> "/api/d3/data/item/" <item-data>
	* GET /api/d3/data/item/COGHsoAIEgcIBBXIGEoRHYQRdRUdnWyzFB2qXu51MA04kwNAAFAKYJMD
	* Note: Leave off the trailing '/' when setting
	*	/api/d3/profile/<battleNetIdName>-<battleNetIdNumber>
	*/
	protected function pullJsonFromBattleNet()
	{
		// Request the item from BattleNet.
		$responseText = $this->dqi->getProfile( $this->key );
		$responseCode = $this->dqi->responseCode();
		$this->url = $this->dqi->getUrl();
		// Log the request.
		$this->sql->addRequest( $this->dqi->getBattleNetId(), $this->url );
		if ( $responseCode === 200 )
		{
			$this->json = $responseText;
			$this->loadedFromBattleNet = TRUE;
			$this->save();
		}
		return $this;
	}

	/**
	* Load properties from the JSON into this object.
	* @return $this Chainable.
	*/
	protected function processJson()
	{
		$this->profile = json_decode( $this->json, TRUE );
		if ( isArray($this->profile) )
		{
			$this->heroes = $this->profile[ 'heroes' ];
		}
		return $this;
	}

	/**
	* Get Hero(s) data.
	*
	* @param $p_heroByName string Optional name to specify a single hero to return.
	* @return mixed Heroes(s) data as an array, or null if none.
	*/
	public function heroes( $p_heroByName = NULL )
	{
		$returnValue = NULL;
		if ( isArray($this->heroes) )
		{
			if ( $p_heroByName !== NULL && array_key_exists($p_heroByName, $this->heroes) )
			{
				$returnValue = $this->heroes[ $p_heroByName ];
			}
			else
			{
				$returnValue = $this->heroes;
			}
		}

		return $returnValue;
	}

	/**
	* Get raw JSON data returned from Battle.net.
	*/
	public function json()
	{
		return $this->json;
	}

	/**
	* Save the users profile locally to the database.
	* @return bool
	*/
	protected function save()
	{
		$utcTime = gmdate( "Y-m-d H:i:s" );
		$query = sprintf( BattleNet_Sql::INSERT_PROFILE, DB_NAME );
		return $this->sql->save( $query, [
			"battleNetId" => [ $this->key, \PDO::PARAM_STR ],
			"json" => [ $this->json, \PDO::PARAM_STR ],
			"ipAddress" => [ $this->sql->ipAddress(), \PDO::PARAM_STR ],
			"lastUpdated" => [ $utcTime, \PDO::PARAM_STR ],
			"dateAdded" => [ $utcTime, \PDO::PARAM_STR ]
		]);
	}
}
?>