<?php
/**
* Get an image via HTTP request and serve it up.
* - ImageSaver Class - takes an iamge url and fileStream object and save a file to disk.
*   - When an image is not found on the server execute a special script.
*     - Process all remote image URLs through this image class
*       - Check if the image exists, if not, download it from Battle.net.
*
*/
	$imageUrl = $_GET['url'];
	$imageFile = '.' . $_GET['file'];
	$httpRequestor = new d3\HttpRequestor( $imageUrl );
	$responseText = $httpRequestor->send();
	$responseCode = $httpRequestor->responseCode();
	if ( $responseCode === 200 )
	{
		// Save image data to a file.
		saveFile( $imageFile, $responseText );
		// Wait for the file to be saved.
		sleep( 2 );
		// Output the image to the browser.
		header( "Content-Type: image/png" );
		echo $responseText;
	}
?>