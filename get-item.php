<?php namespace d3;
// Get the profile and store it.
require_once( "php/Tool.php" );
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
		$battleNetDqi = new BattleNetDqi( $battleNetId );
		$sql = new Sql( DSN, DB_USER, DB_PSWD, USER_IP_ADDRESS );
		$item = new Item( $itemUID, $itemIdType, $battleNetDqi, $sql );
		// Init item as an object.
		if ( is_object($item) )
		{
			$itemModel = new ItemModel( $item->json() );
		}
	}
	else
	{// Redirect if no data.
		header( "Location: /item.html" );
	}
?><?php if ( is_object($itemModel) ): ?>
<?php if ( $showExtra ): ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?= $itemModel->name ?></title>
		<link rel="stylesheet" href="/css/d3.css" />
		<link rel="stylesheet" href="/css/tooltips.css" />
		<link rel="stylesheet" href="/css/item.css" />
	</head>
	<body>
<?php endif; ?>
		<div class="item-tool-tip item">
			<h3 class="header smaller <?= $itemModel->displayColor; ?>"><?= $itemModel->name; ?></h3>
			<div class="effect-bg <?= "armor" ?>">
				<div class="icon <?= $itemModel->displayColor; ?> inline-block top" data-hash="<?= substr( $itemModel->tooltipParams, 5 ); ?>" data-type="<?= getItemSlot( $itemModel->type['id'] ) ?>">
					<img class="gradient" src="/media/images/icons/items/large/<?= $itemModel->icon; ?>.png" alt="<?= $itemModel->name; ?>" />
				</div>
				<div class="inline-block top">
					<div class="type-name inline-block <?= $itemModel->displayColor; ?>"><?= $itemModel->typeName; ?></div>
					<div class="type-name inline-block slot"><?= getItemSlot( $itemModel->type['id'] ) ?></div>
					<?php if ( isset($itemModel->armor) ): ?>
					<div class="big value"><?= displayRange( $itemModel->armor ); ?></div>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( isArray($itemModel->attributes) ): ?>
			<ul class="properties blue">
				<?php foreach ( $itemModel->attributes as $key => $value ): ?>
				<li class="effect"><?= formatAttribute( $value, "value" ) ?></li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
			<?php if ( isArray($itemModel->gems) ): ?>
			<ul class="list gems">
				<li class="full-socket d3-color-<?= $itemModel->gems[0]['item']['displayColor'] ?>">
					<img class="gem" src="http://media.blizzard.com/d3/icons/items/small/<?= $itemModel->gems[0]['item']['icon'] ?>.png">
					<?= $itemModel->gems[0]['attributes'][0] ?>
				</li>
			</ul>
			<?php endif; ?>
			<?php if ( isset($itemModel->set) && isArray($itemModel->set) ): ?>
			<ul class="list set">
				<li class="name d3-color-green"><?= $itemModel->set['name'] ?></li>
				<?php foreach ( $itemModel->set['items'] as $key => $value ): ?>
				<li class="piece"><?= $value['name'] ?></li>
				<?php endforeach; ?>
				<?php foreach ( $itemModel->set['ranks'] as $key => $value ): ?>
				<li class="rank">(<?= $value['required'] ?>) Set:</li>
				<?php if ( isArray($value['attributes']) ): ?>
				<?php foreach ( $value['attributes'] as $key => $value ): ?>
				<li class="piece"><?= formatAttribute( $value, "value" ); ?></li>
				<?php endforeach; ?>
				<?php endif; ?>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
			<div class="levels">			
				<div class="level left required inline-block">Required Level: <span class="value"><?= $itemModel->requiredLevel; ?></span></div>
				<div class="level right max inline-block">Item Level: <span class="value"><?= $itemModel->itemLevel; ?></span></div>
			</div>
			<?php if ( isset($itemModel->flavorText) ): ?>
			<div class="flavor"><?= $itemModel->flavorText; ?></div>
			<?php endif; ?>
		</div>
		<?php endif ?>
<?php if ( $showExtra ): ?>
		<pre class="json-data scroll"><?= $itemModel; ?></pre>
	</body>
</html>
<?php endif; ?>