## Description
A PHP library for accessing Battle.net Diablo 3 REST service.

## Requirements

* PHP 5.4

NOTE: I've removed some thing that required PHP 5.6, so it should work on
      5.4, or 5.5. Unless I missed something.

## Installation

Add to your composer.json

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/b01/slib"
    },
    {
        "type": "vcs",
        "url": "https://github.com/b01/d3"
    }
],
"require": {
    "kshabazz/slib": "~1.1",
    "kshabazz/battlenet-d3": "dev-master"
}
```


## Examples

### Pull a profile from Diablo 3 (Battle.net)

```php
<?php

use
	\Kshabazz\Slib\HttpClient,
	\Kshabazz\BattleNet\D3\Connections\Http as D3_Http,
	\Kshabazz\BattleNet\D3\Models\Profile as D3_Profile;

// DO NOT FORGET TO SET THIS!!!
$apiKey = 'apiKeyFromMashery';

$battleNetTag = 'msuBREAKER#1374';

// Get an HTTP client, currently only my custom HTTP client works.
$httpClient = new HttpClient();

// Initialize a battle.net HTTP client.
$bnClient = new D3_Http( $apiKey, $battleNetTag, $httpClient );

// Get the profile for the Battle.net tag (this will be the raw JSON).
$profileJson = $bnClient->getProfile();

// Pass the profile JSON into a Profile model for accessing common properties.
$profile = new D3_Profile( $profileJson );

var_dump( $profile->heroes() );
?>
```

### Pull a Hero from Diablo 3 (Battle.net)

```php
<?php

use
	\Kshabazz\Slib\HttpClient,
	\Kshabazz\BattleNet\D3\Connections\Http as D3_Http,
	\Kshabazz\BattleNet\D3\Models\Hero as D3_Hero;

// DO NOT FORGET TO SET THIS!!!
$apiKey = 'apiKeyFromMashery';

$battleNetTag = 'msuBREAKER#1374';
$heroId = 3955832;

// Get an HTTP client.
$httpClient = new HttpClient();

// Initialize a Diablo 3 battle.net HTTP client.
$bnClient = new D3_Http( $apiKey, $battleNetTag, $httpClient );

// Get the Diablo 3 Hero (this will be the raw JSON).
$heroJson = $bnClient->getHero( $heroId );

// Pass the hero JSON into a Hero model for accessing common properties.
$hero = new D3_Hero( $heroJson );

echo $hero->name();
?>
```

### Pull an Item from Diablo 3 (Battle.net)

```php
<?php

use
	\Kshabazz\Slib\HttpClient,
	\Kshabazz\BattleNet\D3\Connections\Http as D3_Http,
	\Kshabazz\BattleNet\D3\Models\Hero as D3_Hero,
	\Kshabazz\BattleNet\D3\Models\Item as D3_Item;

// DO NOT FORGET TO SET THIS!!!
$apiKey = 'apiKeyFromMashery';

$battleNetTag = 'msuBREAKER#1374';
$heroId = 3955832;
$itemHash = NULL;
$heroItemHashes = NULL;

// Get an HTTP client.
$httpClient = new HttpClient();

// Initialize a Diablo 3 battle.net HTTP client.
$bnClient = new D3_Http( $apiKey, $battleNetTag, $httpClient );

// Get the Diablo 3 Hero (this will be the raw JSON).
$heroJson = $bnClient->getHero( $heroId );

// Pass the hero JSON into a Hero model for accessing common properties.
$hero = new D3_Hero( $heroJson );

// Get a list of items from the Hero.
$heroItemHashes = $hero->itemsHashesBySlot();

// Get the item from Battle.net.
$itemJson = $bnClient->getItem( $heroItemHashes['mainHand'] );

// Put the JSON into a more usable state.
$item = new D3_Item( $itemJson );

// Test Item
echo $item->name();
?>
```