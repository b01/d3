<?php namespace Kshabazz\BattleNet\D3\Models;
/**
 *
 */

/**
 * Class Skill
 *
 * @package Kshabazz\BattleNet\D3\Models
 */
class Skill
{
	private
		$type;

	/**
	 * Constructor
	 *
	 * @param string $pType
	 * @param array $pSkill
	 */
	public function __construct( $pType, array $pSkill )
	{
		$this->type = $pType;
	}
}