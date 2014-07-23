<?php
/**
 * Diablo 3 Assistant License is under The MIT License (MIT) [OSI Approved License]
 * Please read LICENSE.txt, included with this software for the full licensing information.
 * If no LICENSE.txt accompanied this software, then no license is granted.
 * @package ${NAMESPACE}
 *
 * @copyright (c) 2012-2013 diablo-3-assistant by Khalifah K. Shabazz
 */
require_once __DIR__ . '/../vendor/autoload.php';
$fixturesPath = realpath( __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' );
define( 'FIXTURES_PATH', $fixturesPath . DIRECTORY_SEPARATOR );
// Tell PHP-VCR where to store request.
\VCR\VCR::configure()->setCassettePath( $fixturesPath );
// Set which HTTP lib request to intercept.
\VCR\VCR::configure()->enableLibraryHooks([ 'stream_wrapper' ]);
?>