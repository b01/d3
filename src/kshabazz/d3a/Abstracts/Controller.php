<?php namespace kshabazz\d3a\Abstracts;
/**
 * Diablo 3 Assistant License is under The MIT License (MIT) [OSI Approved License]
 * Please read LICENSE.txt, included with this software for the full licensing information.
 * If no LICENSE.txt accompanied this software, then no license is granted.
 * @package kshabazz\d3a
 *
 * @copyright (c) 2012-2013 diablo-3-assistant by Khalifah K. Shabazz
 * timestamp: 11/11/13:7:49 AM
 */
 /**
  * Class Controller
  *
  * @package kshabazz\d3a
  * @abstract
  *
  */
abstract class Controller
{
	/**
	 * Get/set something from the application without getting/storing a
	 * reference to the application object. So as not to cause some kind of
	 * circular reference. Application object by proxy.
	 * Which is exactly what we do for the super globals object. By NOT storing
	 * a reference to any of the super global arrays, we can almost prevent
	 * unintended circumstances caused by circular reference.
	 * Like memory leaks for example.
	 */
	public function appProxy( $method, array $params = [] )
	{
		$app = $GLOBALS[ 'application' ];
		return \call_user_func_array( [$app, $method], $params );
	}
}
// Writing below this line can cause headers to be sent before intended
?>