<?php namespace kshabazz\d3a;
/**
* Get the users item from Battle.Net and present it to the user; store it locally in a database behind the scenes.
* The item will only be updated after a few ours of retrieving it.
*
*/

/**
* var $pDqi object Data Query Interface.
* var $pSql object SQL.
*/
abstract class BattleNet_Model
{
	protected
		$dqi,
		$forceLoadFromBattleNet,
		$json,
		$key,
		$loadedFromBattleNet,
		$sql;

	/**
	* Constructor
	*/
	public function __construct( $pKey, BattleNet_Dqi $pDqi, Sql $pSql, $pForceLoadFromBattleNet )
	{
		$this->dqi = $pDqi;
		$this->forceLoadFromBattleNet = $pForceLoadFromBattleNet;
		$this->json = NULL;
		$this->key = $pKey;
		$this->loadedFromBattleNet = FALSE;
		$this->sql = $pSql;

		$this->pullJson()
			->processJson();
	}

	/**
	* Get raw JSON data returned from Battle.net.
	*/
	public function json()
	{
		return $this->json;
	}

	/**
	* Get the item, first check the local DB, otherwise pull from Battle.net.
	*
	* @return string JSON item data.
	*/
	abstract protected function pullJson();

	/**
	* Get the JSON from Battle.Net.
	* @return Hero
	*/
	abstract protected function pullJsonFromBattleNet();

	/**
	* Get hero data from local database.
	* @return Hero
	*/
	abstract protected function pullJsonFromDb();

	/**
	* Load the users hero into this class
	*/
	abstract protected function processJson();

	/**
	* Save the users hero in a local database.
	* @return bool Indicates success (TRUE) or failure (FALSE).
	*/
	abstract protected function save();
}
?>