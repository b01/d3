<?php
/**
* This is the settings (BOOTSTRAP) file. Place all  of your PHP application secrets here that you want available on
* every page. They will be loaded once per page.
*
* Requirements
* Only const, define, include, and require statements are allowed here! So no logic code please.
*  Then LOCK THIS FILE DOWN like the Pentagon, or your nerd pron (commic books) collection.
*/
namespace d3;
// load some setting that the site will need in order to function.
$settingsJson = file_get_contents( "php/settings.json" );
$settings = json_decode( $settingsJson, TRUE );
if ( $settings === NULL )
{
	echo json_last_error();
}
else
{
	// Grab the namespace first, if it exists.
	$nameSpace = array_key_exists("namespace", $settings) ? $settings[ 'namespace' ] : '';
	foreach ( $settings as $name => $value )
	{
		define( $nameSpace . $name, $value );
	}
}
// Run any setup code you want done on every page of your site.

//DO NOT PUT ANY CHARACTERS OR EVEN WHITE-SPACE after the closing php tag, or headers may be sent before intended.
?>