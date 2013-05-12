<?php namespace D3;
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