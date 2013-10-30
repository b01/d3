<?php namespace kshabazz\d3a;

use \kshabazz\d3a\SuperGlobals;

/**
* House various kinds of data for the application.
*
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
	*/
	public function __construct( array $pSettings )
	{
		$this->settings = $pSettings;
		$this->data = [];
		$this->models = [];
		$this->superGlobals = new SuperGlobals();
	}

	/**
	* Get a specific model by class name.
	* @return mixed
	*/
	public function getModel( $pKey )
	{
		return $this->models[ $pKey ];
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
	* Get a variable from a superglobals array, default is $_REQUEST.
	*
	* @param string $pKey name of variable in the array.
	* @param string $pDefault value to return.
	* @param string $pType to return.
	* @param string $pSuper name of array.
	* @return mixed null by default
	*/
	public function getParam( $pKey, $pDefault = null, $pType = 'string', $pSuper = 'REQUEST' )
	{
		$super = '_' . $pSuper;
		$returnValue = $pDefault;

		if ( array_key_exists($super, $GLOBALS)
			 && is_array($GLOBALS[$super])
			 && array_key_exists($pKey, $GLOBALS[$super]) )
		{
			$returnValue =
			$typeConversion = settype( $GLOBALS[$super][$pKey], $pType );
			if ( $typeConversion )  {
				$returnValue = $GLOBALS[$super][$pKey];
			}
		}
		return $returnValue;
	}

	/**
	* Set a model on the application
	* @return kshabazz\d3a\Application
	*/
	public function setModel( $pModel )
	{
		if ( is_object($pModel) )
		{
			$this->models[] = $pModel;
		}

		return $this;
	}

	/**
	* Store a variable in the application.
	* Note: requires a reference. So you can not use methods as the value parameter
	* without first assigning their return value to a variable.
	*
	* @param string $pKey name assigned to the value that is stored.
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