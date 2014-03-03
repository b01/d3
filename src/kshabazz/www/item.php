<!DOCTYPE html>
<html>
	<head>
		<title>{{ item.name }}</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" rel="stylesheet" href="/css/d3.css" />
		<link rel="stylesheet" rel="stylesheet" href="/css/site.css" />
		<link rel="stylesheet" rel="stylesheet" href="/css/tooltips.css" />
		<link rel="stylesheet" rel="stylesheet" href="/css/item.css" />
	</head>
	<body>
		<div class="item-tool-tip item">
			<h3 class="header smaller {{ item.displayColor }}">
				{{ item.name }}
				<span class="close">Close</span>
			</h3>
			<div class="effect-bg {{ item.effects() }}">
				<div class="icon {{ item.displayColor }} inline-block top"
				     data-dbid="Place DB Unique ID here for forged items"
				     data-hash="{{ item.tooltipParams() }}"
				     data-type="{{ func('\\kshabazz\\d3a\\getItemSlot', item.type.id ) }}"
					>
					<img class="gradient" src="/media/images/icons/items/large/{{ item.icon }}.png" alt="{{ item.name }}" />
				</div>
				<div class="inline-block top">
					<div class="type-name inline-block {{ item.displayColor }}">{{ item.typeName }}</div>
					<div class="type-name inline-block slot">{{ func('\\kshabazz\\d3a\\getItemSlot', item.type.id) }}</div>
					{% if item.armor %}
						<div class="big value">{{ func('\\kshabazz\\d3a\\displayRange', item.armor) }}</div>
					{% endif %}
					{% if func('\\kshabazz\\d3a\\isWeapon', item) %}
						<div class="big value">{{ func('number_format', item.dps.min, 1 ) }}</div>
						<div class="damage"><span class="value">{{ func('\\kshabazz\\d3a\\displayRange', item.damage) }}</span> Damage</div>
						<div class="small"><span class="value">
						{{ func('number_format', item.attacksPerSecond.min, 2) }}</span> Attacks per Second
						</div>
					{% endif %}
				</div>
			</div>
			{% if func('isArray', item.attributes) %}
				<ul class="properties blue">
					{% autoescape false %}
					{% for key, value in item.attributes %}
						<li class="effect">{{ func('\\kshabazz\\d3a\\formatAttribute', value, "value") }}</li>
					{% endfor %}
					{% endautoescape %}
				</ul>
			{% endif %}
			{% if func('isArray', item.gems) %}
				<ul class="list gems">
					<li class="full-socket d3-color{{ item.gems[0].item.displayColor }}">
						<img class="gem" src="http://media.blizzard.com/d3/icons/items/small/{{ item.gems.0.item.icon }}.png">
						{{ item.gems.0.attributes.0 }}
					</li>
				</ul>
			{% endif %}
			{% if item.set and func('isArray', item.set) %}
				<ul class="list set">
					<li class="name d3-color-green">{{ item.set.name }}</li>
					{% for key, value in item.set.items %}
						<li class="piece">{{ value.name }}</li>
					{% endfor %}
					{% for key, value in item.set.ranks %}
						<li class="rank">({{ value.required }}) Set:</li>
						{% if func('isArray', value.attributes) %}
							{% for ley, walue in value.attributes %}
								<li class="piece">{{ func('formatAttribute', walue, "value") }}</li>
							{% endfor %}
						{% endif %}
					{% endfor %}
				</ul>
			{% endif %}
			<div class="levels">
				<div class="level left required inline-block">Required Level: <span class="value">{{ item.requiredLevel }}</span></div>
				<div class="level right max inline-block">Item Level: <span class="value">{{ item.itemLevel }}</span></div>
			</div>
			<ul class="list stats">
				<li class="stat">
					<span class="label"><span class="toggle inline-block">+</span> Hash</span>
					<div class="expandable" ><textarea class="copy-box" readonly="readonly">{{ item.tooltipParams }}</textarea></div>
				</li>
				<li class="stat">
					<span class="label"><span class="toggle inline-block">+</span> Json</span>
					<div class="expandable" ><textarea class="copy-box" readonly="readonly">{{ item.json }}</textarea></div>
				</li>
			</ul>
			{% if item.flavorText %}
				<div class="flavor">{{ item.flavorText }}</div>
			{% endif %}
		</div>
	</body>
</html>