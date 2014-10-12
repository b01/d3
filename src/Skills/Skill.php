<?php namespace Kshabazz\BattleNet\D3\Skills;
/**
 * Interface Skill
 *
 * @package Kshabazz\BattleNet\D3\Skills
 */
interface Skill
{
	/**
	 * Constructor
	 *
	 * @param array $pSkill
	 */
	public function __construct( array $pSkill );

	/**
	 * @return string
	 */
	public function description();

	/**
	 * @return string
	 */
	public function name();

	/**
	 * @return int
	 */
	public function requiredLevel();

	/**
	 * @return string
	 */
	public function simpleDescription();

	/**
	 * @return string
	 */
	public function slug();

	/**
	 * @return string
	 */
	public function tooltipParams();
}
?>