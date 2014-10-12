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
	 * @inheritdoc
	 */
	public function description()
	{
		return $this->description;
	}

	/**
	 * @inheritdoc
	 */
	public function name()
	{
		return $this->name;
	}

	/**
	 * @inheritdoc
	 */
	public function requiredLevel()
	{
		return $this->requiredLevel;
	}

	/**
	 * @inheritdoc
	 */
	public function simpleDescription()
	{
		return $this->simpleDescription;
	}

	/**
	 * @inheritdoc
	 */
	public function slug()
	{
		return $this->slug;
	}

	/**
	 * @inheritdoc
	 */
	public function tooltipParams()
	{
		return $this->tooltipParams;
	}
}
?>