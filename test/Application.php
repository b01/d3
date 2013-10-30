<?php
/**
*
*/
class ApplicationTest extends PHPUnit_Framework_TestCase
{
	/**
	*
	*/
	public function test_getParam()
	{
		$app->getParam( $pKey, $pDefault, $pType = 'string', $pSuper = 'REQUEST' )
		$this->markTestIncomplete('This has not been tested');
	}

	/**
	*
	*/
	public function test_store()
	{
		$app = new \kshabazz\d3a\Application();
		$returnValue = $app->store( $pKey, & $pValue );
		$this->markTestIncomplete('This has not been tested');
	}

	/**
	*
	*/
	public function test_retrieve( $pKey )
	{
		$app = new \kshabazz\d3a\Application();
		$returnValue = $app->retrieve( $pKey, & $pValue );
		$this->markTestIncomplete('This has not been tested');
	}

	/**
	*
	*/
	public function test_templateFilter( $pModel, $pParser )
	{
		$app = new \kshabazz\d3a\Application();
		// $returnValue = $app->templateFilter();
		$this->markTestIncomplete('This has not been tested');
	}
}
?>