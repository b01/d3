<?php
/**
* Get the users profile from Battle.Net and present it to the user; store it locally in a database behind the scenes.
* The profile will only be updated after a few ours of retrieving it.
*
*/
namespace d3cb\Api;

use \d3cb\Tool;

/**
* var $p_itemId string User BattleNet ID.
* var $p_dqi object Data Query Interface.
* var $p_sql object SQL.
* var $p_userIp string User IP address.
*/
class Item
{
	protected 
		$itemId,
		$dqi,
		$sql,
		$profile,
		$info,
		$profileJson,
		$userIp;
	
	
	/**
	* Constructor
	*/
	public function __construct( $p_itemId, \d3cb\BattleNetDqi $p_dqi, \d3cb\Sql $p_sql, $p_userIp )
	{
		$this->itemId = $p_itemId;
		$this->dqi = $p_dqi;
		$this->sql = $p_sql;
		$this->userIp = $p_userIp;
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
			$this->itemId,
			$this->dqi,
			$this->sql,
			$this->profile,
			$this->info,
			$this->profileJson,
			$this->userIp
		);
	}
	
	/**
	* Destructor
	*/
	protected function getJson()
	{
		// Get the profile from local database.
		$this->info = $this->sql->getItem( $this->itemId );
		if ( Tool::isArray($this->info) )
		{
			$this->profileJson = $this->info['profile_json'];
		}
		// If that fails, then try to get it from Battle.net.
		if ( !Tool::isString($this->profileJson) )
		{
			// Request the profile from BattleNet.
			$profileJson = $this->dqi->getProfile( $this->itemId );
			$responseCode = $this->dqi->responseCode();
			$url = $this->dqi->getUrl();
			// Log the request.
			$this->sql->addRequest( $this->itemId, $url, $this->userIp );
			if ( $responseCode == 200 )
			{
				$this->profileJson = $profileJson;
				$this->save();
			}
		}
		
		return $this->profileJson;
	}
	
	/**
	* Destructor
	*/
	public function getHeroes( $p_heroByName = NULL )
	{
		$returnValue = NULL;
		if ( Tool::isArray($this->profile) )
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
	* Destructor
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
		$this->getJson( $this->itemId );
		// Convert the JSON to an associative array.
		if ( Tool::isString($this->profileJson) )
		{
			$profile = Tool::parseJson( $this->profileJson );
			if ( Tool::isArray($profile) )
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
		return $this->sql->saveProfile( $this->itemId, $this->profileJson, $this->userIp );
	}
}
?>