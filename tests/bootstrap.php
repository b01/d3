<?php
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
	. DIRECTORY_SEPARATOR . 'config'
	. DIRECTORY_SEPARATOR . 'unit-test.json';
if ( \file_exists($testConfig) )
{
	$configJson = \file_get_contents( $testConfig );
	$config = \json_decode( $configJson );
	$apiKey = $config->apiKey;
}
\define( 'D3_TEST_API_KEY', $apiKey );
?>