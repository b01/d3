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
	 * @param $pSuper Access to the super globals.
	 */
	public function __construct( array $pSettings, $pSuper )
	{
		$this->settings = $pSettings;
		$this->data = [];
		$this->models = [];
		$this->superGlobals = $pSuper;
		$this->parseConstants();
	}

	/**
	 * Load the attribute map from file.
	 *
	 * @param string $pFile attribute map file contents.
	 * @throw \Exception
	 * @return array
	 */
	function loadJsonFile( $pFile )
	{
		$contents = \file_get_contents( $pFile );
		$returnValue = \json_decode( $contents, TRUE ) ?: [];
		return $returnValue;
	}

	/**
	 * Pass all errors through this handler.
	 *
	 * @param $pSeverity
	 * @param $pMessage
	 * @param $pFilename
	 * @param $lineNo
	 * @return bool
	 */
	public function notice_error_handler( $pSeverity, $pMessage, $pFilename, $lineNo )
	{
		$loggableErrorMessage = "\n{$pMessage} {$pFilename} on line {$lineNo}.";
		\error_log( $loggableErrorMessage );
		return TRUE;
	}

	/**
	 * Parse constants in the settings array.
	 */
	protected function parseConstants()
	{
		$constantsKey = 'constants';
		// We have to specify the namespace when defining constants.
		if (array_key_exists( $constantsKey, $this->settings) ) {
			foreach ( $this->settings[$constantsKey] as $name => $value )
			{
				define( $name, $value );
			}
			unset( $this->settings[$constantsKey] );
		}
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
	 * Get a setting.
	 *
	 * @param string $pKey Setting name.
	 * @return mixed
	 */
	public function setting( $pKey )
	{
		return $this->settings[ $pKey ];
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
	 * Filter the buffer with a template engine of some sort.
	 * Note: currently this works with the PHP version of Mustache & Twig (~v1.14.*) without modification.
	 *
	 * @param object $pModel Should contain the necessities to fill in the holes by the template engine.
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