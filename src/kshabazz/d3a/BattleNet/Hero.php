<?php namespace kshabazz\d3a;
/**
 * Request a hero from BattleNet.
 */
/**
 * Class BattleNet_Hero
 *
 * @package kshabazz\d3a
 */
class BattleNet_Hero extends BattleNet_Model
{
	protected
		$key,
		$url;

	/**
	 * Get the JSON from Battle.Net.
	 * @return $this
	 */
	protected function requestJsonFromApi()
	{
		// Request the hero from BattleNet.
		$responseText = $this->bnr->getHero( $this->key );
		$requestSuccessful = ( $this->bnr->responseCode() === 200 );
		// Used for logging info to the DB.
		$url = $this->bnr->url();
		// Log the request.
		$this->sql->addRequest( $this->bnr->battleNetId(), $url );
		if ( $requestSuccessful )
		{
			$this->json = $responseText;
		}
		return $this;
	}

	/**
	 * Get hero data from local database.
	 * @return $this
	 */
	protected function pullJsonFromDb()
	{
		$result = $this->sql->getHero( $this->key );
		if ( \Kshabazz\Slib\isArray($result) )
		{
			$this->json = $result[ 0 ][ 'json' ];
		}
		return $this;
	}

	/**
	 * @return $this
	 */
	protected function processJson()
	{
		return $this;
	}

	/**
	 * Save the hero in a local database.
	 * @return bool Indicates success (TRUE) or failure (FALSE).
	 */
	protected function save()
	{
		// There is no need to save what was loaded from the database.
		if ( $this->loadFromDb )
		{
			return FALSE;
		}
		$utcTime = gmdate( 'Y-m-d H:i:s' );
		return $this->sql->save( BattleNet_Sql::INSERT_HERO, [
			'heroId' => [ $this->key, \PDO::PARAM_STR ],
			'battleNetId' => [ $this->bnr->battleNetId(), \PDO::PARAM_STR ],
			'json' => [ $this->json, \PDO::PARAM_STR ],
			'ipAddress' => [ $this->sql->ipAddress(), \PDO::PARAM_STR ],
			'lastUpdated' => [ $utcTime, \PDO::PARAM_STR ],
			'dateAdded' => [ $utcTime, \PDO::PARAM_STR ]
		]);
	}
}
?>
