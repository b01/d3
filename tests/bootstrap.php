<?php
// Load composer auto-loader.
require_once __DIR__
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'vendor'
	. DIRECTORY_SEPARATOR . 'autoload.php';

define( 'TESTS_ROOT', realpath(__DIR__) );
define( 'FIXTURES_PATH', TESTS_ROOT . DIRECTORY_SEPARATOR . 'fixtures' );
?>