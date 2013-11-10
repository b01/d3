<?php namespace kshabazz\d3a;
use \kshabazz\d3a\SuperGlobals;

/**
 * House various kinds of data for the application.
 * Class Application
 * @package kshabazz\d3a
 * @var $data
 * @var $settings
 */
class Application
{
	protected
		$data,
		$models,
		$settings,
		$superGlobals;

	/**
	 * Constructor
	 * @param array $pSettings
	 */
	public function __construct( array $pSettings )
	{
		$this->settings = $pSettings;
		$this->data = [];
		$this->models = [];
		$this->superGlobals = new SuperGlobals();
	}

	/**
	 * Get SuperGlobals object
	 * @return \kshabazz\d3a\SuperGlobals
	 */
	public function superGlobals()
	{
		return $this->superGlobals;
	}

	/**
	 * Store a variable in the application.
	 * Note: stores a reference. So you can not use methods as the value parameter
	 * without first assigning their return value to a variable.
	 *
	 * @param string $pKey name assigned to the value that is stored.
	 * @param mixed
	 * @return mixed
	 */
	public function store( $pKey, & $pValue )
	{
		return $this->data[$pKey] = $pValue;
	}

	/**
	* Retrieve a variable previously stored in the application.
	*
	* @param string $pKey value to retrieve.
	* @return mixed
	*/
	public function &retrieve( $pKey )
	{
		return $this->data[$pKey];
	}

	/**
	* Filter the buffer with a template engine of some sort.
	* Note: currently this works with the PHP version of Mustache & Twig (~v1.14.*) without modification.
	*
	* @param Model $pModel Should contain the necessities to fill in the holes by the template engine.
	* @param mixed $pParser A template engine to pass the output buffer through.
	* @return void
	*/
	public function templateFilter( $pModel, $pParser )
	{
		ob_start();
		// Hi-jack the output buffer.
		register_shutdown_function(function ($pModel, $pParser)
		{
			// grab and remove the content from the current buffer.
			$template = ob_get_clean();
			// run all view logic and fill in all place-holders and update the current buffer.
			echo $pParser->render( $template, \get_object_vars($pModel) );
		}, $pModel, $pParser );
	}
}
?>