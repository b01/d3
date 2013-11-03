<?php namespace kshabazz\d3a\Controller;
use kshabazz\d3a\SuperGlobals;
use kshabazz\d3a\Model_Index;
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
 * Class Index the home page controller.
 *
 * @package kshabazz\d3a\Controller
 */
class Index
{
    protected
        $model;

	/**
	 * Controller actions go here.
	 */
	public function __construct( SuperGlobals $dSuper )
	{
		// Process form
		/**
		* TODO do some logic to determine which models will be needed for the view
		* TODO somehow let the application know which models to initialized
		* REASON: the controller does not need the models, so no need to construct
		* them here, but there needs to be some way to let the application/page know what models are needed.
		*/
		// For now, just initialize models in each controller.
		$this->model = new Model_Index( $dSuper );
	}

	public function getModel()
	{
		return $this->model;
	}
}
?>