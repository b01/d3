<?php namespace kshabazz\d3a;
/**
 * Diablo 3 Assistant License is under The MIT License (MIT) [OSI Approved License]
 * Please read LICENSE.txt, included with this software for the full licensing information.
 * If no LICENSE.txt accompanied this software, then no license is granted.
 * @package kshabazz\d3a
 *
 * @copyright (c) 2012-2013 diablo-3-assistant by Khalifah K. Shabazz
 * timestamp: 12/28/13:9:17 AM
 */
/**
 * Class Session
 * @package kshabazz\d3a
 */
class Session
{
	/**
	 * Display a time left.
	 * In an effort to unify the format of time left on a session expiration variable's display.
	 *
	 * @param $pTimeLeft int Amount of time left on a session expiration variable.
	 * @return string
	 */
	public function displaySessionTimer( $pTimeLeft )
	{
		return ( is_numeric($pTimeLeft) && $pTimeLeft > 0 ) ?
			'Seconds left till cache expires ' . $pTimeLeft : 'Reloaded from Battle.Net';
	}

	/**
	 * Get info for a session expiration variable (a variable soley used as a timer/count-down).
	 *
	 * @param string $pSessionVarName string Session time variable.
	 * @param bool $pClear overwrite the cache starting now.
	 * @param int $pDuration Amount of time to before cache times out.
	 * @return bool
	 */
	public function getSessionExpireInfo( $pSessionVarName, $pClear = FALSE, $pDuration = 0 )
	{
		$timeElapsed = 0;
		$loadFromBattleNet = $this->sessionTimeExpired( $pSessionVarName, $pDuration, $pClear, $timeElapsed );
		$timeLeft = $pDuration - $timeElapsed;
		return [
			'loadFromBattleNet' => $loadFromBattleNet,
			'timeLeft' => $timeLeft,
			'message' => $this->displaySessionTimer( $timeLeft )
		];
	}

	/**
	 * Determine if time in a session has lapsed.
	 *
	 * @param string $pKey Session time variable.
	 * @param int $pTimeLimit Amount of time to check against.
	 * @param bool $pForceToExpire expires the cache now.
	 * @param int $pTimeElapsed how much time has passed since data was cached.
	 * @return bool
	 */
	public function sessionTimeExpired( $pKey, $pTimeLimit, $pForceToExpire = FALSE, &$pTimeElapsed )
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
}
// Writing below this line can cause headers to be sent before intended ?>