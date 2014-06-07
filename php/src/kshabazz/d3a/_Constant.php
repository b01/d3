<?php namespace kshabazz\d3a;
/**
 * Application Constants
 */

define( 'kshabazz\\d3a\\USER_IP_ADDRESS', ( array_key_exists('REMOTE_ADDR', $_SERVER) ) ? $_SERVER[ 'REMOTE_ADDR' ] : NULL );
define( 'kshabazz\\d3a\\D3_API_PROFILE_URL', 'http://us.battle.net/api/d3/profile' );
define( 'kshabazz\\d3a\\D3_API_HERO_URL', 'http://us.battle.net/api/d3/profile/%s/hero/%d' );
define( 'kshabazz\\d3a\\D3_API_ITEM_URL', 'http://us.battle.net/api/d3/data/%s' );

// Writing below this line can cause headers to be sent before intended ?>