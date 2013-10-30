<?php namespace kshabazz\d3a\Controller;
/**
* Controller for the home page.
*/
class GetHero extends \kshabazz\d3a\Abstract_Controller
{
	protected
		$app,
		$battleNetUrlSafeId,
		$id,
		$cache,
		$items,
		$bnrHero,
		$hero,
		$heroItems,
		$model,
		$view;

	/**
	* Controller actions go here.
	*/
	public function __construct( \kshabazz\d3a\Application $pApp )
	{
		$this->app = $pApp;
		$this->battleNetUrlSafeId = $this->app->getParam( 'battleNetId', '' );
		var_dump($this->battleNetUrlSafeId);
		$this->id = $this->app->getParam( 'heroId' );
		$this->cache = ( bool )$this->app->getParam( 'cache' );
		$this->items = NULL;
		$this->bnrHero = NULL;
		$this->dqi = NULl;
		$this->sql = NULL;
		$this->bnrHero = NULL;
		$this->hero = NULL;
		$this->heroItems = [];

		$this->setupModel();
	}

	/**
	*
	*/
	public function getModel( $pModel = NULL )
	{
		return $this->model;
	}

	/**
	*
	*/
	public function setModel( $pModel = NULL )
	{
		$this->model = $pModel;
	}

	/**
	*
	*/
	public function getView()
	{
		return $this->view;
	}

	/**
	*
	*/
	public function setView( $pView = NULL )
	{
		$this->view = $pView;
	}

	public function setupModel()
	{
		if ( \kshabazz\d3a\isString($this->battleNetUrlSafeId) && \kshabazz\d3a\isString($this->id) )
		{
			// Check if the cache has expired for the hero JSON.
			$this->sessionCacheInfo = \kshabazz\d3a\getSessionExpireInfo( 'heroTime', $this->cache );
			// Put the hash back in the BattleNet ID.
			$battleNetId = \str_replace( '-', '#', $this->battleNetUrlSafeId );
			// Build the view model.
			$this->bnr = new \kshabazz\d3a\BattleNet_Requestor( $battleNetId );
			$this->sql = new \kshabazz\d3a\BattleNet_Sql(
				\kshabazz\d3a\DSN,
				\kshabazz\d3a\DB_USER,
				\kshabazz\d3a\DB_PSWD,
				\kshabazz\d3a\USER_IP_ADDRESS
			);
			$this->bnrHero = new \kshabazz\d3a\BattleNet_Hero(
				$this->id,
				$this->bnr,
				$this->sql,
				$this->sessionCacheInfo['loadFromBattleNet']
			);

			$this->model = new \kshabazz\d3a\Model_GetHero(
				$this->bnrHero,
				$this->app->retrieve('attribute_map'),
				$this->bnr,
				$this->sql
			);

			$this->hero = new \kshabazz\d3a\Hero( $this->bnrHero->json() );
			$this->model->setHero( $this->hero );
		}
	}

	/**
	* Set BattleNet_Requestor object
	*/
	public function setBnr( BattleNet_Requestor $pBnr )
	{
		// Set a valid BattleNet_Requestor object or throw an exception.
		if ( $pBnr instanceof BattleNet_Requestor )
		{
			$this->dqi = $pBnr;
			return $this;
		}

		throw new Exception( 'Must be a valid BattleNet_Requestor object, no other values are excepted, not even NULL.' );
	}

	/**
	* Set BattleNet_Sql object
	*/
	public function setSql( \kshabazz\d3a\BattleNet_Sql $pSql)
	{
		// Set a valid BattleNet_Sql object or throw an exception.
		if ( $pSql instanceof \kshabazz\d3a\BattleNet_Sql )
		{
			$this->sql = $pSql;
			return $this;
		}

		throw new Exception( 'Must be a valid BattleNet_Sql object, no other values are excepted, not even NULL.' );
	}

	/**
	* Set Hero object
	*
	* @param Hero $pHero
	* @return GetHero
	*/
	public function setHero( \kshabazz\d3a\Hero $pHero )
	{
		// Set a valid Hero object or throw an exception.
		if ( $pHero instanceof \kshabazz\d3a\Hero )
		{
			$this->hero = $pHero;
			return $this;
		}

		throw new Exception( 'Must be a valid Hero object, no other values are excepted, not even NULL.' );
	}
}
?>