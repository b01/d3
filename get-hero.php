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
		$heroModel = new HeroModel( $hero->getRawData() );
		$items = $hero->getItems();
	
?><!DOCTYPE html>
<html>
	<head>
		<title>Hero <?= $heroModel->name ?></title>
		<meta name="charset" content="utf-8" />
		<meta name="author" content="Khalifah Shabazz" />
		<link rel="stylesheet" type="text/css" href="/css/smoothness/jquery-ui-1.9.2.custom.min.css" />
		<link rel="stylesheet" type="text/css" href="/css/site.css" />
		<link rel="stylesheet" type="text/css" href="/css/hero.css" />
		<link rel="stylesheet" type="text/css" href="/css/item.css" />
		<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-1.9.2.custom.js"></script>
		<script type="text/javascript" src="/js/jquery.form.js"></script>
		<script type="text/javascript" src="/js/hero.js"></script>
	</head>
	<body class="hero-page">
		<?php if ( isArray($items) ): ?>
		<div class="hero inline-block">
			<?php foreach ( $items as $key => $item ):
				$hash = $item[ 'tooltipParams' ];
				$d3Item = new Item( str_replace("item/", '', $hash), "hash", $battleNetDqi, $sql );
				$itemModel = new ItemModel( $d3Item->json() );
				$heroItems[ $key ] = $itemModel;
				$heroJson[ $key ] = substr( $itemModel->tooltipParams, 5 );
			?>
				<a class="item-slot <?= $key . translateSlotName( $key ) ?>" href="/get-item.php?<?= "battleNetId=" . $urlBattleNetId . '&' . str_replace( '/', "Hash=", $hash ) ?>&extra=0" data-slot="<?= $key ?>">
					<div class="icon <?= $itemModel->displayColor; ?> inline-block top" data-hash="<?= substr( $hash, 5 ); ?>" data-type="<?= getItemSlot( $itemModel->type['id'] ) ?>">
						<img class="gradient" src="/media/images/icons/items/large/<?= $item['icon'] ?>.png" alt="<?= $key ?>" />
					</div>
					<div class="id"><?= $item['id'] ?></div>
				</a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		<?php if ( isArray($heroItems) ): ?>
		<ul class="list stats inline-block">
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
		<div class="inline-block">
			<div id="item-lookup"></div>
			<div id="item-lookup-result" class="inline-block"></div>
			<div id="item-place-holder" class="inline-block"></div>
		</div>
		<script type="text/javascript">
			// Store this stuff in a cookie.
			var heroJson = <?= json_encode( $heroJson ) ?>,
				battleNetId = "<?= $urlBattleNetId ?>",
				heroClass = "<?= $hero->getCharacterClass() ?>";
		</script>
		<?php $time = microtime( TRUE ) - $_SERVER[ 'REQUEST_TIME_FLOAT' ]; ?>
		<!-- Page output in <?= $time ?> seconds -->
		<?php endif; ?>
	</body>
</html><?php } ?>