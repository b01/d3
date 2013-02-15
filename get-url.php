<?php namespace d3;

	$which = getStr( "which" );
	$class = getStr( "class" );
	$slug = getStr( "slug" );
	if ( !empty($which) )
	{
		$local = FALSE;
		$body = NULL;
		switch ( trim($which) )
		{
			case 'form':
				$url = "item.html";
				$local = TRUE;
				break;
			case 'skill-1':
				$url = "http://us.battle.net/d3/en/class/{$class}/active/";
				break;
			case 'skill-2':
				$url = "http://us.battle.net/d3/en/class/{$class}/active/{$slug}";
				break;
			case 'skill-3':
				$url = "http://us.battle.net/d3/en/class/{$class}/passive/";
				break;
		}

		if ( $local )
		{
			$body = get_include_contents( $url );
		}
		else
		{
			$httpRequestor = new \HttpRequestor( $url );
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