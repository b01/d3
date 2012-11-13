<?php
namespace d3cb;
// Get the profile and store it.
require_once( "php/Tool.php" );
require_once( "php/BattleNetDqi.php" );
require_once( "php/Item.php" );
require_once( "php/Sql.php" );

	$itemId = getPostStr( "itemId" );
	$itemName = getPostStr( "itemName" );
	$itemHash = getPostStr( "itemHash" );
	$battleNetId = getPostStr( "battleNetId" );
	if ( isString($itemId) || isString($itemName) || isString($itemHash) )
	{
		$battleNetDqi = new BattleNetDqi( $battleNetId );
		
		$dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=UTF-8";
		$sql = new Sql( $dsn, DB_USER, DB_PSWD, USER_IP_ADDRESS );
		$item = new Item( $itemId, $battleNetDqi, $sql );
		if ( isString($itemName) )
		{
			$item->loadByName( $itemName );
		}
		if ( isString($itemHash) )
		{
			$item->loadByHash( $itemHash );
		}
	}
?>

<?php if ( is_object($item) ): ?>
<?php endif; ?>