<?php namespace kshabaz\d3a;
use kshabazz\d3a\SuperGlobals;
/**
 * Diablo 3 Assistant License is under The MIT License (MIT)
 * [OSI Approved License]. Please read LICENSE.txt, included with this
 * software for the full licensing information. If no LICENSE.txt accompanied
 * this software, then no license is granted.
 *
 * @package kshabazz\d3a\Controller
 * @copyright (c) 2012-2013 Khalifah K. Shabazz
 */
/**
* Interface for controllers.
*/
interface iController
{
	public function __construct( SuperGlobals $pAdd );
	public function getModel();
}
?>