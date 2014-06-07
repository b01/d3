
/**
* Show an item Tool-top.
* @return bool
*/
function getItemTooltip( pEvent )
{
	var $this = $( this ),
		uid = pEvent.data.uid,
		selector = "[data-hash='" + uid + "'], [data-dbid='" + uid + "']",
		$item;

	pEvent.preventDefault();
	// See if item has already been loaded.
	$item = $( "#ajaxed-items" ).find( selector );
	if ( $item.length > 0 )
	{
		// find the parent tool-tip of the item and show it.
		showItemTooltip( $item.closest(".item-tool-tip"), pEvent );
	}
	// Get remotely.
	else
	{
		console.log(this);
		console.log(this.search.substr( 1 ));
		console.log($this.attr( "href" ));
		$.ajax({
			"data": this.search.substr( 1 ),
			"dataType": "html",
			"type": "post",
			"url": $this.attr( "href" )
		}).done(function ( pData )
			{
				var $item = $( $.parseHTML($.trim(pData)) );
				// Style a few things.
				$( "#ajaxed-items" ).append( $item );
				// Add close button functionality.
				$item.find( ".close" ).on( "click.d3", {"$toolTip": $item}, function (pEvent)
				{
					pEvent.data.$toolTip.fadeOut();
				});
				// Add list toggle functionality.
				$item.find( ".list" ).toggleList();
				$item.draggable();
				showItemTooltip( $item, pEvent );
			});
	}

	return false;
}

function showItemTooltip( $pItem, pEvent )
{
	if ( typeof $pItem === "object" )
	{
		// Show the item.
		$pItem.css({
			"display": "block",
			"left": pEvent.pageX + "px",
			"position": "absolute",
			"opacity": 1,
			"top": pEvent.pageY + "px"
		});
	}
}

function postTo( p_url, pData, p_function )
{
	$.ajax({
		"data": pData,
		"dataType": "html",
		"success": p_function,
		"type": "post",
		"url": p_url
	});
}

function updateReplaced( pData )
{
	$( "#item-place-holder" ).html( pData );
}

function updateCalculations()
{
	$.ajax({
		"data": "battleNetId=" + window[ "battleNetId" ] + "&heroClass=" + window["heroClass"] + "&json=" + JSON.stringify( window["heroJson"] ),
		"dataType": "html",
		"success": function ( pData )
		{
			var $newStats = $( $.parseHTML(pData) );
			$newStats.statsToggle();
			$( ".list.stats" ).replaceWith( $newStats );
		},
		"type": "post",
		"url": "/get-calculations.php"
	});
}

function centerGems()
{
	$( ".sockets" ).each(function ()
	{
		var $this = $( this );
		$this.position({ of: $this.parent() });
	});
}

// Wait until document.readySate status is complete.
jQuery( window ).load(function ()
{
	centerGems();
	// Load an items details via HTTP request.
	$( ".item-slot" ).each(function ()
	{
		var $this = $( this ), $icon = $this.find( ".icon" ),
			uid = $icon.data( "hash" ) || $icon.data( "dbid" );
		$this.on( "click.d3a", {"uid": uid}, getItemTooltip );
	});
});

// Run code interactively.
jQuery( document ).ready(function ($)
{
	// Turn the form in an Ajax posting form.
	var $itemForm = $( "#battlenet-get-item" );
	$itemForm.ajaxForm({ "success": getItemFormSuccess })
			 .find( "[name='extra']" ).parent().remove();
	$itemForm.find( "[name='battleNetId']" ).attr( "readonly", "readonly" );

	// Toggle stat details.
	$( ".list" ).toggleList();

	$( ".item-slot" ).droppable({
		"activeClass": "ui-state-hover",
		"hoverClass": "ui-state-active",
		"accept": function ( p_draggable )
		{
			var slot = p_draggable.data( "type" );
			return $( this ).hasClass( slot );
		},
		"drop": function ( pEvent, p_ui )
		{
			var $this = $( this ),
				oldHash = $this.attr( "href" ),
				newHash,
				slot = $this.data( "slot"), $oldItem;
			// swap the two items.
			$oldItem = $this.find(".icon");
			if ( $oldItem.length > 0 )
			{
				$oldItem.replaceWith( p_ui.draggable );
				p_ui.draggable.css({ "left": 0, "top": 0 });
				newHash = oldHash.replace( /.+\?(.+itemHash=)[^&]+(.*)$/, "$1" + $oldItem.data("hash") + "$2" );
				postTo( "/get-item.php", newHash, updateReplaced );
				window[ "heroJson" ][ slot ] = p_ui.draggable.data( "hash" );
				updateCalculations();
			}
		}
	});
	// Select all text on mouse up.
	$( document ).on( "mouseup.d3", ".copy-box", function ()
	{
		$( this ).select();
	});
	var $itemForgeLink = $( "#item-forge" ),
		href = $itemForgeLink.attr( "href" );
	// Add the hero class to the item forge link.
	$itemForgeLink.attr( "href", href + "?class=" + window.heroClass );
});

/**
* Allow the item form to submit via jqXHR.
*/
function getItemFormSuccess( pResponseText )
{
	var $itemToolTip = $( $.parseHTML(pResponseText) );
	if ( $itemToolTip.find( ".icon" ).length > 0 )
	{
		$( "#item-lookup-result" ).html( $itemToolTip );
		$itemToolTip.find( ".icon" ).draggable({ "revert": "invalid", "helper": "clone" });
		$itemToolTip.find( ".list" ).toggleList();
	}
	else
	{
		$( "#item-lookup-result" ).text( "No item found" );
	}
}