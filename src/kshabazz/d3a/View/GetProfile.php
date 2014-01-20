<?php namespace kshabazz\d3a\View;
/**
 *
 */

use kshabazz\d3a\BattleNet_Profile;
use function kshabazz\d3a\getSessionExpireInfo, kshabazz\d3a\displaySessionTimer;

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
		$sessionCacheInfo,
		$superss;

	/**
	 * Constructor
	 *
	 * @param array $pModels
	 */
	public function __construct( array $pModels )
	{
		$this->dqi = $pModels[ 'dqi' ];
		$this->sql = $pModels[ 'sql' ];
		$this->supers = $pModels[ 'supers' ];
		$this->battleNetId = $pModels[ 'battleNetId' ];
		$this->heroes = NULL;
		$this->clearCache = $pModels[ 'clearCache' ];
		$this->profile = $pModels[ 'profile' ];

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
		$this->heroUrl = sprintf( self::HERO_URL, $this->battleNetUrlSafeId );

		if ( isString($this->battleNetId) )
		{
			$this->sessionCacheInfo = getSessionExpireInfo(
				'profile-' . $this->battleNetUrlSafeId,
				$this->clearCache
			);
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