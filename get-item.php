<?php namespace d3cb;
// Get the profile and store it.
require_once( "php/Tool.php" );
require_once( "php/BattleNetDqi.php" );
require_once( "php/Item.php" );
require_once( "php/Sql.php" );

	$itemId = getPostStr( "itemId" );
	$itemName = getPostStr( "itemName" );
	$itemHash = getPostStr( "itemHash" );
	$battleNetId = getPostStr( "battleNetId" );
	$item = NULL;
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
	else
	{
		header( "Location: /item.html" );
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title></title>
	</head>
	<body>
		<?php if ( is_object($item) ): ?>
		<div class="json-data"><?= $item; ?></div>
		<div class="tooltip">
			<div class="tooltip-content">
				<div class="d3-tooltip d3-tooltip-item">
					<div class="tooltip-head tooltip-head-yellow">
						<h3 class="d3-color-yellow">Hunter's Scale</h3>
					</div>
					<div class="tooltip-body effect-bg effect-bg-poison">
						<span class="d3-icon d3-icon-item d3-icon-item-large  d3-icon-item-yellow">
							<span class="icon-item-gradient">
								<span class="icon-item-inner icon-item-default" style="background-image: url(http://media.blizzard.com/d3/icons/items/large/axe_1h_207_demonhunter_male.png);">
								</span>
							</span>
						</span>
						<div class="d3-item-properties">
							<ul class="item-type-right">
								<li class="item-slot">1-Hand</li>
							</ul>
							<ul class="item-type">
								<li>
									<span class="d3-color-yellow">Rare Axe</span>
								</li>
							</ul>
							<ul class="item-armor-weapon item-weapon-dps">
								<li class="big"><span class="value">931.6</span></li>
								<li>Damage Per Second</li>
							</ul>
							<ul class="item-armor-weapon item-weapon-damage">
								<li><p><span class="value">423&ndash;904</span> <span>Damage</span></p></li>
								<li><p><span class="value">1.40</span> <span>Attacks per Second</span></p></li>
							</ul>
							<div class="item-before-effects"></div>
							<ul class="item-effects">
								<li class="d3-color-blue"><p><span class="value">+</span><span class="value">272&ndash;626</span> Poison Damage</p></li>
								<li class="d3-color-blue"><p><span class="value">+63</span> Strength</p></li>
								<li class="d3-color-blue"><p><span class="value">+253</span> Dexterity</p></li>
								<li class="d3-color-blue"><p>Increases Attack Speed by <span class="value">8</span><span class="value">%</span></p></li>
								<li class="d3-color-blue"><p><span class="value">2.40</span><span class="value">%</span> of Damage Dealt Is Converted to Life</p></li>
								<li class="d3-color-white full-socket">
									<img class="gem" src="http://media.blizzard.com/d3/icons/items/small/emerald_11_demonhunter_male.png"/>
									<span class="socket-effect">Critical Hit Damage Increased by 70%</span>
								</li>
							</ul>
							<ul class="item-extras">			
								<li class="item-reqlevel"><span class="d3-color-gold">Required Level: </span><span class="value">60</span></li>
								<li class="item-ilvl">Item Level: <span class="value">63</span></li>
							</ul>
							<span class="clear"><!-- --></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</body>
</html>