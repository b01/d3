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
	 * Test store and retrieve, which use references to pass values, with a string.
	 */
	public function test_storing_and_retrieving_a_string()
	{
		$app = new Application( [], $this->supers );
		$inputVar = "test string";
		$app->store( 'test', $inputVar );
		$outputVar = $app->retrieve( 'test' );
		$this->assertTrue( $inputVar === $outputVar, "The string stored is NOT equal to the string retrieved." );
	}

	/**
	 * Test store and retrieve, which use references to pass values, with an array, .
	 */
	public function test_storing_and_retrieving_an_array()
	{
		$app = new Application( [], $this->supers );
		$inputVar = [ "test string" ];
		$app->store( 'testArray', $inputVar );
		$outputVar = $app->retrieve( 'testArray' );
		$ref1 = &$inputVar;
		$ref2 = &$outputVar;
		$this->assertTrue( $ref1 === $ref2, // TODO: Make sure this works!
			"The reference value of array stored is NOT equal to the reference of the array retrieved." );
	}

	/**
	 *
	 */
//	public function test_templateFilter()
//	{
//		// TODO: put "{test}" in the buffer.
//		$app = new \kshabazz\d3a\Application( [], $this->supers );
//		$twigLoader = new \Twig_Loader_String();
//		$twig = new \Twig_Environment( $twigLoader );
//		$model = ( object ) [
//			"test" => 123
//		];
//		$app->templateFilter( $model, $twig );
//		// TODO: Retrieve the buffer and asset it equals 123.
//		$this->markTestIncomplete('This has not been tested');
//	}

	/**
	 * Verify setting SuperGlobals in application.
	 */
	public function test_set_and_get_of_super_globals()
	{
		$app = new Application( [], $this->supers );
		$super = $app->superGlobals();
		$this->assertTrue( $super === $this->supers,
			"Failed to successfully set and get SuperGlobas in application object." );
	}

	public function test_getting_a_setting()
	{
		$value = 'test';
		$app = new Application( ['test' => $value], $this->supers );
		$setting = $app->setting( 'test' );
		$this->assertTrue( $setting === $value,
			"Failed to get setting." );
	}

	/**
	 * Test parsing constants.
	 */
	public function test_constants_are_set()
	{
		$value = 'test';
		$app = new Application( [
			'constants' => [
				'TEST_123' => 123,
				'kshabazz\\d3a\\TEST_123' => 1234
			]
		], $this->supers );
		$this->assertTrue( \TEST_123 === 123,
			"TEST_123 constant not set." );
		$this->assertTrue( \kshabazz\d3a\TEST_123 === 1234,
			"\\kshabazz\\d3a\\TEST_123 constant not set." );
	}

	public function test_loading_a_json_file()
	{
		$filePath = './test/mock/data/attribute-map.txt';
		$app = new Application( [], $this->supers );
		$testArray = $app->loadJsonFile( $filePath );
		$this->assertArrayHasKey( 'test', $testArray, 'Failed to load JSON file.' );
	}

	public function test_loading_json_data_correclty()
	{
		$filePath = './test/mock/data/attribute-map.txt';
		$app = new Application( [], $this->supers );
		$testArray = $app->loadJsonFile( $filePath );
		$this->assertTrue( $testArray['test'] === 1234, 'Failed to load data correctly from JSON file.' );
	}

	public function test_loading_an_empty_json_file()
	{
		$filePath = './test/mock/data/empty-map.txt';
		$app = new Application( [], $this->supers );
		$testArray = $app->loadJsonFile( $filePath );
		$this->assertTrue( is_array($testArray), 'Failed to return array.' );
	}
}
?>