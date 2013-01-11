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
require_once( "php/Tool.php" );
require_once( "php/settings.php" );
// We have to specify the namespace when defining constants.
$nameSpace = array_key_exists( "namespace", $siteSettings ) ? $siteSettings[ 'namespace' ] : '';
foreach ( $siteSettings as $name => $value )
{
	define( $nameSpace . $name, $value );
}
// Run any setup code you want done on every page of your site.

//DO NOT PUT ANY CHARACTERS OR EVEN WHITE-SPACE after the closing php tag, or headers may be sent before intended.
?>