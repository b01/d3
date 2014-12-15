## Description

An API for accessing Diablo 3 REST service.

## Examples

### Pull a profile from Diablo 3 (Battle.net)

```php

use
    \Kshabazz\Slib\HttpClient,
    \Kshabazz\BattleNet\D3\Connections\Http,
    \Kshabazz\BattleNet\D3\Models\Profile;

$apiKey = 'apikeyFromMashery';
$battleNetTag = 'msuBREAKER#1374';
$heroId = 3955832;

// Get an HTTP client, currently only my custom HTTP client works. I will need to modify it to work with Guzzle.
$httpClient = new HttpClient();

// Initialize a battle.net HTTP client.
$bnClient = new Http( $apiKey, $battleNetTag, $httpClient );

// Get the profile based on the (this will be the raw JSON.
$bnrClient->getProfile()

// Pass the profile JSON into a Profile model for accessing common properties.
$profile new Profile( $profileJson );
```