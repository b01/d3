<?php
/**
* Save some content to a file.
*
* @author Khalifah Shabazz <http://kshabazz.net>
*/
try
{
	$fileName = $_REQUEST[ 'file' ];
	$content = $_REQUEST[ 'content' ];
	$success = FALSE;
	// TODO make sure the IP is local only.
	if ( isString($fileName) && isString($content) )
	{
		$file =  "./media/data-files/{$fileName}.json";
		// Save image data to a file.
		saveFile( $file, $content );
		// Wait for the file to be saved.
		sleep( 1 );
		// Output the success message to the browser.
		header( "Content-Type: application/json" );
		$success = TRUE;
	}
}
catch ( \Exception $pError )
{
	$success = FALSE;
}
echo json_encode(["success" => $success ]);
?>