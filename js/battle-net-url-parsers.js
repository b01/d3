/**
* Parse item properties within item HTML from battle.Net into a JSON object with no line-breaks or tabbing.
* CAUTION: This relies on Battle.Net HTML and class naming conventions which is subject to change at anytime.
*
* @return string JSON
*/
function parseItemsFlat( $pItems )
{
	var jsonString = '[',
		$items = $pItems || $( ".item-details" ),
		itemCount = $items.length - 1;

	$items.each(function (i)
	{
		var $this = $( this ),
			removeWS = /\r|\n|\t|\s{2,}/g,
			itemClass = $this.find( ".d3-item-properties .item-type span" ).text().replace( removeWS, ' ' ),
            armorOrDps = $this.find( ".item-armor-weapon .value" ).text().replace( removeWS, ' ' ),
			name = $this.find( ".subheader-3 a" ).text().replace( removeWS, ' ' ),
			itemType = $this.find( ".item-armor-weapon .big + li" ).text().replace( removeWS, ' ' ).toLowerCase(),
			level = $this.find( ".item-ilvl .value" ).text().replace( removeWS, ' ' ),
			$effects = $this.find( ".item-effects li" ),
			effectCount = $effects.length,
			effectsArray = '[';

			$effects.each(function (j)
			{
				var value = $( this ).text().replace( removeWS, ' ' );
				if ( typeof value === "string" && value.length > 0 )
				{
					effectsArray += '"' + value;
					effectsArray += ( j < (effectCount - 1) ) ? '", ' : '"';
				}
			}),
			effectsArray += ']';
			jsonString += '{';
			jsonString += '"name": "' + name + '",';
			jsonString += '"class": "' + itemClass + '",';
			jsonString += '"' + itemType + '": "' + armorOrDps + '",';
			jsonString += '"type": "' + itemType + '",';
			jsonString += '"level": "' + level + '",';
			jsonString += '"effects": ' + effectsArray;
			jsonString += ( i < itemCount ) ? '},' : '}';
	});
	jsonString += ']';
	return jsonString;
}

/**
* Parst item properties within item HTML from battle.Net into a JSON object.
* CAUTION: This relies on Battle.Net HTML and class naming conventions which is subject to change at anytime.
*
* @return string JSON
*/
function parseItems( $pItems )
{
	var jsonString = '[',
		$items = $pItems || $( ".item-details" ),
		itemCount = $items.length - 1;

	$items.each(function (i)
	{
		var $this = $( this ),
			removeWS = /\r|\n|\t|\s{2,}/g,
			itemClass = $this.find( ".d3-item-properties .item-type span" ).text().replace( removeWS, ' ' ),
            armorOrDps = $this.find( ".item-armor-weapon .value" ).text().replace( removeWS, ' ' ),
			name = $this.find( ".subheader-3 a" ).text().replace( /\r|\n|\t/g, ' ' ).replace( removeWS, ' ' ),
			itemType = $this.find( ".item-armor-weapon .big + li" ).text().replace( removeWS, ' ' ),
			level = $this.find( ".item-ilvl .value" ).text().replace( removeWS, ' ' ),
			$effects = $this.find( ".item-effects li" ),
			effectCount = $effects.length,
			effectsArray = '\n\t\t\t[';

			$effects.each(function (j)
			{
				var value = $( this ).text().replace( removeWS, ' ' );
				if ( typeof value === "string" && value.length > 0 )
				{
					effectsArray += '\n\t\t\t\t"' + value;
					effectsArray += ( j < (effectCount - 1) ) ? '",' : '"';
				}
			}),
			effectsArray += '\n\t\t\t]';
			jsonString += '\n\t\t{';
			jsonString += '\n\t\t\t"name": "' + name + '",';
			jsonString += '\n\t\t\t"class": "' + itemClass + '",';
			jsonString += '\n\t\t\t"' + itemType.toLowerCase() + '": "' + armorOrDps + '",';
			jsonString += '\n\t\t\t"type": "' + itemType.toLowerCase() + '",';
			jsonString += '\n\t\t\t"level": "' + level + '",';
			jsonString += '\n\t\t\t"effects": ' + effectsArray;
			jsonString += ( i < itemCount ) ? '\n\t\t},' : '\n\t\t}';
	});
	jsonString += "\n\t]";
	return jsonString;
}