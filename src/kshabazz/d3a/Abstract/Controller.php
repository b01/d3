<?php namespace kshabazz\d3a;
abstract class Abstract_Controller
{
	/**
	*
	*/
	public function getModel( $pModel = NULL )
	{
		return $this->model;
	}

	/**
	*
	*/
	public function setModel( $pModel = NULL )
	{
		$this->model = $pModel;
	}

	/**
	*
	*/
	public function getView()
	{
		return $this->view;
	}

	/**
	*
	*/
	public function setView( $pView = NULL )
	{
		$this->view = $pView;
	}
}
?>