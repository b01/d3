<?php namespace kshabazz\d3a\test;
/**
 * Created by PhpStorm.
 * User: Khalifah
 * Date: 1/4/14
 * Time: 9:24 AM
 */
use kshabazz\d3a\Sql;

/**
 * Class SqlTest
 * @package kshabazz\d3a\test
 */
class SqlTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test instantiation.
	 */
	public function test_retrieving_an_sql_object()
	{
		$sql = new Sql();
		$this->assertTrue(
			$sql instanceof Sql,
			'Could not instantiate a new Sql object.'
		);
	}
}
// Do not write below this line. ?>