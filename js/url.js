/**
* Parst item properties within item HTML from battle.Net into a JSON object.
* CAUTION: This relies on Battle.Net HTML and class naming conventions which is subject to change at anytime.
*
* @return string JSON
*/
function parseItems()
{
	var jsonString = '{\n\t"items": [',
		$items = $( ".item-details" ),
		itemCount = $items.length - 1;

	$items.each(function (i)
	{
		var $this = $( this ),
			itemClass = $this.find( ".d3-item-properties .item-type span" ).text()
            armorOrDps = $this.find( ".item-armor-weapon .value" ).text(),
			name = $this.find( ".subheader-3 a" ).text(),
			itemType = $this.find( ".item-armor-weapon .big + li" ).text(),
			level = $this.find( ".item-ilvl .value" ).text(),
			$effects = $this.find( ".item-effects li" ),
			effectCount = $effects.length,
			effectsArray = '[';

			$effects.each(function (j)
			{
				var value = $( this ).text().replace( /\r\n|\n|\t/g, '' );
				if ( typeof value === "string" && value.length > 0 )
				{
					effectsArray += '\n\t\t\t\t"' + value;
					effectsArray += ( j < (effectCount - 1) ) ? '", ' : '"';
				}
			}),
			effectsArray += '\n\t\t\t]';

			jsonString += '\n\t\t{';
			jsonString += '\n\t\t\t"name": "' + name + '",';
			jsonString += '\n\t\t\t"class": "' + itemClass + '",';
			jsonString += '\n\t\t\t"armorOrDps": "' + armorOrDps + '",';
			jsonString += '\n\t\t\t"type": "' + itemType + '",';
			jsonString += '\n\t\t\t"level": "' + level + '",';
			jsonString += '\n\t\t\t"effects": ' + effectsArray;
			jsonString += ( i < itemCount ) ? '\n\t\t},' : '\n\t\t}';
	});
	jsonString += "\n\t]\n}";
	return jsonString;
}
console.log( parseItems() );