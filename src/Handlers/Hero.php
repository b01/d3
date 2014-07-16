<?php namespace Kshabazz\BattleNet\D3\Handlers;
/**
 * Request a hero from BattleNet.
 */
/**
 * Class Hero
 *
 * @package Kshabazz\BattleNet\D3
 */
class Hero implements Handler
{
	private $heroId;

	public function __constructor( $pHeroId )
	{
		$this->heroId = $pHeroId;
	}

	/**
	 * Get the JSON from Battle.Net.
	 *
	 * @param \Kshabazz\BattleNet\D3\Requestors\Http $pHttp
	 * @return string|null
	 */
	public function getJson( \Kshabazz\BattleNet\D3\Requestors\Http $pHttp )
	{
		// Request the hero from BattleNet.
		$responseText = $pHttp->getHero( $this->heroId );
		$requestSuccessful = ( $pHttp->responseCode() === 200 );
		if ( $requestSuccessful )
		{
			return $responseText;
		}
		return NULL;
	}

	/**
	 * Get hero data from local database.
	 *
	 * @param \Kshabazz\BattleNet\D3\Requestors\Sql $pSql
	 * @return string|null
	 */
	public function getJsonFromDb( \Kshabazz\BattleNet\D3\Requestors\Sql $pSql )
	{
		$result = $pSql->getHero( $this->heroId );
		if ( \Kshabazz\Slib\isArray($result) )
		{
			return $result[ 0 ][ 'json' ];
		}
		return NULL;
	}

	/**
	 * Save the hero in a local database.
	 *
	 * @param \Kshabazz\BattleNet\D3\Requestors\Sql $pSql
	 * @return bool Indicates success (TRUE) or failure (FALSE).
	 */
	public function save( \Kshabazz\BattleNet\D3\Requestors\Sql $pSql )
	{
		$utcTime = gmdate( 'Y-m-d H:i:s' );
		return $this->sql->pdoQueryBind( \Kshabazz\BattleNet\D3\Requestors\Sql::INSERT_HERO, [
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
