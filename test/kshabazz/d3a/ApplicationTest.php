<?php namespace kshabazz\d3a;
/**
 * Application object unit test.
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
	private
		$supers;

	public function setUp()
	{
		$this->supers = new SuperGlobals();
	}

	/**
	 *
	 */
	public function test_app_initialized_with_no_settings()
	{
		$app = new Application( [], $this->supers );
		$this->assertTrue( $app instanceof Application,
						   'Application initiallized with no settings.');
	}

	/**
	 *
	 */
	public function test_get_param()
	{
		$app = new Application( [], $this->supers );
		// TODO set a param.
//		$app->getParam( $pKey, $pDefault, $pType = 'string', $pSuper = 'REQUEST' );
		$this->markTestIncomplete('This has not been tested');
	}

	/**
	 *
	 */
	public function test_store()
	{
		$app = new Application( [], $this->supers );
		$inputVar = "test string";
		$app->store( 'test', $inputVar );
		$outputVar = $app->retrieve( 'test' );
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