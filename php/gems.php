
						<?php if ( isArray($item->gems) ): ?>
						<div class="sockets inline-block">
							<?php foreach ( $item->gems as $gem ): ?>
							<?php if ( array_key_exists('item', $gem) && array_key_exists('icon', $gem['item']) ): ?>
							<div class="socket inline-block">
								<img class="gem" src="http://media.blizzard.com/d3/icons/items/small/<?= $gem[ 'item' ][ 'icon' ] ?>.png" />
							</div>
							<?php endif; ?>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>
