<?php
/**
* Tools to help simplify repetitive task.
*
* @author Khalifah Shabazz
*
*/
	/**
	* Automatically load classes instead of using require/include statements.
	*
	*/
	function __autoload( $p_className )
	{
		$classPath = str_replace( "\\", '/', $p_className );
		// Single quote strings are used to optimize/prevent PHP from parsing the string.
		$classFilePath = 'php/' . $classPath . '.php';
		if ( file_exists($classFilePath) )
		{
			require_once( $classFilePath );
		}
	}

	/**
	* Check the PHP version, and throws an error if it does not meet the minimum version.
	*
	* @param int $pMajor Required major version.
	* @param int $pMinor If set, then the required minor version.
	* @param int $pRelease If set, then the required release version.
	* @return string
	*/
	function checkPhpVersion( $pMajor, $pMinor = NULL, $pRelease = NULL )
	{
		$triggerError = FALSE;
		$versionString = $pMajor;
		$phpVersion = phpversion();
		$version = explode( '.', phpversion() );
		// Check the major version.
		if ( $version[0] < $pMajor )
		{
			$triggerError = TRUE;
		}
		// Check the minor version if set.
		if ( is_int($pMinor) && $version[1] < $pMinor )
		{
			$triggerError = TRUE;
			$versionString .= '.' . $pMinor;
		}
		// Check the release version if set.
		if ( is_int($pRelease) && $version[1] < $pRelease )
		{
			$triggerError = TRUE;
			$versionString .= '.' . $pRelease;
		}
		// Throw the error when the required version is not met.
		if ( $triggerError )
		{
			throw new Exception( "Your PHP version is '{$phpVersion}'. The minimum required PHP version is '{$versionString}'. You'll need to upgrade in order to use this application." );
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
			$returnValue .= ( $min === $max ) ? '' : " - " . $max;
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
	* Parse number in string and add HTML tags around it.
	* @param $p_key CSS class to add to the element.
	* @return string
	*/
	function tidyHtml( $p_html )
	{
		$returnValue = NULL;

		$tidy = new tidy;
		$tidy->parseString( $p_html );
		$tidy->cleanRepair();
		$returnValue = $tidy;
		// Output
		return $returnValue;
	}

	/**
	* Convert an array of item hashes to item models.
	*
	* @return array
	*/
	function getHtmlInnerBody( $p_html )
	{
		$returnValue = NULL;
		if ( gettype($p_html) === "string" )
		{
			$start = strpos( $p_html, "<body" );
			$start = strpos( $p_html, '>', $start + 5 );
			$end = strpos( $p_html, "</body>", $start ) - $start;
			$returnValue = substr( $p_html, $start + 1, $end );
		}
		return $returnValue;
	}

	/**
	* Capture the output of an include statment.
	* Taken from PHP example of include function.
	*
	*/
	function get_include_contents( $p_filename )
	{
		if ( is_file($p_filename) )
		{
			ob_start();
			include $p_filename;
			return ob_get_clean();
		}
		return FALSE;
	}

	/**
	* Convert an array of item hashes to item models.
	*
	* @return array
	*/
	function getItemModels( $p_items, $p_battleNetDqi, $p_sql )
	{
		$itemModels = [];

		foreach ( $p_items as $key => $item )
		{
			$hash = $item[ 'tooltipParams' ];
			$d3Item = new Item( str_replace("item/", '', $hash), "hash", $p_battleNetDqi, $p_sql );
			$itemModel = new ItemModel( $d3Item->json() );
			$this->itemModels[ $key ] = $itemModel;
		}

		$returnValue = ( isArray($itemModels) ) ? $itemModels : NULL;

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
			case 'fistweapon':
			case 'axe':
				$returnValue = "weapon";
				break;
			default:
				$returnValue = $itemType;
				break;
		}
		return $returnValue;
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
		$itemType = strtolower( $p_item->type['id'] );
		return ( in_array($itemType, \d3\ItemModel::$oneHandWeaponTypes) );
	}

	/**
	* Check if a variable is a string of length greater than 0.
	* @return bool TRUE is yes, false otherwise.
	*/
	function logError( Exception $p_error, $p_devMessage, $p_userMessage )
	{
		$trace = debug_backtrace();
		$loggableErrorMessage = $p_error->getMessage();
		$loggableErrorMessage .= "\n". sprintf( $p_devMessage, $trace[0]['file'], $trace[0]['line'] );
		error_log( $loggableErrorMessage );
		showUserFriendlyError( $p_userMessage );
	}

	/**
	* Output an associative array in sprintf fasion.
	* @return bool TRUE is yes, false otherwise.
	*/
	function output( $p_format, array $p_array )
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
			// logError( Exception new Exception("JSON decode error."), "Unable to decode JSON: {$p_jsonString}.", "" );
		}
		return $returnValue;
	}

	/**
	* Save the attribute map as JSON to a text file to be easily loaded again at page load.
	*
	* @credit Taken from a Stack Overflow answeer:
	* 	http://stackoverflow.com/questions/5612656/generating-unique-random-numbers-within-a-range-php
	*/
	function saveAttributeMap( $p_attributeMap )
	{
		$attributeMapOutput = json_encode( $p_attributeMap, TRUE );
		// Save image data to a file.
		saveFile( "./media/data-files/attribute-map.txt", $attributeMapOutput );
	}

	/**
	* Generate an array of random numbers within a specified range.
	* @credit Taken from a Stack Overflow answeer:
	* 	http://stackoverflow.com/questions/5612656/generating-unique-random-numbers-within-a-range-php
	*/
	function saveFile( $p_fileName, $p_content )
	{
		try
		{
			$directory = dirname( $p_fileName );
			if ( !is_dir($directory) )
			{
				$madeDir = @mkdir( $directory, 0755, TRUE );
				if ( $madeDir === FALSE )
				{
					throw new ErrorException( "mkdir: Unable make direcotry '{$directory}'." );
				}
			}
			// Save image data to a file.
			$fileSaved = @file_put_contents( $p_fileName, $p_content, LOCK_EX );
			if ( $fileSaved === FALSE )
			{
				throw new ErrorException( "file_put_contents: Unable to save file '{$p_fileName}'." );
			}
		}
		catch ( \Exception $p_error )
		{
			logError( $p_error, "There is a problem in %s on line %s.", "System hiccup, continuing on." );
		}
	}

	/**
	* Generate an array of random numbers within a specified range.
	* @credit Taken from a Stack Overflow answeer:
	* 	http://stackoverflow.com/questions/5612656/generating-unique-random-numbers-within-a-range-php
	*/
	function showUserFriendlyError( $p_message )
	{
		echo "<div class=\"error\">{$p_message}</div>";
		die();
	}

	/**
	* Determine if time in a session has lapsed.
	*
	* @param $p_key string Session time variable.
	* @param $p_duration int Amount of time to check against.
	* @return bool
	*/
	function sessionTimeExpired( $p_key, $p_duration, $p_setToExpireNow = FALSE )
	{
		$timeExpired = TRUE;
		if ( array_key_exists( $p_key, $_SESSION) && !$p_setToExpireNow )
		{
			$timeElapsed = timeElapsed( $_SESSION[$p_key] );
			if ( is_numeric($timeElapsed) )
			{
				$timeExpired = $timeElapsed > $p_duration;
				$timeLeft = \d3\BATTLENET_CACHE_LIMIT - $timeElapsed;
				echo "<div class=\"time-elapsed\">Seconds left till cache expires = {$timeLeft}</div>";
			}
		}
		// if the session key has not been set, or it expired, then (re)set it to now.
		if ( $timeExpired )
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
			case "mainhand":
			case "offhand":
				$returnValue = " weapon";
				break;
			default:
				$returnValue = '';
				break;
		}
		return $returnValue;
	}

	/**
	* Generate an array of random numbers within a specified range.
	* @credit Taken from a Stack Overflow answeer:
	* 	http://stackoverflow.com/questions/5612656/generating-unique-random-numbers-within-a-range-php
	*/
	function UniqueRandomNumbersWithinRange( $p_min, $p_max, $p_quantity )
	{
		$numbersAry = range( $p_min, $p_max );
		shuffle( $numbersAry );
		return array_slice( $numbersAry, 0, $p_quantity );
	}
?>