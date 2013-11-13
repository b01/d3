<?php namespace kshabazz\d3a\Controller;
/**
 * Get the profile and store it.
 * @package kshabazz\d3a\Controller
 */
/**
 * Diablo 3 Assistant License is under The MIT License (MIT) [OSI Approved License]
 * Please read LICENSE.txt, included with this software for the full licensing information.
 * If no LICENSE.txt accompanied this software, then no license is granted.
 * @package kshabazz\d3a\Controller
 *
 * @copyright (c) 2012-2013 diablo-3-assistant by Khalifah K. Shabazz
 * Timestamp: 11/11/13:7:37 AM
 */
 /**
 * Class Item
 * @package kshabazz\d3a\Controller
 */
class Item
{
	protected
		$battleNetId,
		$hash,
		$id,
		$name,
		$model,
		$showExtra;

	/**
	 * Initialize the object.
	 */
	public function __construct()
	{
		$this->battleNetId = getPostStr( "battleNetId" );
		$this->Hash = getPostStr( "itemHash" );
		$this->id = getPostStr( "itemId" );
		$this->name = getPostStr( "itemName" );
		$this->model = NULL;
		$this->showExtra = getPostBool( "extra" );
		if ( isString($itemHash) )
		{
			$itemUID = $itemHash;
			$itemIdType = "hash";
		}
		else if ( isString($itemId) )
		{
			$itemUID = $itemId;
			$itemIdType = "id";
		}
		else if ( isString($itemHash) )
		{
			$itemUID = $itemName;
			$itemIdType = "name";
		}

		if ( isString($battleNetId) && isString($itemUID) )
		{
			$battleNetDqi = new BattleNet_Dqi( $battleNetId );
			$sql = new BattleNet_Sql( DSN, DB_USER, DB_PSWD, USER_IP_ADDRESS );
			$itemModel = new BattleNet_Item( $itemUID, $itemIdType, $battleNetDqi, $sql );
			// Init item as an object.
			if ( is_object($itemModel) )
			{
				$itemJson = $itemModel->json();
				$item = new Item( $itemJson );
				$itemHash = substr( $item->tooltipParams, 5 );
			}
		}
		else
		{// Redirect if no data.
			header( "Location: /item.html" );
		}
	}
}
// Writing below this line can cause headers to be sent before intended
?>