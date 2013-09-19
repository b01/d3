<?php namespace kshabazz\d3a;

class BattleNet_Sql extends Sql
{
	const
		SELECT_PROFILE = "SELECT `battle_net_id`, `json`, `ip_address`, `last_updated`, `date_added` FROM `%s`.`d3_profiles` WHERE `battle_net_id` = :battleNetId;",
		INSERT_PROFILE = "INSERT INTO `%s`.`d3_profiles` (`battle_net_id`, `json`, `ip_address`, `last_updated`, `date_added`) VALUES(:battleNetId, :json, :ipAddress, :lastUpdated, :dateAdded) ON DUPLICATE KEY UPDATE `json` = VALUES(json), `ip_address` = VALUES(ip_address), `last_updated` = VALUES(last_updated);",
		INSERT_REQUEST = "INSERT INTO `battlenet_api_request` (`battle_net_id`, `ip_address`, `url`, `date_number`, `date_added`) VALUES(:battleNetId, :ipAddress, :url, :dateNumber, :dateAdded);",
		SELECT_REQUEST = "SELECT `ip_address`, `url`, `date`, `date_added` FROM `battlenet_api_request` WHERE  `date` = :date;",
		SELECT_ITEM = "SELECT `hash`, `id`, `name`, `item_type`, `json`, `ip_address`, `last_updated`, `date_added` FROM `%s`.`d3_items` WHERE `%s` = :selectValue;",
		INSERT_ITEM = "INSERT INTO `d3_items` (`hash`, `id`, `name`, `item_type`, `json`, `ip_address`, `last_updated`, `date_added`) VALUES(:hash, :id, :name, :itemType, :json, :ipAddress, :lastUpdate, :dateAdded);",
		SELECT_HERO = "SELECT `id`, `battle_net_id`, `json`, `ip_address`, `last_updated`, `date_added` FROM `d3_heroes` WHERE `id` = :id;",
		INSERT_HERO = "INSERT INTO `d3_heroes` (`id`, `battle_net_id`, `json`, `ip_address`, `last_updated`, `date_added`) VALUES(:heroId, :battleNetId, :json, :ipAddress, :lastUpdated, :dateAdded) ON DUPLICATE KEY UPDATE `json` = VALUES(json), `ip_address` = VALUES(ip_address), `last_updated` = VALUES(last_updated);";

	/**
	* Add record of Battle.net Web API request.
	* @param $p_url string The Battle.net url web API URL requested.
	* @return bool
	*/
	public function addRequest( $p_battleNetId, $p_url )
	{
		$returnValue = FALSE;
		try
		{
			if ( $this->pdoh !== NULL )
			{
				$today = date( "Y-m-d" );
				$stmt = $this->pdoh->prepare( self::INSERT_REQUEST );
				$stmt->bindValue( ":battleNetId", $p_battleNetId, \PDO::PARAM_STR );
				$stmt->bindValue( ":ipAddress", $this->ipAddress, \PDO::PARAM_STR );
				$stmt->bindValue( ":url", $p_url, \PDO::PARAM_STR );
				$stmt->bindValue( ":dateNumber", strtotime($today), \PDO::PARAM_STR );
				$stmt->bindValue( ":dateAdded", date("Y-m-d H:i:s"), \PDO::PARAM_STR );
				$returnValue = $this->pdoQuery( $stmt, FALSE );
			}
		}
		catch ( \Exception $p_error )
		{
			// TODO: Log error.
			// echo $p_error->getMessage();
		}
		return $returnValue;
	}

	/**
	* Get hero data from local database.
	*/
	public function getHero( $p_heroId )
	{
		if ( $p_heroId !== NULL )
		{
			return $this->getData( self::SELECT_HERO, [
				"id" => [ $p_heroId, \PDO::PARAM_STR ]
			]);
		}
		return NULL;
	}

	/**
	* Get battle.net user profile.
	*/
	public function getProfile( $p_battleNetId )
	{
		$returnValue = NULL;
		try
		{
			if ($this->pdoh !== NULL)
			{
				$query = sprintf( self::SELECT_PROFILE, DB_NAME );
				$stmt = $this->pdoh->prepare( $query );
				$stmt->bindValue( ":battleNetId", $p_battleNetId, \PDO::PARAM_STR );
				$profileRecord = $this->pdoQuery( $stmt );
				if ( isArray($profileRecord) )
				{
					$returnValue = $profileRecord[0];
				}
			}
		}
		catch ( \Exception $p_error )
		{
			logError(
				$p_error,
				$p_error->getMessage(),
				"Unable to retrieve your profile, please try again later."
			);
		}
		return $returnValue;
	}
}
// DO NOT PUT ANY CHARACTERS OR EVEN WHITE-SPACE after the closing php tag, or headers may be sent before intended.
?>