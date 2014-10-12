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
	 * @param string $pType
	 * @param array $pSkill
	 */
	public function __construct( $pType, array $pSkill );

	/**
	 * @return string
	 */
	public function getDescription();

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @return int
	 */
	public function getRequiredLevel();

	/**
	 * @return string
	 */
	public function getSimpleDescription();

	/**
	 * @return string
	 */
	public function getSlug();

	/**
	 * @return string
	 */
	public function getTooltipParams();

	/**
	 * @return string
	 */
	public function getType();
}
?>