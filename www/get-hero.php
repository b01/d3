<?php namespace kshabazz\d3a;




	if ( $model instanceof Model_GetHero )
	{
		$hero = $model->hero();
		$items = $model->getItemModels();
		$attrMapFile = $d3a->retrieve( 'attribute_map' );
		$calculator = new Calculator( $hero, $attrMapFile, $items );

		$model->getReadyToRender();

?><!DOCTYPE html>
<html>
	<head>
		<title>Hero {{name}}</title>
		<meta name="charset" content="utf-8" />
		<meta name="author" content="Khalifah K. Shabazz" />
		<meta name="description" content="View your hero’s stats, and then try out different items to see if those stats improve or not. Use the item for by pasting the D3 web-hash or ID (of forge generated item), and then drag and drop the item to the appropriate slot, your stats will automatically update. " />
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
		<div class="panel info">
			<div class="dead-{{dead}}">{{deadText}}</div>
			<div class="progress">{{progress}}</div>
				<form action="/get-profile.php" method="post">
					<input class="input" type="hidden" name="battleNetId" value="{{battleNetId}}" />
					<input type="submit" value="Back to Heroes" />
					<span class="time-elapsed">{{sessionTimeLeft}}</span>
				</form>
		</div>
		<div class="inline-block section one">
			<!-- START ITEMS MODULE -->
			<div class="hero">
			{% for key, item in items %}
				{{ item.tooltipParams }}
				<a class="item-slot {{ $key }} {{ key|translateSlotName }}" href="/get-item.php?battleNetId={{battleNetId}}&{{ item.tooltipParams|str_replace( '/', 'Hash=') }}&extra=0&showClose=1" data-slot="{{ $key }}">
						<div class="icon <?= $item->displayColor; ?> inline-block top" data-hash="<?= substr( $hash, 5 ); ?>" data-type="{{ item.type.id|getItemSlot( $item->type['id'] ) ?>">
							<img class="gradient" src="/media/images/icons/items/large/<?= $item->icon ?>.png" alt="<?= $key ?>" />
							{% include 'templates/gems.php' %}
						</div>
						<div class="id">{{ item.id }}</div>
						<!-- img src="http://media.blizzard.com/d3/icons/items/small/dye_10_demonhunter_male.png" / -->
						<!-- img src="/media/images/icons/items/small/< $item->dye >.png" / -->
				</a>
			{% endfor %}
			</div>
			<?php if ( isArray($items) ): ?>
			<div class="hero">
				<?php foreach ( $items as $key => $item ):
					$hash = $item->tooltipParams;
				?>
					<a class="item-slot <?= $key . translateSlotName( $key ) ?>" href="/get-item.php?battleNetId={{battleNetId}}<?= '&' . str_replace( '/', "Hash=", $hash ) ?>&extra=0&showClose=1" data-slot="<?= $key ?>">
						<div class="icon <?= $item->displayColor; ?> inline-block top" data-hash="<?= substr( $hash, 5 ); ?>" data-type="<?= getItemSlot( $item->type['id'] ) ?>">
							<img class="gradient" src="/media/images/icons/items/large/<?= $item->icon ?>.png" alt="<?= $key ?>" />
							<?php require( 'templates/gems.php' ); ?>
						</div>
						<div class="id"><?= $item->id ?></div>
						<!-- img src="http://media.blizzard.com/d3/icons/items/small/dye_10_demonhunter_male.png" / -->
						<!-- img src="/media/images/icons/items/small/< $item->dye >.png" / -->
					</a>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
			<!-- END ITEMS MODULE -->
			<?php $stats = $hero->stats; ?>
			<ul class="list stats inline-block">
			{% for key, stat in stats %}
				<li class="stat">
					<span class="label">{{key}}</span>: <span class="nuetral">{{stat}}</span>
				</li>
			{% endfor %}
			</ul>
			<?php $stats = $hero->stats; ?>
			<ul class="list stats inline-block">
			<?php foreach ( $stats as $key => $value ): ?>
				<li class="stat">
					<span class="label"><?= $key ?></span>: <span class="nuetral"><?= $value ?></span>
				</li>
			<?php endforeach; ?>
			</ul>
			<!-- START SKILLS MODULE -->
			<div class="skills">
				<div class="active">
				<?php for ( $i = 0, $len = count($hero->skills['active']); $i < $len; $i++ ):
					  $skill = $hero->skills['active'][$i]['skill']; ?>
					<a class="fake-link link skill-<?= $i + 1 ?>" href="//us.battle.net/d3/en/class/<?= $hero->class; ?>/active/<?= $skill['slug']; ?>">
						<span class="slot slot-1"></span>
					<?php if (!empty($skill['icon'])): ?>
						<img src="http://media.blizzard.com/d3/icons/skills/42/<?= $skill['icon'] ?>.png" />
					<?php endif; ?>
					</a>
				<?php endfor; ?>
				</div>
				<div class="passive">
				<?php for ( $i = 0, $len = count($hero->skills['passive']); $i < $len; $i++ ):
					  $skill = $hero->skills['passive'][$i]['skill']; ?>
					<a class="fake-link link skill-<?= $i + 1 ?>" href="//us.battle.net/d3/en/class/<?= $hero->class; ?>/passive/<?= $skill['slug']; ?>">
					<?php if (!empty($skill['icon'])): ?>
						<img src="http://media.blizzard.com/d3/icons/skills/64/<?= $skill['icon'] ?>.png" />
					<?php endif; ?>
					</a>
				<?php endfor; ?>
				</div>
			</div>
			<!-- END SKILLS MODULE -->
		</div>
		<div class="inline-block section two">
			<?php if ( $calculator instanceof Calculator ): ?>
			<div>
				<div id="item-lookup"><?php $which = "form"; include 'get-url.php';?></div>
				<div id="item-lookup-result" class="inline-block"></div>
				<div id="item-place-holder" class="inline-block"></div>
			</div>
			<br />
			<ul class="calculated list stats inline-block">
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
			var heroJson = {{heroItemHashes}},
				battleNetId = "{{battleNetId}}",
				heroClass = "{{class}}";
		</script>
		<?php $time = microtime( TRUE ) - $_SERVER[ 'REQUEST_TIME_FLOAT' ]; ?>
		<!-- Page output in <?= $time ?> seconds -->
		<?php else: ?>
			<p>This hero does NOT have any items equipped.</p>
		<?php endif; ?>
		<div id="ajaxed-items"></div>
		<textarea name="hero-json" class="hide">{{heroJson}}</textarea>
	</body>
</html><?php }
?>