
		<!-- div class="item-tool-tip item hide">
			{{if attributes}}
			<ul class="properties blue">
				{{loop attributes}}
				<li class="effect">{{formatAttribute( $value, "value" )}}</li>
				{{/loop attributes}}
			</ul>
			{{\if attributes}}
			{{ if ( isArray($itemModel->gems) ): }}
			<ul class="list gems">
				<li class="full-socket d3-color-{{ $itemModel->gems[0]['item']['displayColor'] }}">
					<img class="gem" src="http://media.blizzard.com/d3/icons/items/small/{{ $itemModel->gems[0]['item']['icon'] }}.png">
					{{ $itemModel->gems[0]['attributes'][0] }}
				</li>
			</ul>
			{{ endif; }}
		</div -->
		<div class="item tool-tip hide">
			<h3 class="name">{{name}}</h3>
			<span class="close white">Close</span>
			<div class="effect-bg">
				<div class="icon inline-block top" data-hash="" data-dbid="" data-type="{{slot}}">
					<img class="gradient icon-item-inner icon-item-default"
						src="//media.blizzard.com/d3/icons/items/large/shoulders_204_demonhunter_male.png"
						alt="{{name}}" />
				</div>
				<div class="inline-block top details">
					<div class="type-name inline-block class">
						<select readonly="readonly" name="class">
							<option class="white"></option>
							<option class="blue">Magic</option>
							<option class="yellow">Rare</option>
							<option class="orange">Legendary</option>
							<option class="green">Set</option>
						</select>
					</div>
					<div class="type-name inline-block slot">{{slot}}</div>
					<div class="armor">
						<div class="big value armor"><input type="text" name="armor" value="{{armor-points}}" /></div>
						<div class="class">Armor</div>
					</div>
					<div class="weapon">
						<div class="big value weapn">{{hit-points}}</div>
						<div class="damage"><span class="value"></span> Damage</div>
						<div class="small"><span class="value"></span> Attacks per Second</div>
					</div>
				</div>
			</div>
			<ul class="effects properties"></ul>
			<ul class="list set">
			<!--
			{{ if ( isset($itemModel->set) && isArray($itemModel->set) ): }}
				<li class="name d3-color-green">{{ $itemModel->set['name'] }}</li>
				{{ foreach ( $itemModel->set['items'] as $key => $value ): }}
				<li class="piece">{{ $value['name'] }}</li>
				{{ endforeach; }}
				{{ foreach ( $itemModel->set['ranks'] as $key => $value ): }}
				<li class="rank">({{ $value['required'] }}) Set:</li>
				{{ if ( isArray($value['attributes']) ): }}
				{{ foreach ( $value['attributes'] as $key => $value ): }}
				<li class="piece">{{ formatAttribute( $value, "value" ); }}</li>
				{{ endforeach; }}
				{{ endif; }}
				{{ endforeach; }}
			{{ endif; }}
			-->
			</ul>
			<div class="level"><span class="label">Item Level</span><input class="input value" name="level" readonly="readonly" type="text" value="{{level}}" /></div>
			<div class="level-required hide">
				<span class="label">Requires level</span><input class="input value" name="required-level" readonly="readonly"  type="text" value="{{required-level}}" /></span>
			</div>
			<input type="submit" value="save" />
		</div>