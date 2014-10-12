<?php namespace Kshabazz\BattleNet\D3\Skills;
/**
 *
 */

/**
 * Class Skill
 *
 * @package Kshabazz\BattleNet\D3\Models
 */
class Passive implements Skill
{
	private
		/** @var string A long description of the skill benefits. */
		$description,
		/** @var string Flavor text. */
		$flavor,
		/** @var string A cool name. */
		$name,
		/** @var int Order in which the skill should be sorted. */
		$orderIndex,
		/** @var int Level required to use the skill. */
		$requiredLevel,
		/** @var string Shortened version of the description. */
		$simpleDescription,
		/** @var string */
		$skillCalcId,
		/** @var string Name of the skill that is useful when coding. */
		$slug,
		/** @var string Battle.Net URI for the skill. */
		$tooltipParams;

	/**
	 * @inheritdoc
	 */
	public function __construct( array $pSkill )
	{
		$this->description = $pSkill[ 'description' ];
		$this->name = $pSkill[ 'name' ];
		$this->orderIndex = $pSkill[ 'orderIndex' ];
		$this->requiredLevel = $pSkill[ 'requiredLevel' ];
		$this->simpleDescription = $pSkill[ 'simpleDescription' ];
		$this->slug = $pSkill[ 'slug' ];
		$this->tooltipParams = $pSkill[ 'tooltipParams' ];
	}

	/**
	 * @return string
	 */
	public function description()
	{
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function flavor()
	{
		return $this->flavor;
	}

	/**
	 * @return string
	 */
	public function name()
	{
		return $this->name;
	}

	/**
	 * @return int
	 */
	public function orderIndex()
	{
		return $this->orderIndex;
	}

	/**
	 * @return int
	 */
	public function requiredLevel()
	{
		return $this->requiredLevel;
	}

	/**
	 * @return string
	 */
	public function simpleDescription()
	{
		return $this->simpleDescription;
	}

	/**
	 * @return string
	 */
	public function slug()
	{
		return $this->slug;
	}

	/**
	 * @return string
	 */
	public function tooltipParams()
	{
		return $this->tooltipParams;
	}
}
?>