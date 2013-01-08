<?php namespace d3;
// Get the profile and store it.

	$urlBattleNetId = getStr( "battleNetId" );
	$heroId = getStr( "heroId" );
	$heroId = "3955832";
	$items = NULL;
	$hero = NULL;
	$heroModel = NULL;
	$heroItems = [];
	if ( isString($urlBattleNetId) && isString($heroId) )
	{
		$battleNetId = str_replace( '-', '#', $urlBattleNetId );
		$battleNetDqi = new BattleNetDqi( $battleNetId );
		$sql = new Sql( DSN, DB_USER, DB_PSWD, USER_IP_ADDRESS );
		$hero = new Hero( $heroId, $battleNetDqi, $sql );
		$heroModel = new HeroModel( $hero->getJson() );
		$items = $hero->getItems();
	
?><!DOCTYPE html>
<html>
	<head>
		<title>Hero <?= $heroModel->name ?></title>
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
			
			function clickStatToggle( p_event )
			{
				var $toggle = p_event.data.$toggle,
					updateSign = $toggle.text() === '-' ? '+' : '-';
				p_event.data.$expandable.slideToggle( "fast", function ()
				{
					$toggle.text( updateSign );
				});
			}
			
			function showItemTooltip( p_data )
			{
				$( "body" ).append( p_data );
			}
			jQuery( document ).ready(function ($)
			{
				// Load an items details via HTTP request.
				$( ".item" ).each(function ()
				{
					$( this ).on( "click", clickItemLink );
				});
				// Toggle stat details.
				$( ".stat" ).each(function ()
				{
					var $this = $( this ),
						$expandable = $this.find( ".expandable" ),
						$label = $this.children( ".label" ),
						$toggle;
					if ( $expandable.length > 0 && $label.length > 0 )
					{
						$toggle = $label.children( ".toggle" );
						if ( $toggle.length > 0 )
						{
							// $label.toggle(function ()
							// {
								// $toggle.text( '+' );
								// $expandable.slideUp( "fast" );
							// }, function ()
							// {
								// $toggle.text( '-' );
								// $expandable.slideDown( "fast" );
							// });
							
							$label.on( "click.d3", {"$expandable": $expandable, "$toggle": $toggle}, clickStatToggle );
						}
					}
				});
			});
		</script>
	</head>
	<body>
		<?php if ( isArray($items) ): ?>
		<div class="hero inline-block">
			<?php foreach ( $items as $key => $item ):
				$hash = $item[ 'tooltipParams' ];
				$d3Item = new Item( str_replace("item/", '', $hash), "hash", $battleNetDqi, $sql );
				$heroItems[ $key ] = new ItemModel( $d3Item->getRawData() );
			?>
				<a class="item <?= $key ?>" href="/get-item.php?<?= "battleNetId=" . $urlBattleNetId . '&' . str_replace( '/', "Hash=", $item['tooltipParams'] ) ?>&extra=0">
					<div class="tooltipParams"><?= $item['tooltipParams'] ?></div>
					<img src="/media/images/icons/items/large/<?= $item['icon'] ?>.png" alt="<?= $key ?>" />
					<div class="id"><?= $item['id'] ?></div>
				</a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		<?php if ( isArray($heroItems) ): ?>
		<ul>
			<?php  $calculator = new Calculator( $heroItems, $hero->getCharacterClass() ); ?>
			<li class="stat">
				<span class="label"><span class="toggle inline-block">-</span> Weapon Damage</span>: <?= $calculator->getWeaponDamage(); ?>
				<ul class="expandable">
					<?= output( "<li><span class=\"label\">%s</span>:%s</li>", $calculator->getWeaponDamageData() ) ?>
				</ul></li>
			<li class="stat">
				<span class="label"><span class="toggle inline-block">-</span> Attack Speed</span>: <?= $calculator->attackSpeed(); ?>%
				<ul class="expandable">
					<?= output( "<li><span class=\"label\">%s</span>:%s%%</li>", $calculator->attackSpeedData() ) ?>
				</ul>
			</li>
			<li class="stat">
				<span class="label"><span class="toggle inline-block">-</span> Critical Hit Chance</span>: <?= $calculator->getCriticalHitChance(); ?>%
				<ul class="expandable">
					<?= output( "<li><span class=\"label\">%s</span>:%s%%</li>", $calculator->getCriticalHitChanceData() ) ?>
				</ul>
			</li>
			<li class="stat">
				<span class="label"><span class="toggle inline-block">-</span> Critical Hit Damage</span>: <?= $calculator->getCriticalHitDamage(); ?>%
				<ul class="expandable">
					<?= output( "<li><span class=\"label\">%s</span>:%s%%</li>", $calculator->getCriticalHitDamageData() ) ?>
				</ul>
			</li>
			<li class="stat">
				<span class="label"><span class="toggle inline-block">-</span> Primary Attribute Damage</span>: <?= $calculator->getPrimaryAttributeDamage() . ' ' . str_replace( "_Item", '', $calculator->getPrimaryAttribute() ) ?>
				<ul class="expandable">
					<?= output( "<li><span class=\"label\">%s</span>:%s</li>", $calculator->getPrimaryAttributeDamageData() ) ?>
				</ul>
			</li>
			<li class="stat">
				<span class="label"><span class="toggle inline-block">-</span> Damage Per Second</span>: <?= $calculator->getDps(); ?>
				<ul class="expandable">
					<?= output( "<li><span class=\"label\">%s</span>:%s</li>", $calculator->getDpsData() ) ?>
				</ul>
			</li>
		</ul>
		<?php endif; ?>
	</body>
</html>
<?php
	}
?>