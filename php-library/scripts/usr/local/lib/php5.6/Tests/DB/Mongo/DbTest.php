<?php
namespace Tests\DB\Mongo;
use Library\DB;

/*
 * 		DB\Mongo\DbTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * Mongo\DbTest.
 *
 * MongoDb\Object class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage DB.
 */

class DbTest extends \Application\Launcher\Testing\UtilityMethods
{
	/**
	 * __construct
	 *
	 * Class constructor - pass parameters on to parent class
	 */
	public function __construct()
	{
		parent::__construct();
		$this->assertSetup(1, 0, 0, array('Application\Launcher\Testing\Base', 'assertCallback'));
	}

	/**
	 * __destruct
	 *
	 * Class destructor.
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
     * assertionTests
     *
     * run the current assertion test steps
     * @parm string $logger = (optional) name of the logger to use, null for none 
     */
    public function assertionTests($logger=null, $format=null)
    {
    	parent::assertionTests($logger, $format);
    	$options = array('connect' 	=> false,
						 'timeout' 	=> 2000);

    	$this->a_newDb('mongo', 'mongodb://localhost:27017', $options);
    	$this->a_connect();
    	
    	$this->a_createDb('TestDatabase', true);
    	$this->a_dbExists($this->dbName);
    }

    /**
     * a_dbExists
     * 
     * Check for existence of the named database
     * @param string $dbName = database name
     */
    public function a_dbExists($dbName)
    {
    	$this->labelBlock('DB Exists', 40, '*');

    	$this->dbName = $dbName;
    	$assertion = '$this->a_exists = $this->a_dbHandle->dbExists($this->a_dbName);';
		if (! $this->assertExceptionTrue($assertion, sprintf("DB Exists - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_createDb
     * 
     * Create a new database, or open an existing database
     * @param string $dbName = database name
     * @param boolean $dropOkay = (optional) delete existing database if true
     */
    public function a_createDb($dbName, $dropOkay=false)
    {
    	$this->labelBlock('CreateDB', 40, '*');

    	$this->dbName = $dbName;
    	$this->dropOkay = $dropOkay;

    	$assertion = '$this->a_dbHandle->create($this->a_dbName, $this->a_dropOkay);';
		if (! $this->assertExceptionFalse($assertion, sprintf("CreateDB - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_connect
     *
     * Connect to the database server
     */
    public function a_connect()
    {
    	$this->labelBlock('Connect', 40, '*');

    	$assertion = '$this->a_dbHandle = $this->a_handle->connect();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Connect - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_newDb
     *
     * Open the Db object handle
     * @param string $dsn = Data Source Name
     * @param array $options = array of dsn options
     */
    public function a_newDb($driver, $dsn, $options)
    {
    	$this->labelBlock('New Db object.', 40, '*');

    	$this->a_dbDriver     = $driver;
    	$this->a_dbDsn        = $dsn;
    	$this->a_dbOptions 	  = $options;

    	$assertion = '$this->a_handle = \Library\DB\Factory::instantiateClass($this->a_dbDriver, $this->a_dbDsn, $this->a_dbOptions);';
		if (! $this->assertExceptionTrue($assertion, sprintf("New %s - Asserting: %s", $this->a_dbDriver, $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

}
