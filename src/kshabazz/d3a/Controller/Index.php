<?php namespace kshabazz\d3a\Controller;
use kshabazz\d3a\Application;
use kshabazz\d3a\Model_Index;

/**
* Controller for the home page.
*/
class Index {

	/**
	* Controller actions go here.
	*/
	public function __construct( Application $d3a )
	{
		$this->model = new Model_Index( $d3a );
	}

    /**
     *
     */
    public function getModel( $pModel = NULL )
    {
        return $this->model;
    }
}
?>