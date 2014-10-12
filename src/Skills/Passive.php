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
		/** @var string A cool name. */
		$name,
		/** @var int Order in which the skill should be sorted. */
		$orderIndex,
		/** @var int Level required to use the skill. */
		$requiredLevel,
		/** @var string Shortened version of the description. */
		$simpleDescription,
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
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return int
	 */
	public function getOrderIndex()
	{
		return $this->orderIndex;
	}

	/**
	 * @return int
	 */
	public function getRequiredLevel()
	{
		return $this->requiredLevel;
	}

	/**
	 * @return string
	 */
	public function getSimpleDescription()
	{
		return $this->simpleDescription;
	}

	/**
	 * @return string
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * @return string
	 */
	public function getTooltipParams()
	{
		return $this->tooltipParams;
	}
}
?>