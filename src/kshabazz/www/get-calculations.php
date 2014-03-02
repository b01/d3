<?php namespace kshabazz\d3a;
// Get the profile and store it.

	$urlBattleNetId = getPostStr( "battleNetId" );
	$heroItemsJson = getPostStr( "json" );
	$heroClass = getPostStr( "heroClass" );
	$calculator = NULL;
	if ( isString($urlBattleNetId) && isString($heroItemsJson) )
	{
		$battleNetId = str_replace( '-', '#', $urlBattleNetId );
		$heroItemHashes = json_decode( $heroItemsJson, TRUE );
		$heroItems = [];
		if ( isArray($heroItemHashes) )
		{
			$battleNetDqi = new BattleNet_Requestor( $battleNetId );
			$sql = new BattleNet_Sql( USER_IP_ADDRESS );
			foreach( $heroItemHashes as $slot => $itemHash )
			{
				$item = new Item( $itemHash, "hash", $battleNetDqi, $sql );
				// Init item as an object.
				if ( is_object($item) )
				{
					$heroItems[ $slot ] = new ItemModel( $item->json() );
				}
			}
		}

		if ( isArray($heroItems) )
		{
			$calculator = new Calculator( $heroItems, $heroClass );
		}
	}
?>
		<?php if ( $calculator !== NULL ): ?>
		<ul class="list stats inline-block">
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
		<?php $time = microtime( TRUE ) - $_SERVER[ 'REQUEST_TIME_FLOAT' ]; ?>
		<!-- Page output in <?= $time ?> seconds -->
		<?php endif; ?>