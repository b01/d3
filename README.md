# Description
A library, written in PHP, for accessing Battle.net Diablo 3 REST
service.

This API provides a client for accessing Diablo 3 profiles, heroes,
and items; which require an API key and battle-tag. There are also a
few object models for: Profile, Hero, Item, and Skill (Active and
Passive).


## Requirements

* PHP 5.4 - 5.6


## Installation

Add to your composer.json

```json
"require": {
    "kshabazz/battlenet-d3": "^1.2"
}
```

## Summary

You can either get the raw JSON data returned from Battle.Net or use
some simple models that this library provides.

### Examples for Retrieving data (as JSON) from Battle.net

```php
use
    \Kshabazz\Slib\HttpClient,
    \Kshabazz\BattleNet\D3\Connections\Http as D3_Http,
    \Kshabazz\BattleNet\D3\Profile as D3_Profile;

// An API key and Battle.Net Tag are required for all request.
$apiKey       = 'apiKeyFromMashery';
$battleNetTag = 'msuBREAKER#1374';
$heroId       = 3955832;
$itemHash     = 'item/CioI4YeygAgSBwgEFcgYShEdhBF1FR2dbLMUHape7nUwDTiTA0'
              . 'AAUApgkwMYkOPQlAI';

// Get an HTTP client, currently only my custom HTTP client works.
$httpClient = new HttpClient();

// Initialize a battle.net HTTP client.
$d3Client = new D3_Http( $apiKey, $battleNetTag, $httpClient );

// Get the profile for the Battle.net tag (this will be the raw JSON).
$profileJson = $d3Client->getProfile();

// Get the Hero (again, this will be the raw JSON).
$heroJson = $d3Client->getHero( $heroId );

// Get an item (and again, this will be the raw JSON).
// Get the item from Battle.net.
$itemJson = $d3Client->getItem( $itemHash );

var_dump(
    "Profile:" . $profileJson,
    "\nHero:" . $heroJson,
    "\nItem" . $itemJson
);
```

### Examples Using Models (Profile/Hero/Item)

The following examples show how to use the models this library provides.

```php
<?php
use \Kshabazz\BattleNet\D3\Client as D3_Client;

$apiKey       = 'apiKeyFromMashery';
$battleNetTag = 'msuBREAKER#1374';
$heroId       = 3955832;
$itemHash     = 'item/CioI4YeygAgSBwgEFcgYShEdhBF1FR2dbLMUHape7nUwDTiTA0'
              . 'AAUApgkwMYkOPQlAI';

// Using a factory method:
$d3Client = new D3_Client( $apiKey, $battleNetTag );

// Get a profile from Battle.net and return a Profile model.
$profile = $d3Client->getProfile();

// Get a hero from Battle.net and return a Hero model.
$hero = $d3Client->getHero( $heroId );

// Get an item from Battle.net and return an Item Model.
$item = $d3Client->getItem( $itemHash );

var_dump( $profile, $hero, $item );
```

### Example using factory methods

```php
<?php
use \Kshabazz\BattleNet\D3\Client as D3_Client;

$apiKey       = 'apiKeyFromMashery';
$battleNetTag = 'msuBREAKER#1374';
$heroId       = 3955832;
$itemHash     = 'item/CioI4YeygAgSBwgEFcgYShEdhBF1FR2dbLMUHape7nUwDTiTA0'
              . 'AAUApgkwMYkOPQlAI';

// This is mainly when you need to pull multiple profiles at a time.
$profile = D3_Client::profileFactory( $apiKey, $battleNetTag );

// When you want to grab multiple heroes, even from multiple profiles, at a time.
$hero = D3_Client::heroFactory( $apiKey, $battleNetTag, $heroId );


// Get a list of items from the Hero.
$heroItemHashes = $hero->itemsHashesBySlot();

// Get the item from Battle.net.
$itemJson = $d3Client->getItem( $heroItemHashes['mainHand'] );

// Returns an Array.
var_dump( $profile->heroes() );
var_dump( $profile->json() );
?>
```

## Live Examples

You can see this library in use here: http://d3a.kshabazz.net/

Quick links to live examples:
* [http://d3a.kshabazz.net/get-profile.php?battleNetId=msuBREAKER%231374(profile)]
* [http://d3a.kshabazz.net/get-profile.php?battleNetId=msuBREAKER%231374(profile)]
* [http://d3a.kshabazz.net/get-profile.php?battleNetId=msuBREAKER%231374(profile)]