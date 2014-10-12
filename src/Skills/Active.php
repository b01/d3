<?php namespace Kshabazz\BattleNet\D3\Skills;
/**
 *
 */

/**
 * Class Skill
 *
 * @package Kshabazz\BattleNet\D3\Models
 */
class Active implements Skill
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
		$tooltipParams,
		/** @var string active/passive. */
		$type;

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
		$this->type = $pType;
	}

	/**
	 * @inheritdoc
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @inheritdoc
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @inheritdoc
	 */
	public function getRequiredLevel()
	{
		return $this->requiredLevel;
	}

	/**
	 * @inheritdoc
	 */
	public function getSimpleDescription()
	{
		return $this->simpleDescription;
	}

	/**
	 * @inheritdoc
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * @inheritdoc
	 */
	public function getTooltipParams()
	{
		return $this->tooltipParams;
	}
}
?>