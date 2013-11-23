<?php namespace kshabazz\d3a;
/**
 * Structured access to retrieve data from PHP super global arrays. Only get access, no there is no set method of any
 * kind. Use see {@link Application::store()} for passing data around within the application.
 *
 * @package kshabazz\d3a
 */
/**
 * Class SuperGlobals
 * @package kshabazz\d3a
 */
class SuperGlobals
{
	protected
		/**
		 * @var string
		 */
		$defaultSuper,
		/**
		 * @var string
		 */
		$defaultType,
		/**
		 * @var null|string
		 */
		$defaultValue;
	/**
	 * Constructor
	 *
	 * @param string $pSuper name of array.
	 * @param string $pType to return.
	 * @param string $pValue default value to return.
	 * @return mixed null by default
	 */
	public function __construct( $pSuper = 'REQUEST', $pType = 'string', $pValue = NULL )
	{
		 $this->defaultSuper = $pSuper;
		 $this->defaultType = $pType;
	}

	/**
	 * Get a variable from a super-globals array; $_REQUEST is the default.
	 *
	 * @param string $pKey name of variable in the array.
	 * @param string $pDefault value to return.
	 * @param string $pType to return.
	 * @param string $pSuper name of array.
	 * @return mixed null by default
	 */
	public function getParam( $pKey, $pDefault = NULL, $pType = 'string', $pSuper = 'REQUEST' )
	{
		$type = $pType ?: $this->defaultType;
		$super = $pSuper ?: $this->defaultSuper;
		$super = '_' . $super;
		$returnValue = $pDefault;

		if ( array_key_exists($super, $GLOBALS)
			 && is_array($GLOBALS[$super])
			 && array_key_exists($pKey, $GLOBALS[$super]) )
		{
			$returnValue =
			$typeConversion = settype( $GLOBALS[$super][$pKey], $type );
			if ( $typeConversion )  {
				$returnValue = $GLOBALS[$super][$pKey];
			}
		}
		return $returnValue;
	}
}
?>