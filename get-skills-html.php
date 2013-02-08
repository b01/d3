<?php namespace d3;
require_once( "php/Tool.php" );

	$urlPath = getStr( "urlPath" );
	$body = NULL;
	if ( !empty($urlPath) )
	{
		$battleNetHostUrl = "http://us.battle.net/d3/en/class";

		// * http://us.battle.net/d3/en/class/barbarian/active/
		// * http://us.battle.net/d3/en/class/barbarian/active/{skill-slug}
		// * http://us.battle.net/d3/en/class/barbarian/passive/
		$httpRequestor = new HttpRequestor( "{$battleNetHostUrl}/{$urlPath}" );
		$responseText = $httpRequestor->send();

		if ( $httpRequestor->responseCode() == 200 )
		{
			$start = strpos( $responseText, "<body" );
			$start = strpos( $responseText, '>', $start + 5 );
			$end = strpos( $responseText, "</body>", $start ) - $start;
			echo substr( $responseText, $start + 1, $end );
		}
	}
?>