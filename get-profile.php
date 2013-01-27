<?php namespace d3;

// Get the profile and store it.

	$battleNetId = getPostStr( "battleNetId" );
	$heroes = NULL;

	if ( isString($battleNetId) )
	{
		$battleNetDqi = new BattleNetDqi( $battleNetId );
		$sql = new Sql( DSN, DB_USER, DB_PSWD, USER_IP_ADDRESS );
		$battleNetProfile = new Profile( $battleNetId, $battleNetDqi, $sql );
		$heroes = $battleNetProfile->getHeroes();
		$battleNetUrlSafeId = str_replace( '#', '-', $battleNetId );
		$heroUrl = "/get-hero.php?battleNetId={$battleNetUrlSafeId}&heroId=";
	}
?><!DOCTYPE html>
<html>
	<head>
		<title>Profiles</title>
		<link rel="stylesheet" type="text/css" href="/css/site.css" />
		<link rel="stylesheet" type="text/css" href="/css/profile.css" />
	</head>
	<body>
	<?php if ( isArray($heroes) ): ?>
		<div class="heroes">
		<?php foreach ( $heroes as $hero ): ?>
			<a href="<?= $heroUrl . $hero['id'] ?>" class="inline-block profile <?= $hero[ 'class' ] ?><?php echo ($hero[ 'gender' ] == 0) ? " man" : " woman"?>">
				<span class="name"><?= $hero['name'] ?> <span class="level">(<?= $hero['level'] ?>)</span></span>
			</a>
		<?php endforeach; ?>
		</div>
	<?php else: ?>
		<p>Hmmm...You seem to have no hero profiles. Since that is very unlikey, this app is probably broken in some
		way Please try again later.
		</p>
	<?php endif; ?>
	</body>
</html>