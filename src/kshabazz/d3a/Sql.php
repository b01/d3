<?php namespace kshabazz\d3a;
/**
 * Generic methods for retrieving data from a database.
 *
 */
use function \Kshabazz\Slib\isArray, \Kshabazz\Slib\logError;
/**
 * Class Sql
 *
 * @package kshabazz\d3a
 */
class Sql
{
	protected
		$pdoh,
		$ipAddress;

	/**
	 * Constructor - Get an SQL connection object.
	 */
	public function __construct( $p_ipAddress = NULL, $type = NULL )
	{
		$this->ipAddress = $p_ipAddress;
		try
		{
			$this->pdoh = include __DIR__ . '/private/Pdo.php';
		}
		catch ( \Exception $pError )
		{
			$this->pdoh = null;
			//	logError(
			//		$p_error,
			//		"Unable to establish a connection with the database.\n\tin %s on line %s",
			//		"There was a problem with the system, please try again later."
			//	);
			logError( $pError, "Unable to establish a connection with the database.\n\tin %s on line %s" );
		}
	}

	/**
	 * Destructor
	 */
	public function __destruct()
	{
		// close the DB connection.
		$this->pdoh = null;
		unset(
			$this->ipAddress
		);
	}

	/**
	 * Get data from local database.
	 * @param string $pQuery SQL statement.
	 * @param array $pParameters parametrized statement values.
	 * @return array
	 */
	public function getData( $pQuery, array $pParameters = NULL )
	{
		$returnValue = NULL;
		try
		{
			$stmt = $this->pdoh->prepare( $pQuery );
			foreach ( $pParameters as $parameter => $data )
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
//			logError(
//				$p_error,
//				"Bad query {$pQuery} : " . print_r($pParameters, TRUE) . "\n\tin %s on line %s",
//				"There was a problem with the system, please try again later."
//			);
			logError( $p_error, "Bad query {$pQuery} : " . print_r($pParameters, TRUE) . "\n\tin %s on line %s" );
		}
		return $returnValue;
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
	 * Run a query statement as a prepared PDO statement. Optionally returns the result.
	 *
	 * @param string $pStmt PDO statement
	 * @param bool $pReturnResults TRUE to return the results, or FALSE not to.
	 * @throws \Exception
	 * @return mixed
	 */
	public function pdoQuery( $pStmt, $pReturnResults = TRUE )
	{
		$returnValue = NULL;
		try
		{
			// Call the database routine
			$returnValue = $pStmt->execute();

			if ( $returnValue && $pReturnResults )
			{
				// Fetch all rows into an array.
				$rows = $pStmt->fetchAll( \PDO::FETCH_ASSOC );
				if ( isArray($rows) )
				{
					$returnValue = $rows;
				}
			}
			$pStmt->closeCursor();
		}
		catch ( \Exception $p_error )
		{
//			logError(
//				$p_error,
//				"Unable to run PDO statement on\n\tin %s on line %s",
//				"Uh-oh, where experiencing some technical difficulties. Please \"bear\" with this website, and try again."
//			);
			logError( $p_error, "Unable to run PDO statement on\n\tin %s on line %s" );
		}
		return $returnValue;
	}

	/**
	 * Run an SQL statement with an arbitrary number of values.
	 *
	 * This is done in a generic way. so that any statement can
	 * be parametrized in a generic way. Reason: Simplifies writing
	 * function that save data to a DB.
	 *
	 * @param string $pSqlStatement
	 * @param array $pValues
	 * @return bool Indication of success or failure.
	 */
	public function save( $pSqlStatement, array $pValues )
	{
		$returnValue = FALSE;
		try
		{
			if ( $this->pdoh !== NULL )
			{
				// Bind values to the prepared statement.
				$stmt = $this->pdoh->prepare( $pSqlStatement );
				foreach ( $pValues as $parameterName => $data )
				{
					$stmt->bindValue( ':' . $parameterName, $data[0], $data[1] );
				}
				// Run the query
				$returnValue = $this->pdoQuery( $stmt, FALSE );
			}
		}
		catch ( \Exception $p_error )
		{
//			logError(
//				$p_error,
//				"Bad query {$pSqlStatement} \n\t\n\tin %s on line %s.",
//				"Failed to save data; Which could mean lots of request to battle.net for the same data. Alerting system admin. You don't need to worry about this though"
//			);
			logError( $p_error, "Bad query {$pSqlStatement} \n\t\n\tin %s on line %s." );
		}
		return $returnValue;
	}

	/**
	 * Perform a simple SELECT that does NOT have any parameters.
	 *
	 * @param string $pSelectStament
	 * @return mixed
	 */
	public function select( $pSelectStament )
	{
		$returnArray = NULL;
		try
		{
			// Set the select.
			$stmt = $this->pdoh->prepare( $pSelectStament );
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
//			logError(
//				$p_error,
//				"Select statment failed '{$pSelectStament}' on \n\tin %s on line %s",
//				"Uh-oh, where experiencing some technical difficulties. Please bear with this website, and try again."
//			);
			logError( $p_error, "Select statment failed '{$pSelectStament}' on \n\tin %s on line %s" );
		}
		return $returnArray;
	}
}
// DO NOT PUT ANY CHARACTERS OR EVEN WHITE-SPACE after the closing PHP tag, or headers may be sent before intended. ?>