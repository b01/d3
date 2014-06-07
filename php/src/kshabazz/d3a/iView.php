<?php namespace kshabazz\d3a;
/**
 * Diablo 3 Assistant License is under The MIT License (MIT) [OSI Approved License]
 * Please read LICENSE.txt, included with this software for the full licensing information.
 * If no LICENSE.txt accompanied this software, then no license is granted.
 * @package kshabazz\d3a
 *
 * @copyright (c) 2012-2014 diablo-3-assistant by Khalifah K. Shabazz
 * timestamp: 1/20/14:1:43 PM
 */

/**
 * Interface iView
 * @package kshabazz\d3a
 */
interface iView
{
	public function __construct( array $pData );

	public function render();
}
// Writing below this line can cause headers to be sent before intended ?>