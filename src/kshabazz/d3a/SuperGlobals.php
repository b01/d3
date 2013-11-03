<?php namespace kshabazz\d3a;
/**
* Structured access to SuperGlobals.
*
* @var array $p_items A hash array of items, by which the keys indicate where the items are placed
*	on the hero.
*/
class SuperGlobals
{
	protected
		$defaultSuper,
		$defaultType,
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
		 $this->defaultValue = $pValue;
	}



	/**
	 * Get/set something from the application without storing a reference to
	 * the application object. So as not to cause some kind of circular
	 * reference. Application object by proxy.
	 * Which is exactly what we do for the super globals object. By NOT storing
	 * a reference to any of the super global arrays, we can almost prevent
	 * unintended circumstances caused by circular reference.
	 * Like memory leaks for example.
	 */
	public function appProxy( $method, array $params = [] )
	{
		$app = $GLOBALS[ 'application' ];
		return \call_user_func_array( [$app, $method], $params );
	}

	/**
	* Get a variable from a superglobals array; $_REQUEST is the default.
	*
	* @param string $pKey name of variable in the array.
	* @param string $pDefault value to return.
	* @param string $pType to return.
	* @param string $pSuper name of array.
	* @return mixed null by default
	*/
	public function getParam( $pKey, $pDefault = NULL, $pType = 'string', $pSuper = 'REQUEST' )
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
}
?>