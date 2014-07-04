<?php namespace kshabazz\d3a\BattleNet\Handlers;
/**
 * Get the users profile from Battle.Net and present it to the user; store it locally in a database behind the scenes.
 * The profile will only be updated after a few ours of retrieving it.
 */
use function \Kshabazz\Slib\isArray;
/**
 * var $p_battleNetId string User BattleNet ID.
 * var $pBnr object Data Query Interface.
 * var $pSql object SQL.
 */
class Profile extends \kshabazz\d3a\BattleNet_Model
{
	protected
		$column,
		$heroes,
		$profile,
		$sql,
		$url;

	/**
	 * Constructor
	 *
	 * @param string $pBattleNetId
	 * @param \kshabazz\d3a\BattleNet\Requestors\Http $pBnr
	 * @param \kshabazz\d3a\BattleNet\Requestors\Sql $pSql
	 * @param bool $pLoadFromCache
	 */
	public function __construct(
		$pBattleNetId,
		\kshabazz\d3a\BattleNet\Requestors\Http $pBnr,
		\kshabazz\d3a\BattleNet\Requestors\Sql $pSql,
		$pLoadFromCache )
	{
		$this->column = "battle_net_id";
		$this->profile = NULL;
		parent::__construct( $pBattleNetId, $pBnr, $pSql, $pLoadFromCache );
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
	 * Request the profile from BattleNet.
	 *
	 * @return $this
	 */
	protected function requestJsonFromApi()
	{
		$responseText = $this->bnr->getProfile( $this->key );
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
		$query = \kshabazz\d3a\BattleNet\Requestors\Sql::INSERT_PROFILE;
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