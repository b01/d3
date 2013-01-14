<?php
/**
* Tools to help simplify repetitive task.
*
* @author Khalifah Shabazz <shabazzk@gmail.com>
*
*/

	/**
	* Automatically load classes instead of using require/include statements.
	*
	*/
	function __autoload( $p_className )
	{
		// Single quote strings are used to optimize/prevent PHP from parsing the string.
		$classFilePath = 'php/' . basename( $p_className ) . '.php';
		if ( file_exists($classFilePath) )
		{
			require_once( $classFilePath );
		}
	}

	/**
	* Display a value as a single number or a range if min and max are different.
	* @param array Containing the min and max values of a property.
	* @return string
	*/
	function displayRange( $p_values )
	{
		$returnValue = '';
		if ( isArray($p_values) )
		{
			$min = $p_values['min'];
			$max = $p_values['max'];
			$returnValue = $min;
			$returnValue .= ( $min === $max ) ? '' : '-' . $p_max;
		}
		return $returnValue;
	}

	/**
	* Parse number in string and add HTML tags around it.
	* @param $p_key CSS class to add to the element.
	* @return string 
	*/
	function formatAttribute( $p_attribute, $p_class = NULL )
	{
		$returnValue = NULL;
		$cssClass = empty( $p_class ) ? '' : " class=\"{$p_class}\"";
		return preg_replace( ['/(\+?\d+\.?\d*%?)/', '/(\(.*\))/'], ["<span{$cssClass}>$1</span>", "<span class=\"d3-color-red\">$1</span>"], $p_attribute );
	}

	/**
	* Get a value from the global POST array as a string, even if it is a numercal value.
	* @param $p_key string Variable to retrieve from the post array.
	* @return string 
	*/
	function getPostStr( $p_key )
	{
		$returnValue = NULL;
		
		if ( array_key_exists($p_key, $_POST) )
		{
			$returnValue = ( string )$_POST[ $p_key ];
		}
		return $returnValue;
	}

	/**
	* Get a value from the global POST array as a boolean.
	* @param $p_key string Variable to retrieve from the post array.
	* @return string 
	*/
	function getPostBool( $p_key )
	{
		$returnValue = FALSE;
		
		if ( array_key_exists($p_key, $_POST) )
		{
			$returnValue = ( bool )$_POST[ $p_key ];
		}
		return $returnValue;
	}

	/**
	* Get a value from the global GET array as a string, even if it is a numercal value.
	* @param $p_key string Variable to retrieve from the post array.
	* @return string 
	*/
	function getStr( $p_key )
	{
		$returnValue = NULL;
		
		if ( array_key_exists($p_key, $_GET) )
		{
			$returnValue = ( string )$_GET[ $p_key ];
		}
		return $returnValue;
	}

	/**
	* Get item name, by type id.
	* @param $p_itemType Item type id
	* @return string 
	*/
	function translateSlotName( $p_itemType )
	{
		$returnValue = '';
		switch ( strtolower($p_itemType) )
		{
			case "leftfinger":
			case "rightfinger":
				$returnValue = " finger";
				break;
			default:
				$returnValue = '';
				break;
		}
		return $returnValue;
	}

	/**
	* Get slot the you equipe the item, by type id.
	* @param $p_itemType Item type id
	* @return string 
	*/
	function getItemSlot( $p_itemType )
	{
		$returnValue = '';
		$itemType = strtolower( $p_itemType );
		switch ( $itemType )
		{
			case 'amulet':
				$returnValue = "neck";
				break;
			case 'belt':
				$returnValue = "waist";
				break;
			case 'boots':
				$returnValue = "foot";
				break;
			case 'chest':
				$returnValue = "torso";
				break;
			case 'gloves':
				$returnValue = "hands";
				break;
			case 'helm':
				$returnValue = "head";
				break;
			case 'shield':
			case 'quiver':
				$returnValue = "off-hand";
				break;
			case 'ring':
			case "leftFinger":
			case "rightFinger":
				$returnValue = "finger";
				break;
			case 'shoulders':
				$returnValue = "shoulders";
				break;
			case 'weapon':
				$returnValue = "1-hand";
				break;
			default:
				$returnValue = $itemType;
				break;
		}
		return $returnValue;
	}

	/**
	* Check if a variable is an array of length greater than 0.
	* @return bool TRUE is yes, false otherwise.
	*/
	function isArray( $p_variable )
	{
		return ( is_array($p_variable) && count($p_variable) > 0 );
	}

	/**
	* Check if a variable is a string of length greater than 0.
	* @return bool TRUE is yes, false otherwise.
	*/
	function isString( $p_value )
	{
		return ( is_string($p_value) && strlen($p_value) > 0 );
	}

	/**
	* Check if an item is a weapon.
	*/
	function isWeapon( \d3\ItemModel $p_item )
	{
		$returnValue = FALSE;
		$weaponTypes = [
			"FistWeapon",
			"sword"
		];
		$itemType = $p_item->type[ 'id' ];
		if ( array_key_exists($itemType, $weaponTypes) )
		{
			$returnValue = TRUE;
		}
		return $returnValue;
	}

	/**
	* Check if a variable is a string of length greater than 0.
	* @return bool TRUE is yes, false otherwise.
	*/
	function logError( Exception $p_error, $p_devMessage, $p_userMessage )
	{
		$trace = debug_backtrace();
		echo $p_error->getMessage();
		echo sprintf( $p_devMessage, $trace[0]['file'], $trace[0]['line'] );
		showUserFriendlyError( $p_userMessage );
	}

	/**
	* Output an associative array in sprintf fasion.
	* @return bool TRUE is yes, false otherwise.
	*/
	function  output( $p_format, array $p_array )
	{
		$returnValue = '';
		foreach ( $p_array as $key => $value )
		{
			$returnValue .= sprintf( $p_format, $key, $value );
		}
		return $returnValue;
	}

	/**
	* Random x elements from an array.
	*/
	function randomElementsFromArray( $p_arraySource, $p_quantity = 5 )
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
	function parseJson( $p_jsonString )
	{
		$returnValue = NULL;
		if ( isString( $p_jsonString ) )
		{
			// Convert JSON string into a PHP Array.
			$data = json_decode( $p_jsonString, TRUE );
			// Grab specific values from the JSON that help determine what was returned.
			if ( isArray($data) )
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
	function saveFile( $p_fileName, $p_content )
	{
		$directory = dirname( $p_fileName );
		if ( !is_dir($directory) )
		{
			mkdir( $directory, 0755, TRUE );
		}
		// Save image data to a file.
		file_put_contents( $p_fileName, $p_content, LOCK_EX );
	}

	/**
	* Generate an array of random numbers within a specified range.
	* @credit Taken from a Stack Overflow answeer: http://stackoverflow.com/questions/5612656/generating-unique-random-numbers-within-a-range-php
	*/
	function showUserFriendlyError( $p_message )
	{
		echo "<div class=\"error\">{$p_message}</div>";
	}

	/**
	* Determine if time in a session has lapsed.
	*
	* @param $p_key string Session time variable.
	* @param $p_duration int Amount of time to check against.
	* @return bool
	*/
	function sessionTimeExpired( $p_key, $p_duration, $p_setExpiredToNow = FALSE )
	{
		$timeExpired = TRUE;
		if ( array_key_exists( $p_key, $_SESSION) )
		{
			$timeElapsed = timeElapsed( $_SESSION[$p_key] );
			$timeExpired = $timeElapsed > $p_duration;
			echo "<div class=\"time-elapsed\">timeElapsed = {$timeElapsed}</div>";
		}
		// if the session key has not been set, or it expired, then (re)set it to now.
		if ( $timeExpired && $p_setExpiredToNow )
		{
			$_SESSION[ $p_key ] = time();
		}
		return $timeExpired;
	}

	/**
	* Take a date time, and return how much time has elapsed since then.
	* 	A negative number indicates a time in the future. Rquired your clock to be set.
	* @param int Time in seconds.
	* @return int seconds since epoch date.
	*/
	function timeElapsed( $p_time )
	{
		$now = time();
		if ( is_numeric($p_time) )
		{
			return $now - $p_time;
		}
		return FALSE;
	}

	/**
	* Generate an array of random numbers within a specified range.
	* @credit Taken from a Stack Overflow answeer: http://stackoverflow.com/questions/5612656/generating-unique-random-numbers-within-a-range-php
	*/
	function UniqueRandomNumbersWithinRange( $p_min, $p_max, $p_quantity )
	{
		$numbersAry = range( $p_min, $p_max );
		shuffle( $numbersAry );
		return array_slice( $numbersAry, 0, $p_quantity );
	}
?>