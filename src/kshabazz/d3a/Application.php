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
	public function retrieve( $pKey )
	{
		return $this->data[$pKey];
	}
}
?>