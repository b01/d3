<?php
/**
* Application settings
* To minimize clutter these are run through a loop to define them as constants.
*/
define( 'kshabazz\\d3a\\EMAIL', 'khalifah@kshabazz.net' );
define( 'kshabazz\\d3a\\TITLE', 'Diablo 3 Character Builder by Khalifah Shabazz' );
define( 'kshabazz\\d3a\\DOMAIN_NAME', 'kshabazz.net' );
define( 'kshabazz\\d3a\\IMG_DIR', 'media/images' );
define( 'kshabazz\\d3a\\DEBUG_SWITCH', FALSE );
define( 'kshabazz\\d3a\\WEB_ROOT', $_SERVER[ 'DOCUMENT_ROOT' ] . '\\\\');
define( 'kshabazz\\d3a\\USER_INI', ini_get( 'user_ini.filename' ) );
define( 'kshabazz\\d3a\\BATTLENET_WEB_API_KEY', '' );
define( 'kshabazz\\d3a\\BATTLENET_D3_API_DOMAIN', 'us.battle.net/api/d3' );
define( 'kshabazz\\d3a\\BATTLENET_D3_API_PROFILE_URL', 'http://us.battle.net/api/d3/profile' );
define( 'kshabazz\\d3a\\BATTLENET_D3_API_HERO_URL', 'http://us.battle.net/api/d3/profile/%s/hero/%d' );
define( 'kshabazz\\d3a\\BATTLENET_D3_API_ITEM_URL', 'http://us.battle.net/api/d3/data/item/%s' );
define( 'kshabazz\\d3a\\USER_IP_ADDRESS', $_SERVER[ 'REMOTE_ADDR' ] );
define( 'kshabazz\\d3a\\CACHE_LIMIT', 600 );
define( 'kshabazz\\d3a\\ATTRIBUTE_MAP_FILE', './media/data-files/attribute-map.txt' );
define( 'kshabazz\\d3a\\HTTP_REFERER', ( array_key_exists('HTTP_REFERER', $_SERVER) ) ? $_SERVER[ 'HTTP_REFERER' ] : NULL );
define( 'kshabazz\\d3a\\ROOT', 'E:\Khalifah\Projects\diablo-3-assistant' );
// Writing below this line can cause headers to be sent before intended
?>