<?php
// Get the profile and store it.
namespace d3cb;
require_once( "php/Tool.php" );
require_once( "php/BattleNetDqi.php" );
require_once( "php/Hero.php" );
require_once( "php/Sql.php" );

$battleNetId = getPostStr( "battleNetId" );
$heroId = getPostStr( "heroId" );
$battleNetId = "msuBREAKER#1374";
$heroId = "3955832";
?>
	<?php if ( isString($battleNetId) && isString($heroId) ):
		$battleNetDqi = new BattleNetDqi( $battleNetId );
		$dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=UTF-8";
		$sql = new Sql( $dsn, DB_USER, DB_PSWD, USER_IP_ADDRESS );
		$hero = new Hero( $heroId, $battleNetDqi, $sql );
		$items = $hero->getItems();
	?>
		<?php if ( isArray($items) ): ?>
			<?php foreach ( $items as $key => $item ): ?>
			<div class="hero">
				<div class=="item-tooltipParams"><?= $item['tooltipParams']; ?></div>
				<div class=="item-<?= $key ?>">
					<img src="http://media.blizzard.com/d3/icons/items/large/<?= $item['icon'] ?>.png" alt="<?= $key ?>" />
				<div class=="item-<?= $item['id'] ?>"><?= $item['id'] ?></div>
				</div>
			</div>
			<?php endforeach; ?>
		<?php endif; ?>
	<?php endif; ?>