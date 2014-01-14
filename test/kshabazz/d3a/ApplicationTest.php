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
		$app = new Application( $this->supers );
		$this->assertTrue( $app instanceof Application,
						   'Application initiallized with no settings.');
	}

	/**
	 *
	 */
	public function test_get_server_param()
	{
		$app = new Application( $this->supers );
		$_SERVER['test'] = 123;
		$supers = $app->superGlobals();
		$param = $supers->getParam( 'test', 'failed', 'int', 'SERVER' );
		$this->assertEquals( 123, $param, 'Failed to get parameter from super global _SERVER.' );
	}

	/**
	 * Test store and retrieve, which use references to pass values, with a string.
	 */
	public function test_storing_and_retrieving_a_string()
	{
		$app = new Application( $this->supers );
		$inputVar = "test string";
		$app->store( 'test', $inputVar );
		$outputVar = $app->retrieve( 'test' );
		$this->assertEquals( $outputVar, $inputVar, "The string stored is NOT equal to the string retrieved." );
	}

	/**
	 * Test store and retrieve, which use references to pass values, with an array, .
	 */
	public function test_storing_and_retrieving_an_array()
	{
		$app = new Application( $this->supers );
		$inputVar = [ "test string" ];
		$app->store( 'testArray', $inputVar );
		$outputVar = $app->retrieve( 'testArray' );
		$ref1 = &$inputVar;
		$ref2 = &$outputVar;
		// TODO: Make sure this works!
		$this->assertEquals( $ref2, $ref1,
			"The reference value of array stored is NOT equal to the reference of the array retrieved." );
	}

	/**
	 *
	 */
	public function test_render()
	{
		$app = new Application( $this->supers );
		$twigLoader = new \Twig_Loader_String();
		$twig = new \Twig_Environment( $twigLoader );
		$model = ( object ) [
			"test" => 123
		];
		// Put '{test}' in the buffer.
		ob_start();
		echo '{{ test }}';
		$app->render( $model, $twig );
		$output = ob_get_clean();
		// TODO: Retrieve the buffer and asset it equals 123.
		$this->assertEquals( '123', $output, 'This has not been tested' );
	}

	/**
	 * Verify setting SuperGlobals in application.
	 */
	public function test_set_and_get_of_super_globals()
	{
		$app = new Application( $this->supers );
		$super = $app->superGlobals();
		$this->assertEquals( $this->supers, $super,
			"Failed to successfully set and get SuperGlobas in application object." );
	}

	public function test_getting_a_setting()
	{
		$value = 'test';
		$app = new Application( $this->supers, ['test' => $value] );
		$setting = $app->setting( 'test' );
		$this->assertEquals( $value, $setting, "Failed to get setting." );
	}

	public function test_custom_error_handler()
	{
		$app = new Application( $this->supers );
		$testError = $app->errorHandlerNotice(1, 'Test error message', __FILE__, 145);
		$this->assertTrue( $testError, 'Failed to return array.' );
	}

//	/**
//	 * @exspectedException \Exception
//	 */
//	public function test_throwing_a_real_notice_error()
//	{
//		$app = new Application( $this->supers );
//		$this->markTestIncomplete('Incomplete.');
//	}
}
?>