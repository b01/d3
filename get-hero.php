<?php namespace d3;
// Get the profile and store it.
require_once( "php/BattleNetDqi.php" );
require_once( "php/Hero.php" );
require_once( "php/Item.php" );
require_once( "php/Sql.php" );
require_once( "php/Tool.php" );

	$urlBattleNetId = getStr( "battleNetId" );
	$heroId = getStr( "heroId" );
	$heroId = "3955832";
	$items = NULL;
	if ( isString($urlBattleNetId) && isString($heroId) )
	{
		$battleNetId = str_replace( '-', '#', $urlBattleNetId );
		$dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=UTF-8";
		$battleNetDqi = new BattleNetDqi( $battleNetId );
		$sql = new Sql( $dsn, DB_USER, DB_PSWD, USER_IP_ADDRESS );
		$hero = new Hero( $heroId, $battleNetDqi, $sql );
		$items = $hero->getItems();
	}
?><!DOCTYPE html>
<html>
	<head>
		<title>Hero <?= "name" ?></title>
		<meta name="charset" content="utf-8" />
		<meta name="author" content="Khalifah Shabazz" />
		<script type="text/javascript" src="/js/jquery-1.8.2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="/css/site.css" />
		<link rel="stylesheet" type="text/css" href="/css/hero.css" />
		<link rel="stylesheet" type="text/css" href="/css/item.css" />
		<script type="text/javascript">
			function clickItemLink( p_event )
			{
				var $this = $( this );
				p_event.preventDefault();
				$.ajax({
					"data": this.search.substr( 1 ),
					"dataType": "html",
					"success": showItemTooltip,
					"type": "post",
					"url": $this.attr( "href" )
				});
			}
			
			function showItemTooltip( p_data )
			{
				$( "body" ).append( p_data );
			}
			jQuery( document ).ready(function ($)
			{
				$( ".item" ).each(function ()
				{
					$( this ).on( "click", clickItemLink );
				});
			});
		</script>
	</head>
	<body>
		<?php if ( isArray($items) ): ?>
		<div class="hero">
			<?php foreach ( $items as $key => $item ):
				$hash = $item[ 'tooltipParams' ];
				$d3Item = new Item( str_replace("item/", '', $hash), "hash", $battleNetDqi, $sql );
				$itemModels[ $key ] = new ItemModel( $d3Item->getRawData() );
			?>
				<a class="item <?= $key ?>" href="/get-item.php?<?= "battleNetId=" . $urlBattleNetId . '&' . str_replace( '/', "Hash=", $item['tooltipParams'] ) ?>">
					<div class="tooltipParams"><?= $item['tooltipParams'] ?></div>
					<img src="http://media.blizzard.com/d3/icons/items/large/<?= $item['icon'] ?>.png" alt="<?= $key ?>" />
					<div class="id"><?= $item['id'] ?></div>
				</a>
			<?php endforeach; ?>
		</div>
		<pre>
			<?php foreach ( $itemModels as $key => $model ): ?>
			<?= $key ?>
			<?= $model ?>
			<?php endforeach; ?>
		</pre>
		<?php endif; ?>
	</body>
</html>