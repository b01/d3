<?php namespace kshabazz\d3a\Controller;
use \kshabazz\d3a;
use \kshabazz\d3a\Abstract_Controller;
use \kshabazz\d3a\Application;
use \kshabazz\d3a\BattleNet_Requestor;
use \kshabazz\d3a\Model_GetProfile;
use \kshabazz\d3a\BattleNet_Sql;
/**
* Controller for the home page.
*/
class GetProfile extends Abstract_Controller
{
	protected
		$app,
		$battleNetId,
		$dqi,
		$model,
		$sql;

	/**
	* Controller actions go here.
	*/
	public function __construct( Application $pApp )
	{
		$this->app = $pApp;
		$this->dqi = NULL;
		$this->sql = NULL;
		$this->battleNetId = $this->app->getParam( 'battleNetId' );

		$this->setup();
	}

	public function setup()
	{
		$this->dqi = new BattleNet_Requestor( $this->battleNetId );
		$this->sql = new BattleNet_Sql(
			\kshabazz\d3a\DSN,
			\kshabazz\d3a\DB_USER,
			\kshabazz\d3a\DB_PSWD,
			\kshabazz\d3a\USER_IP_ADDRESS
		);
		$this->model = new Model_GetProfile( $this->app->superGlobals(), $this->dqi, $this->sql );
		$this->app->setModel( $this->model );
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