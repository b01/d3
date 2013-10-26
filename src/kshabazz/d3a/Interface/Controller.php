<?php namespace kshabaz\d3a;
/**
* Interface for controllers.
*/
interface Interface_Controller
{
	public function getModel();
	public function getView();
	public function setModel( Model $pModel );
	public function setView( View $pView );
}
?>