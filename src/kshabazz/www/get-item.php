<?php namespace kshabazz\d3a;
// Get the profile and store it.
// All classes are loaded on-the-fly, so no need to require them.

	$battleNetId = getPostStr( "battleNetId" );
	$itemHash = getPostStr( "itemHash" );
	$itemId = getPostStr( "itemId" );
	$itemName = getPostStr( "itemName" );
	$item = NULL;
	$itemModel = NULL;
	$showExtra = getPostBool( "extra" );
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
		$battleNetDqi = new BattleNet_Requestor( $battleNetId );
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
?>
<?php if ( $item instanceof Item ): ?>
<?php if ( $showExtra ): ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?= $item->name ?></title>
		<meta charset="utf-8" />
		<link rel="stylesheet" rel="stylesheet" href="/css/d3.css" />
		<link rel="stylesheet" rel="stylesheet" href="/css/site.css" />
		<link rel="stylesheet" rel="stylesheet" href="/css/tooltips.css" />
		<link rel="stylesheet" rel="stylesheet" href="/css/item.css" />
	</head>
	<body>
<?php endif; ?>
		<?php include( 'templates/item.php' ); ?>
		<?php endif ?>
<?php if ( $showExtra ): ?>
		<pre class="json-data scroll"><?= $item; ?></pre>
	</body>
</html>
<?php endif; ?>