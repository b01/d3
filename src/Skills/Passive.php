<?php namespace Kshabazz\BattleNet\D3\Skills;
/**
 *
 */

/**
 * Class Skill
 *
 * @package Kshabazz\BattleNet\D3
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
	public function __construct( array $pProperies )
	{
		$this->description = $pProperies[ 'description' ];
		$this->name = $pProperies[ 'name' ];
		$this->slug = $pProperies[ 'slug' ];

		// Only available when you use the skills from the Hero JSON.
		$this->flavor = $this->getProperty( 'flavor', $pProperies );
		$this->level = $this->getProperty( 'level', $pProperies );
		$this->skillCalcId = $this->getProperty( 'skillCalcId', $pProperies );
		$this->tooltipUrl = $this->getProperty( 'tooltipUrl', $pProperies );

		// Only available when you call a skill from the tool-tip param.
		$this->orderIndex = $this->getProperty( 'orderIndex', $pProperies );
		$this->requiredLevel = $this->getProperty( 'requiredLevel', $pProperies );
		$this->simpleDescription = $this->getProperty( 'simpleDescription', $pProperies );
		$this->tooltipParams = $this->getProperty( 'tooltipParams', $pProperies );
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

	/**
	 * Get the property or return null.
	 *
	 * @param $property
	 * @param $pProperties
	 * @return null
	 */
	private function getProperty( $property, $pProperties )
	{
		if ( \array_key_exists($property, $pProperties) )
		{
			return $pProperties[ $property ];
		}

		return NULL;
	}
}
?>