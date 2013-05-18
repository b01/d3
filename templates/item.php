<?php namespace D3;
	// Stuff that belongs in a controller.
	$showClose = getPostBool( 'showClose' );
	// This was an AJAX request, instead of PHP include.
	$itemJson = getPostStr( 'json' );
	if ( isString($itemJson) )
	{
		$item = new Item( $itemJson );
		$showClose = FALSE;
		header( 'Content-Type: text/html' );
	}
?>
		<?php if ( $item instanceof Item ): ?>
		<div class="item-tool-tip item">
			<h3 class="header smaller <?= $item->displayColor ?>">
				<?= $item->name ?>
				<?php if ( $showClose ): ?><span class="close">Close</span><?php endif; ?>
			</h3>
			<div class="effect-bg <?= $item->effects() ?>">
				<div class="icon <?= $item->displayColor ?> inline-block top"
					data-dbid="Place DB Unique ID here for forged items"
					data-hash="<?= $item->hash() ?>"
					data-type="<?= getItemSlot( $item->type['id'] ) ?>"
				>
					<img class="gradient" src="/media/images/icons/items/large/<?= $item->icon; ?>.png" alt="<?= $item->name; ?>" />
				</div>
				<div class="inline-block top">
					<div class="type-name inline-block <?= $item->displayColor; ?>"><?= $item->typeName; ?></div>
					<div class="type-name inline-block slot"><?= getItemSlot( $item->type['id'] ) ?></div>
					<?php if ( isset($item->armor) ): ?>
					<div class="big value"><?= displayRange( $item->armor ); ?></div>
					<?php endif; ?>
					<?php if ( isWeapon($item) ): ?>
					<div class="big value"><?= number_format( $item->dps['min'], 1 ); ?></div>
					<div class="damage"><span class="value"><?= displayRange( $item->damage ); ?></span> Damage</div>
					<div class="small"><span class="value">
						<?= number_format( $item->attacksPerSecond['min'], 2 ); ?></span> Attacks per Second
					</div>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( isArray($item->attributes) ): ?>
			<ul class="properties blue">
				<?php foreach ( $item->attributes as $key => $value ): ?>
				<li class="effect"><?= formatAttribute( $value, "value" ) ?></li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
			<?php if ( isArray($item->gems) ): ?>
			<ul class="list gems">
				<li class="full-socket d3-color-<?= $item->gems[0]['item']['displayColor'] ?>">
					<img class="gem" src="http://media.blizzard.com/d3/icons/items/small/<?= $item->gems[0]['item']['icon'] ?>.png">
					<?= $item->gems[0]['attributes'][0] ?>
				</li>
			</ul>
			<?php endif; ?>
			<?php if ( isset($item->set) && isArray($item->set) ): ?>
			<ul class="list set">
				<li class="name d3-color-green"><?= $item->set['name'] ?></li>
				<?php foreach ( $item->set['items'] as $key => $value ): ?>
				<li class="piece"><?= $value['name'] ?></li>
				<?php endforeach; ?>
				<?php foreach ( $item->set['ranks'] as $key => $value ): ?>
				<li class="rank">(<?= $value['required'] ?>) Set:</li>
				<?php if ( isArray($value['attributes']) ): ?>
				<?php foreach ( $value['attributes'] as $key => $value ): ?>
				<li class="piece"><?= formatAttribute( $value, "value" ); ?></li>
				<?php endforeach; ?>
				<?php endif; ?>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
			<div class="levels">
				<div class="level left required inline-block">Required Level: <span class="value"><?= $item->requiredLevel; ?></span></div>
				<div class="level right max inline-block">Item Level: <span class="value"><?= $item->itemLevel; ?></span></div>
			</div>
			<ul class="list stats">
				<li class="stat">
					<span class="label"><span class="toggle inline-block">+</span> Hash</span>
					<div class="expandable" ><textarea class="copy-box" readonly="readonly"><?= $item->hash() ?></textarea></div>
				</li>
				<li class="stat">
					<span class="label"><span class="toggle inline-block">+</span> Json</span>
					<div class="expandable" ><textarea class="copy-box" readonly="readonly"><?= $item->json() ?></textarea></div>
				</li>
			</ul>
			<?php if ( isset($item->flavorText) ): ?>
			<div class="flavor"><?= $item->flavorText; ?></div>
			<?php endif; ?>
		</div>
		<?php endif ?>
