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
		$select.append( "<option>" + item.name + "</option>" );
	}
	$( pElement ).append( $select );
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
		jsonString = pFlat ? parseItems( $items ) : parseItemsFlat( $items );
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

	$selects = $( ".subs select" ), $form = $( "#item-forge" );

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

	if ( $form.length > 0 )
	{
		$form.on( "submit.d3", function (pEvent)
		{
			var $this = $( this ), url, $pre, itemType, itemClass;
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
	}

	if ( typeof itemType === "string" && itemType.length > 0 && itemClass.length > 0 )
	{
		var url = "/get-url.php?which=build-item&type=" + itemType + "&class=" + itemClass;
		getBattleNetPage( url, $(".pre"), processItemForgeHtml );
	}
	return $;
});