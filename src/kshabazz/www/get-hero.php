<!DOCTYPE html>
<html>
	<head>
		<title>{{ pageTitle }} - Hero {{ name }}</title>
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
			<div class="dead-{{dead}}">{{ deadText }}</div>
			<div class="progress">{{ progress }}</div>
			<form action="/get-profile.php" method="post">
				<input class="input" type="hidden" name="battleNetId" value="{{battleNetId}}" />
				<input type="submit" value="Back to Heroes" />
				<span class="time-elapsed">{{ sessionTimeLeft }}</span>
			</form>
		</div>
		<div class="inline-block section one">
			<!-- START ITEMS MODULE -->
			<div class="hero">
				{% for key, item in items %}
				<a class="item-slot {{ key }}{{ func('\\kshabazz\\d3a\\translateSlotName', key) }}" href="/item.php?battleNetId={{ battleNetUrlSafeId }}&hash={{ item.hash }}" data-slot="{{ key }}">
				{# <a class="item-slot {{ key }}{{ func('\\kshabazz\\d3a\\translateSlotName', key) }}" href="/get-item.php?battleNetId={{ battleNetUrlSafeId }}&itemHash={{ item.hash }}&extra=0&showClose=1" data-slot="{{ key }}"> #}
					<div class="icon {{ item.displayColor }} inline-block top" data-hash="{{ item.hash }}" data-type="{{ func('\\kshabazz\\d3a\\getItemSlot', key) }}">
						<img class="gradient" src="/media/images/icons/items/large/{{ item.icon }}.png" alt="{{ key }}" />
						<!-- include 'templates/gems.php' -->
					</div>
					<div class="id">{{ item.id }}</div>

				<!-- TODO: figure out how to get item dye number
					Is it on the Hero, item, or tooltip JSON.
					<img src="http://media.blizzard.com/d3/icons/items/small/dye_10_demonhunter_male.png" />
					<img src="/media/images/icons/items/small/%s.png" />
					<a
						xmlns="http://www.w3.org/1999/xhtml" href="/d3/en/item/cardinal-dye"
						class="item-dye" data-d3tooltip="item/cardinal-dye">
						<img src="http://media.blizzard.com/d3/icons/items/small/dye_05_demonhunter_male.png" />
					</a>
				-->

				</a>
				{% endfor %}
			</div>
			<!-- END ITEMS MODULE -->
			{% if func('isArray',hero.stats) %}
			<ul class="list stats inline-block">
				{% for key, stat in hero.stats %}
				<li class="stat"><span class="label">{{ key }}</span>: <span class="nuetral">{{ stat }}</span></li>
				{% endfor %}
			</ul>
			{% endif %}
			<!-- END SKILLS MODULE -->
			<!-- START SKILLS MODULE -->
			<div class="skills">
				<div class="active">
					{% set len = hero.skills['active']|length - 1 %}
					{% for i in range(0, len) %}
					{% set skill = hero.skills['active'][i]['skill'] %}
					<a class="fake-link link skill-{{ i + 1 }}" href="//us.battle.net/d3/en/class/{{ hero.class }}/active/{{ skill['slug'] }}">
						<span class="slot slot-1"></span>
						{% if skill['icon']|length > 0 %}
						<img src="http://media.blizzard.com/d3/icons/skills/42/{{ skill['icon'] }}.png" />
						{% endif %}
					</a>
					{% endfor %}
				</div>
				<div class="passive">
					{% set len = hero.skills['passive']|length - 1 %}
					{% for i in range(0, len) %}
					{% set skill = hero.skills['passive'][i]['skill'] %}
					<a class="fake-link link skill-{{ i + 1 }}" href="//us.battle.net/d3/en/class/{{ hero.class }}/passive/{{ skill['slug'] }}">
						{% if skill['icon']|length > 0 %}
						<img class="icon" src="http://media.blizzard.com/d3/icons/skills/42/{{ skill['icon'] }}.png" />
						{% endif %}
					</a>
					{% endfor %}
				</div>
			</div>
		</div>
		{% if calculator %}
		<div class="inline-block section two">
			<div>
				<div id="item-lookup"><?php $which = "form"; include 'get-url.php';?></div>
				<div id="item-lookup-result" class="inline-block"></div>
				<div id="item-place-holder" class="inline-block"></div>
			</div>
			<br />
			<ul class="calculated list stats inline-block">
				<li class="stat">
					<span class="label"><span class="toggle inline-block">-</span> Armor</span>: <span class="nuetral">{{ calculator.armor() }}</span>
					<ul class="expandable">
						{% autoescape false %}
						{{ func('\\kshabazz\\d3a\\output', '<li><span class="label">%s</span>: %s</li>', calculator.armorData()) }}
						{% endautoescape %}
					</ul>
				</li>
				<li class="stat">
					<span class="label"><span class="toggle inline-block">-</span> Attack Speed</span>: <span class="nuetral">{{ calculator.attackSpeed() }}</span>
					<ul class="expandable">
						{% autoescape false %}
						{{ func('\\kshabazz\\d3a\\output', '<li><span class="label">%s</span>: %s%%</li>', calculator.attackSpeedData()) }}
						{% endautoescape %}
					</ul>
				</li>
				<li class="stat">
					<span class="label"><span class="toggle inline-block">-</span> Base Weapon Damage</span>: <span class="nuetral">{{ calculator.baseWeaponDamage() }}</span>
					<ul class="expandable">
						{% autoescape false %}
						{{ func('\\kshabazz\\d3a\\output', '<li><span class="label">%s</span>: %s</li>', calculator.baseWeaponDamageData()) }}
						{% endautoescape %}
					</ul>
				</li>
				<li class="stat">
					<span class="label"><span class="toggle inline-block">-</span> Critical Hit Chance</span>: <span class="nuetral">{{ calculator.criticalHitChance() }}%</span>
					<ul class="expandable">
						{% autoescape false %}
						{{ func('\\kshabazz\\d3a\\output', '<li><span class="label">%s</span>: %s</li>', calculator.criticalHitChanceData()) }}
						{% endautoescape %}
					</ul>
				</li>
				<li class="stat">
					<span class="label"><span class="toggle inline-block">-</span> Critical Hit Damage</span>: <span class="nuetral">{{ calculator.criticalHitDamage() }}%</span>
					<ul class="expandable">
						{% autoescape false %}
						{{ func('\\kshabazz\\d3a\\output', '<li><span class="label">%s</span>: %s</li>', calculator.criticalHitDamageData()) }}
						{% endautoescape %}
					</ul>
				</li>
				<li class="stat">
					<span class="label"><span class="toggle inline-block">-</span> Damage Per Second</span>: <span class="nuetral">{{ calculator.damagePerSecond() }}</span>
					<ul class="expandable">
						{% autoescape false %}
						{{ func('\\kshabazz\\d3a\\output', '<li><span class="label">%s</span>: %s</li>', calculator.damagePerSecondData()) }}
						{% endautoescape %}
					</ul>
				</li>
				<li class="stat">
					<span class="label"><span class="toggle inline-block">-</span> Primary Attribute Damage</span>:
					<span class="nuetral">{{ calculator.primaryAttributeDamage() }} {{ func('str_replace', "_Item", '', calculator.primaryAttribute()) }}</span>
					<ul class="expandable">
						{% autoescape false %}
						{{ func('\\kshabazz\\d3a\\output', '<li><span class="label">%s</span>: %s</li>', calculator.primaryAttributeDamageData()) }}
						{% endautoescape %}
					</ul>
				</li>
			</ul>
			<ul class="calculated list stats inline-block">
				<li class="stat">
					<span class="label"><span class="toggle inline-block">-</span> Battle.Net Calculated Stats</span>:
					<ul class="expandable">
						{% autoescape false %}
						{{ func('\\kshabazz\\d3a\\output', '<li><span class="label">%s</span>: %s</li>', hero.stats) }}
						{% endautoescape %}
					</ul>
				</li>
			</ul>
			{% if func('isArray',calculator.debug) %}
			<div class="calculated list stats inline-block">
				<div class="debug-info">
					{% for key, line in calculator.debug %}
					<div class="debug">{{key}} = {{ line }}</div>
					{% endfor %}
				</div>
			</div>
			{% endif %}
		</div>
		{% autoescape false %}
		<script type="text/javascript">
			// Store this stuff in a cookie.
			var heroJson = {{ func('json_encode', itemHashes) }},
				battleNetId = "{{ battleNetId }}",
				heroClass = "{{ class }}";
		</script>
		{% endautoescape %}
		{% else %}
		<p>This hero does NOT have any items equipped.</p>
		{% endif %}
		{% autoescape false %}
		<div id="ajaxed-items"></div>
		<textarea name="hero-json" class="hide">{{ hero.json }}</textarea>

		{% set time = func('microtime', TRUE) - requestTime %}
		<!-- Page output in {{ time }} seconds -->
		{% endautoescape %}
	</body>
</html>