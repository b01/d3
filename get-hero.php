<?php namespace D3;

	$battleNetUrlSafeId = getStr( "battleNetId" );
	$heroId = getStr( "heroId" );
	$cache = ( bool )getStr( "cache" );
	$items = NULL;
	$hero = NULL;
	$heroModel = NULL;
	$heroItems = [];
	if ( isString($battleNetUrlSafeId) && isString($heroId) )
	{
		// Check if the cache has expired for the hero JSON.
		$loadFromBattleNet = sessionTimeExpired( "heroTime", D3_CACHE_LIMIT, $cache, $timeElapsed );
		$timeLeft = D3_CACHE_LIMIT - $timeElapsed;
		$battleNetId = str_replace( '-', '#', $battleNetUrlSafeId );
		$battleNetDqi = new BattleNet_Dqi( $battleNetId );
		$sql = new BattleNet_Sql( DSN, DB_USER, DB_PSWD, USER_IP_ADDRESS );
		$hero = new BattleNet_Hero( $heroId, $battleNetDqi, $sql, $loadFromBattleNet );
		$heroModel = new Hero( $hero->json() );
		$items = $hero->items();

?><!DOCTYPE html>
<html>
	<head>
		<title>Hero <?= $heroModel->name ?></title>
		<meta name="charset" content="utf-8" />
		<meta name="author" content="Khalifah Shabazz" />
		<link rel="stylesheet" type="text/css" href="/css/smoothness/jquery-ui-1.10.2.custom.min.css" />
		<link rel="stylesheet" type="text/css" href="/css/site.css" />
		<link rel="stylesheet" type="text/css" href="/css/item.css" />
		<link rel="stylesheet" type="text/css" href="/css/hero.css" />
		<script type="text/javascript" src="/js/jquery-2.0.0.min.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-1.10.2.custom.min.js"></script>
		<script type="text/javascript" src="/js/jquery.form.js"></script>
		<script type="text/javascript" src="/js/jquery.ui.toggleList.js"></script>
		<script type="text/javascript" src="/js/hero.js"></script>
	</head>
	<body class="hero-page">
		<div class=\"time-elapsed\">Seconds left till cache expires <?= $timeLeft ?></div>
		<form action="/get-profile.php" method="post">
			<input class="input" type="hidden" name="battleNetId" value="<?= $battleNetId ?>" />
			<input type="submit" value="Back to Heroes" />
		</form>
		<?php if ( isArray($items) ): ?>
		<div class="inline-block section one">
			<div class="hero">
				<?php foreach ( $items as $key => $item ):
					$hash = $item[ 'tooltipParams' ];
					$d3Item = new BattleNet_Item( str_replace("item/", '', $hash), "hash", $battleNetDqi, $sql );
					$itemModel = new Item( $d3Item->json() );
					$heroItems[ $key ] = $itemModel;
					$heroJson[ $key ] = substr( $itemModel->tooltipParams, 5 );
					$heroModel->getItemModels();
				?>
					<a class="item-slot <?= $key . translateSlotName( $key ) ?>" href="/get-item.php?<?= "battleNetId=" . $battleNetUrlSafeId . '&' . str_replace( '/', "Hash=", $hash ) ?>&extra=0&showClose=1" data-slot="<?= $key ?>">
						<div class="icon <?= $itemModel->displayColor; ?> inline-block top" data-hash="<?= substr( $hash, 5 ); ?>" data-type="<?= getItemSlot( $itemModel->type['id'] ) ?>">
							<img class="gradient" src="/media/images/icons/items/large/<?= $item['icon'] ?>.png" alt="<?= $key ?>" />
						<?php if ( isset($itemModel->gems) ): ?>
							<?php foreach ( $itemModel->gems as $gem ): ?>
							<?php if ( array_key_exists('item', $gem) && array_key_exists('icon', $gem['item']) ): ?>
							<div class="socket inline-block">
								<img class="gem" src="http://media.blizzard.com/d3/icons/items/small/<?= $gem[ 'item' ][ 'icon' ] ?>.png" />
							</div>
							<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
						</div>
						<div class="id"><?= $item['id'] ?></div>
						<!-- img src="http://media.blizzard.com/d3/icons/items/small/dye_10_demonhunter_male.png" / -->
					</a>
				<?php endforeach; ?>
			</div>
			<div class="skills">
				<div class="active">
				<?php foreach ( $heroModel->skills['active'] as $key => $skill ): $skill = $skill['skill']; ?>
						<img src="http://media.blizzard.com/d3/icons/skills/64/<?= $skill['icon'] ?>.png" />
				<?php endforeach; ?>
				</div>
				<div class="passive">
				<?php foreach ( $heroModel->skills['passive'] as $key => $skill ): $skill = $skill['skill']; ?>
						<img src="http://media.blizzard.com/d3/icons/skills/64/<?= $skill['icon'] ?>.png" />
				<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<?php if ( isArray($heroItems) ): ?>
		<ul class="list stats inline-block">
			<?php  $calculator = new Calculator( $heroItems, $heroModel ); ?>
			<li class="stat">
				<span class="label"><span class="toggle inline-block">-</span> Attack Speed</span>: <span class="nuetral"><?= $calculator->attackSpeed(); ?></span>
				<ul class="expandable">
					<?= output( "<li><span class=\"label\">%s</span>: %s%%</li>", $calculator->attackSpeedData() ) ?>
				</ul>
			</li>
			<li class="stat">
				<span class="label"><span class="toggle inline-block">-</span> Base Weapon Damage</span>: <span class="nuetral"><?= $calculator->baseWeaponDamage(); ?></span>
				<ul class="expandable">
					<?= output( "<li><span class=\"label\">%s</span>: %s</li>", $calculator->baseWeaponDamageData() ) ?>
				</ul>
			</li>
			<li class="stat">
				<span class="label"><span class="toggle inline-block">-</span> Critical Hit Chance</span>: <span class="nuetral"><?= $calculator->criticalHitChance(); ?>%</span>
				<ul class="expandable">
					<?= output( "<li><span class=\"label\">%s</span>: %s</li>", $calculator->criticalHitChanceData() ) ?>
				</ul>
			</li>
			<li class="stat">
				<span class="label"><span class="toggle inline-block">-</span> Critical Hit Damage</span>: <span class="nuetral"><?= $calculator->criticalHitDamage(); ?>%</span>
				<ul class="expandable">
					<?= output( "<li><span class=\"label\">%s</span>: %s</li>", $calculator->criticalHitDamageData() ) ?>
				</ul>
			</li>
			<li class="stat">
				<span class="label"><span class="toggle inline-block">-</span> Damage Per Second</span>: <span class="nuetral"><?= $calculator->damagePerSecond(); ?></span>
				<ul class="expandable">
					<?= output( "<li><span class=\"label\">%s</span>: %s</li>", $calculator->damagePerSecondData() ) ?>
				</ul>
			</li>
			<li class="stat">
				<span class="label"><span class="toggle inline-block">-</span> Primary Attribute Damage</span>:
				<span class="nuetral"><?= $calculator->primaryAttributeDamage() . ' ' . str_replace( "_Item", '', $calculator->primaryAttribute() ) ?></span>
				<ul class="expandable">
					<?= output( "<li><span class=\"label\">%s</span>: %s</li>", $calculator->primaryAttributeDamageData() ) ?>
				</ul>
			</li>
			<li class="stat">
				<span class="label"><span class="toggle inline-block">+</span> Battle.Net Calculated Stats</span>:
				<ul class="expandable">
					<?= output( "<li><span class=\"label\">%s</span>: %s</li>", $heroModel->stats ) ?>
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
				battleNetId = "<?= $battleNetUrlSafeId ?>",
				heroClass = "<?= $hero->characterClass() ?>";
		</script>
		<?php $time = microtime( TRUE ) - $_SERVER[ 'REQUEST_TIME_FLOAT' ]; ?>
		<!-- Page output in <?= $time ?> seconds -->
		<?php else: ?>
			<p>This hero does NOT have any items equipped.</p>
		<?php endif; ?>
	</body>
</html><?php } ?>