<?php namespace kshabazz\d3a;
/**
* Get an image via HTTP request and serve it up.
* - ImageSaver Class - takes an iamge url and fileStream object and save a file to disk.
*   - When an image is not found on the server execute a special script.
*     - Process all remote image URLs through this image class
*       - Check if the image exists, if not, download it from Battle.net.
*
*/
//item:                 http://media.blizzard.com/d3/icons/items/large/shoulders_105_monk_male.png
//item:                 http://media.blizzard.com/d3/icons/items/large/amulet08_demonhunter_male.png
//dye:                  http://media.blizzard.com/d3/icons/items/small/dye_19_demonhunter_male.png
//dye:                  http://media.blizzard.com/d3/icons/items/small/dye_15_demonhunter_male.png
//gem:                  http://media.blizzard.com/d3/icons/items/small/amethyst_08_demonhunter_male.png
//item-bg:              http://us.battle.net/d3/static/images/item/icon-bgs/yellow.png
//overlays (gem,etc):   http://us.battle.net/d3/static/images/profile/hero/skill-overlays.png
//item-bg:              http://us.battle.net/d3/static/images/item/icon-bgs/gradient.png
$imageFile = getStr( 'file' );

// Url translations.
$bUrl = str_replace( '/media/images/', "http://media.blizzard.com/d3/", $imageFile );
$imageUrl = str_replace( 'media.blizzard.com/d3/ui/', "us.battle.net/d3/static/images/ui/", $bUrl );
$imageUrl = str_replace( 'media.blizzard.com/d3/icon-bgs/', "us.battle.net/d3/static/images/item/icon-bgs/", $bUrl );
$imageUrl = str_replace( 'media.blizzard.com/d3/effect-bgs/', "us.battle.net/d3/static/images/item/effect-bgs/", $bUrl );

// Get the image and save it to a file.
$httpRequestor = new HttpRequestor( $imageUrl );
$responseText = $httpRequestor->send();
$responseCode = $httpRequestor->responseCode();
if ( $responseCode === 200 )
{
	// Save image data to a file.
	saveFile( '.' . $imageFile, $responseText );
	// Wait for the file to be saved.
	sleep( 1 );
	// Output the image to the browser.
	header( "Content-Type: image/png" );
	echo $responseText;
}
?>