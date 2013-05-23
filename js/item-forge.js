/**
* Parse Battle.Net items HTML page to produce JSON for programming.
*
*/
function getItemsHtml( pData, pParams )
{
	var items, json;
	json = processItemForgeHtml.call( this, pData );
	items = JSON.parse( json );
	processItemForgeJson( items, this );
	// Save the JSON to a file for later use.
	$.ajax( "file-saver.php", {
		"dataType": "json",
		"data": { "file": pParams.itemType + '-' + pParams.itemClass, "content": json },
		"method": "POST"
	});
}

/**
* Parse Battle.Net items HTML page to produce JSON for programming.
*
*/
function processItemForgeJson( pItems, pElement )
{
	var item, items, hashKey, $select;
	items = pItems || [];
	$select = $( "<select><option>select one</option></select>" );

	for ( hashKey in items )
	{
		item = items[ hashKey ];
		$option = $( "<option>" + item.name + "</option>" );
		$option.data( "item", item );
		$option.on( "click.d3", function () { console.log(this); } );
		$select.append( $option );
	}
	$select.on( "change.d3", buildItemToolTip );
	$( pElement ).append( $select );
}

/**
* Build item tool-tip
*
*/
function buildItemToolTip()
{
	var $toolTip = $( ".item-tool-tip" ),
		effectTpl = $( "#templates #effect" ).html().trim(), $effect,
		item = $( this ).find( "option:selected" ).data( "item" ),
		i;
	// console.log( item );
	if ( typeof item === "object" )
	{
		$armor = $toolTip.find( ".armor" );
		$weapon = $toolTip.find( ".weapon" );
		// item.icon;
		// Read-only
		$toolTip.find( ".name" ).text( item.name );
		$toolTip.find( "[name='class']" ).val( item.class );
		$toolTip.find( ".slot" ).text( item.slot );
		$toolTip.find( ".level input" ).val( item.level );
		$toolTip.find( ".required-level input" ).val( item.requiredLevel );
		if ( item.type === "armor" )
		{
			$armor.removeClass( "hide" );
			$weapon.addClass( "hide" );
			// $toolTip.find( "[name='armor']" ).val( item[item.type] );
			$toolTip.find( "[name='" + item.type + "']" ).val( item.typeValue );
		}
		// Generate the effects
		if ( typeof item.effects === "object" && item.effects.length > 0 )
		{
			$effectList = $toolTip.find( ".effects" );
			for ( i = 0; i < item.effects.length; i++ )
			{
				$effect = $( effectTpl );
				effect = item.effects[ i ];
				// $effect.find( ".label" ).text( effect );
				// $effect.find( "input" ).attr( "name", "rawAttribtes[" + effect.name + "]" );
				$effect.find( "input" ).val( effect );
				$effectList.append( $effect );
			}
		}
		$toolTip.removeClass( "hide" );
		generatePsuedoBattleNetItemJson( item );
	}
}

/**
* Generate JSON similar to what Battle.Net would return for an Item.
*
* This will not have a web-hash, among other things.
*
* @param object pItem Item data used to generate the JSON.
* @return string
*/
function generatePsuedoBattleNetItemJson( pItem )
{
}

/**
* Parse Battle.Net items HTML page to produce JSON for programming.
*
*/
function processItemForgeHtml( pData, pFlat )
{
	var jsonString = '',
		html = $.parseHTML( pData ),
		$items = $( html ).find( ".item-details" );

	if ( $items.length > 0 )
	{
		// jsonString = pFlat ? parseItems( $items ) : parseItemsFlat( $items );
		jsonString = newParseItems( $items, pFlat );
	}

	return jsonString;
}

jQuery( document ).ready(function ($)
{
	var $selects, $form;

	checkForIE();
	if ( window.ie !== null && window.ie.version < 9 )
	{
		alert( "Sorry, but you'll find no love for users of IE 6-8 here!\nWe reccommend you upgrade your browser to IE 10 or greater.\n\n  And as always you can use a free browser like the latest version of Google Chrome or Firefox without having to upgrade your Windows operating system. :)" );
		document.getElementsByTagName( "body" )[ 0 ].innerHTML = "Nothing to see here IE " + window.ie.version + " client";
		return $;
	}

	$selects = $( ".subs select" ), $form = $( "#item-forge" ), $itemSelectors = $( "#ARMOR, #WEAPONS" );

	$( "select[name='type']" ).on( "change.d3", function ()
	{
		var itemType = $( this ).val();
		$selects.each(function ()
		{
			var $this = $( this );
			if ( itemType === $this.attr("id") )
			{
				this.disabled = false;
				$this.removeClass( "hide" );
			}
			else
			{
				this.disabled = true;
				$this.addClass( "hide" );
			}
		});
	});

	// $form.on( "submit.d3", function (pEvent)
	$itemSelectors.on( "change.d3", function (pEvent)
	{
		var $this = $form, url, $pre, itemType, itemClass;
		pEvent.preventDefault();
		itemType = $this.find( "[name='type']" ).val();
		itemClass = $this.find( "[name='class']" ).val();
		$pre = $( ".pre" );
		url = "media/data-files/" + itemType + '-' + itemClass + ".json";
		$.ajax( url, {
			"dataType": "json"
		}).done(function (pData)
			{
				processItemForgeJson( pData, $pre );

			}).fail(function ()
			{
				url = "/get-url.php?which=item-forge-json&type=" + itemType + "&class=" + itemClass;
				getBattleNetPage( url, $pre, function (pData)
				{
					getItemsHtml.call( $pre, pData, {"itemType": itemType, "itemClass": itemClass});
				});
			});
	});

	if ( typeof itemType === "string" && itemType.length > 0 && itemClass.length > 0 )
	{
		var url = "/get-url.php?which=build-item&type=" + itemType + "&class=" + itemClass;
		getBattleNetPage( url, $(".pre"), processItemForgeHtml );
	}
	return $;
});