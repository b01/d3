<?php namespace D3;
/**
* This is the settings (BOOTSTRAP) file. Place all  of your PHP application secrets here that you want available on
* every page. They will be loaded once per page.
*
* Requirements
* Only const, define, include, and require statements are allowed here! So no logic code please.
*  Then LOCK THIS FILE DOWN like the Pentagon, or your nerd pron (commic books) collection.
*/
require_once( "D3/settings.php" );
require_once( "D3/Tool.php" );

checkPhpVersion( 5, 4 );
// We have to specify the namespace when defining constants.
$nameSpace = array_key_exists( "namespace", $settings ) ? $settings[ 'namespace' ] : '';
foreach ( $settings as $name => $value )
{
	define( $nameSpace . $name, $value );
}
// Run any setup code you want done on every page of your site.

// Get the attribute map file.
$file = $settings[ 'ATTRIBUTE_MAP_FILE' ];
$settings[ 'ATTRIBUTE_MAP' ] = ( file_exists($file) ) ? json_decode( file_get_contents($file), TRUE ) : [];
$settings[ 'HTTP_REFERER' ] = ( array_key_exists('HTTP_REFERER', $_SERVER) ) ? $_SERVER[ 'HTTP_REFERER' ] : NULL;

// Turn on D3 Assitant error handling.
set_error_handler( 'd3a_notice_error_handler', E_NOTICE );
//DO NOT PUT ANY CHARACTERS OR EVEN WHITE-SPACE after the closing php tag, or headers may be sent before intended.
?>