<?php namespace kshabazz\d3a\View;
/**
 *
 */
use kshabazz\d3a\BattleNet_Profile;
use kshabazz\d3a\Model\Profile;

use function kshabazz\d3a\displaySessionTimer, kshabazz\d3a\getSessionExpireInfo, kshabazz\d3a\isBattleNetId;
/**
 * Class vGetProfile
 * @package kshabazz\d3a\View
 */
class GetProfile
{
	const
		HERO_URL = '/get-hero.php?battleNetId=%s&heroId=';

	protected
		$battleNetId,
		$battleNetUrlSafeId,
		$clearCache,
		$heroes,
		$heroUrl,
		$loadFromDb,
		$profile,
		$sessionCacheInfo;

	/**
	 * Constructor
	 *
	 * @param array $pModels
	 */
	public function __construct( array $pModels )
	{
		$this->dqi = $pModels[ 'bnr' ];
		$this->sql = $pModels[ 'sql' ];
		$this->battleNetId = $pModels[ 'battleNetId' ];
		$this->heroes = NULL;
		$this->clearCache = $pModels[ 'clearCache' ];

		$this->load();
	}

	/**
	 * Load event.
	 *
	 * @return TRUE to proceed, FALSE will log the event failed and cancel call to {$this::render()}.
	 */
	public function load()
	{
		$this->battleNetUrlSafeId = \str_replace( '#', '-', $this->battleNetId );
        // used for the vew to link to the hero page.
		$this->heroUrl = sprintf( self::HERO_URL, $this->battleNetUrlSafeId );

		$this->sessionCacheInfo = getSessionExpireInfo(
			'profile-' . $this->battleNetUrlSafeId,
			$this->clearCache
		);
		$this->bnrProfile = new BattleNet_Profile(
			$this->battleNetId,
			$this->dqi,
			$this->sql,
			$this->sessionCacheInfo[ 'loadFromBattleNet' ]
		);
        $this->profile = new Profile( $this->bnrProfile->json() );

		if ( isBattleNetId($this->battleNetId) )
		{
			$this->heroes = $this->profile->heroes();
		}
		return TRUE;
	}

	public function render()
	{
		$data = [
			'battleNetUrlSafeId' => $this->battleNetUrlSafeId,
			'heroes' => $this->heroes,
			'heroUrl' => $this->heroUrl,
			'sessionTimeLeft' => displaySessionTimer( $this->sessionCacheInfo['timeLeft'] ),
			'pageTitle' => $this->battleNetUrlSafeId
		];
		return $data;
	}
}
?>