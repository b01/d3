<?php
// Load composer auto-loader.
require_once __DIR__
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'vendor'
	. DIRECTORY_SEPARATOR . 'autoload.php';

$fixturesPath = realpath( __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' );
define( 'FIXTURES_PATH', $fixturesPath . DIRECTORY_SEPARATOR );
?>