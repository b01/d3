
			function clickItemLink( p_event )
			{
				var $this = $( this );
				p_event.preventDefault();
				$.ajax({
					"data": this.search.substr( 1 ),
					"dataType": "html",
					"success": function ( p_data )
					{
						var $data = $( $.parseHTML(p_data) );
						// Style a few things.
						$( "body" ).append( $data );
						$data.css({
							"position": "absolute",
							"left": p_event.pageX + "px",
							"top": p_event.pageY + "px",
							"opacity": 1
						});
						// Add close button functionality.
						$data.find( ".close" ).on( "click.d3", {"$toolTip": $data}, function (p_event)
						{
							p_event.data.$toolTip.fadeOut();
						});
						// Add list toggle functionality.
						$data.find( ".list" ).toggleList();
					},
					"type": "post",
					"url": $this.attr( "href" )
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

			function updateCalculations()
			{
				$.ajax({
					"data": "battleNetId=" + window[ "battleNetId" ] + "&heroClass=" + window["heroClass"] + "&json=" + JSON.stringify( window["heroJson"] ),
					"dataType": "html",
					"success": function ( p_data )
					{
						var $newStats = $( $.parseHTML(p_data) );
						$newStats.statsToggle();
						$( ".list.stats" ).replaceWith( $newStats );
					},
					"type": "post",
					"url": "/get-calculations.php"
				});
			}

			jQuery( document ).ready(function ($)
			{
				// Load an items details via HTTP request.
				$( ".item-slot" ).each(function ()
				{
					$( this ).on( "click.d3", clickItemLink );
				});
				// Toggle stat details.
				$( ".list" ).toggleList();

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
							newHash,
							slot = $this.data( "slot" );
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
			});

			$.ajax( "/get-url.php?which=form", {
				"dataType": "html",
				"statusCode": {
					200: function ( p_data )
					{
						var $form = $( $.parseHTML(p_data) ),
							battleNetId = window.battleNetId;
						console.log( $form );
						if ( $form.length > 0 )
						{
							$form.find( "input[name='battleNetId']" ).val( battleNetId );
							$form.find( "input[name='battleNetId']" ).attr( "type", "hidden" );
							$form.find( "input[name='extra']" ).removeAttr( "checked" );
							$form.ajaxForm({
								"success": function ( p_responseText, statusText )
								{
									var $itemToolTip = [],
										$itemToolTip = $( $.parseHTML(p_responseText) );
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
				}
			});