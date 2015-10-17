<?php namespace Kshabazz\BattleNet\D3;

/**
 * Class Profile
 *
 * @package \Kshabazz\BattleNet\D3
 */
class Profile implements \JsonSerializable
{
	use Json;

	private
		/** @var string */
		$battleTag,
		/** @var \stdClass */
		$data,
		/** @var array */
		$heroes,
		/** @var string JSON returned from Battle.Net. */
	 	$json;

	/**
	 * Constructor
	 *
	 * @param $pJson
	 * @throws \Exception
	 */
	public function __construct( $pJson )
	{
		$this->json = $pJson;
		$this->init();
		if ( isset($this->data->code) )
		{
			if ( $this->data->code === 'NOTFOUND' )
			{
				throw new \Exception( 'Profile not found.' );
			}
			else
			{
				throw new \Exception( $this->data->reason );
			}
		}
	}

	/**
	 * Get battle net tag.
	 *
	 * @return string
	 */
	public function battleTag()
	{
		return $this->data->battleTag;
	}

	/**
	 * Get property
	 */
	public function get( $pProperty )
	{
		if ( isset($this->{$pProperty}) )
		{
			return $this->{ $pProperty };
		}

		if ( isset($this->data->{$pProperty}) )
		{
			$value = $this->data->{ $pProperty };
			return $this->{ $pProperty } = $value;
		}

		$trace = \debug_backtrace();
		throw new \Exception(
			'Undefined property: ' . $pProperty .
			' in ' . $trace[0]['file'] .
			' on line ' . $trace[0]['line'],
			E_USER_NOTICE
		);
	}

	/**
	 * @return mixed Heroes(s) data as an array, or null if none.
	 */
	public function heroes()
	{
		return $this->data->heroes;
	}

	/**
	 * Get hero by name
	 *
	 * @param mixed $pHeroByName string Optional name to specify a single hero to return.
	 * @return array|null
	 */
	public function getHero( $pHeroByName )
	{
		$returnValue = NULL;
		if ( \is_array($this->data->heroes) )
		{
			foreach ( $this->data->heroes as $hero )
			{
				if ( \strcmp($pHeroByName, $hero->name) === 0 )
				{
					$returnValue = $hero;
					break;
				}
			}
		}
	    return $returnValue;
	}

	/**
	 * Initialize all the properties for this object.
	 *
	 * @return $this
	 * @throws \Exception
	 */
	protected function init()
	{
		$profile = \json_decode( $this->json );
		if ( $profile !== NULL )
		{
			$this->data = $profile;
		}
		else
		{
			throw new \Exception( 'Tried to initialize ItemModel with invalid JSON.' );
		}

		return $this;
	}
}
?>