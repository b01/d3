/*global equal, module, ok, test */
/**
 * Created by Khalifah on 11/27/13.
 */
module( 'hero', {
	'setup': function ()
	{
		"use strict";
		var $fixtures = $( '#qunit-fixture' ),
			$div = $( '<div id="item-place-holder"></div>' );
		$fixtures.append( $div );
	}
});

test('test replacing HTML of item-place-holder with updateReplaced function', function ()
{
	var $div = $( '#item-place-holder' );
	updateReplaced( 'test1' );
	deepEqual( $div.html(), 'test1', 'updateReplaced succeeded' );

});