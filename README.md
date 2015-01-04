## Description
An interface (written in PHP) for accessing Battle.net Diablo 3 REST
service.


## Summary
This API provides a client for accessing Diablo 3 profiles, heroes, and
items; which require an API key and battle-tag. There are also a few
object models for: Profile, Hero, Item, and Skill (Active and Passive).


## Requirements

* PHP 5.4


## Installation

Add to your composer.json

```json
"require": {
    "kshabazz/battlenet-d3": "dev-master"
}
```

## Quick-Start Examples

### Pull a profile from Diablo 3 (Battle.net)

```php
<?php

use
	\Kshabazz\Slib\HttpClient,
	\Kshabazz\BattleNet\D3\Connections\Http as D3_Http,
	\Kshabazz\BattleNet\D3\Profile as D3_Profile;

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
	\Kshabazz\BattleNet\D3\Hero as D3_Hero;

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
	\Kshabazz\BattleNet\D3\Hero as D3_Hero,
	\Kshabazz\BattleNet\D3\Item as D3_Item;

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