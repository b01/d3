<?php namespace kshabazz\d3a;
/**
 * Get the users item from Battle.Net and present it to the user; store it locally in a database behind the scenes.
 * The item will only be updated after a few ours of retrieving it.
 */

/**
 * Class BattleNet_Model
 *
 * @package kshabazz\d3a
 */
abstract class BattleNet_Model
{
	protected
		$bnr,
		$json,
		$key,
		$loadFromDb,
		$requestSuccessful,
		$sql;

    /**
     * Constructor
     *
     * @param string $pKey
     * @param BattleNet\Requestors\Http $pBnr
     * @param BattleNet\Requestors\Sql $pSql
     * @param bool $pLoadFromCache
     */
    public function __construct( $pKey, BattleNet\Requestors\Http $pBnr, BattleNet\Requestors\Sql $pSql, $pLoadFromCache = TRUE )
	{
		$this->bnr = $pBnr;
		$this->json = NULL;
		$this->key = $pKey;
        $this->loadFromDb = $pLoadFromCache;
		$this->requestSuccessful = FALSE;
		$this->sql = $pSql;

        $this->pullJson()
             ->save();
	}

    /**
     * Get raw JSON data returned from Battle.net.
     * @return null
     */
    public function json()
	{
		return $this->json;
	}

    /**
     * Get fresh JSON data from Battle.net after {@see CACHE_LIMIT}, otherwise pull from the DB.
     * @return $this
     */
    protected function pullJson()
    {
        if ( $this->loadFromDb )
        {
            $this->pullJsonFromDb();
        }
        else
        {
            $this->requestJsonFromApi();
        }
        return $this;
    }

	/**
	 * Get the JSON from Battle.Net.
	 * @return $this
	 */
	abstract protected function requestJsonFromApi();

	/**
	 * Get JSON data from a database.
	 * @return $this
	 */
	abstract protected function pullJsonFromDb();

	/**
	 * Save data (usually JSON pulled from the API) to a local database.
	 * @return bool Indicates TRUE on success or FALSE when skipped or a failure occurs.
	 */
	abstract protected function save();
}
?>