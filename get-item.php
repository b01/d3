<?php
// Get the profile and store it.
namespace d3cb;
require_once( "php/Tool.php" );
require_once( "php/BattleNetDqi.php" );
require_once( "php/Item.php" );
require_once( "php/Sql.php" );

	$itemId = Tool::getPostStr( "itemId" );
	$battleNetId = Tool::getPostStr( "battleNetId" );
	if ( Tool::isString($itemId) )
	{
		$battleNetDqi = new BattleNetDqi( $battleNetId );
		
		$dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=UTF-8";
		$sql = new Sql( $dsn, DB_USER, DB_PSWD );
		$item = new Api\Item( $itemId, $battleNetDqi, $sql, USER_IP_ADDRESS );
	}
	
?>