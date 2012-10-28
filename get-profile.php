<?php
// Get the profile and store it.
namespace d3cb;
require_once( "php/Tool.php" );
require_once( "php/BattleNetDqi.php" );
require_once( "php/Profile.php" );
require_once( "php/Sql.php" );

	$battleNetId = Tool::getPostStr( "battleNetId" );
	if ( Tool::isString($battleNetId) )
	{
		$battleNetDqi = new BattleNetDqi();
		
		$dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=UTF-8";
		$sql = new Sql( $dsn, DB_USER, DB_PSWD );
		$battleNetProfile = new Api\Profile( $battleNetId, $battleNetDqi, $sql );
		
		$heroes = $battleNetProfile->getHeroes();
	}
	
?>
		<ul class="heroes-select">
		<?php foreach ( $heroes as $hero ): ?>
			<li value="<?= $hero['id'] ?>"><?= $hero['name'] ?> (<?= $hero['level'] ?>)</li>
		<?php endforeach; ?>
		</ul>