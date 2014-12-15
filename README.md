## Description

An API for accessing Diablo 3 REST service.

## Examples

### Pull a profile from Diablo 3 (Battle.net)

```php
<?php

use
    \Kshabazz\Slib\HttpClient,
    \Kshabazz\BattleNet\D3\Connections\Http as D3_Http,
    \Kshabazz\BattleNet\D3\Models\Profile as D3_Profile;

$apiKey = 'apiKeyFromMashery';
$battleNetTag = 'msuBREAKER#1374';
$heroId = 3955832;

// Get an HTTP client, currently only my custom HTTP client works. I will need to modify it to work with Guzzle.
$httpClient = new HttpClient();

// Initialize a battle.net HTTP client.
$bnClient = new D3_Http( $apiKey, $battleNetTag, $httpClient );

// Get the profile based on the (this will be the raw JSON.
$profileJson = $bnrClient->getProfile();

// Pass the profile JSON into a Profile model for accessing common properties.
$profile = new D3_Profile( $profileJson );

?>
```

### Pull a Hero from Diablo 3 (Battle.net)

```php
<?php

use
    \Kshabazz\Slib\HttpClient,
    \Kshabazz\BattleNet\D3\Connections\Http as D3_Http,
    \Kshabazz\BattleNet\D3\Models\Hero as D3_Hero;

$apiKey = 'apiKeyFromMashery';
$battleNetTag = 'msuBREAKER#1374';
$heroId = 3955832;

// Get an HTTP client, currently only my custom HTTP client works. I will need to modify it to work with Guzzle.
$httpClient = new HttpClient();

// Initialize a battle.net HTTP client.
$bnClient = new D3_Http( $apiKey, $battleNetTag, $httpClient );

// Get the profile based on the (this will be the raw JSON.
$hero = $bnrClient->getHero( $heroId );

// Pass the profile JSON into a Profile model for accessing common properties.
$hero = new D3_Hero( $noItemsJson );

?>
```