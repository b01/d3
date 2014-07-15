<?php namespace Kshabazz\BattleNet\D3\Handlers;
/**
 * Get the users profile from Battle.Net and present it to the user; store it locally in a database behind the scenes.
 * The profile will only be updated after a few ours of retrieving it.
 */
use \Kshabazz\BattleNet\D3\Requestors\Http,
	\Kshabazz\BattleNet\D3\Requestors\Sql;
use function \Kshabazz\Slib\isArray;
/**
 * var $p_battleNetId string User BattleNet ID.
 * var $pBnr object Data Query Interface.
 * var $pSql object SQL.
 */
class Profile implements Handler
{
	/**
	 * Get profile JSON from the database.
	 *
	 * @param Sql $pSql
	 * @param $pKey
	 * @return null
	 */
	public function pullJson( Sql $pSql, $pKey )
	{
		// Get the profile from local database.
		$result = $pSql->getProfile( $pKey );
		if ( isArray($result) )
		{
			return $result[ 'json' ];
		}
		return NULL;
	}

	/**
	 * Request the profile from BattleNet.
	 *
	 * @param Http $pBnr
	 * @return null|string
	 */
	public function getJson( \Kshabazz\BattleNet\D3\Requestors\Http $pBnr )
	{
		$responseText = $pBnr->getProfile();
		// Log the request.
//		$pSql->addRequest( $pBnr->battleNetId(), $pBnr->url() );
		// Verify that the request was successful.
		$requestSuccessful = ( $pBnr->responseCode() === 200 );
		// Return the response.
		if ( $requestSuccessful )
		{
			return $responseText;
		}
		return NULL;
	}

	/**
	 * Save the users profile locally to the database.
	 *
	 * @param Sql $pResource
	 * @return bool
	 */
	public function save( Sql $pResource )
	{
		// save it to the database.
		$utcTime = gmdate( 'Y-m-d H:i:s' );
		$query = \Kshabazz\BattleNet\D3\Requestors\Sql::INSERT_PROFILE;
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