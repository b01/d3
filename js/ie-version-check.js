/**
* Get the version of IE and set it as a property 'ie' on the window.
*
* Uses a combination of IE JavaScript Conditional compilation and
* feature detection to determine the clients' version of IE
* otherwise null.
* This must set an object in global space because of conditional
* compilations nature. See: Microsoft Developer Network
* <http://msdn.microsoft.com/> for more on "cc_on Statement (JavaScript)"
*
* @example:
*	checkForIE();
*	if ( window.ie !== null && window.ie.version < 10 ) { ... }
*
* @author Khalifah Shabazz <http://www.kshabazz.net/>
*/
function checkForIE()
{
	// Init only on page load.
	if ( typeof window.ie !== "object" )
	{
		window.ieVersion = -1;
		window.ie = null;
	}
	else {
		console.log( window.ie);
	}

	/*@cc_on
	if ( @_win32 || @_win64 )
	{
		window.ie = {};

		if ( @_win32 )
		{
			window.ie.winAt = @_win32;
			window.ie.bits = 32;
		}
		else if ( @_win64 )
		{
			window.ie.winAt = @_win64;
			window.ie.bits = 64;
		}

		if ( typeof(document.documentMode) != "undefined" )
		{
			window['ieVersion'] = document.documentMode;
			window.ie.version = document.documentMode;
		}
		else if ( typeof(document.compatMode) != "undefined" && document.compatMode == "CSS1Compat" )
		{
			window['ieVersion'] = 7;
			window.ie.version = 7;
		}
		else if ( typeof(@_jscript_version) != "undefined" )
		{
			if ( @_jscript_version == 5.6 )
			{
				window['ieVersion'] = 6;
				window.ie.version = 6;
			}
			else if ( @_jscript_version < 5.6 && @_jscript_version > 3.9 )
			{
				window['ieVersion'] = 5;
				window.ie.version = 5;
			}
			else if ( @_jscript_version < 4 )
			{
				window['ieVersion'] = 3;
				window.ie.version = 3;
			}
		}
	}
	@*/
	return window.ie;
}