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