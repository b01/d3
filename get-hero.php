<?php namespace d3cb;
// Get the profile and store it.
require_once( "php/Tool.php" );
require_once( "php/BattleNetDqi.php" );
require_once( "php/Hero.php" );
require_once( "php/Sql.php" );

	$urlBattleNetId = getStr( "battleNetId" );
	$heroId = getStr( "heroId" );
	$heroId = "3955832";
	$items = NULL;
	if ( isString($urlBattleNetId) && isString($heroId) )
	{
		$battleNetId = str_replace( '-', '#', $urlBattleNetId );
		$dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=UTF-8";
		$battleNetDqi = new BattleNetDqi( $battleNetId );
		$sql = new Sql( $dsn, DB_USER, DB_PSWD, USER_IP_ADDRESS );
		$hero = new Hero( $heroId, $battleNetDqi, $sql );
		$items = $hero->getItems();
	}
?><!DOCTYPE html>
<html>
	<head>
		<title>Hero <?= "name" ?></title>
		<link rel="stylesheet" type="text/css" href="/css/site.css" />
		<link rel="stylesheet" type="text/css" href="/css/hero.css" />
	</head>
	<body>
		<?php if ( isArray($items) ): ?>
		<div class="hero">
			<?php foreach ( $items as $key => $item ): ?>
				<a class="item <?= $key ?>" href="/get-item.php?<?= "battleNetId=" . $urlBattleNetId . '&' . str_replace( '/', "Hash=", $item['tooltipParams'] ) ?>">
					<div class="tooltipParams"><?= $item['tooltipParams'] ?></div>
					<img src="http://media.blizzard.com/d3/icons/items/large/<?= $item['icon'] ?>.png" alt="<?= $key ?>" />
					<div class="id"><?= $item['id'] ?></div>
				</a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</body>
</html>