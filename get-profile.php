<?php
// Get the profile and store it.
namespace d3;
require_once( "php/Tool.php" );
require_once( "php/BattleNetDqi.php" );
require_once( "php/Profile.php" );
require_once( "php/Sql.php" );

$battleNetId = getPostStr( "battleNetId" );
?>
	<?php if ( isString($battleNetId) ):
		$battleNetDqi = new BattleNetDqi( $battleNetId );
		$sql = new Sql( DSN, DB_USER, DB_PSWD, USER_IP_ADDRESS );
		$battleNetProfile = new Profile( $battleNetId, $battleNetDqi, $sql );
		$heroes = $battleNetProfile->getHeroes();
		$battleNetUrlSafeId = str_replace( '#', '-', $battleNetId );
	?>
		<ul class="heroes">
		<?php foreach ( $heroes as $hero ): ?>
			<li value="<?= $hero['id'] ?>"><a href="/get-hero.php?battleNetId=<?= $battleNetUrlSafeId ?>&heroId=<?= $hero['id'] ?>"><?= $hero['name'] ?> (<?= $hero['level'] ?>)</a></li>
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>