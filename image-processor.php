<?php namespace D3;
/**
* Get an image via HTTP request and serve it up.
* - ImageSaver Class - takes an iamge url and fileStream object and save a file to disk.
*   - When an image is not found on the server execute a special script.
*     - Process all remote image URLs through this image class
*       - Check if the image exists, if not, download it from Battle.net.
*
*/
	$imageUrl = getStr( 'url' );
	$imageFile = '.' . getStr( 'file' );
	// Url translations.
	$imageUrl = str_replace( 'media.blizzard.com/d3/ui/', "us.battle.net/d3/static/images/ui/", $imageUrl );
	$imageUrl = str_replace( 'media.blizzard.com/d3/icon-bgs/', "us.battle.net/d3/static/images/item/icon-bgs/", $imageUrl );
	$imageUrl = str_replace( 'media.blizzard.com/d3/effect-bgs/', "us.battle.net/d3/static/images/item/effect-bgs/", $imageUrl );
	// $imageUrl = preg_replace( 'media.blizzard.com/d3/(icon-bgs|effect-bgs)/', "us.battle.net/d3/static/images/item/$1/", $imageUrl );
	$httpRequestor = new HttpRequestor( $imageUrl );
	$responseText = $httpRequestor->send();
	$responseCode = $httpRequestor->responseCode();
	if ( $responseCode === 200 )
	{
		// Save image data to a file.
		saveFile( $imageFile, $responseText );
		// Wait for the file to be saved.
		sleep( 1 );
		// Output the image to the browser.
		header( "Content-Type: image/png" );
		echo $responseText;
	}
?>