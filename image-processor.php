<?php
/**
* Get an image via HTTP request and serve it up.
*
*/
require_once( "php/HttpRequestor.php" );
require_once( "php/Tool.php" );

use \d3;

	$imageUrl = $_GET['url'];
	$imageFile = '.' . $_GET['file'];
	$httpRequestor = new d3\HttpRequestor( $imageUrl );
	$responseText = $httpRequestor->send();
	$responseCode = $httpRequestor->responseCode();
	if ( $responseCode === 200 )
	{
		// Save image data to a file.
		d3\saveFile( $imageFile, $responseText );
		// Output the image to the browser.
		header( "Content-Type: image/png" );
		echo $responseText;
	}
?>