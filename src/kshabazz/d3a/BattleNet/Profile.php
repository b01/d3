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
		$column,
		$heroes,
		$profile,
		$sql,
		$url;

    /**
     * Constructor
     * @param                     $pBattleNetId
     * @param BattleNet_Requestor $pDqi
     * @param BattleNet_Sql       $pSql
     * @param bool                $pLoadFromCache
     */
    public function __construct( $pBattleNetId, BattleNet_Requestor $pDqi, BattleNet_Sql $pSql, $pLoadFromCache )
	{
		$this->column = "battle_net_id";
		$this->profile = NULL;
		parent::__construct( $pBattleNetId, $pDqi, $pSql, $pLoadFromCache );
	}

    /**
     * Get profile JSON from the database.
     * @return $this
     */
    protected function pullJsonFromDb()
	{
		// Get the profile from local database.
		$result = $this->sql->getProfile( $this->key );
		if ( isArray($result) )
		{
			$this->json = $result[ 'json' ];
		}
		return $this;
	}

	/**
	 * 	Request the profile from BattleNet.
	 *
	 * @return $this
	 */
	protected function requestJsonFromApi()
	{
		$responseText = $this->dqi->getProfile( $this->key );
		$requestSuccessful = ( $this->dqi->responseCode() === 200 );
        // Used for logging info to the DB.
        $url = $this->dqi->url();
		// Log the request.
		$this->sql->addRequest( $this->dqi->battleNetId(), $url );
		if ( $requestSuccessful )
		{
			$this->json = $responseText;
		}
		return $this;
	}

    /**
     * Load properties from the JSON into this object.
     * @return $this
     */
    protected function processJson()
	{
		return $this;
	}

    /**
     * Save the users profile locally to the database.
     * @return bool
     */
    protected function save()
	{
        // There is no need to save what was loaded from the database.
        if ( $this->loadFromDb )
        {
            return false;
        }
        // save it to the database.
		$utcTime = gmdate( 'Y-m-d H:i:s' );
		$query = BattleNet_Sql::INSERT_PROFILE;
		return $this->sql->save( $query, [
			'battleNetId' => [ $this->key, \PDO::PARAM_STR ],
			'json' => [ $this->json, \PDO::PARAM_STR ],
			'ipAddress' => [ $this->sql->ipAddress(), \PDO::PARAM_STR ],
			'lastUpdated' => [ $utcTime, \PDO::PARAM_STR ],
			'dateAdded' => [ $utcTime, \PDO::PARAM_STR ]
		]);
	}
}
?>