<?php
/**
 * Diablo 3 Assistant License is under The MIT License (MIT) [OSI Approved License]
 * Please read LICENSE.txt, included with this software for the full licensing information.
 * If no LICENSE.txt accompanied this software, then no license is granted.
 * @package ${NAMESPACE}
 *
 * @copyright (c) 2012-2013 diablo-3-assistant by Khalifah K. Shabazz
 * Timespamp: 11/7/13:8:07 AM
 */
require_once __DIR__ . '/../vendor/autoload.php';
// configure PHP-VCR
$vcrSettings = \VCR\VCR::configure();
// where to save HTTP request & responses.
$vcrSettings->setCassettePath('test/fixture');
// specify which HTTP lib request to intercept.
$vcrSettings->enableLibraryHooks(array('curl'));
\VCR\VCR::turnOn();
// Writing below this line can cause headers to be sent before intended ?>