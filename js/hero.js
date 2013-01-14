
			function clickItemLink( p_event )
			{
				var $this = $( this );
				p_event.preventDefault();
				$.ajax({
					"data": this.search.substr( 1 ),
					"dataType": "html",
					"success": function ( p_data )
					{
						var $data = $( p_data );
					
						$( "body" ).append( $data );
						$data.css({
							"position": "absolute",
							"left": p_event.pageX + "px",
							"top": p_event.pageY + "px",
						}).click(function ()
						{
							$( this ).fadeOut();
						});
					},
					"type": "post",
					"url": $this.attr( "href" )
				});
			}
			
			function clickStatToggle( p_event )
			{
				var $toggle = p_event.data.$toggle,
					updateSign = $toggle.text() === '-' ? '+' : '-';
				p_event.data.$expandable.slideToggle( "fast", function ()
				{
					$toggle.text( updateSign );
				});
			}
			
			function showItemTooltip( p_data )
			{
				$( "body" ).append( p_data );
			}
			
			function postTo( p_url, p_data, p_function )
			{
				$.ajax({
					"data": p_data,
					"dataType": "html",
					"success": p_function,
					"type": "post",
					"url": p_url
				});
			}
			
			function updateReplaced( p_data )
			{
				$( "#item-place-holder" ).html( p_data );
			}
			
			jQuery( document ).ready(function ($)
			{
				// Load an items details via HTTP request.
				$( ".item-slot" ).each(function ()
				{
					$( this ).on( "click", clickItemLink );
				});
				// Toggle stat details.
				$( ".stat" ).each(function ()
				{
					var $this = $( this ),
						$expandable = $this.find( ".expandable" ),
						$label = $this.children( ".label" ),
						$toggle;
					if ( $expandable.length > 0 && $label.length > 0 )
					{
						$toggle = $label.children( ".toggle" );
						if ( $toggle.length > 0 )
						{
							// $label.toggle(function ()
							// {
								// $toggle.text( '+' );
								// $expandable.slideUp( "fast" );
							// }, function ()
							// {
								// $toggle.text( '-' );
								// $expandable.slideDown( "fast" );
							// });
							
							$label.on( "click.d3", {"$expandable": $expandable, "$toggle": $toggle}, clickStatToggle );
						}
					}
				});
			
				$( ".item-slot" ).droppable({
					activeClass: "ui-state-hover",
					hoverClass: "ui-state-active",
					"accept": function ( p_draggable )
					{
						var slot = p_draggable.data( "type" );
						return $( this ).hasClass( slot );
					},
					"drop": function ( p_event, p_ui )
					{
						var $this = $( this ),
							oldHash = $this.attr( "href" ),
							newHash;
						// swap the two items.
						$oldItem = $this.find(".icon");
						if ( $oldItem.length > 0 )
						{
							$oldItem.replaceWith( p_ui.draggable );
							p_ui.draggable.css({ "left": 0, "top": 0 });
							newHash = oldHash.replace( /.+\?(.+itemHash=)[^&]+(.*)$/, "$1" + $oldItem.data("hash") + "$2" );
							postTo( "/get-item.php", newHash, updateReplaced );
						}
					}
				});
			});
			
			$.ajax( "/item-form.html", {
				"dataType": "html",
				"statusCode": {
					200: function ( p_data )
					{
						$form = $( p_data ).ajaxForm({
							"success": function ( p_responseText, statusText )
							{
								var $itemToolTip = $( p_responseText );
								if ( $itemToolTip.find( ".icon" ).length > 0 )
								{
									$( "#item-lookup-result" ).html( $itemToolTip );
									$itemToolTip.find( ".icon" ).draggable({ "revert": "invalid", "helper": "clone" });
								}
								else
								{
									$( "#item-lookup-result" ).text( "No item found" );
								}
							}
						});
						$( "#item-lookup" ).append( $form );
					}
				}
			});