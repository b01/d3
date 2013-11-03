<?php namespace kshabazz\d3a;
/**
 * Diablo 3 Assistant License is under The MIT License (MIT)
 * [OSI Approved License]. Please read LICENSE.txt, included with this
 * software for the full licensing information. If no LICENSE.txt accompanied
 * this software, then no license is granted.
 *
 * @package kshabazz\d3a\Controller
 * @copyright (c) 2012-2013 Khalifah K. Shabazz
 */
use kshabazz\d3a\Abstracts\Application;

/**
 * The page is an idea that it contains what it needs to build itself.
 * It is also what connects every thing together.
 * Controller - along with the application, helps setup the environment.
 * Model - Used business logic to setup data.
 * View - Take data from the model, and runs it through any logic needed
 *  to further process the model data for rendering.
 * The page uses the above components to put it all together like so:
 * 1. PHP loads an html file, then post appends the bootstrap file.
 * 2. The bootstrap file then constructs an application object, which is a hub for events and data.
 * 3. The application then construct a page object.
 * 4. Once a page object is constructed the following can occur:
 *  1. Consume the application object and initialize a controller, based on the route requested.
 *  2. Once the controller has run, initialize a model, based on the route, to process any data set in the environment.
 *  3. Initialize a view, and run it's render setup method.
 *  4. Pull the view HTML, stored in the environment and render with a template engine.
 * Now control is passed back to the application and any clean-up can be done, like closing DB connections.
 *
 * @package kshabazz\d3a\Controller
 * @abstract
 */
abstract class Page
{
    protected
        $app,
        $controller,
        $model,
        $view;

    /**
     * Constructor
     */
	public function __constructor( Application $pApp)
	{
		$this->app = $pApp;
	}

    /**
     * Get the page controller
     */
    public function controller()
    {
        return $this->controller;
    }
}
// DO NOT WRITE ANYTHING BELOW THIS LINE, NOT EVEN A WHITE SPACE CHARS.
?>