<?php namespace kshabazz\d3a;
/**
 *
 */
use \Kshabazz\Slib;

	$which = ( !isset($which) ) ? getStr( 'which' ) : $which;
	$class = getStr( 'class' );
	$slug = getStr( 'slug' );
	$name = getStr( 'name' );
	$country = 'us';
	$lang = 'en';
	$battleNetUrl = "http://{$country}.battle.net/d3/{$lang}/";
	if ( !empty($which) )
	{
		$local = FALSE;
		$body = NULL;
		switch ( trim($which) )
		{
			case 'form':
				$url = 'item.html';
				$local = TRUE;
				break;
			case 'skill-1':
				$url = "class/{$class}/active/";
				break;
			case 'skill-2':
				$url = "class/{$class}/active/{$slug}";
				break;
			case 'skill-3':
				$url = "class/{$class}/passive/";
				break;
			case 'item-forge-json':
				$url = "item/{$class}/";
				break;
		}

		if ( !isString($url) )
		{
			return null;
		}

		if ( $local )
		{
			$body = get_include_contents( $url );
		}
		else
		{
			$httpRequestor = new HttpRequestor( $battleNetUrl . $url, [
				\CURLOPT_HTTPHEADER => [ "Content-Type: text/json; charset=utf-8" ],
				\CURLOPT_USERAGENT => $_SERVER[ 'HTTP_USER_AGENT' ]
			]);
			$responseText = $httpRequestor->send();
			if ( $httpRequestor->responseCode() == 200 )
			{
				$body = $responseText;
			}
		}

		if ( $body !== NULL )
		{
			echo getHtmlInnerBody( $body );
		}
	}
?>