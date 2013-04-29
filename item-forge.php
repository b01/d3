<!DOCTYPE html>
<html>
	<head>
		<title>Build an Item</title>
		<link type="text/css" rel="stylesheet" href="/css/site.css" />
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
	</body>
</html>