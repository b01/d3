<?php namespace kshabazz\d3a;
/**
* Welcome to the BOOTSTRAP file.
* Place all of your PHP application initialization here that you want available on
* every page.
*
* Requirements
* Only const, define, include, and require statements should go here!
*
*/
require_once __DIR__ . '/../src/kshabazz/d3a/settings.php';
require_once __DIR__ . '/../src/kshabazz/d3a/Tool.php';
require_once __DIR__ . '/../vendor/autoload.php';

checkPhpVersion( 5, 4 );

// We have to specify the namespace when defining constants.
$nameSpace = array_key_exists( "namespace", $settings ) ? $settings[ 'namespace' ] : '';
foreach ( $settings as $name => $value )
{
	define( $nameSpace . $name, $value );
}

// Turn on D3 error handling.
\set_error_handler( '\kshabazz\d3a\notice_error_handler', E_NOTICE );

require_once __DIR__ . '/../src/kshabazz/d3a/loader.php';

//DO NOT PUT ANY CHARACTERS OR EVEN WHITE-SPACE after the closing PHP tag, or headers may be sent before intended.
?>