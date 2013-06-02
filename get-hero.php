<?php namespace D3;

	$battleNetUrlSafeId = getStr( "battleNetId" );
	$id = getStr( "heroId" );
	$cache = ( bool )getStr( "cache" );
	$items = NULL;
	$heroModel = NULL;
	$hero = NULL;
	$heroItems = [];
	if ( isString($battleNetUrlSafeId) && isString($id) )
	{
		// Check if the cache has expired for the hero JSON.
		$sessionCacheInfo = getSessionExpireInfo( "heroTime", $cache );

		$battleNetId = str_replace( '-', '#', $battleNetUrlSafeId );
		$battleNetDqi = new BattleNet_Dqi( $battleNetId );
		$sql = new BattleNet_Sql( DSN, DB_USER, DB_PSWD, USER_IP_ADDRESS );
		$heroModel = new BattleNet_Hero( $id, $battleNetDqi, $sql, $sessionCacheInfo['loadFromBattleNet'] );
		$hero = new Hero( $heroModel->json() );
		$items = $heroModel->items();
		$hardcore = ( $hero->hardcore ) ? 'Hardcore' : '';
		$deadText = '';
		if ( $hero->dead )
		{
			$deadText = "This {$hardcore} hero fell on " . date( 'm/d/Y', $hero->{'last-updated'} ) . ' :(';
		}

?><!DOCTYPE html>
<html>
	<head>
		<title>Hero <?= $hero->name ?></title>
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
		<script type="text/javascript" src="//us.battle.net/d3/static/js/tooltips.js"></script>
	</head>
	<body class="hero-page">
		<div class="info">
			<div class="dead-<?= $hero->dead ?>"><?= $deadText ?></div>
			<div class="progress"><?= getProgress( $hero->progress ) ?></div>
			<div class="time-elapsed"><?= displaySessionTimer( $sessionCacheInfo['timeLeft'] ) ?></div>
		</div>
		<form action="/get-profile.php" method="post">
			<input class="input" type="hidden" name="battleNetId" value="<?= $battleNetId ?>" />
			<input type="submit" value="Back to Heroes" />
		</form>
		<?php if ( isArray($items) ): ?>
		<div class="inline-block section one">
			<div class="hero">
				<?php foreach ( $items as $key => $itemData ):
					$hash = $itemData[ 'tooltipParams' ];
					$d3Item = new BattleNet_Item( str_replace("item/", '', $hash), "hash", $battleNetDqi, $sql );
					$item = new Item( $d3Item->json() );
					$heroItems[ $key ] = $item;
					$heroJson[ $key ] = substr( $item->tooltipParams, 5 );
					// $heroItems = $hero->getItemModels();
				?>
					<a class="item-slot <?= $key . translateSlotName( $key ) ?>" href="/get-item.php?<?= "battleNetId=" . $battleNetUrlSafeId . '&' . str_replace( '/', "Hash=", $hash ) ?>&extra=0&showClose=1" data-slot="<?= $key ?>">
						<div class="icon <?= $item->displayColor; ?> inline-block top" data-hash="<?= substr( $hash, 5 ); ?>" data-type="<?= getItemSlot( $item->type['id'] ) ?>">
							<img class="gradient" src="/media/images/icons/items/large/<?= $itemData['icon'] ?>.png" alt="<?= $key ?>" />
							<?php require( 'templates/gems.php' ); ?>
						</div>
						<div class="id"><?= $itemData['id'] ?></div>
						<!-- img src="http://media.blizzard.com/d3/icons/items/small/dye_10_demonhunter_male.png" / -->
						<!-- img src="/media/images/icons/items/small/< $itemData['dye'] >.png" / -->
					</a>
				<?php endforeach; ?>
			</div>
			<div class="skills">
				<div class="active">
				<?php foreach ( $hero->skills['active'] as $key => $skill ): $skill = $skill['skill']; ?>
					<a class="fake-link" href="//us.battle.net/d3/en/class/<?= $hero->class; ?>/active/<?= $skill['slug']; ?>">
						<img src="http://media.blizzard.com/d3/icons/skills/64/<?= $skill['icon'] ?>.png" />
					</a>
				<?php endforeach; ?>
				</div>
				<div class="passive">
				<?php foreach ( $hero->skills['passive'] as $key => $skill ): $skill = $skill['skill']; ?>
					<a class="fake-link" href="//us.battle.net/d3/en/class/<?= $hero->class; ?>/passive/<?= $skill['slug']; ?>">
						<img src="http://media.blizzard.com/d3/icons/skills/64/<?= $skill['icon'] ?>.png" />
					</a>
				<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<div class="inline-block section two">
			<?php if ( isArray($heroItems) ): ?>
			<div>
				<div id="item-lookup"><?php $which = "form"; include 'get-url.php';?></div>
				<div id="item-lookup-result" class="inline-block"></div>
				<div id="item-place-holder" class="inline-block"></div>
			</div><br/>
			<ul class="calculated list stats inline-block">
				<?php  $calculator = new Calculator( $heroItems, $hero ); ?>
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
						<?= output( "<li><span class=\"label\">%s</span>: %s</li>", $hero->stats ) ?>
					</ul>
				</li>
			</ul>
			<ul class="calculated list stats inline-block">
				<li class="stat">
					<span class="label"><span class="toggle inline-block">-</span> Battle.Net Calculated Stats</span>:
					<ul class="expandable">
						<?= output( "<li><span class=\"label\">%s</span>: %s</li>", $hero->stats ) ?>
					</ul>
				</li>
			</ul>
		</div>
		<script type="text/javascript">
			// Store this stuff in a cookie.
			var heroJson = <?= json_encode( $heroJson ) ?>,
				battleNetId = "<?= $battleNetUrlSafeId ?>",
				heroClass = "<?= $hero->class; ?>";
		</script>
		<?php $time = microtime( TRUE ) - $_SERVER[ 'REQUEST_TIME_FLOAT' ]; ?>
		<!-- Page output in <?= $time ?> seconds -->
		<?php else: ?>
			<p>This hero does NOT have any items equipped.</p>
		<?php endif; ?>
		<div id="ajaxed-items"></div>
		<textarea name="hero-json" class="hide"><?= $heroModel->json() ?></textarea>
	</body>
</html><?php } ?>