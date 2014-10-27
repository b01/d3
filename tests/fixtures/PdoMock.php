<?php
/**
 * Aid in mocking a PDO object.
 */
class PdoMock extends \PDO
{
	public function __construct()
	{
	}
}

class PDOStatementMock
{
	private
		$fetchAllReturn,
		$executeReturn;

	public function __construct( $pFetchAllReturn, $pExecuteReturn = TRUE )
	{
		$this->fetchAllReturn = $pFetchAllReturn;
		$this->executeReturn = $pExecuteReturn;
	}

	function bindValue()
	{
	}

	function execute()
	{
		return $this->executeReturn;
	}

	function fetchAll()
	{
		return $this->fetchAllReturn;
	}

	public function closeCursor()
	{
	}
}
?>