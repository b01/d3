<?php
/**
* This is the settings (BOOTSTRAP) file. Place all  of your PHP application secrets here that you want available on
* every page. They will be loaded once per page.
*
* Requirements
* Only const, define, include, and require statements are allowed here! So no logic code please.
*  Then LOCK THIS FILE DOWN like the Pentagon, or your nerd pron (commic books) collection.
*/
namespace d3cb;

// [CONTACT INFO]
	// Email
	define( "d3cb\WM_EMAIL", "myWebsiteContactEmail@myDomain.com" );
	// Default content for the title tag.
	define( "d3cb\TITLE", "Diablo 3 Character Builder by Khalifah Shabazz" );

// [SERVERS]
	define( "d3cb\DOMAIN_NAME", "myDomain.com" );

	define( "d3cb\DB_SERVER", "127.0.0.1" );

// [DATABASE ACCESS]
	// Assign these at login! and store in a session.
	define( "d3cb\DB_USER", "myDbUser" );
	// Assign these at login! and store in a session.
	define( "d3cb\DB_PSWD", "myDbPassword" );
	// This site main database for dynamic functionality.
	define( "d3cb\DB_NAME", "myDbSchemaName" );

// [DIRECTORIES]
	// Main directory for PHP scripts.
	define( "d3cb\PHP_DIR", "php" );

	// Main directory for images.
	define( "d3cb\IMG_DIR", "media/images" );
	
	// Show site debug messages.
	define( "d3cb\DEBUG_SWITCH", FALSE );
	
	define( "d3cb\UPLOAD_DIR", "./uploads/" );
	// This should alway point to the root of the web application.
	define( "d3cb\WEB_ROOT", "{$_SERVER['DOCUMENT_ROOT']}\\" );
	
	// Get the php user customizable ini file.
	define( "d3cb\USER_INI", ini_get("user_ini.filename") );
	
// [Diablo 3 Battle.Net]
	define( "d3cb\BATTLENET_WEB_API_KEY", "" );
	define( "d3cb\BATTLENET_D3_API_DOMAIN", "us.battle.net/api/d3" );
	define( "d3cb\BATTLENET_D3_PROFILE", BATTLENET_D3_API_DOMAIN . "/profile" );

// [USER]
	define( "d3cb\USER_IP_ADDRESS", $_SERVER['REMOTE_ADDR'] );
?>
