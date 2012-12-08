<?php
/**
* Get the users profile from Battle.Net and present it to the user; store it locally in a database behind the scenes.
* The profile will only be updated after a few ours of retrieving it.
*
*/
namespace d3;

use \d3\Tool;

/**
* var $p_battleNetId string User BattleNet ID.
* var $p_dqi object Data Query Interface.
* var $p_sql object SQL.
*/
class Profile
{
	protected 
		$battleNetId,
		$dqi,
		$sql,
		$profile,
		$info,
		$profileJson;
	
	
	/**
	* Constructor
	*/
	public function __construct( $p_battleNetId, \d3\BattleNetDqi $p_dqi, \d3\Sql $p_sql )
	{
		$this->battleNetId = $p_battleNetId;
		$this->dqi = $p_dqi;
		$this->sql = $p_sql;
		$this->profile = NULL;
		$this->info = NULL;
		$this->profileJson = NULL;
		$this->load();
	}
	
	/**
	* Destructor
	*/
	public function __destruct()
	{
		unset(
			$this->battleNetId,
			$this->dqi,
			$this->sql,
			$this->profile,
			$this->info,
			$this->profileJson
		);
	}
	
	/**
	* Get the user hero profiles, first check the local DB, otherwise pull from Battle.net.
	*
	* @return string JSON item data.
	*/
	protected function getJson()
	{
		// Get the profile from local database.
		$this->info = $this->sql->getProfile( $this->battleNetId );
		if ( isArray($this->info) )
		{
			$this->profileJson = $this->info['profile_json'];
		}
		// If that fails, then try to get it from Battle.net.
		if ( !isString($this->profileJson) )
		{
			// Request the profile from BattleNet.
			$profileJson = $this->dqi->getProfile( $this->battleNetId );
			$responseCode = $this->dqi->responseCode();
			$url = $this->dqi->getUrl();
			// Log the request.
			$this->sql->addRequest( $this->battleNetId, $url );
			if ( $responseCode == 200 )
			{
				$this->profileJson = $profileJson;
				$this->save();
			}
		}
		
		return $this->profileJson;
	}
	
	/**
	* Get Hero(s) data.
	*
	* @param $p_heroByName string Optional name to specify a single hero to return.
	* @return mixed Heroes(s) data as an array, or null if none.
	*/
	public function getHeroes( $p_heroByName = NULL )
	{
		$returnValue = NULL;
		if ( isArray($this->profile) )
		{
			if ( $p_heroByName !== NULL && array_key_exists($p_heroByName, $this->profile['heroes']) )
			{
				$returnValue = $this->profile['heroes'][ $p_heroByName ];
			}
			else
			{
				$returnValue = $this->profile['heroes'];
			}
		}
		
		return $returnValue;
	}
	
	/**
	* Get raw JSON data returned from Battle.net.
	*/
	public function getRawData()
	{
		if ( $this->profileJson !== NULL )
		{
			return $this->profileJson;
		}
		return NULL;
	}
	
	/**
	* Load the users profile into this class
	*/
	protected function load()
	{
		// Get the profile from local database.
		$this->getJson();
		// Convert the JSON to an associative array.
		if ( isString($this->profileJson) )
		{
			$profile = parseJson( $this->profileJson );
			if ( isArray($profile) )
			{
				$this->profile = $profile;
			}
		}
		
		return $this->profile;
	}
	
	/**
	* Save the users profile locally, in this case a database
	*/
	protected function save()
	{
		return $this->sql->saveProfile( $this->battleNetId, $this->profileJson );
	}
}
?>