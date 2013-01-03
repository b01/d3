<?php namespace d3;
// Get the profile and store it.
require_once( "php/BattleNetDqi.php" );
require_once( "php/Item.php" );
require_once( "php/ItemModel.php" );
require_once( "php/Sql.php" );
require_once( "php/Tool.php" );

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
		$item = new Item(  $itemUID, $itemIdType, $battleNetDqi, $sql );
		// Init item as an object.
		if ( is_object($item) )
		{
			$itemModel = new ItemModel( $item->getRawData() );
		}
	}
	else
	{// Redirect if no data.
		header( "Location: /item.html" );
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" href="/css/d3.css" />
		<link rel="stylesheet" href="/css/tooltips.css" />
		<link rel="stylesheet" href="/css/item.css" />
	</head>
	<body>
		<?php if ( is_object($itemModel) ): ?>
		<div class="item-tool-tip item">
			<h3 class="header smaller <?= $itemModel->displayColor; ?>"><?= $itemModel->name; ?></h3>
			<div class="effect-bg armor">
				<div class="icon <?= $itemModel->displayColor; ?> inline-block top">
					<img class="gradient" src="http://media.blizzard.com/d3/icons/items/large/<?= $itemModel->icon; ?>.png" alt="<?= $itemModel->name; ?>" />
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
			<div class="hash"><?= $itemModel->tooltipParams; ?></div>
		</div>
		<?php endif ?>
		<?php if ( $showExtra ): ?>
		<!-- D# Official HTML -->
		<div class="inline-block top ui-tooltip">
			<div class="tooltip-content">
				<div class="d3-tooltip d3-tooltip-item">
					<div class="tooltip-head tooltip-head-green">
						<h3 class="d3-color-green smaller">Tal Rasha's Guise of Wisdom</h3>
					</div>
					<div class="tooltip-body effect-bg effect-bg-armor effect-bg-armor-default">
						<span class="d3-icon d3-icon-item d3-icon-item-large  d3-icon-item-green">
							<span class="icon-item-gradient">
								<span class="icon-item-inner icon-item-default" style="background-image: url(http://media.blizzard.com/d3/icons/items/large/unique_helm_010_104_demonhunter_male.png);"></span>
							</span>
						</span>
						<div class="d3-item-properties">
							<ul class="item-type-right">
								<li class="item-slot">Head</li>
							</ul>
							<ul class="item-type">
								<li>
									<span class="d3-color-green">Set Helm</span>
								</li>
							</ul>
							<ul class="item-armor-weapon item-armor-armor">
								<li class="big"><span class="value">727</span></li>
								<li>Armor</li>
							</ul>
							<div class="item-before-effects"></div>
							<ul class="item-effects">
								<li class="d3-color-blue"><p><span class="value">+94</span> Intelligence</p></li>
								<li class="d3-color-blue"><p><span class="value">+</span><span class="value">41</span> Fire Resistance</p></li>
								<li class="d3-color-blue"><p><span class="value">+11</span><span class="value">%</span> Life</p></li>
								<li class="d3-color-blue"><p><span class="value">+275</span> Armor</p></li>
								<li class="d3-color-blue"><p>Critical Hit Chance Increased by <span class="value">5.5</span><span class="value">%</span></p></li>
								<li class="d3-color-white full-socket">
									<img class="gem" src="http://media.blizzard.com/d3/icons/items/small/topaz_08_demonhunter_male.png" />
									<span class="socket-effect">
										19% Better Chance of Finding Magical Items
									</span>
								</li>
							</ul>
							<ul class="item-itemset">
								<li class="item-itemset-name"><span class="d3-color-green">Tal Rasha's Sacrifice</span></li>
								<li class="item-itemset-piece indent">
									<span class="d3-color-gray">Tal Rasha's Allegiance</span>
								</li>
								<li class="item-itemset-piece indent">
									<span class="d3-color-gray">Tal Rasha's Brace</span>
								</li>
								<li class="item-itemset-piece indent">
									<span class="d3-color-white">Tal Rasha's Guise of Wisdom</span>
								</li>
								<li class="item-itemset-piece indent">
									<span class="d3-color-gray">Tal Rasha's Relentless Pursuit</span>
								</li>
								<li class="item-itemset-piece indent">
									<span class="d3-color-gray">Tal Rasha's Unwavering Glare</span>
								</li>
								<li class="d3-color-gray item-itemset-bonus-amount">(2) Set:</li>
								<li class="d3-color-gray item-itemset-bonus-desc indent">Fire skills deal <span class="value">3</span><span class="value">%</span> more damage.</li>
								<li class="d3-color-gray item-itemset-bonus-amount">(3) Set:</li>
								<li class="d3-color-gray item-itemset-bonus-desc indent">Lightning skills deal <span class="value">3</span><span class="value">%</span> more damage.</li>
								<li class="d3-color-gray item-itemset-bonus-amount">(4) Set:</li>
								<li class="d3-color-gray item-itemset-bonus-desc indent">Cold skills deal <span class="value">3</span><span class="value">%</span> more damage.</li>
								<li class="d3-color-gray item-itemset-bonus-desc indent">Increases Arcane Power Regeneration by <span class="value">2.00</span> per Second <span class="d3-color-red">(Wizard Only)</span></li>
							</ul>
							<ul class="item-extras">			
								<li class="item-reqlevel"><span class="d3-color-gold">Required Level: </span><span class="value">60</span></li>
								<li class="item-ilvl">Item Level: <span class="value">63</span></li>
							</ul>
							<span class="item-unique-equipped">Unique Equipped</span>	
							<span class="clear"><!-- --></span>
						</div>
					</div>
					<div class="tooltip-extension ">
						<div class="flavor">The symbol of the Horadric order.</div>
					</div>
				</div>
			</div>
		</div>
		
		<pre class="json-data scroll"><?= $itemModel; ?></pre>
		<?php endif; ?>
	</body>
</html>