<?php namespace kshabazz\d3a;
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
		$settings;

	/**
	* Constructor
	*/
	public function __construct( array $pSettings )
	{
		$this->settings = $pSettings;
		$this->data = [];
	}

	/**
	* Store a variable in the application.
	* Note: requires a reference. So you can not use methods as the value parameter
	* without first assigning their return value to a variable.
	*
	* @param string $pKey name assigned to the value that is stored.
	* @return mixed
	*/
	public function getParam( $pKey )
	{
		return getStr($pKey);
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
	* Note: currently this works with the PHP version of Mustache.
	*
	* @param Model $pModel Should contain the necessities to fill in the holes by the template engine.
	* @param mixed $pParser A template engine to pass the output buffer through.
	* @return void
	*/
	public function templateFilter( $pModel, $pParser )
	{
		ob_start();
		register_shutdown_function(function ($pModel, $pParser)
		{// Hi-jack the output buffer.
			// grabbing the current buffer.
			$buffer = ob_get_contents();
			// clean the output buffer.
			ob_clean();
			// fill in all the template place-holders and put it back.
			echo $pParser->render( $buffer, $pModel );
		}, $pModel, $pParser );
	}

}
?>