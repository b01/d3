<?php namespace kshabazz\d3a\Page;
/**
 * Diablo 3 Assistant License is under The MIT License (MIT)
 * [OSI Approved License]. Please read LICENSE.txt, included with this
 * software for the full licensing information. If no LICENSE.txt accompanied
 * this software, then no license is granted.
 *
 * @package kshabazz\d3a\Controller
 * @copyright (c) 2012-2013 Khalifah K. Shabazz
 */

use kshabazz\d3a\Abstracts\aPage;
use kshabazz\d3a\Application;
use kshabazz\d3a\View\Index as vIndex;

/**
 * Class Index the home page controller.
 *
 * @package kshabazz\d3a\Controller
 */
class Index extends aPage
{
    protected
        $super,
        $view;

	/**
	 * Controller actions go here.
	 */
	public function __construct( Application $pApp )
	{
		$this->super = $pApp->superGlobals();
		$this->battleNetId = $this->super->getParam( 'battleNetId', NULL, 'string', 'POST' );
	}

	public function getView()
	{
		$this->view = new vIndex( $this->battleNetId );
		return $this->view;
	}
}
?>