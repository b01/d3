<?php namespace kshabazz\d3a;
trait Shared {
	/**
	* Destructor
	*/
	public function __destruct()
	{
		foreach ( $this as $name => $value )
		{
			unset( $this->$name );
		}
	}
}
?>