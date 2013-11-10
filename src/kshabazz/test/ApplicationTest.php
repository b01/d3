<?php
/**
 *
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
	/**
	 *
	 */
	public function test_app_initialized_with_no_settings()
	{
		$app = new \kshabazz\d3a\Application([]);
		$this->assertTrue( $app instanceof \kshabazz\d3a\Application,
						   'Application initiallized in array with no settings.');
	}

	/**
	 *
	 */
	public function test_get_param()
	{
		$app = new \kshabazz\d3a\Application([]);
//		$app->getParam( $pKey, $pDefault, $pType = 'string', $pSuper = 'REQUEST' );
		$this->markTestIncomplete('This has not been tested');
	}

	/**
	 *
	 */
	public function test_store()
	{
//		$app = new \kshabazz\d3a\Application();
//		$returnValue = $app->store( $pKey, & $pValue );
		$this->markTestIncomplete('This has not been tested');
	}

	/**
	*
	*/
	public function test_retrieve()
	{
//		$app = new \kshabazz\d3a\Application();
//		$returnValue = $app->retrieve( $pKey, & $pValue );
		$this->markTestIncomplete('This has not been tested');
	}

	/**
	*
	*/
	public function test_templateFilter()
	{
//		$app = new \kshabazz\d3a\Application();
		// $returnValue = $app->templateFilter();
		$this->markTestIncomplete('This has not been tested');
	}
}
?>