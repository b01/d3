<?php
/**
* Generic methods for retrieving data from a database.
*
*/
class Sql
{
	protected
		$pdoh,
		$ipAddress;

	/**
	* Constructor
	*/
	public function __construct( $p_dsn, $p_dbUser, $p_dbPass, $p_ipAddress = NULL )
	{
		$this->getPDO( $p_dsn, $p_dbUser, $p_dbPass );
		$this->ipAddress = $p_ipAddress;
	}

	/**
	* Destructor
	*/
	public function __destruct()
	{
		unset(
			$this->ipAddress,
			$this->pdoh
		);
	}

	/**
	* Get data from local database.
	* @param string $p_query SQL statment.
	* @param array $p_parameters parameterized statement values.
	* @return array
	*/
	public function getData( $p_query, array $p_parameters = NULL )
	{
		$returnValue = NULL;
		try
		{
			$stmt = $this->pdoh->prepare( $p_query );
			foreach ( $p_parameters as $parameter => $data )
			{
				$stmt->bindValue( ":{$parameter}", $data[0], $data[1] );
			}
			$records = $this->pdoQuery( $stmt );
			if ( isArray($records) )
			{
				$returnValue = $records;
			}
		}
		catch ( \Exception $p_error )
		{
			logError(
				$p_error,
				"Bad query {$p_query} : " . print_r($p_parameters, TRUE) . "\n\tin %s on line %s",
				"There was a problem with the system, please try again later."
			);
		}
		return $returnValue;
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
				$this->pdoh->setAttribute( \PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC );
			}
			catch ( \Exception $p_error )
			{
				$this->pdoh = NULL;
				die( $p_error->getMessage() );
				logError(
					$p_error,
					"Unable to establish a connection with the database.\n\tin %s on line %s",
					"There was a problem with the system, please try again later."
				);
			}
		}
		return $this->pdoh;
	}

	/**
	* Get currently set user IP address.
	* @return {string|null} IP address or null.
	*/
	public function ipAddress()
	{
		return $this->ipAddress;
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
				if ( isArray($rows) )
				{
					$returnValue = $rows;
				}
			}
			$p_stmt->closeCursor();
		}
		catch ( \Exception $p_error )
		{
			logError(
				$p_error,
				"Unable to run PDO statement on\n\tin %s on line %s",
				"Uh-oh, where experiencing some technical difficulties. Please bear with this website, and try again."
			);
		}
		return $returnValue;
	}

	/**
	* Run an SQL statment with an arbitrary number of values, in a generic way.
	*	so that any statement can be perameterized in a generic way.
	* Reason: Simplfies writing function that save data to a DB.
	* @return bool Indication of success or failure.
	*/
	public function save( $p_sqlStatement, array $p_values )
	{
		$returnValue = FALSE;
		try
		{
			if ( $this->pdoh !== NULL )
			{
				// Bind values to the prepared statment.
				$stmt = $this->pdoh->prepare( $p_sqlStatement );
				foreach ( $p_values as $parameterName => $data )
				{
					$stmt->bindValue( ':' . $parameterName, $data[0], $data[1] );
				}
				// Run the query
				$returnValue = @$this->pdoQuery( $stmt, FALSE );
			}
		}
		catch ( \Exception $p_error )
		{
			logError(
				$p_error,
				"Bad query {$p_sqlStatement} \n\twith values:" . print_r( $p_values, TRUE) . "\n\tin %s on line %s.",
				"Failed to save data; Which could mean lots of request to battle.net for the same data. Alerting system admin. You don't need to worry about this though"
			);
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
			if ( isArray($rows) )
			{
				$returnArray = $rows;
			}
			$stmt->closeCursor();
		}
		catch ( \Exception $p_error )
		{
			logError(
				$p_error,
				"Select statment failed '{$p_selectStament}' on \n\tin %s on line %s",
				"Uh-oh, where experiencing some technical difficulties. Please bear with this website, and try again."
			);
		}
		return $returnArray;
	}
}
// DO NOT PUT ANY CHARACTERS OR EVEN WHITE-SPACE after the closing php tag, or headers may be sent before intended.
?>