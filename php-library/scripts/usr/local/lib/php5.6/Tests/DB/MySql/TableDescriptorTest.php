<?php
namespace Tests\DB\MySql;
use Library\DB;
use Library\DB\Table\Create;

/*
 * 		MySql\TableDescriptorTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * TableDescriptorTest.
 *
 * MySql class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage DB.
 */

class TableDescriptorTest extends \Application\Launcher\Testing\UtilityMethods
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

    	$this->a_dbHandle('mysql');
    	$this->a_dbConnect('mysql://localhost/jay@7mcMF596gcYa');

        if (! $this->a_serverVersion('50000'))
    	{
    		$this->assertLogMessage('Invalid mysql version ... skipping tests.');
    		return;
    	}

    	$this->a_dbExists('MySqlDbTest', false, false);
    	if ($this->a_data)
    	{
    		$this->a_dbDelete($this->a_dbName, false);
    	}

    	$this->a_dbCreate($this->a_dbName);
    	$this->a_dbExists($this->a_dbName, true, false);
    	$this->a_dbNameList();
    	
    	$this->a_printArray($this->a_dbList, 'Database Names', false, false, true);

    	$this->a_tableList($this->a_dbName, false);
    	$this->a_dbOpen($this->a_dbName);
    	
    	$this->a_printArray($this->a_tableNames, 'Table Names', false, false, true);

    	$this->a_createEmployeeTable();
		$this->a_tableList($this->a_dbName);
    	$this->a_printArray($this->a_tableNames, 'Table Names', false, false, true);

    	$this->a_dropTable('Employee');
		$this->a_tableList($this->a_dbName);
    	$this->a_printArray($this->a_tableNames, 'Table Names', false, false, true);
    	
    	$this->a_printArray($this, 'Class properties', false, false, true);
    	
    }


    public function a_createEmployeeTable()
    {
		$table = array('name' 			=> 'Employee',
			           'ifnotexists' 	=> true,
			 		   'engine' 		=> 'myisam',
					   'collation'		=> 'utf8_unicode_ci',
					   'collateDefault' => false,
					   'charset'		=> 'utf8',
					   'charsetDefault'	=> true,
					   'autoincrement'	=> '3',
					   'comment'		=> 'Employee basic data',
					   );

		$columns = array(  0 => array('field' 		=> 'id',
			  						  'type'  		=> 'int(12)',
									  'null'  		=> 'NO',
									  'extra' 		=> 'auto_increment'),

						   1 => array('field' 		=> 'firstname',
									  'type'  		=> 'varchar(36)',
									  'null'  		=> 'NO'),

						   2 => array('field' 		=> 'middlename',
									  'type'  		=> 'varchar(36)',
									  'null'  		=> 'NO'),

						   3 => array('field' 		=> 'lastname',
									  'type'  		=> 'varchar(36)',
									  'null'  		=> 'NO'),

						   4 => array('field' 		=> 'streetaddress',
									  'type'  		=> 'varchar(36)',
									  'null'  		=> 'NO'),

						   5 => array('field' 		=> 'city',
									  'type'  		=> 'varchar(48)',
									  'null'  		=> 'NO'),

						   6 => array('field' 		=> 'state',
									  'type'  		=> 'char(2)',
									  'null'  		=> 'NO'),

						   7 => array('field' 		=> 'zip',
									  'type'  		=> 'varchar(9)',
									  'null'  		=> 'NO'),

						   8 => array('field'		=> 'valid',
						   			  'type'		=> 'tinyint(1)',
						   			  'null'		=> 'NO',
						   			  'default'		=> '1'),

						   9 => array('field'		=> 'active',
						   			  'type'		=> 'tinyint(1)',
						   			  'null'		=> 'NO',
						   			  'default'		=> '1'),
						   );

		$keys = array(  0 => array('indexes'	=> array('index' 		=> 'primary',
				                                         'type'			=> 'primary',
				  				                         'fields'		=> array('id' => 0)),
                      			   'sizes'		=> array()),
					   1 => array('indexes' 	=> array('index'        => 'person',
					                        	         'type'         => 'unique',
					                        	         'fields'		=> array('lastname'   => 3,
						    													 'middlename' => 2,
						   														 'firstname'  => 1)),
						   			  'sizes'	=> array()),
		);

		$this->a_createTableStructure($table, $columns, $keys);
    }

    /**
     * a_createTableStructure
     *
     * Create a table from the descriptor
     * @param array $table   = array containing the table information
     * @param array $columns = array containing the column information
     * @param array $keys    = array containing the key information
     */
    public function a_createTableStructure($table, $columns, $keys)
    {
    	$this->labelBlock('createTableStructure', 40, '*');

    	$this->a_table	  		= $table;
		$this->a_columns 		= $columns;
		$this->a_keys    		= $keys;

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_table, 'table');
			$this->a_showData($this->a_columns, 'columns');
			$this->a_showData($this->a_keys, 'keys');
		}

    	$assertion = '$this->a_handle->createTableStructure($this->a_table, $this->a_columns, $this->a_keys);';
		if (! $this->assertExceptionFalse($assertion, sprintf("createTableStructure - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_serverVersion
     * 
     * Check minimum version
     * @param integer $minimumVersion = minimum version to check for
     * @return boolean true if minimum version is met, false if not
     */
    public function a_serverVersion($minimumVersion)
    {
    	$this->labelBlock('serverVersion', 40, '*');

    	if ($this->verbose > 1)
    	{
    		$this->a_showData($minimumVersion, 'minimumVersion');
    	}

    	$this->a_data = false;
    	$assertion = sprintf('$this->a_data = $this->a_handle->serverVersion();');
    	if (! $this->assertExceptionTrue($assertion, sprintf("serverVersion - Asserting: %s", $assertion)))
    	{
    		$this->a_showData($this->a_data, 'a_data');
    		return false;
    	}

    	if ($this->verbose > 1)
    	{
    		$this->a_showData($this->a_data, 'a_data');
    	}

    	$this->a_exceptionCaughtFalse(false);
    	
    	if ($this->a_data && ($this->a_data >= $minimumVersion))
    	{
    		return true;
    	}

    	return false;
    }

    /**
     * a_dropTable
     *
     * Drop a table
     * @param string $tableName = the table to drop
     */
    public function a_dropTable($tableName)
    {
    	$this->labelBlock('dropTable', 40, '*');

    	$this->a_tableName = $tableName;

    	if ($this->verbose > 1)
		{
			$this->a_showData($this->a_tableName, 'tableName');
		}

    	$assertion = sprintf('$this->a_handle->dropTable("%s");', $this->a_tableName);
		if (! $this->assertExceptionFalse($assertion, sprintf("dropTable - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_tableList
     *
     * Get a list of database tables
     * @param string $dbName = name of the database to query
     * @param boolean $expected = (optional) expected result, default = true
     */
    public function a_tableList($dbName=null, $expected=true)
    {
    	$this->labelBlock('tableList', 40, '*');

    	$this->a_dbName = $dbName;
    	$this->a_tableNames = null;

       	if ($this->verbose > 1)
		{
			$this->a_showData($this->a_dbName, 'dbName');
			$this->a_showData($expected, 'expected');
		}

    	if ($this->a_dbName)
    	{
   			$assertion = sprintf('$this->a_tableNames = $this->a_handle->tableList("%s")', $this->a_dbName);
    	}
    	else
    	{
   			$assertion = '$this->a_tableNames = $this->a_handle->tableList()';
    	}

    	if (! $expected)
		{
   			$assertion = sprintf('((%s) == array())', $assertion);
		}
		else
		{
			$assertion = sprintf('((%s) !== false)', $assertion);
		}

		$assertion .= ';';

		if (! $this->assertExceptionTrue($assertion, sprintf("tableList - Asserting: %s", $assertion)))
		{
			$this->showData($this->a_tableNames, 'tableNames');
			$this->a_outputAndDie();
		}
		
        if ($this->verbose > 1)
    	{
			$this->a_showData($this->a_tableNames, 'tableNames');
    	}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_dbCreate
     *
     * Create the referenced database name
     * @param string $dbName = name of the database to create
     * @param boolean $dropOkay = (optional) drop flag (true = drop if already exists, else not)
     * @param boolean $exit = (optional) exit flag on assert error (true to exit, false to not)
     */
    public function a_dbCreate($dbName, $dropOkay=true, $exit=false)
    {
    	$this->labelBlock('dbCreate', 40, '*');

    	$this->a_dbName = $dbName;
    	$this->a_dbDrop = $dropOkay;
   
        if ($this->verbose > 1)
		{
			$this->a_showData($this->a_dbName, 'dbName');
			$this->a_showData($this->a_dbDrop, 'dropOkay');
		}

    	$assertion = sprintf('$this->a_handle->dbCreate("%s", $this->a_dbDrop);', $this->a_dbName);
		if (! $this->assertExceptionTrue($assertion, sprintf("dbCreate - Asserting: %s", $assertion)))
		{
			if ($exit)
			{
				$this->a_showData($this->a_data, 'a_data');
				$this->a_outputAndDie();
			}
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_dbDelete
     *
     * Delete the referenced database name
     * @param string $dbName = name of the database to delete
     */
    public function a_dbDelete($dbName, $exit=false)
    {
    	$this->labelBlock('dbDelete', 40, '*');

    	$this->a_dbName = $dbName;
        if ($this->verbose > 1)
		{
			$this->a_showData($this->a_dbName, 'dbName');
		}

//$this->a_data = $this->a_handle->dbDelete($this->a_dbName);
    	$assertion = sprintf('$this->a_data = $this->a_handle->dbDelete("%s");', $this->a_dbName);
		if (! $this->assertExceptionTrue($assertion, sprintf("dbDelete - Asserting: %s", $assertion)))
		{
			if ($exit)
			{
				$this->a_showData($this->a_data, 'a_data');
				$this->a_outputAndDie();
			}
		}

        if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_dbList
     *
     * Get a list (array) of database names
     */
    public function a_dbNameList()
    {
    	$this->labelBlock('dbNameList', 40, '*');

    	$assertion = '$this->a_dbList = $this->a_handle->dbNameList();';
		if (! $this->assertExceptionTrue($assertion, sprintf("dbNameList - Asserting: %s", $assertion)))
		{
			$this->a_showData($this->a_dbList, 'a_dbList');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_dbList, 'a_dbList');
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_dbExists
     *
     * Open the database
     * @param string $database = database name to open
     * @param boolean $expected = (optional) expected result, default = true
     * @param boolean $exit = (optional) exit if error (true), or don't (false)
     */
    public function a_dbExists($database, $expected=true, $exit=true)
    {
    	$this->labelBlock('dbExists', 40, '*');

    	$this->a_dbName = $database;
    	$this->a_expected = $expected;

        if ($this->verbose > 1)
		{
			$this->a_showData($this->a_dbName, 'dbName');
			$this->a_showData($this->a_expected, 'expected');
		}

//$this->a_data = $this->a_handle->dbExists($this->a_dbName);
    	$assertion = sprintf('$this->a_data = $this->a_handle->dbExists("%s");', $this->a_dbName);
		if (! $this->assertExceptionTrue($assertion, sprintf("dbExists - Asserting: %s", $assertion)))
		{
			if ($exit)
			{
				$this->a_showData($this->a_data, 'a_data');
				$this->a_outputAndDie();
			}
		}

        if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_dbOpen
     *
     * Open the database
     * @param string $database = database name to open
     */
    public function a_dbOpen($database)
    {
    	$this->labelBlock('dbOpen', 40, '*');

    	$this->a_dbName = $database;
        if ($this->verbose > 1)
		{
			$this->a_showData($this->a_dbName, 'a_dbName');
		}

    	$assertion = sprintf('$this->a_dbLink = $this->a_handle->dbOpen("%s");', $this->a_dbName);
		if (! $this->assertExceptionTrue($assertion, sprintf("dbOpen - Asserting: %s", $assertion)))
		{
			$this->a_showData($this->a_dbLink, 'a_dbLink');
			$this->a_outputAndDie();
		}
		
        if ($this->verbose > 1)
		{
			$this->a_showData($this->a_dbLink, 'a_dbLink');
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_dbClose
     *
     * Close the connection to the database server
     */
    public function a_dbClose()
    {
    	$this->labelBlock('dbClose', 40, '*');

    	$assertion = '$this->a_handle->dbClose();';
		if (! $this->assertFalse($assertion, sprintf("dbClose - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_dbConnect
     *
     * Connect to the database server
     */
    public function a_dbConnect($dsn, $options=array(), $expected=true)
    {
    	$this->labelBlock('dbConnect', 40, '*');

    	$this->a_dsn = $dsn;
		$this->a_options = $options;

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_dsn, 'a_dsn');
			$this->a_showData($this->a_options, 'a_options');
		}

		$assertion = sprintf('$this->a_dbHandle = $this->a_handle->dbConnect("%s", $this->a_options);', $this->a_dsn);
    	if ($expected)
    	{
			if (! $this->assertExceptionTrue($assertion, sprintf("dbConnect - Asserting: %s", $assertion)))
			{
				$this->a_showData($this->a_dbHandle, 'a_dbHandle');
				$this->a_outputAndDie();
			}

    		if ($this->verbose > 1)
			{
				$this->a_showData($this->a_dbHandle, 'a_dbHandle');
			}

			$this->a_exceptionCaughtFalse();
    	}
    	else
    	{
			if (! $this->assertExceptionFalse($assertion, sprintf("dbConnect - Asserting: %s", $assertion)))
			{
				$this->a_showData($this->a_dbHandle, 'a_dbHandle');
				$this->a_outputAndDie();
			}

			if ($this->verbose > 1)
			{
				$this->a_showData($this->a_dbHandle, 'a_dbHandle');
			}

			$this->a_exceptionCaughtTrue();
			$this->a_printException();
    	}

    }

    /**
     * a_dbHandle
     *
     * Open the Db object handle
     * @param string $dsn = Data Source Name
     * @param array $options = array of dsn options
     */
    public function a_dbHandle($driver)
    {
    	$this->labelBlock('New dbHandle.', 40, '*');

    	$this->a_dbDriver = $driver;

    	if ($this->verbose > 1)
		{
			$this->a_showData($this->a_dbDriver, 'a_dbDriver');
		}

    	$assertion = '$this->a_handle = \Library\DB\Factory::instantiateClass($this->a_dbDriver);';
		if (! $this->assertExceptionTrue($assertion, sprintf("New %s - Asserting: %s", $this->a_dbDriver, $assertion)))
		{
			$this->a_showData($this->a_handle, 'a_handle');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_handle, 'a_handle');
		}

		$this->a_exceptionCaughtFalse();
    }

}
