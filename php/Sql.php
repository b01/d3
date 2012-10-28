<?php
namespace d3cb;
class Sql
{
	const
		SELECT_PROFILE = "SELECT `battle_net_id`, `profile_json`, `ip_address`, `last_updated`, `date_added` FROM `%s`.`d3_profiles` WHERE `battle_net_id` = :battleNetId;",
		INSERT_PROFILE = "INSERT INTO `%1\$s`.`d3_profiles` (`battle_net_id`, `profile_json`, `ip_address`, `last_updated`, `date_added`) VALUES(:battleNetId, :profileJson, :ipAddress, :lastUpdated, :addedDate) ON DUPLICATE KEY UPDATE `profile_json` = :profileJson, `ip_address` = :ipAddress, `last_updated` = :lastUpdated;";
		
	protected 
		$pdoh;

	/**
	* Constructor
	*/
	public function __construct( $p_dsn, $p_dbUser, $p_dbPass )
	{
		$this->getPDO( $p_dsn, $p_dbUser, $p_dbPass );
	}

	/**
	* Destructor
	*/
	public function __destruct()
	{
		$this->pdoh = NULL;
	}
	
	/**
	* PDO Object Factory
	* @return 
	*/
	public function getPDO( $p_dsn, $p_dbUser, $p_dbPass )
	{
		if ( !isset($this->pdoh) )
		{
			try
			{
				$this->pdoh = new \PDO( $p_dsn, $p_dbUser, $p_dbPass );
				// Show human readable errors from the database server when they occur.
				$this->pdoh->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
				$this->pdoh->setAttribute( \PDO::ATTR_EMULATE_PREPARES, FALSE );
			}
			catch ( \PDOException $p_error )
			{
				$this->pdoh = NULL;
				// TODO: Log error.
				// echo $p_error->getMessage();
				echo  "Unable to establis a connection with the database.";
			}
		}
		return $this->pdoh;
	}
	
	/**
	* Get battle.net user profile.
	*/
	public function getProfile( $p_battleNetId )
	{
		$returnValue = NULL;
		try
		{
			$query = sprintf( self::SELECT_PROFILE, DB_NAME );
			if ($this->pdoh !== NULL)
			{
				$stmt = $this->pdoh->prepare( $query );
				$stmt->bindValue( ":battleNetId", $p_battleNetId, \PDO::PARAM_STR );
				$profileRecord = $this->pdoQuery( $stmt );
				if ( Tool::isArray($profileRecord) )
				{
					$returnValue = $profileRecord[0];
				}
			}
		}
		catch ( \Exception $p_error )
		{
			// TODO: Log error.
			// echo $p_error->getMessage();
			echo "Unable to retrieve your profile, please try again later.";
		}
		return $returnValue;
	}
	
	/**
	* Cache a battle.net user profile.
	*/
	public function saveProfile( $p_battleNetId, $p_profileJson, $p_ipAddress )
	{
		$returnValue = FALSE;
		try
		{
			if ($this->pdoh !== NULL)
			{
				$query = sprintf( self::INSERT_PROFILE, DB_NAME );
				$stmt = $this->pdoh->prepare( $query );
				$stmt->bindValue( ":battleNetId", $p_battleNetId, \PDO::PARAM_STR );
				$stmt->bindValue( ":profileJson", $p_profileJson, \PDO::PARAM_STR );
				$stmt->bindValue( ":ipAddress", $p_ipAddress, \PDO::PARAM_STR );
				$stmt->bindValue( ":lastUpdated", date("Y-m-d H:i:s"), \PDO::PARAM_STR );
				$stmt->bindValue( ":addedDate", date("Y-m-d H:i:s"), \PDO::PARAM_STR );
				$returnValue = $this->pdoQuery( $stmt, FALSE );
			}
		}
		catch ( \Exception $p_error )
		{
			// TODO: Log error.
			// echo $p_error->getMessage();
			echo "Unable to save your profile, something fishy is going on here; Don't worry we'll get to the bottom of this.";
		}
		return $returnValue;
	}
	
	/**
	*
	*/
	public function pdoQuery( $p_stmt, $p_returnResults = TRUE )
	{
		$returnValue = NULL;
		try
		{
			// Call the database routine
			$returnValue = $p_stmt->execute();
			if ( $p_returnResults )
			{
				// Fetch all rows into an array.
				$rows = $p_stmt->fetchAll( \PDO::FETCH_ASSOC );
				if ( Tool::isArray($rows) )
				{
					$returnValue = $rows;
				}
			}
			$p_stmt->closeCursor();
		}
		catch ( \Exception $p_error )
		{
			// TODO: Log error.
			// echo $p_error->getMessage();
			echo "Uh-oh, where experiencing some technical difficulties. Please bear with this website, while it alerts a developer.";
		}
		return $returnValue;
	}
	
	/**
	* Perform a simple SELECT that does NOT have any parameters.
	*/
	public function select( $p_selectStament )
	{
		$returnArray = NULL;
		try
		{
			// Set the select.
			$stmt = $this->pdoh->prepare( $p_selectStament );
			// Call the database routine
			$stmt->execute();
			// Fetch all rows into an array.
			$rows = $stmt->fetchAll( \PDO::FETCH_ASSOC );
			if ( Tool::isArray($rows) )
			{
				$returnArray = $rows;
			}
			$stmt->closeCursor();
		}
		catch ( \Exception $p_error )
		{
			echo $p_error->getMessage();
		}
		return $returnArray;
	}
}
// DO NOT PUT ANY CHARACTERS OR EVEN WHITE-SPACE after the closing php tag, or headers may be sent before intended.	
?>