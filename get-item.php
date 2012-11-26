<?php namespace d3cb;
// Get the profile and store it.
require_once( "php/Tool.php" );
require_once( "php/BattleNetDqi.php" );
require_once( "php/Item.php" );
require_once( "php/Sql.php" );

	$battleNetId = getPostStr( "battleNetId" );
	$itemHash = getPostStr( "itemHash" );
	$itemId = getPostStr( "itemId" );
	$itemName = getPostStr( "itemName" );
	$item = NULL;
	$itemModel = NULL;
	$dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=UTF-8";
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
		$sql = new Sql( $dsn, DB_USER, DB_PSWD, USER_IP_ADDRESS );
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
		<link rel="stylesheet" href="/css/item.css" />
		<link rel="stylesheet" href="/css/tooltips.css" />
	</head>
	<body>
		<?php if ( is_object($itemModel) ): ?>
		<pre class="json-data"><?= $itemModel; ?></pre>
		<div class="item tool-tip">
			<h3 class="header d3-color-<?= $itemModel->displayColor; ?>"><?= $itemModel->name; ?></h3>
			<div class="effect-bg poison">
				<div class="icon <?= $itemModel->displayColor; ?> inline-block">
					<img src="http://media.blizzard.com/d3/icons/items/large/<?= $itemModel->icon; ?>.png" alt="<?= $itemModel->name; ?>" />
				</div>
			</div>
			<?php if ( isArray($itemModel->attributes) ): ?>
			<ul>
				<?php for ( $i = 0; $i < count($itemModel->attributes); $i++ ): ?>
				<li><?= $itemModel->attributes[ $i ]; ?></li>
				<?php endfor; ?>
			</ul>
			<?php endif; ?>
			<div class="levels">			
				<div class="required inline-block"><span class="d3-color-gold">Required Level: </span><?= $itemModel->requiredLevel; ?></div>
				<div class="max inline-block"><span class="d3-color-gold">Item Level: </span><?= $itemModel->itemLevel; ?></div>
			</div>
		</div>
		
		<!-- D# Official HTML -->
		<div xmlns="http://www.w3.org/1999/xhtml" class="tooltip-content">
			<div class="d3-tooltip">
	<div class="d3-tooltip d3-tooltip-item">




	<div class="tooltip-head tooltip-head-orange">
		<h3 class="d3-color-orange">Broken Crown</h3>
	</div>

	<div class="tooltip-body effect-bg effect-bg-armor effect-bg-armor-default">

		


	<span class="d3-icon d3-icon-item d3-icon-item-large  d3-icon-item-orange">
		<span class="icon-item-gradient">
			<span class="icon-item-inner icon-item-default" style="background-image: url(http://media.blizzard.com/d3/icons/items/large/unique_helm_001_104_demonhunter_male.png);">
			</span>
		</span>
	</span>



	<div class="d3-item-properties">



	<ul class="item-type-right">

			<li class="item-slot">Head</li>

	</ul>


	<ul class="item-type">
		<li>

			<span class="d3-color-orange">Legendary Helm</span>
		</li>
	</ul>
		<ul class="item-armor-weapon item-armor-armor">
			<li class="big"><span class="value">54–71</span></li>
			<li>Armor</li>
		</ul>




	<div class="item-before-effects"></div>



		<ul class="item-effects">

		<li class="d3-color-blue"><p><span class="value">+18-23</span> Vitality</p></li>
		<li class="d3-color-blue"><p>Attack Speed Increased by <span class="value">3-4</span><span class="value">%</span></p></li>
		<li class="d3-color-blue"><p>Monster kills grant <span class="value">+9-10</span> experience.</p></li>



	<li class="d3-color-blue"><p><span class="value">+</span><span class="value">3</span> Random Magic Properties</p></li>



		</ul>


		<ul class="item-extras">			
		<li class="item-reqlevel"><span class="d3-color-gold">Required Level: </span><span class="value">16</span></li>
		<li class="item-ilvl">Item Level: <span class="value">19</span></li>
		</ul>
		<span class="item-unique-equipped">Unique Equipped</span>	
	<span class="clear"><!-- --></span>
	</div>

	</div>

	<div class="tooltip-extension ">
				<div class="flavor">The ancient crown of Rakkis, first ruler of Westmarch.</div>
	</div>

	</div>
			</div>
		</div>
		<?php endif; ?>
	</body>
</html>