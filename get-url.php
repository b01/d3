<?php namespace D3;

	$which = getStr( 'which' );
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
			$httpRequestor = new HttpRequestor( $battleNetUrl . $url );
			$responseText = $httpRequestor->send();
			if ( $httpRequestor->responseCode() == 200 )
			{
				$body = $responseText;
			}
		}

		if ( $body !== NULL )
		{
			$html = getHtmlInnerBody( $body );
			echo tidyHtml( $html );
		}
	}
?>