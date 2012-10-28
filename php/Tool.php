<?php
/**
* Tools to help simplify repetitive task.
*
* @author Khalifah Shabazz <shabazzk@gmail.com>
*
*/
namespace d3cb;

class Tool
{
	/**
	* Calulate the number of pages, give the number of items per page, and the total number of items.
	*/
	static public function calculatePages( $p_itemsPerPage, $p_totalItems )
	{
		$pageCount = 0;
		if ( is_int($p_itemsPerPage) && $p_itemsPerPage > 0 && is_int($p_totalItems) && $p_totalItems > 0 )
		{
			$pageCount = ( int )ceil( $p_totalItems / $p_itemsPerPage );
		}
		return $pageCount;
	}
	
	/**
	* Random x elements from an array.
	*/
	public function getPortionOfShuffledArray( $p_arraySource, $p_quantity = 5, $p_offset = 0, $p_shuffle = TRUE )
	{
		$returnAry = NULL;
		if ( self::isArray($p_arraySource) )
		{
			if ( $p_shuffle )
			{
				shuffle( $p_arraySource );
			}
			
			$returnAry = array_slice( $p_arraySource, $p_offset, $p_quantity );
		}
		return $returnAry;
	}
	
	/**
	* Get a value from the global POST array as a string, even if it is a numercal value.
	* @param $p_key string Variable to retrieve from the post array.
	* @return string 
	*/
	static public function getPostStr( $p_key )
	{
		$returnValue = NULL;
		
		if ( array_key_exists($p_key, $_POST) )
		{
			$returnValue = ( string )$_POST[ $p_key ];
		}
		return $returnValue;
	}
	
	/**
	* Check if a variable is an array of length greater than 0.
	* @return bool TRUE is yes, false otherwise.
	*/
	static public function isArray( $p_variable )
	{
		return ( is_array($p_variable) && count($p_variable) > 0 );
	}

	/**
	* Check if a variable is a string of length greater than 0.
	* @return bool TRUE is yes, false otherwise.
	*/
	static public function isString( $p_value )
	{
		return ( is_string($p_value) && strlen($p_value) > 0 );
	}
	
	/**
	* Random x elements from an array.
	*/
	static public function randomElementsFromArray( $p_arraySource, $p_quantity = 5 )
	{
		$returnAry = NULL;
		if ( self::isArray($p_arraySource) )
		{
			shuffle( $p_arraySource );
			$returnAry = array_slice( $p_arraySource, 0, $p_quantity );
		}
		return $returnAry;
	}
	
	/**
	* Convert JSON text into a PHP Array.
	*/
	static public function parseJson( $p_jsonString )
	{
		$returnValue = NULL;
		if ( Tool::isString( $p_jsonString ) )
		{
			// Convert JSON string into a PHP Array.
			$data = json_decode( $p_jsonString, TRUE );
			// Grab specific values from the JSON that help determine what was returned.
			if ( Tool::isArray($data) )
			{
				$returnValue = $data;
			}
		}
		else
		{
			// Log error.
		}
		return $returnValue;
	}
	
	/**
	* Generate an array of random numbers within a specified range.
	* @credit Taken from a Stack Overflow answeer: http://stackoverflow.com/questions/5612656/generating-unique-random-numbers-within-a-range-php
	*/
	static public function UniqueRandomNumbersWithinRange( $p_min, $p_max, $p_quantity )
	{
		$numbersAry = range( $p_min, $p_max );
		shuffle( $numbersAry );
		return array_slice( $numbersAry, 0, $p_quantity );
	}
	
	/**
	* Select another database to use beside the default.
	*/
	public function useDb( $p_database )
	{
		$returnValue = NULL;
		$conf = \JFactory::getConfig();
		$user = $conf->getValue( "config.user" );
		$password = $conf->getValue( "config.password" );
		$options = array(
			"driver" => "mysqli",
			"user" => $user,
			"password" => $password,
			"database" => $p_database
		);
		$dboh = \JDatabase::getInstance( $options );
		if ( is_object($dboh) )
		{
			$returnValue = $dboh;
		}
		return $returnValue;
	}
}
?>