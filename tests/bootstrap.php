<?php namespace Kshabazz\BattleNet\D3\Tests;
/**
 * Unit Tests configuration and setup.
 */
// Load composer auto-loader.
require_once __DIR__
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'vendor'
	. DIRECTORY_SEPARATOR . 'autoload.php';

\define( 'TESTS_ROOT', \realpath(__DIR__) );
\define( 'FIXTURES_PATH', TESTS_ROOT . DIRECTORY_SEPARATOR . 'fixtures' );

// When test config exists, load setting from config.
$apiKey = 'noApiKey';
$testConfig = TESTS_ROOT
	. DIRECTORY_SEPARATOR . 'unit-test.json';
if ( !\file_exists($testConfig) )
{
	exit('No configuration found at "' . $testConfig . '". You must create a tests configuration first.');
}

$configJson = \file_get_contents( $testConfig );
$config = \json_decode( $configJson );
\define( 'Kshabazz\\BattleNet\\D3\\Tests\\API_KEY', $config->apiKey );
?>