<?php namespace kshabazz\d3a;

	$cache = ( bool )getStr( "cache" );

	if ( isString($battleNetId) )
	{
		$sessionCacheInfo = getSessionExpireInfo( "profileTime", $cache );
	}
?><!DOCTYPE html>
<html>
	<head>
		<title>Profiles</title>
		<link rel="stylesheet" type="text/css" href="/css/site.css" />
		<link rel="stylesheet" type="text/css" href="/css/profile.css" />
	</head>
	<body>
		<div class="time-elapsed">{{ sessionTimeLeft(timeLeft) }}</div>

		{% if isArray(heroes) %}
		<div class="heroes">
		{% for key, hero in heroes %}
			<a href="{{ heroUrl }}{{ hero.id }}" class="inline-block profile {{ hero.class }} gender-{{ hero.gender }}">
				<span class="name">{{ hero.name }} <span class="level">({{ hero.level }})</span></span>
			</a>
		{% endfor %}
		</div>
		{% else %}
		<p>Hmm...You seem to have no hero profiles. Since that is very unlikely, this app is probably broken in some way Please try again later.</p>
		<br />
		{% endif %}
		<a href="/">Change BattleNet ID</a>
	</body>
</html>