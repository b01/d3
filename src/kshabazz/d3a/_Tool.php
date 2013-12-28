<?php namespace kshabazz\d3a;
/**
 * Tools to help simplify repetitive task.
 * Diablo 3 Assistant License is under The MIT License (MIT)
 * [OSI Approved License]. Please read LICENSE.txt, included with this
 * software for the full licensing information. If no LICENSE.txt accompanied
 * this software, then no license is granted.
 *
 * @package kshabazz\d3a\Controller
 * @copyright (c) 2012-2013 Khalifah K. Shabazz
 */

	/**
	* Capitalize the first letter, and every leter after a dash (-).
	*/
	function camelCase($pString)
	{
		$filter = function ($p)
		{
			return strtoupper($p[0]);
		};
		return preg_replace_callback('/(?:^|-)(.?)/', $filter, $pString);
	}

	/**
	 * Check the PHP version, and throws an error if it does not meet the minimum version.
	 *
	 * @param int $pMajor Required major version.
	 * @param int $pMinor If set, then the required minor version.
	 * @param int $pRelease If set, then the required release version.
	 * @throws Exception
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
	* Turn a string into camel-cased word.
	*
	* @return string.
	*/
	function convertToClassName($pString)
	{
		// strip off the forward slash and extension.
		$className = basename($pString, '.php');
		// Camel Case any words left.
		$className = camelCase($className);
		// remove any chars unqualified for a class name.
		$className = str_replace('-', '', $className);
		return $className;
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
	 * Display a time left.
	 * In an effort to unify the format of time left on a session expiration variable's display.
	 *
	 * @param $pTimeLeft int Amount of time left on a session expiration variable.
	 * @return string
	 */
	function displaySessionTimer( $pTimeLeft )
	{
		return ( is_numeric($pTimeLeft) && $pTimeLeft > 0 ) ?
			'Seconds left till cache expires ' . $pTimeLeft : 'Reloaded from Battle.Net';
	}

	/**
	 * Parse number in string and add HTML tags around it.
	 *
	 * @param string $p_attribute
	 * @param string $p_class
	 * @return string
	 */
	function formatAttribute( $p_attribute, $p_class = NULL )
	{
		$returnValue = NULL;
		$cssClass = empty( $p_class ) ? '' : " class=\"{$p_class}\"";
		return preg_replace( ['/(\+?\d+\.?\d*%?)/', '/(\(.*\))/'], ["<span{$cssClass}>$1</span>", "<span class=\"d3-color-red\">$1</span>"], $p_attribute );
	}

	/**
	 * Get content between <body></body> tags.
	 *
	 * @param string $pHtml
	 * @return array
	 */
	function getHtmlInnerBody( $pHtml )
	{
		$returnValue = NULL;
		if ( gettype($pHtml) === "string" )
		{
			$start = strpos( $pHtml, "<body" );
			$start = strpos( $pHtml, '>', $start + 5 ) + 1;
			$end = strpos( $pHtml, "</body>", $start ) - $start;
			$returnValue = substr( $pHtml, $start, $end );
		}
		return $returnValue;
	}

	/**
	 * Capture the output of an include statement.
	 * Note: Taken from PHP example of include function.
	 *
	 * @param string $pFilename Name of a PHP file to include.
	 * @return mixed
	 */
	function get_include_contents( $pFilename )
	{
		if ( is_file($pFilename) )
		{
			ob_start();
			include $pFilename;
			return ob_get_clean();
		}
		return FALSE;
	}

	/**
	 * Convert an array of item hashes to item models.
	 *
	 * @param $p_items
	 * @param $p_battleNetDqi
	 * @param $pSql
	 * @return array|null
	 */
	function getItemModels( $p_items, $p_battleNetDqi, $pSql )
	{
		$itemModels = [];

		foreach ( $p_items as $key => $item )
		{
			$hash = $item[ 'tooltipParams' ];
			$d3Item = new Item( str_replace("item/", '', $hash), "hash", $p_battleNetDqi, $pSql );
			$itemModel = new ItemModel( $d3Item->json() );
			$this->itemModels[ $key ] = $itemModel;
		}

		$returnValue = ( isArray($itemModels) ) ? $itemModels : NULL;

		return $returnValue;
	}

	/**
	 * Get the name of the slot where you'd equip the item, by type id.
	 * @param $pItemTypeId Item type id
	 * @return string
	 */
	function getItemSlot( $pItemTypeId )
	{
		$returnValue = '';
		$itemTypeId = strtolower( $pItemTypeId );
		$itemTypeId = str_replace( 'generic', '', $itemTypeId );
		switch ( $itemTypeId )
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
				$returnValue = $itemTypeId;
				break;
		}
		return $returnValue;
	}

	/**
	 * Get a value from the global POST array as a boolean.
	 * @param $pKey string Variable to retrieve from the post array.
	 * @return string
	 */
	function getPostBool( $pKey )
	{
		$returnValue = FALSE;

		if ( array_key_exists($pKey, $_POST) )
		{
			$returnValue = ( bool )$_POST[ $pKey ];
		}
		return $returnValue;
	}

	/**
	 * Get a value from the global POST array as a string, even if it is a numercal value.
	 *
	 * @param $pKey string Variable to retrieve from the post array.
	 * @param mixed $pDefault value to return if the variable is not present.
	 * @return string
	 */
	function getPostStr( $pKey, $pDefault = NULL )
	{
		$returnValue = $pDefault;

		if ( array_key_exists($pKey, $_POST) )
		{
			$returnValue = ( string )$_POST[ $pKey ];
		}
		return $returnValue;
	}

	/**
	 * Get info for a session expiration variable (a variable soley used as a timer/count-down).
	 *
	 * @param string $pSessionVarName string Session time variable.
	 * @param bool $pClear overwrite the cache starting now.
	 * @param int $pDuration Amount of time to before cache times out.
	 * @return bool
	 */
	function getSessionExpireInfo( $pSessionVarName, $pClear = FALSE, $pDuration = CACHE_LIMIT)
	{
		$timeElapsed = 0;
		$loadFromBattleNet = sessionTimeExpired( $pSessionVarName, $pDuration, $pClear, $timeElapsed );
		$timeLeft = $pDuration - $timeElapsed;
		return [
			'loadFromBattleNet' => $loadFromBattleNet,
			'timeLeft' => $timeLeft,
			'message' => displaySessionTimer( $timeLeft )
		];
	}

	/**
	* Get a value from the global GET array as a string, even if it is a numercal value.
	* @param $pKey string Variable to retrieve from the post array.
	* @return string
	*/
	function getStr( $pKey )
	{
		$returnValue = NULL;

		if ( array_key_exists($pKey, $_GET) )
		{
			$returnValue = ( string )$_GET[ $pKey ];
		}
		return $returnValue;
	}

	/**
	 * Check if a variable is an array of length greater than 0.
	 *
	 * @param mixed $pVariable to be checked.
	 * @return bool TRUE is yes, false otherwise.
	 */
	function isArray( $pVariable )
	{
		return ( is_array($pVariable) && count($pVariable) > 0 );
	}

	/**
	 * Check if a variable is a string of length greater than 0.
	 *
	 * @param mixed $pVariable to be checked.
	 * @return bool TRUE is yes, false otherwise.
	 */
	function isString( $pVariable )
	{
		return ( is_string($pVariable) && strlen($pVariable) > 0 );
	}

	/**
	 * Check if an item is a type of weapon.
	 *
	 * @param Item $pItem to be checked.
	 * @return bool
	 */
	function isWeapon( Item $pItem )
	{
		$itemType = strtolower( $pItem->type['id'] );
		return in_array( $itemType, Item::$oneHandWeaponTypes );
	}

	/**
	 * Load the attribute map from file.
	 *
	 * @param $pFile Attribute map file.
	 * @return array
	 */
	function loadAttributeMap( $pFile )
	{
		return ( file_exists($pFile) ) ? \json_decode( \file_get_contents($pFile), TRUE ) : [];
	}

	/**
	 * Check if a variable is a string of length greater than 0.
	 *
	 * @param \Exception $p_error
	 * @param string $p_devMessage message the developer will see. Usually the error returned from PHP.
	 * @param string $p_userMessage message the client will see.
	 * @return bool TRUE is yes, false otherwise.
	 */
	function logError( \Exception $p_error, $p_devMessage, $p_userMessage )
	{
		$trace = debug_backtrace();
		$loggableErrorMessage = $p_error->getMessage();
		$loggableErrorMessage .= "\n". sprintf( $p_devMessage, $trace[0]['file'], $trace[0]['line'] );
		error_log( $loggableErrorMessage );
		showUserFriendlyError( $p_userMessage );
	}

	/**
	 * Output an associative array in sprintf fasion.
	 *
	 * @param string $p_format
	 * @param array $p_array
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
	 * @param string $pKey Session time variable.
	 * @param int $pTimeLimit Amount of time to check against.
	 * @param bool $pForceToExpire expires the cache now.
	 * @param ref $pTimeElapsed how much time has passed since data was cached.
	 * @return bool
	 */
	function sessionTimeExpired( $pKey, $pTimeLimit, $pForceToExpire = FALSE, &$pTimeElapsed )
	{
		$timeExpired = TRUE;
		if ( array_key_exists($pKey, $_SESSION) && !$pForceToExpire )
		{
			$timeElapsed = timeElapsed( $_SESSION[$pKey] );
			if ( is_numeric($timeElapsed) )
			{
				$timeExpired = $timeElapsed > $pTimeLimit;
				$pTimeElapsed = $timeElapsed;
			}
		}
		// if the session key has not been set, or it expired, then (re)set it to now.
		if ( $timeExpired )
		{
			$_SESSION[ $pKey ] = time();
		}
		return $timeExpired;
	}

	/**
	* Parse number in string and add HTML tags around it.
	* @param $pKey CSS class to add to the element.
	* @return string
	*/
	function tidyHtml( $p_html, $config = [] )
	{
		$returnValue = NULL;

		$tidy = new tidy;
		$tidy->parseString( $p_html, $config, 'utf8' );
		$tidy->cleanRepair();
		$returnValue = $tidy;
		// Output
		return $returnValue;
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