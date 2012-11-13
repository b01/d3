<?php
// Get the profile and store it.
namespace d3cb;
require_once( "php/Tool.php" );
require_once( "php/BattleNetDqi.php" );
require_once( "php/Profile.php" );
require_once( "php/Sql.php" );

$battleNetId = getPostStr( "battleNetId" );
?>
	<?php if ( isString($battleNetId) ):
		$battleNetDqi = new BattleNetDqi( $battleNetId );
		$dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=UTF-8";
		$sql = new Sql( $dsn, DB_USER, DB_PSWD );
		$battleNetProfile = new Profile( $battleNetId, $battleNetDqi, $sql, USER_IP_ADDRESS );
		$heroes = $battleNetProfile->getHeroes();
		$battleNetUrlSafeId = str_replace( '#', '-', $battleNetId );
	?>
		<ul class="heroes">
		<?php foreach ( $heroes as $hero ): ?>
			<li value="<?= $hero['id'] ?>"><a href="/get-hero.php?battleNetId=<?= $battleNetUrlSafeId ?>&heroId=<?= $hero['id'] ?>"><?= $hero['name'] ?> (<?= $hero['level'] ?>)</a></li>
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>