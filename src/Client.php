<?php namespace Kshabazz\BattleNet\D3;
/**
 * Reduces line of code to get Battle.Net data.
 */

/**
 * Class Stream
 *
 * @package Kshabazz\BattleNet\D3
 */
class Client
{
	/**
	 * @var string Mashery D3 AAPI key.
	 */
	private $apiKey;

	/**
	 * @var string
	 */
	private $battleNetTag;

	public function __construct( $pApiKey, $pBattleNetTag )
	{
		$this->apiKey = $pApiKey;
		$this->battleNetTag = $pBattleNetTag;
	}

	/**
	 * Get a hero profile from Battle.Net.
	 *
	 * @return \Kshabazz\BattleNet\D3\Profile
	 */
	public function getProfile()
	{
		// Pass the profile JSON into a Profile model for accessing common properties.
		return Profile::factory( $this->apiKey, $this->battleNetTag );
	}

	/**
	 * Get a hero from Battle.Net.
	 *
	 * @param int|string Hero ID
	 * @return \Kshabazz\BattleNet\D3\Hero
	 */
	public function getHero( $pHeroId )
	{
		// Pass the profile JSON into a Profile model for accessing common properties.
		return Hero::factory( $this->apiKey, $this->battleNetTag, $pHeroId );
	}

	/**
	 * Get an item from Battle.Net.
	 *
	 * @param string $pItemHash
	 * @return \Kshabazz\BattleNet\D3\Item
	 */
	public function getItem( $pItemHash )
	{
		// Pass the profile JSON into a Profile model for accessing common properties.
		return Item::factory( $this->apiKey, $this->battleNetTag, $pItemHash );
	}
}
?>