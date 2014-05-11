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

	function isBattleNetId( $pBattleNetId )
	{
		return preg_match( '/^[a-zA-Z1-9]+\#[0-9]+$/', $pBattleNetId );
	}

	/**
	 * Check if an item is a type of weapon.
	 *
	 * @param \kshabazz\d3a\Model\Item $pItem to be checked.
	 * @return bool
	 */
	function isWeapon( \kshabazz\d3a\Model\Item $pItem )
	{
		$itemType = strtolower( $pItem->type['id'] );
		return in_array( $itemType, \kshabazz\d3a\Model\Item::$oneHandWeaponTypes );
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
	function saveAttributeMap( $attributeMap, $pFile )
	{
		$attributeMapOutput = json_encode( $attributeMap, TRUE );
		// Save image data to a file.
		\Kshabazz\Slib\saveFile( $pFile, $attributeMapOutput );
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
	 * Run PHP tidy on HTML content.
	 *
	 * @param string $pHtml clean up.
	 * @param array $pConfig PHP tidy configuration options.
	 * @param string $pEncoding for the output.
	 * @return string
	 */
	function tidyHtml( $pHtml, array $pConfig = [], $pEncoding = 'utf8' )
	{
		$returnValue = NULL;

		$tidy = new tidy;
		$tidy->parseString( $pHtml, $pConfig, $pEncoding );
		$tidy->cleanRepair();
		$returnValue = $tidy;
		// Output
		return $returnValue;
	}

	/**
	 * Take a date time, and return how much time has elapsed since then.
	 * 	A negative number indicates a time in the future. Rquired your clock to be set.
	 * @param int $pTime in seconds.
	 * @return int seconds since cached date.
	 */
	function timeElapsed( $pTime )
	{
		$now = time();
		if ( is_numeric($pTime) )
		{
			return $now - $pTime;
		}
		return FALSE;
	}

	/**
	 * Get item name, by type id.
	 * @param $pItemType Item type id
	 * @return string
	 */
	function translateSlotName( $pItemType )
	{
		$returnValue = '';
		switch ( strtolower($pItemType) )
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

	function updateAttributeMap( array $pAttributes, $pFile )
	{
		$currentAttributes = \Kshabazz\Slib\loadJsonFile( $pFile );
		$updateAttributes = $currentAttributes;
		foreach ( $pAttributes as $attribute => $values )
		{
			// don't overwrite existing mapped values.
			if ( !array_key_exists($attribute, $currentAttributes) )
			{
				echo "added {$attribute}<br />";
				$updateAttributes[ $attribute ] = '';
			}
		}
		// don't save unless new attributes were added.
		if (count($updateAttributes) > count($currentAttributes) )
		{
			echo "saving";
			saveAttributeMap( $updateAttributes, $pFile );
		}
	}
?>