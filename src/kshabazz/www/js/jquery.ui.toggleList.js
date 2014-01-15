(function ( window, $, undefined )
{
	$.widget( "st.toggleList", {
		"options": {
			"itemSelector": ".stat",
			"expandableSelector": ".expandable",
			"labelSelector": ".label",
			"toggleSelector": ".toggle",
			"expandedState": '-',
			"collapsedState": '+'
		},
		"_create": function ()
		{
			this.$list = this.element.find( this.options.itemSelector );
		},
		"_destroy": function ()
		{
			delete this.$list;
		},
		"_init": function ()
		{
			var $widget = this;
			this.$list.each(function ()
			{
				var $this = $( this ),
					$expandable = $this.find( $widget.options.expandableSelector ),
					$label = $this.children( $widget.options.labelSelector ),
					$toggle, toggleText;
				if ( $expandable.length > 0 && $label.length > 0 )
				{
					$toggle = $label.children( $widget.options.toggleSelector );
					if ( $toggle.length > 0 )
					{
						// Set initial state based on the toggle value.
						toggleText = $toggle.text();
						if ( toggleText === $widget.options.expandedState )
						{
							$expandable.show();
						}
						else
						{
							$expandable.hide();
						}
						$label.on( "click.statsToggle", {
							"$expandable": $expandable,
							"$toggle": $toggle,
							"$widget": $widget
						}, $widget.clickItemLabel );
					}
				}
			});
		},
		/**
		* Expand/collapse an item when clicking its label.
		*/
		"clickItemLabel": function ( p_event )
		{
			var $toggle = p_event.data.$toggle,
				$widget = p_event.data.$widget;
			p_event.data.$expandable.slideToggle( "fast", function ()
			{
				$widget.toggleIndicator( $toggle );
			});
			return true;
		},
		/**
		* Switch the expand/collapsed indicator.
		*/
		"toggleIndicator": function ( $p_toggleIndicator )
		{
			var indicator = $p_toggleIndicator.text();
			// indicator = ( indicator === this.options.expandedState ) this.options.collapsedState : this.options.expandedState;
			if ( indicator === this.options.expandedState )
			{
				indicator = this.options.collapsedState;
			}
			else
			{
				indicator = this.options.expandedState;
			}
			$p_toggleIndicator.text( indicator );
		}
	});
	
				// Toggle stat details.
})( window, jQuery );