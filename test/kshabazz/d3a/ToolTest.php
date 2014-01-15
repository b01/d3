<?php namespace kshabazz\d3a\test;
/**
 * Diablo 3 Assistant License is under The MIT License (MIT) [OSI Approved License]
 * Please read LICENSE.txt, included with this software for the full licensing information.
 * If no LICENSE.txt accompanied this software, then no license is granted.
 * @package kshabazz\test
 *
 * @copyright (c) 2012-2013 diablo-3-assistant by Khalifah K. Shabazz
 * timestamp: 12/29/13:5:03 PM
 */

/**
 * Class ToolTest
 * @package kshabazz\test
 */
class ToolTest extends \PHPUnit_Framework_TestCase
{
	public function test_loading_a_json_file()
	{
		$filePath = __DIR__ . '/../../fixture/data/attribute-map.txt';
		$testArray = loadJsonFile( $filePath );
		$this->assertArrayHasKey( 'test', $testArray, 'Failed to load JSON file.' );
	}

	public function test_loading_json_data_correclty()
	{
		$filePath =  __DIR__ . '/../../fixture/data/attribute-map.txt';
		$testArray = loadJsonFile( $filePath );
		$this->assertEquals( 1234,  $testArray['test'], 'Failed to load data correctly from JSON file.' );
	}

	public function test_loading_an_empty_json_file()
	{
		$filePath =  __DIR__ . '/../../fixture/data/empty-map.txt';
		$testArray = loadJsonFile( $filePath );
		$this->assertTrue( is_array($testArray), 'Failed to return array.' );
	}
}
// Writing below this line can cause headers to be sent before intended
?>