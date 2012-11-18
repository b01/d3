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
	$itemModel = NULL;
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
		// Init item as an object.
		if ( is_object($item) )
		{
			$itemModel = new ItemModel( $item->getRawData() );
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
		<style type="text/css">
		.inline-block {
			display: inline-block;
		}
		
		.tool-tip {
			background: black;
			padding: 10px;
			color: rgb(207, 185, 145);
			font-size: 12px;
		}
		.tool-tip .icon {
			border-color: rgb(81, 63, 46);
			border-radius: 4px;
			border: 1px solid black;
			margin-right: 10px;
			margin-bottom: 11px;
		}
		.tool-tip .header {
			font: 22px "Palatino Linotype", "Times", serif;
			height: 40px;
			line-height: 37px;
			text-align: center;
			overflow: hidden;
			white-space: nowrap;
			text-overflow: ellipsis;
		}
		
		/* item colors */
		.item .icon.brown {
			background-image: url("http://us.battle.net/d3/static/images/item/icon-bgs/brown.png");
			background-color: rgb(42, 32, 23);
		}
		
		.item .icon.orange {
			border-color: rgb(176, 123, 56);
			background-image: url("http://us.battle.net/d3/static/images/item/icon-bgs/orange.png");
			background-color: rgb(51, 35, 20);
		}
		
		.item .icon.gray {
			border-color: #513f2e;
			background-image: url("http://us.battle.net/d3/static/images/item/icon-bgs/brown.png");
			background-color: #2a2017;
		}
		a:hover .item .icon.gray,
		.hover .item .icon.gray,
		.icon-active .item .icon.gray {
			border-color: #7a5f45;
		}

		/* white */
		.item .icon.white { border-color: #513f2e; background-image: url("http://us.battle.net/d3/static/images/item/icon-bgs/brown.png"); background-color: #2a2017; }
		a:hover .item .icon.white,
		.hover .item .icon.white,
		.icon-active .item .icon.white { border-color: #7a5f45; }

		/* blue */
		.item .icon.blue {
			border-color: #6091a6; background-image: url("http://us.battle.net/d3/static/images/item/icon-bgs/blue.png"); background-color: #232227; }
		a:hover .item .icon.blue,
		.hover .item .icon.blue,
		.icon-active .item .icon.blue { border-color: #90c8d3; }

		/* yellow */
		.item .icon.yellow { border-color: #9b8e3c; background-image: url("http://us.battle.net/d3/static/images/item/icon-bgs/yellow.png"); background-color: #322914; }
		a:hover .item .icon.yellow,
		.hover .item .icon.yellow,
		.icon-active .item .icon.yellow { border-color: #cdc75a; }

		/* orange */
		.item .icon.orange { border-color: #b07b38; background-image: url("http://us.battle.net/d3/static/images/item/icon-bgs/orange.png"); background-color: #332314; }
		a:hover .item .icon.orange,
		.hover .item .icon.orange,
		.icon-active .item .icon.orange { border-color: #d8b954; }

		/* green */
		.item .icon.green { border-color: #748e3d; background-image: url("http://us.battle.net/d3/static/images/item/icon-bgs/green.png"); background-color: #262f14; }
		a:hover .item .icon.green,
		.hover .item .icon.green,
		.icon-active .item .icon.green { border-color: #aec75c; }
/*
	CSS code shared between the D3 Community Site and the Tooltip Script.

	Note: All global styles should be prefixed with d3- to avoid affecting 3rd party sites.
*/

/* item properties (shared between item detail page, item browsing, and item tooltips */
.d3-item-properties ul,
.d3-item-properties div { margin-top: 10px; }
.d3-item-properties ul ul { margin-top: 0; }
.d3-item-properties ul li { margin: 1px 0; }
.d3-item-properties ul li.bump { margin-top: 10px; }
.d3-item-properties p { margin: 0 !important; }
.d3-item-properties .indent { padding-left: 18px; }
.d3-item-properties .value { color: #ded2ab; }
.d3-item-properties .big .value { font-size: 400%; line-height: 100%; font-family: "Palatino Linotype", "Times", serif; text-shadow: 0 0 5px black, 0 0 5px black, 0 0 5px black; }
.d3-item-properties .item-requirement { color: #A99877 }
.d3-item-properties .d3-color-blue .value { color: #bda6db !important; }
.d3-item-properties .d3-color-gold .value { color: white !important; }
.d3-item-properties .item-type,
.d3-item-properties .item-type-right { margin-top: 0; }
.d3-item-properties .item-type-right { float: right; text-align: right; }
.d3-item-properties .item-type { color: white; }
.d3-item-properties .item-slot { color: #909090; }
.d3-item-properties  .item-unique-equipped { text-align:left; clear:both }
.d3-item-properties .item-armor-weapon li { color: #909090; }
.d3-item-properties .item-armor-weapon .value { color: white; }
.d3-item-properties .item-before-effects { display: none; }
.d3-item-properties .item-effects li { padding-left: 16px; background: url("http://us.battle.net/d3/static/images/icons/bullet.gif") 2px 5px no-repeat; }
.d3-item-properties .item-effects li.empty-socket { background: url("http://us.battle.net/d3/static/images/item/empty-socket.png") 0 center no-repeat; }
.d3-item-properties .item-effects li.full-socket { line-height:18px; min-height:18px; background: none; padding-left:0; padding-top:7px; white-space:nowrap }
.d3-item-properties .item-effects li.full-socket .gem { float:left; width:17px; height:17px; margin-right:4px }
.d3-item-properties .item-effects li.full-socket .socket-effect { padding-left: 16px; background: url("http://us.battle.net/d3/static/images/icons/bullet.gif") 2px 5px no-repeat; display:inline-block; min-height:18px; line-height:18px; white-space:normal }

.d3-item-properties .item-effects .gem-effect { color: white; }
.d3-item-properties .item-effects-choice { margin-bottom: 10px; }

.d3-tooltip-wrapper { background: #1d180e; padding: 1px; border: 1px solid #322a20; max-width: 355px; position: absolute; z-index: 2147483647; border-radius: 2px; box-shadow: 0 0 10px #000; }
.d3-tooltip-wrapper-inner { background: black; }

.d3-tooltip .title,
.d3-tooltip .subtitle { font-family: "Palatino Linotype", "Georgia", "Times", serif; color: #F3E6D0; font-weight: normal; margin-bottom: 6px; }
.d3-tooltip .title { font-size: 18px; }
.d3-tooltip .subtitle { font-size: 14px; text-transform: uppercase; }
.d3-tooltip .special { color: #AD835A; }
.d3-tooltip .subtle { color: #7B6D55; display: block; }
.d3-tooltip .subtle em { color: #AD835A; font-weight: bold; font-style: normal; }
.d3-tooltip .flavor { font-size: 16px; color: #AD835A; font-family: "Palatino Linotype", "Times", serif; font-style: italic; }
.d3-tooltip .tip { border-bottom: 0; }
.d3-tooltip p { margin: 10px 0 0 0; }
.d3-tooltip p:first-child { margin-top: 0; }
.d3-tooltip .loading { display: block; width: 32px; height: 32px; background: url("http://us.battle.net/d3/static/images/loaders/default.gif") no-repeat center center; }
.d3-tooltip .wip { position: absolute; z-index: 2; left: 0; top: 33%; width: 350px; text-align: center; font-size: 28px; font-family: "Palatino Linotype", "Georgia", "Times", serif; text-transform: uppercase; color: #A99877; opacity: 0.25; filter: alpha(opacity=25); line-height: 100%; }

	/* style reset */
	.d3-tooltip { padding: 2px; font: normal 12px/1.5 Arial, sans-serif; color: #c7b377; }
	.d3-tooltip * { margin: 0; padding: 0; background: none; }
	.d3-tooltip ul { list-style-type: none; }
	.d3-tooltip .value { color: white; }

	/* header */
	.tool-tip .header {
		background: url("http://us.battle.net/d3/static/images/ui/tooltip-title.jpg") no-repeat;
		font: 22px "Palatino Linotype", "Times", serif;
		height: 40px;
		line-height: 37px;
		overflow: hidden;
		padding: 0 15px;
		text-align: center;
		text-overflow: ellipsis;
		white-space: nowrap;
		width: 320px;
	}
	.tool-tip .header.smaller { font-size: 18px; line-height: 40px; }
	.tool-tip .header.smallest { font-size: 14px; line-height: 42px; }
	.tool-tip .header.gray,
	.tool-tip .header.white  { background-position: 0 -40px; }
	.tool-tip .header.blue   { background-position: 0 -80px; }
	.tool-tip .header.yellow { background-position: 0 -120px; }
	.tool-tip .header.orange { background-position: 0 -160px; }
	.tool-tip .header.purple { background-position: 0 -200px; }
	.tool-tip .header.green  { background-position: 0 -240px; }

	/* body */
	.d3-tooltip .tooltip-body { position: relative; padding: 10px; }

	/* extension */
	.d3-tooltip .tooltip-extension { margin: 10px -2px 0 -2px; padding: 10px 12px; border-top: 2px solid #322A20; } /* more horizontal padding to make up for negative margin */
	.d3-tooltip .tooltip-extension.rune-extension { padding-left: 87px; min-height: 52px; position: relative; }
	.d3-tooltip .tooltip-extension.rune-extension .d3-icon-rune { position: absolute; top: 10px; left: 20px; }

	/* item tooltips */
	.d3-tooltip-item .d3-icon-item { float: left; margin-right: 10px; margin-bottom: 11px; }

	.d3-tooltip-item .item-armor-weapon { clear: right; }
	.d3-tooltip-item .item-before-effects { display: block !important; clear: both; }
	.d3-tooltip-item .item-description { margin-top: 10px; }
	.d3-tooltip-item .item-itemset { font-size: 12px; }
	.d3-tooltip-item .item-reqlevel { float: right; }
	
	.d3-tooltip-item .effect-bg { background-position: 10px 10px; background-repeat: no-repeat; }
	.d3-tooltip-item .effect-bg-arcane { background-image: url("http://us.battle.net/d3/static/images/item/effect-bgs/arcane.jpg"); }
	.d3-tooltip-item .effect-bg-cold { background-image: url("http://us.battle.net/d3/static/images/item/effect-bgs/cold.jpg"); }
	.d3-tooltip-item .effect-bg-fire { background-image: url("http://us.battle.net/d3/static/images/item/effect-bgs/fire.jpg"); }
	.d3-tooltip-item .effect-bg-holy { background-image: url("http://us.battle.net/d3/static/images/item/effect-bgs/holy.jpg"); }
	.d3-tooltip-item .effect-bg-lightning { background-image: url("http://us.battle.net/d3/static/images/item/effect-bgs/lightning.jpg"); }
	.d3-tooltip-item .effect-bg-poison { background-image: url("http://us.battle.net/d3/static/images/item/effect-bgs/poison.jpg"); }
	.d3-tooltip-item .effect-bg-armor { background-image: url("http://us.battle.net/d3/static/images/item/effect-bgs/armor.jpg"); background-position: 78px 20px; }
	.d3-tooltip-item .effect-bg-armor-square { background-position: 78px 14px; }
	.d3-tooltip-item .effect-bg-armor-big { background-position: 96px 20px; }
	.d3-tooltip-item .effect-bg .item-type,
	.d3-tooltip-item .effect-bg .item-armor-weapon { text-shadow: 0 0 5px black, 0 0 5px black, 0 0 5px black; } /* makes the text readable when a background is used */

	/* skill tooltips */
	.d3-tooltip-skill .tooltip-body { padding-left: 85px; }
	.d3-tooltip-skill .d3-icon-skill { position: absolute; left: 10px; top: 10px; }

	/* trait tooltips */
	.d3-tooltip-trait .tooltip-body { padding-left: 105px; min-height: 80px; }
	.d3-tooltip-trait .d3-icon-trait { position: absolute; left: 10px; top: 10px; }

	/* rune tooltips */
	.d3-tooltip-rune .tooltip-body { padding-left: 60px; }
	.d3-tooltip-rune .d3-icon-rune { position: absolute; left: 10px; top: 10px; }

	/* calculator tooltips */
	.d3-tooltip-calculator { padding: 10px; min-width: 200px; }
	.d3-tooltip-calculator .title { font-size: 22px; line-height: 1em; margin-bottom: 0; }
	.d3-tooltip-calculator .subtitle {  }
	.d3-tooltip-calculator .empty { color: #808080; }
	.d3-tooltip-calculator li { position: relative; }
	.d3-tooltip-calculator li:nth-child(even),
	.d3-tooltip-calculator li.row2 { background-color: #101010; }
	.d3-tooltip-calculator .skill-icon { position: absolute; }
	.d3-tooltip-calculator .skill-rune { position: absolute; right: 5px; top: 0; }
	.d3-tooltip-calculator .actives { padding-top: 15px; }
	.d3-tooltip-calculator .actives li { line-height: 24px; padding-left: 26px; padding-right: 35px; }
	.d3-tooltip-calculator .actives .skill-icon { left: 0; top: 2px; }
	.d3-tooltip-calculator .passives { padding-top: 15px; }
	.d3-tooltip-calculator .passives li { line-height: 30px; padding-left: 29px; }
	.d3-tooltip-calculator .passives .skill-icon { left: 0; top: 3px; }

/* icons */
.d3-icon { display: inline-block; overflow: hidden; background: 50% 50% no-repeat; font-size: 1px; }

.d3-icon-item,
.d3-icon-skill { box-shadow: 0 0 5px #000; }

	/* items */
	.d3-icon-item { border: 1px solid black; background: no-repeat 50% 100%; border-radius: 4px; }
	.d3-icon-item .icon-item-gradient { display: block; border-radius: 4px;
		background-image: url("http://us.battle.net/d3/static/images/item/icon-bgs/gradient.png"); /* fallback */
		background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0));
		background-image:    -moz-linear-gradient(top, rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0));
		background-image:     -ms-linear-gradient(top, rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0));
		background-image:      -o-linear-gradient(top, rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0));
		background-image:         linear-gradient(top, rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0));
	}
	.d3-icon-item .icon-item-inner { display: block; padding: 1px; background: no-repeat center center; font-size: 1px; line-height: normal; text-align: center; border-radius: 4px; overflow: hidden; }
	a:hover .d3-icon-item .icon-item-inner,
	.hover .d3-icon-item .icon-item-inner,
	.icon-active .d3-icon-item .icon-item-inner { background-color: rgba(255, 255, 255, 0.05); } /* brighter */

	.item .icon.large .icon-item-default { width: 64px; height: 128px; }
	.item .icon.large .icon-item-square  { width: 64px; height: 64px; }
	.item .icon.large .icon-item-big     { width: 82px; height: 164px; }

	.item .icon.small .icon-item-default { width: 32px; height: 64px; }
	.item .icon.small .icon-item-square  { width: 32px; height: 32px; }
	.item .icon.small .icon-item-big     { width: 41px; height: 82px; }

	.item .icon.small,
	.item .icon.small .icon-item-gradient,
	.item .icon.small .icon-item-inner,
	.item .icon.32,
	.item .icon.32 .icon-item-gradient,
	.item .icon.32 .icon-item-inner { border-radius: 2px; } /* scale border radius accordingly */

	.item .icon.32 .icon-item-default,
	.item .icon.32 .icon-item-square,
	.item .icon.32 .icon-item-big { width: 32px; height: 32px; }

	.item .icon.64 .icon-item-default,
	.item .icon.64 .icon-item-square,
	.item .icon.64 .icon-item-big { width: 64px; height: 64px; }


	/* skills */
	.d3-icon-skill { }
	.d3-icon-skill .frame { display: block; background: no-repeat; }
	.d3-icon-skill-21,
	.d3-icon-skill-21 .frame { width: 21px; height: 21px; }
	.d3-icon-skill-42,
	.d3-icon-skill-42 .frame { width: 42px; height: 42px; }
	.d3-icon-skill-64,
	.d3-icon-skill-64 .frame { width: 64px; height: 64px; }
	.d3-icon-skill-21 .frame { background-image: url("http://us.battle.net/d3/static/images/icons/frames/skill-21.png"); }
	.d3-icon-skill-42 .frame { background-image: url("http://us.battle.net/d3/static/images/icons/frames/skill-42.png"); }
	.d3-icon-skill-64 .frame { background-image: url("http://us.battle.net/d3/static/images/icons/frames/skill-64.png"); }
	.d3-icon-skill.selected .frame { background-position: top right; }

	a:hover .d3-icon-skill .frame,
	.hover .d3-icon-skill .frame { background-position: left bottom; }
	a:hover .d3-icon-skill.selected .frame,
	.hover .d3-icon-skill.selected .frame { background-position: right bottom; }

	a.disabled:hover .d3-icon-skill .frame,
	.disabled.hover .d3-icon-skill .frame { background-position: left top !important; }
	a.disabled:hover .d3-icon-skill.selected .frame,
	.disabled.hover .d3-icon-skill.selected .frame { background-position: right top !important; }

	/* traits */
	.d3-icon-trait { }
	.d3-icon-trait .frame { display: block; background: no-repeat; }
	.d3-icon-trait-21,
	.d3-icon-trait-21 .frame { width: 25px !important; height: 25px !important; }
	.d3-icon-trait-42,
	.d3-icon-trait-42 .frame { width: 51px !important; height: 51px !important; }
	.d3-icon-trait-64,
	.d3-icon-trait-64 .frame { width: 81px !important; height: 81px !important; }
	.d3-icon-trait-21 .frame { background-image: url("http://us.battle.net/d3/static/images/icons/frames/trait-21.png"); }
	.d3-icon-trait-42 .frame { background-image: url("http://us.battle.net/d3/static/images/icons/frames/trait-42.png"); }
	.d3-icon-trait-64 .frame { background-image: url("http://us.battle.net/d3/static/images/icons/frames/trait-64.png"); }
	.d3-icon-trait.selected .frame { background-position: top right; }

	a:hover .d3-icon-trait .frame,
	.hover .d3-icon-trait .frame { background-position: left bottom; }
	a:hover .d3-icon-trait.selected .frame,
	.hover .d3-icon-trait.selected .frame { background-position: right bottom; }

	a.disabled:hover .d3-icon-trait .frame,
	.disabled.hover .d3-icon-trait .frame { background-position: left top !important; }
	a.disabled:hover .d3-icon-trait.selected .frame,
	.disabled.hover .d3-icon-trait.selected .frame { background-position: right top !important; }

	.d3-icon-trait.circle,
	.d3-icon-trait.circle .frame { width: 64px !important; height: 64px !important; }
	.d3-icon-trait.circle .frame { background-image: url("http://us.battle.net/d3/static/images/icons/frames/trait-circle.png"); }

	/* runes */
	.d3-icon-rune { vertical-align: middle; }
	.d3-icon-rune span { display: block; vertical-align: top; background: no-repeat; }

	.d3-icon-rune-large span { width: 50px; height: 50px; background-image: url("http://us.battle.net/d3/static/images/icons/runes/large.png"); }
	.d3-icon-rune-large .rune-a { background-position: 0 0; }
	.d3-icon-rune-large .rune-b { background-position: -50px 0; }
	.d3-icon-rune-large .rune-c { background-position: -100px 0; }
	.d3-icon-rune-large .rune-d { background-position: -150px 0; }
	.d3-icon-rune-large .rune-e { background-position: -200px 0; }
	.d3-icon-rune-large .rune-none { background-position: -250px 0; }

	.d3-icon-rune-medium span { width: 42px; height: 42px; background-image: url("http://us.battle.net/d3/static/images/icons/runes/medium.png"); }
	.d3-icon-rune-medium .rune-a { background-position: 0 0; }
	.d3-icon-rune-medium .rune-b { background-position: -42px 0; }
	.d3-icon-rune-medium .rune-c { background-position: -84px 0; }
	.d3-icon-rune-medium .rune-d { background-position: -126px 0; }
	.d3-icon-rune-medium .rune-e { background-position: -168px 0; }
	.d3-icon-rune-medium .rune-none { background-position: -210px 0; }

	.d3-icon-rune-small span { width: 16px; height: 16px; background-image: url("http://us.battle.net/d3/static/images/icons/runes/small.png"); }
	.d3-icon-rune-small .rune-a { background-position: 0 0; }
	.d3-icon-rune-small .rune-b { background-position: -16px 0; }
	.d3-icon-rune-small .rune-c { background-position: -32px 0; }
	.d3-icon-rune-small .rune-d { background-position: -48px 0; }
	.d3-icon-rune-small .rune-e { background-position: -64px 0; }
	.d3-icon-rune-small .rune-none { background-position: -80px 0; }

/* colors */

	/* general */
	.d3-color-default, .d3-color-default a { color: #fff; }
	.d3-color-blue, .d3-color-blue a { color: #6969ff; }
	.d3-color-gray, .d3-color-gray a { color: #909090; }
	.d3-color-gold, .d3-color-gold a { color: #c7b377; }
	.d3-color-green, .d3-color-green a { color: #00ff00; }
	.d3-color-orange, .d3-color-orange a { color: #bf642f; }
	.d3-color-purple, .d3-color-purple a { color: #a335ee; }
	.d3-color-red, .d3-color-red a { color: #ff0000; }
	.d3-color-white, .d3-color-white a { color: #fff; }
	.d3-color-yellow, .d3-color-yellow a { color: #ffff00; }

	a.d3-color-blue:hover, .d3-color-blue a:hover, a:hover .d3-color-blue,
	a.d3-color-gray:hover, .d3-color-gray a:hover, a:hover .d3-color-gray,
	a.d3-color-gold:hover, .d3-color-gold a:hover, a:hover .d3-color-gold,
	a.d3-color-green:hover, .d3-color-green a:hover, a:hover .d3-color-green,
	a.d3-color-orange:hover, .d3-color-orange a:hover, a:hover .d3-color-orange,
	a.d3-color-purple:hover, .d3-color-purple a:hover, a:hover .d3-color-purple,
	a.d3-color-red:hover, .d3-color-red a:hover, a:hover .d3-color-red,
	a.d3-color-white:hover, .d3-color-white a:hover, a:hover .d3-color-white,
	a.d3-color-yellow:hover, .d3-color-yellow a:hover a:hover .d3-color-yellow { color: #fff }

	/* runes */
	.d3-color-rune { color: #F3E6D0 }
	.d3-color-rune-a { color: #e52817 }
	.d3-color-rune-b { color: #6e7ee5 }
	.d3-color-rune-c { color: #948b91 }
	.d3-color-rune-d { color: #fa8b14 }
	.d3-color-rune-e { color: #f7e9b7 }

.d3-debug { display: none; }
.tooltip-icon-bullet { display: inline-block; width: 8px; height: 8px; margin-right: 2px; vertical-align: middle; background: url("http://us.battle.net/d3/static/images/icons/bullet.gif") no-repeat; }
		</style>
	</head>
	<body>
		<?php if ( is_object($itemModel) ): ?>
		<div class="json-data"><?= $itemModel; ?></div>
		<div class="item tool-tip">
			<h3 class="header d3-color-<?= $itemModel->displayColor; ?>"><?= $itemModel->name; ?></h3>
			<div class="icon <?= $itemModel->displayColor; ?> inline-block">
				<img src="http://media.blizzard.com/d3/icons/items/large/<?= $itemModel->icon; ?>.png" alt="<?= $itemModel->name; ?>" />
			</div>
			<?= $itemModel->requiredLevel; ?>
			<?= $itemModel->itemLevel; ?>
		</div>
		<div class="tooltip">
			<div class="tooltip-content">
				<div class="d3-tooltip d3-tooltip-item">
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