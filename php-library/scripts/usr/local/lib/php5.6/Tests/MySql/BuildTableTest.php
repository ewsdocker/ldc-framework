<?php
namespace Tests\MySql;

use Library\CliParameters;
use Library\DBO\DBOConstants;

/*
 * 		MySql\BuildTableTest is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * MySql\BuildTableTest.
 *
 * MySql Table class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage MySql.
 */

class BuildTableTest extends \Application\Launcher\Testing\UtilityMethods
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

		$host = CliParameters::parameterValue('host', '10.10.10.4');
		$db = CliParameters::parameterValue('db', 'ProjectLibraryTest');
		$table = CliParameters::parameterValue('table', 'TestUser');

		$dsn = sprintf('mysql:host=%s;port=3306;charset=UTF8;dbname=%s', $host, $db);

		$user = CliParameters::parameterValue('user', 'phplibuser');
		$password = CliParameters::parameterValue('password', 'phplibpwd');

		$this->a_newTableDriver($dsn, $user, $password);

		$this->a_tableExists($table);

		if ($this->a_exists)
		{
			$this->a_dropTable($table);
		}

		$properties = array('name' 				=> $table,
							'ifNotExists' 		=> true,
							'storageEngine' 	=> 'innodb',
						    );

		$fieldTypes = array('id' 			=> 'int(7) AUTO_INCREMENT',
							'lastname'		=> 'varchar(36)',
							'firstname'		=> 'varchar(24)',
							'middlename'	=> 'varchar(24)',
							'sex'			=> 'char(1)',
							'birthdate'		=> 'char(10)',
							);
		
		$fieldNullAllowed = array('id'			=> true,
								  'lastname'	=> false,
								  'firstname'	=> false,
								  'middlename'	=> false,
								  'sex'			=> true,
								  'birthdate'	=> true,
								  );

		$this->a_buildDescriptor($properties, $fieldTypes, $fieldNullAllowed);
		$this->a_showDescriptor($this->a_tableDescriptor);

		$this->a_createTable($this->a_tableDescriptor);
		
		$this->a_id = 0;
		$this->a_lastname = '';
		$this->a_firstname = '';
		$this->a_middlename = '';
		$this->a_sex = '';
		$this->a_birthdate = '';
		
		$this->a_buildTestData();
	}

	/**
	 * a_buildTestData
	 * 
	 * Run a group of prepared statement tests
	 */
	public function a_buildTestData()
	{
		$this->a_id = 0;
		$this->a_lastname = 'wright';
		$this->a_firstname = 'jay';
		$this->a_middlename = 'anthony';
		$this->a_sex = 'm';
		$this->a_birthdate = '1950-10-31';

		$sql = "Insert into `Users` (`lastname`, `firstname`, `middlename`, `sex`, `birthdate`) values (?,?,?,?,?)";
		$this->a_prepareStatement($sql);

		$this->a_bindParam('lastname', $this->a_lastname, DBOConstants::TYPE_STRING);
		$this->a_bindParam('firstname', $this->a_firstname, DBOConstants::TYPE_STRING);
		$this->a_bindParam('middlename', $this->a_middlename, DBOConstants::TYPE_STRING);
		$this->a_bindParam('sex', $this->a_sex, DBOConstants::TYPE_STRING);
		$this->a_bindParam('birthdate', $this->a_birthdate, DBOConstants::TYPE_STRING);

		$this->a_executeStatement();

		$this->a_id = 0;
		$this->a_lastname = 'wheeler';
		$this->a_firstname = 'frances';
		$this->a_middlename = 'lee';
		$this->a_sex = 'f';
		$this->a_birthdate = '1949-07-30';

		$this->a_executeStatement();

		$this->a_id = 0;
		$this->a_lastname = 'wheeler';
		$this->a_firstname = 'edward';
		$this->a_middlename = 'anthony';
		$this->a_sex = 'm';
		$this->a_birthdate = '1980-11-01';

		$this->a_executeStatement();

		$this->a_id = 0;
		$this->a_lastname = 'wheeler';
		$this->a_firstname = 'michael';
		$this->a_middlename = 'allan';
		$this->a_sex = 'm';
		$this->a_birthdate = '1980-11-01';

		$this->a_executeStatement();

		$this->a_closeCursor();
		$this->a_result = null;

		$sql = "Select `id`, `lastname`, `firstname`, `middlename`, `sex`, `birthdate` from `Users` where `lastname` like ?";
		$this->a_prepareStatement($sql);

		$this->a_bindParam(0, $this->a_lastname, 's');

		$this->a_bindColumn(0, $this->a_id);
		$this->a_bindColumn(1, $this->a_lastname);
		$this->a_bindColumn(2, $this->a_firstname);
		$this->a_bindColumn(3, $this->a_middlename);
		$this->a_bindColumn(4, $this->a_sex);
		$this->a_bindColumn(5, $this->a_birthdate);

		$this->a_executeStatement();
		
		$this->a_data = true;
		$rowNumber = 0;

		while($this->a_data)
		{
			$this->a_fetch();
			if (! $this->a_data)
			{
				break;
			}

			$this->a_showData($rowNumber++, 'ROW');
			$this->a_showData($this->a_id, "\tid");
			$this->a_showData($this->a_lastname, "\tlastname");
			$this->a_showData($this->a_firstname, "\tfirstname");
			$this->a_showData($this->a_middlename, "\tmiddlename");
			$this->a_showData($this->a_sex, "\tsex");
			$this->a_showData($this->a_birthdate, "\tbirthdate");

			$this->assertLogMessage("----------");
		}
		
	}

	/**
	 * a_closeCursor
	 * 
	 * Close the current cursor
	 */
	public function a_closeCursor()
	{
		$this->labelBlock('Close Cursor.', 40, '*');

		$assertion = '$this->a_data = $this->a_result->closeCursor();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_fetch
	 * 
	 * Fetch the next statement result
	 */
	public function a_fetch()
	{
		$this->labelBlock('Fetch.', 40, '*');

		$assertion = '(($this->a_data = $this->a_result->fetch()) !== true);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			// ignore and let caller test a_data for result
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_bindColumn
	 * 
	 * Bind a column and values to the prepared statement output
	 * @param $column = column to bind
	 * @param $variable = the variable to bind to the column
	 */
	public function a_bindColumn($column, &$variable)
	{
		$this->labelBlock('Bind Column.', 40, '*');

		$this->a_column = $column;
		$this->a_variable = &$variable;
		
		$this->a_showData($this->a_column,   'column');
		$this->a_showData($this->a_variable, 'variable');
		
		$assertion = '$this->a_data = $this->a_result->bindResults($this->a_column, $this->a_variable);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_bindParam
	 * 
	 * Bind a parameter to the prepared statement prior to execution
	 * @param string $parameter = parameter name to be bound to
	 * @param mixed $variable = variable to bind to $parameter
	 * @param string $type = variable type
	 */
	public function a_bindParam($parameter, &$variable, $type)
	{
		$this->labelBlock('Bind Param.', 40, '*');

		$this->a_parameter = $parameter;
		$this->a_variable = &$variable;
		$this->a_type = $type;
		
		$this->a_showData($this->a_parameter, 'parameter');
		$this->a_showData($this->a_variable,  'variable');
		$this->a_showData($this->a_type, 	  'type');

		$assertion = '$this->a_data = $this->a_result->bindParam($this->a_parameter, $this->a_variable, $this->a_type);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_prepareStatement
	 * 
	 * Prepare an sql statement for execution. Expects a MySql\Statement object to be returned
	 * @param string $sql = SQL statement to prepare
	 */
	public function a_prepareStatement($sql)
	{
		$this->labelBlock('Prepare Statement.', 50, '*');

		$this->a_result = null;

		$this->a_sql = $sql;
		$this->a_showData($this->a_sql, 'sql');

		$assertion = '$this->a_result = $this->a_handle->prepare($this->a_sql);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_result, 'a_result');
		$this->a_compareExpectedType(true, get_class($this->a_result), 'Library\MySql\Statement');
	}

	/**
	 * a_executeStatement
	 * 
	 * Execute a prepared statement
	 */
	public function a_executeStatement()
	{
		$this->labelBlock('Execute Statement.', 40, '*');

		$assertion = '$this->a_data = $this->a_result->execute();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_dropTable
	 *
	 * Drop the table described in $descriptor
	 * @param object $tableDescriptor = table descriptor
	 */
	public function a_dropTable($tableDescriptor)
	{
		$this->labelBlock('Drop Table.', 40, '*');

		$this->a_dropDescriptor = $tableDescriptor;
		$this->a_showData($this->a_dropDescriptor);
		
		$assertion = '$this->a_handle->dropTable($this->a_dropDescriptor);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
   			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_buildDescriptor
	 *
	 * Create the table described in $descriptor
	 * @param object $tableDescriptor = table descriptor
	 */
	public function a_buildDescriptor($properties, $fieldTypes, $fieldNullAllowed)
	{
		$this->labelBlock('Build Descriptor.', 40, '*');

		$this->a_tableProperties = $properties;
		$this->a_fieldTypes = $fieldTypes;
		$this->a_fieldNullAllowed = $fieldNullAllowed;
		
		$this->a_showData($this->a_tableProperties, 'Table Properties');
		$this->a_showData($this->a_fieldTypes, 'Field Types');
		$this->a_showData($this->a_fieldNullAllowed, 'Field Null Allowed');

//$this->a_tableDescriptor = $this->a_handle->buildTableDescriptor($this->a_tableProperties, $this->a_fieldTypes, $this->a_fieldNullAllowed);
//return;
		$assertion = '$this->a_tableDescriptor = $this->a_handle->buildTableDescriptor($this->a_tableProperties, $this->a_fieldTypes, $this->a_fieldNullAllowed);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
   			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_showDescriptor
	 * 
	 * Display the contents of the requested descriptor
	 * @param object $descriptor
	 */
	public function a_showDescriptor(&$descriptor)
	{
		$this->labelBlock('Show Descriptor', 40, '*');

		$this->a_showData($descriptor, get_class($descriptor));
		$this->a_showData((string)$descriptor);
	}

	/**
	 * a_createTable
	 *
	 * Create the table described in $descriptor
	 * @param object $tableDescriptor = table descriptor
	 */
	public function a_createTable($tableDescriptor)
	{
		$this->labelBlock('create Table.', 40, '*');

		$this->a_createDescriptor = $tableDescriptor;
		$this->a_showData((string)$this->a_createDescriptor, 'CREATE COMMAND');
		
		$assertion = '$this->a_handle->createTable($this->a_createDescriptor);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
   			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_setAttribute
	 *
	 * Set the requested attribute to the provided value
	 * @param integer $attribute = attribute to set
	 * @param mixed $value = value to set attribute to
	 */
	public function a_setAttribute($attribute, $value)
	{
		$this->labelBlock('SetAttribute.', 40, '*');

		$this->a_attribute = $attribute;
		$this->a_value = $value;
		
		$this->a_showData($this->a_attribute, 'attribute');
		$this->a_showData($this->a_value, 'value');

		$assertion = '$this->a_data = $this->a_handle->setAttribute($this->a_attribute, $this->a_value);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
   			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

   		$this->a_showData($this->a_data, 'a_data');
	}

	/**
	 * a_getAttribute
	 *
	 * Get the requested attribute setting
	 * @param integer $attribute = attribute to get
	 * @param mixed $expected = (optional) expected result
	 */
	public function a_getAttribute($attribute, $expected=null)
	{
		$this->labelBlock('GetAttribute.', 40, '*');

		$this->a_attribute = $attribute;
		$this->a_expected = $expected;
		
		$this->a_showData($this->a_attribute, 'attribute');
		$this->a_showData($this->a_expected, 'expected');

		$this->a_data = null;
		
		$assertion = '(($this->a_data = $this->a_handle->getAttribute($this->a_attribute)) !== false);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
		}

   		$this->a_showData($this->a_data, 'a_data');
		$this->a_exceptionCaughtFalse();

		if ($this->a_expected !== null)
		{
			$this->a_compareExpected($this->a_expected);
		}
	}

	/**
	 * a_setFetchMode
	 *
	 * Set the fetch mode for the current result
	 * @param string $mode = Fetch mode
	 */
	public function a_setFetchMode($mode)
	{
		$this->labelBlock('SetFetchMode.', 40, '*');
		
		$this->a_mode = $mode;
		
		$this->a_showData($this->a_mode, 'a_mode');

		$assertion = '($this->a_result->setFetchMode($this->a_mode) !== true);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_tableExists
	 *
	 * check if the requested table exists
	 * @param string $name = table name
	 */
	public function a_tableExists($name)
	{
		$this->labelBlock('TableExists.', 40, '*');
		
		$this->a_name = $name;
		$this->a_showData($this->a_name, 'a_name');

		$assertion = '$this->a_exists = $this->a_handle->tableExists($this->a_name)';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			;
		}
		
		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_exists, 'Exists');
	}

	/**
	 * a_exec
	 *
	 * Send a query to be executed by the database.  Expects number of rows affected to be returned
	 * @param string $sql = SQL statement to use for query
	 * @param integer $expected = (optional) number of rows expected to be affected
	 */
	public function a_exec($sql, $expected=null)
	{
		$this->labelBlock('Exec.', 45, '*');
		
		$this->a_sql = $sql;
		$this->a_expected = $expected;
		
		$this->a_showData($this->a_sql, 'a_sql');
		$this->a_showData($this->a_expected, 'a_expected');

		$assertion = '$this->a_result = $this->a_handle->exec($this->a_sql);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			if ($this->a_expected !== null)
			{
				$this->a_outputAndDie();
			}
		}
		
		if ($this->exceptionCaught())
		{
			$this->a_result = false;
			if ($expected == null)
			{
				$this->assertLogMessage('Exception caught...');
			}
			else 
			{
				$this->a_printException();
			}
		}

		if ($this->a_expected !== null)
		{
			$this->a_compareExpectedType(true, $this->a_expected, $this->a_result);
		}
		else 
		{
			$this->a_showData($this->a_result, 'result');
		}
	}

	/**
	 * a_newTableDriver
	 *
	 * Open the MySql\Table driver
	 * @param string $dsn = Data Source Name
	 * @param array $options = array of dsn options
	 */
	public function a_newTableDriver($dsn, $user, $password, $attributes=array())
	{
		$this->labelBlock('New Table Driver.', 60, '*');

		$this->a_dsn = $dsn;
		$this->a_user = $user;
		$this->a_password = $password;
		$this->a_attributes = $attributes;

   		$this->a_showData($this->a_dsn,		   'a_dsn');
   		$this->a_showData($this->a_user,	   'a_user');
   		$this->a_showData($this->a_password,   'a_password');
   		$this->a_showData($this->a_attributes, 'a_attributes');

		$assertion = '$this->a_handle = new \Library\MySql\Table($this->a_dsn, $this->a_user, $this->a_password, $this->a_attributes);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		
		$this->a_showData($this->a_handle, 'a_handle');
	}

}
