<?php namespace kshabazz\d3a\BattleNet\Requestors;

use function \Kshabazz\Slib\logError, \Kshabazz\Slib\isArray;

class Sql extends \kshabazz\d3a\Sql
{
	const
		SELECT_PROFILE = "SELECT `battle_net_id`, `json`, `ip_address`, `last_updated`, `date_added` FROM `d3_profiles` WHERE `battle_net_id` = :battleNetId;",
		INSERT_PROFILE = "INSERT INTO `d3_profiles` (`battle_net_id`, `json`, `ip_address`, `last_updated`, `date_added`) VALUES(:battleNetId, :json, :ipAddress, :lastUpdated, :dateAdded) ON DUPLICATE KEY UPDATE `json` = VALUES(json), `ip_address` = VALUES(ip_address), `last_updated` = VALUES(last_updated);",
		INSERT_REQUEST = "INSERT INTO `battlenet_api_request` (`battle_net_id`, `ip_address`, `url`, `date_number`, `date_added`) VALUES(:battleNetId, :ipAddress, :url, :dateNumber, :dateAdded);",
		SELECT_REQUEST = "SELECT `ip_address`, `url`, `date`, `date_added` FROM `battlenet_api_request` WHERE  `date` = :date;",
		SELECT_ITEM = "SELECT `hash`, `id`, `name`, `item_type`, `json`, `ip_address`, `last_updated`, `date_added` FROM `d3_items` WHERE `%s` = :selectValue;",
		INSERT_ITEM = "INSERT INTO `d3_items` (`hash`, `id`, `name`, `item_type`, `json`, `ip_address`, `last_updated`, `date_added`) VALUES(:hash, :id, :name, :itemType, :json, :ipAddress, :lastUpdate, :dateAdded);",
		SELECT_HERO = "SELECT `id`, `battle_net_id`, `json`, `ip_address`, `last_updated`, `date_added` FROM `d3_heroes` WHERE `id` = :id;",
		INSERT_HERO = "INSERT INTO `d3_heroes` (`id`, `battle_net_id`, `json`, `ip_address`, `last_updated`, `date_added`) VALUES(:heroId, :battleNetId, :json, :ipAddress, :lastUpdated, :dateAdded) ON DUPLICATE KEY UPDATE `json` = VALUES(json), `ip_address` = VALUES(ip_address), `last_updated` = VALUES(last_updated);";

	/**
	* @param $p_url string The Battle.net url web API URL requested.
	* @return bool
	*/
    /**
     * Add record of Battle.net Web API request.
     * @param $p_battleNetId
     * @param $p_url
     * @return bool|mixed
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
     *
     * @param $pHeroId
     * @return array|null
     */
    public function getHero( $pHeroId )
	{
		if ( $pHeroId !== NULL )
		{
			return $this->getData( self::SELECT_HERO, [ "id" => [$pHeroId, \PDO::PARAM_STR] ]);
		}
		return NULL;
	}

    /**
     * Get battle.net user profile.
     *
     * @param $pBattleNetId
     * @return null
     */
    public function getProfile($pBattleNetId )
	{
		$returnValue = NULL;
		try
		{
			if ($this->pdoh !== NULL)
			{
				$query = self::SELECT_PROFILE;
				$stmt = $this->pdoh->prepare( $query );
				$stmt->bindValue( ":battleNetId", $pBattleNetId, \PDO::PARAM_STR );
				$profileRecord = $this->pdoQuery( $stmt );
				if ( isArray($profileRecord) )
				{
					$returnValue = $profileRecord[0];
				}
			}
		}
		catch ( \Exception $p_error )
		{
//			logError(
//				$p_error,
//				$p_error->getMessage(),
//				"Unable to retrieve your profile, please try again later."
//			);
			logError( $p_error, $p_error->getMessage() );
		}
		return $returnValue;
	}
}
// DO NOT PUT ANY CHARACTERS OR EVEN WHITE-SPACE after the closing php tag, or headers may be sent before intended.
?>