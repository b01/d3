<!DOCTYPE html>
<html>
	<head>
		<title>Build an Item</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" rel="stylesheet" href="/css/d3.css" />
		<link type="text/css" rel="stylesheet" href="/css/site.css" />
		<link rel="stylesheet" rel="stylesheet" href="/css/tooltips.css" />
		<link rel="stylesheet" rel="stylesheet" href="/css/item.css" />
		<script type="text/javascript" src="/js/ie-version-check.js"></script>
		<script type="text/javascript" src="/js/jquery-2.0.0.min.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-1.10.2.custom.min.js"></script>
		<script type="text/javascript" src="/js/skill-script.js"></script>
		<script type="text/javascript" src="/js/battle-net-url-parsers.js"></script>
		<script type="text/javascript" src="/js/item-forge.js"></script>
	</head>
	<body>
		<form id="item-forge" action="get-url.php" method="get">
			<fieldset>
				<input type="hidden" name="which" value="build-item" />
				<select name="type">
					<option>ARMOR</option>
					<option>WEAPONS</option>
					<!-- option>OTHER</option -->
				</select>
			</fieldset>
			<fieldset class="subs">
				<label for="class">Item</label>
				<select id="ARMOR" name="class">
					<optgroup label="head">
						<option value="helm">Helms</option>
						<option value="spirit-stone">Spirit Stones ( Monk )</option>
						<option value="voodoo-mask">Voodoo Masks ( Witch Doctor )</option>
						<option value="wizard-hat">Wizard Hats ( Wizzard )</option>
					</optgroup>
					<optgroup label="shoulders">
						<option value="pauldrons">Pauldrons</option>
					</optgroup>
					<optgroup label="torso">
						<option value="chest-armor">Chest Armor
						<option value="cloak">Cloaks ( Demon Hunter )</option>
					</optgroup>
					<optgroup label="Wrists">
						<option value="bracers">Bracers</option>
					</optgroup>
					<optgroup label="Hands">
						<option value="gloves">Gloves</option>
					</optgroup>
					<optgroup label="Waist">
						<option value="belt">Belts</option>
						<option value="mighty-belt">Mighty Belts ( Barbarian )</option>
					</optgroup>
					<optgroup label="Legs">
						<option value="pants">Pants</option>
					</optgroup>
					<optgroup label="Feet">
						<option value="boots">Boots</option>
					</optgroup>
					<optgroup label="Jewelry">
						<option value="amulet">Amulets</option>
						<option value="ring">Rings</option>
					</optgroup>
					<optgroup label="Off-Hand">
						<option value="shield">Shields</option>
						<option value="mojo">Mojos ( Witch Doctor )</option>
						<option value="orb">Orbs Wiz</option>
						<option value="quiver">Quivers ( Demon Hunter )</option>
					</optgroup>
					<optgroup label="Follower Special">
						<option value="enchantress-focus">Enchantress Focuses</option>
						<option value="scoundrel-token">Scoundrel Tokens</option>
						<option value="templar-relic">Templar Relics</option>
					</optgroup>
				</select>
				<select id="WEAPONS" name="class" class="hide" disabled="disabled">
					<optgroup label="one-handed">
						<option value="axe-1h">Axes</option>
						<option value="dagger">Daggers</option>
						<option value="mace-1h">Maces</option>
						<option value="spear">Spears</option>
						<option value="sword-1h">Swords</option>
						<option value="ceremonial-knife">Ceremonial Knives ( Witch Doctor )</option>
						<option value="fist-weapon">Fist Weapons ( Monk )</option>
						<option value="mighty-weapon-1h">Mighty Weapons ( Barbarian )</option>
					</optgroup>
					<optgroup label="two-handed">
						<option value="axe-2h">Axes</option>
						<option value="mace-2h">Maces</option>
						<option value="polearm">Polearms</option>
						<option value="staff">Staves</option>
						<option value="sword-2h">Swords</option>
						<option value="daibo">Daibo ( Monk )</option>
						<option value="mighty-weapon-2h">Mighty Weapons ( Barbarian )</option>
					</optgroup>
					<optgroup label="ranged">
						<option value="bow">Bows</option>
						<option value="crossbow">Crossbows</option>
						<option value="hand-crossbow">Hand Crossbows ( Demon Hunter )</option>
						<option value="wand">Wands Wiz</option>
					</optgroup>
				</select>
				<!-- You won't need to customize other itesm, so keep them out of the mix -->
				<!-- select id="OTHER" name="class" disabled="disabled">
					<optgroup label="Consumables">
						<option value="potion">Potions</option>
					</optgroup>
					<optgroup label="Crafting">
						<option value="crafting-material">Crafting Materials</option>
						<option value="blacksmith-plan">Blacksmith Plans</option>
						<option value="jeweler-design">Jeweler Designs</option>
						<option value="page-of-training">Pages of Training</option>
						<option value="dye">Dyes</option>
						<option value="gem">Gems</option>
						<option value="misc">Miscellaneous</option>
					</optgroup>
				</select -->
			</fieldset>
			<input type="submit" value="submit" />
		</form>
		<pre class="pre"></pre>


		<div class="item-tool-tip item hide">

			<h3 class="header smaller {{displayColor}}">{{itemName}}</h3>

			<div class="effect-bg {{effects}}">
				<div class="icon {{displayColor}} inline-block top" data-hash="{{Hash}}" data-type="{{slot}}">
					<img class="gradient" src="/media/images/icons/items/large/{{icon}}.png" alt="{{name}}" />
				</div>
				<div class="inline-block top">
					<div class="type-name inline-block {{displayColor}}">{{type}}</div>
					<div class="type-name inline-block slot">{{slot}}</div>
					{{if armor}}
					<div class="big value armor">{{armorValue}}</div>
					{{/if armor}}
					{{if weapon}}
					<div class="big value weapn">{{ number_format( $itemModel->dps['min'], 1 ); }}</div>
					<div class="damage"><span class="value">{{ displayRange( $itemModel->damage ); }}</span> Damage</div>
					<div class="small"><span class="value">{{ number_format( $itemModel->attacksPerSecond['min'], 2 ); }}</span> Attacks per Second</div>
					{{/if weapon}}
				</div>
			</div>
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
			{{ if ( isset($itemModel->set) && isArray($itemModel->set) ): }}
			<ul class="list set">
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
			</ul>
			{{ endif; }}
			<div class="levels">
				<div class="level left required inline-block">Required Level: <span class="value">{{ $itemModel->requiredLevel; }}</span></div>
				<div class="level right max inline-block">Item Level: <span class="value">{{ $itemModel->itemLevel; }}</span></div>
			</div>
			<ul class="list stats inline-block">
				<li class="stat">
					<span class="label"><span class="toggle inline-block">+</span> Hash</span>
					<div class="expandable" ><textarea class="copy-box" readonly="readonly">{{ $itemHash }}</textarea></div>
				</li>
				<li class="stat">
					<span class="label"><span class="toggle inline-block">+</span> Json</span>
					<div class="expandable" ><textarea class="copy-box" readonly="readonly">{{ $item->json() }}</textarea></div>
				</li>
			</ul>
			{{ if ( isset($itemModel->flavorText) ): }}
			<div class="flavor">{{ $itemModel->flavorText; }}</div>
			{{ endif; }}
		</div>
	</body>
</html>