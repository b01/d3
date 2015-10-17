<?php namespace Kshabazz\BattleNet\D3;
/**
 * Utilities methods for handling JSON data.
 * @license MIT
 */

/**
 * @class Json Common JSON methods.
 * @package Kshabazz\BattleNet\D3
 */
trait Json
{
	/**
	 * Cast to a string, returning the original JSON use to instantiate the object.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->json;
	}

	/**
	 * Get the original JSON use to instantiate the object.
	 */
	public function json()
	{
		return $this->json;
	}

	/**
	 * Specify how this object is to be used with json_encode.
	 *
	 * @return \stdClass
	 */
	public function jsonSerialize()
	{
		return $this->data;
	}
}
?>