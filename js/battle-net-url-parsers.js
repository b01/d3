/**
* Parst item properties within item HTML from battle.Net into a JSON object.
* CAUTION: This relies on Battle.Net HTML and class naming conventions which is subject to change at anytime.
*
* @return string JSON
*/
function newParseItems( $pItems, pBeautify )
{
	var jsonString = '[',
		$items = $pItems || $( ".item-details" ),
		itemCount = $items.length - 1;

	$items.each(function (i)
	{
		var $this = $( this ),
			removeWS = /\r|\n|\t|\s{2,}/g,
			classSlot = $this.find( ".d3-item-properties .item-type span" ).text().replace( removeWS, ' ' ),
			item = {
				"class": '',
				"typeValue": $this.find( ".item-armor-weapon .value" ).text().replace( removeWS, ' ' ),
				"name": $this.find( ".subheader-3 a" ).text().replace( removeWS, ' ' ),
				"type": $this.find( ".item-armor-weapon .big + li" ).text().replace( removeWS, ' ' ).toLowerCase(),
				"level": $this.find( ".item-ilvl .value" ).text().replace( removeWS, ' ' ),
				"requiredLevel": $this.find( ".detail-level-number" ).text().replace( removeWS, ' ' ),
				"slot": '',
				"uniqueEquipped": $this.find( ".item-unique-equipped" ).text(),
				"$effects": $this.find( ".item-effects li" )
			};

			if ( /^Legendary|^Magic|^Rare|^Set/.test(classSlot) )
			{
				item.class = classSlot.split( ' ', 1 );
				item.slot = classSlot.replace( item.class, '' );
			}
			else
			{
				item.class = '';
				item.slot = classSlot;
			}
			jsonString += formatItemJson( item, ( i < itemCount ), pBeautify );
	});
	jsonString += ']';

	return jsonString;
}

function formatItemJson( pItem, pNotLastItem, formatted )
{
	var effectsArray, effectsCount,
		jsonString = '{',
		removeWS = /\r|\n|\t|\s{2,}/g,
		tabs = ( formatted ) ? '\n\t\t\t' : '',
		eTabs = ( formatted ) ? tabs + '\t' : '',
		lTab = ( formatted ) ? '\n\t\t' : '';

		jsonString += tabs + '"name": "' + pItem.name + '",'
			+ tabs + '"class": "' + pItem.class + '",'
			+ tabs + '"slot": "' + pItem.slot + '",'
			+ tabs + '"typeValue": "' + pItem.typeValue + '",'
			+ tabs + '"' + pItem.type + '": "' + pItem.typeValue + '",'
			+ tabs + '"type": "' + pItem.type + '",'
			+ tabs + '"level": "' + pItem.level + '",'
			+ tabs + '"requiredLevel": "' + pItem.requiredLevel + '",'
			+ tabs + '"uniqueEquipped": "' + pItem.uniqueEquipped + '",';
		// Appending effects
		effectsArray = tabs + '[';
		effectsCount = pItem.$effects.length - 1;
		pItem.$effects.each(function (i)
		{
			var value = $( this ).text().replace( removeWS, ' ' );
			if ( typeof value === "string" && value.length > 0 )
			{
				effectsArray += eTabs + '"' + value;
				effectsArray += ( i < effectsCount ) ? '",' : '"';
			}
		});
		effectsArray += tabs + ']';
		jsonString += tabs + '"effects": ' + effectsArray;
		jsonString +=  lTab + '}' + ( (pNotLastItem) ? ',' : '' );

		return jsonString;
}