jQuery( document ).ready(function ($)
{
	var $selects = $( ".subs select" ), $form = $( "#item-forge" );
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
			var $this = $( this ), url, $pre;
			pEvent.preventDefault();
			url = "/get-url.php?which=build-item&type="
				+ $this.find( "[name='type']" ).val()
				+ "&class=" + $this.find( "[name='class']" ).val();
			$pre = $( ".pre" );
			getBattleNetPage( url, $pre, getItemsHtml );
		});
	}

	if ( typeof itemType === "string" && itemType.length > 0 && itemClass.length > 0 )
	{
		var url = "/get-url.php?which=build-item&type=" + itemType + "&class=" + itemClass;
		getBattleNetPage( url, $(".pre"), processItemsHtml );
	}
});

/**
* Parse Battle.Net items HTML page to produce JSON for programming.
*
*/
function getItemsHtml( pData )
{
	var item, items, json, hashKey, $select;
	json = processItemsHtml.call( this, pData );
	items = JSON.parse( json );
	$select = $( "<select></select>" );
	for ( hashKey in items )
	{
		item = items[ hashKey ];
		$select.append( "<option>" + item.name + "</option>" );
	}
	$( this ).append( $select );
}

/**
* Parse Battle.Net items HTML page to produce JSON for programming.
*
*/
function processItemsHtml( pData, pFlat )
{
	var jsonString = '',
		html = $.parseHTML( pData ),
		$items = $( html ).find( ".item-details" );

	if ( $items.length > 0 )
	{
		jsonString = pFlat ? parseItems( $items ) : parseItemsFlat( $items );
		$( this ).empty();
		$( this ).append( jsonString );
	}
	return jsonString;
}

/**
* Parse Battle.Net items HTML page to produce JSON for programming.
*
*/
function processItemsJson( pData )
{
	var $items = pData;
console.log( typeof pData );
console.log( pData );
	if ( $items.length > 0 )
	{
	}
}