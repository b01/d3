<?php namespace kshabazz\d3a\View;
/**
 * Diablo 3 Assistant License is under The MIT License (MIT) [OSI Approved License]
 * Please read LICENSE.txt, included with this software for the full licensing information.
 * If no LICENSE.txt accompanied this software, then no license is granted.
 * @package kshabazz\d3a\View
 *
 * @copyright (c) 2012-2014 diablo-3-assistant by Khalifah K. Shabazz
 * timestamp: 1/20/14:1:41 PM
 */
use kshabazz\d3a\iView;

/**
 * Class Item
 * @package kshabazz\d3a\View
 */
class Item implements iView
{
	protected
		$item,
		$json;
	public function __construct( array $pData )
	{
		$this->item = $pData[ 'item' ];
		$this->json = $pData[ 'json' ];
	}

	public function render()
	{
		$data = [
			'item' => $this->item,
			'json' => $this->json
		];
		return $data;
	}
}
// Writing below this line can cause headers to be sent before intended ?>