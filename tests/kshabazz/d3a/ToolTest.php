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

use function kshabazz\d3a\isBattleNetId;

/**
 * Class ToolTest
 * @package kshabazz\test
 */
class ToolTest extends \PHPUnit_Framework_TestCase
{
	public function test_valid_id_with_isBattleNetId()
	{
		$test = isBattleNetId( 'msuBREAKER#1374' );
		$this->assertEquals( 1,  $test, 'isBattleNetID failed to detect valid ID.' );
	}

	public function test_invalid_id_with_isBattleNetId()
	{
		$test = isBattleNetId( 'msuBREAKER-1374' );
		$this->assertEquals( 0,  $test, 'isBattleNetID failed to detect invalid ID.' );
		$test = isBattleNetId( 'msuBREAKER#137A' );
		$this->assertEquals( 0,  $test, 'isBattleNetID failed to detect invalid ID.' );
	}
}
// Writing below this line can cause headers to be sent before intended
?>