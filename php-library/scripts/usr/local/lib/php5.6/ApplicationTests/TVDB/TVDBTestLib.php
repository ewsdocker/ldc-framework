<?php
namespace ApplicationTests\TVDB;

use Application\Launcher\Testing\UtilityMethods;
use Library\CliParameters;

/*
 * 		TVDB\DB\DbTestLib is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * TVDB\DB\DbTestLib.
 *
 * TVDB DB Test Library methods.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage TVDB.
 */

class TVDBTestLib extends UtilityMethods
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

		$this->a_hostParam = CliParameters::parameterValue('host', '10.10.10.2');
		$this->a_dbParam = CliParameters::parameterValue('db', 'TVDB');
		$this->a_dbDeleteAll = CliParameters::parameterValue('dbreset', false);

		$this->a_userParam = CliParameters::parameterValue('user', 'phplibuser');
		$this->a_passwordParam = CliParameters::parameterValue('password', 'phplibpwd');

		$this->a_dsnParam = sprintf('mysql:host=%s;port=3306;charset=UTF8;dbname=%s', $this->a_hostParam, $this->a_dbParam);

		$this->a_apiKey = CliParameters::parameterValue('apikey', 'D29BEE8036451BD0');
		$this->a_apiUrl = CliParameters::parameterValue('apiurl', 'http://thetvdb.com/api/');

		$this->a_seriesName = CliParameters::parameterValue('series', null);
		$this->a_seriesid = CliParameters::parameterValue('seriesid', null);
		$this->a_seriesUrl = sprintf('%s%s/series/%s/%s', $this->a_apiUrl, $this->a_apiKey, $this->a_seriesid, $this->a_xmlFile);

		$this->a_account = CliParameters::parameterValue('account', '9FC72AE60462A27C');
		$this->a_accountOwn = CliParameters::parameterValue('accountown', 'jaywheeler22');
		$this->a_accountPwd = CliParameters::parameterValue('accountpwd', 'MurPhy22');

		$this->a_subTest = CliParameters::parameterValue('subtest', 'all');
		$this->a_updateSeries = CliParameters::parameterValue('update', '0');

		$table = CliParameters::parameterValue('table', null);

		if ($table != null)
		{
			$this->a_table = $table;
		}
	}

	/**
	 * a_dropTables
	 *
	 * Drop all of the tables in the database
	 * @param array $tables = array of table names to be dropped
	 */
	public function a_dropTables($tables)
	{
		$this->labelBlock('Drop Tables.', 40, '*');

		$this->a_localArray = $tables;
		$this->a_showData($this->a_localArray, 'Tables');

		$assertion = '($this->a_handle->dropTable($this->a_localArray) !== false);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_updateData
	 *
	 * Post downloaded information from the xml data class
	 * @param integer $seriesId = id of the series that the data belongs to
	 */
	public function a_updateData($SeriesId, $dataLink)
	{
		$this->labelBlock('Update Data.', 40, '*');

		$this->a_SeriesId = $SeriesId;
		$this->a_dataLink = $dataLink;

		$this->a_showData($this->a_SeriesId, 'SeriesId');
		$this->a_showData($this->a_dataLink, 'DataLink');

		$assertion = '($this->a_handle->updateData($this->a_SeriesId, $this->a_dataLink) != true);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_insertData
	 *
	 * Post downloaded information from the xml data class
	 * @param integer $seriesId = id of the series that the data belongs to
	 */
	public function a_insertData($SeriesId, $dataLink)
	{
		$this->labelBlock('Insert Data.', 40, '*');

		$this->a_SeriesId = $SeriesId;
		$this->a_dataLink = $dataLink;

		$this->a_showData($this->a_SeriesId, 'SeriesId');
		$this->a_showData($this->a_dataLink, 'DataLink');

		$assertion = '($this->a_handle->insertData($this->a_SeriesId, $this->a_dataLink) != true);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_rewind
	 *
	 * Rewind the table data pointer
	 */
	public function a_rewind()
	{
		$this->labelBlock('Rewind.', 40, '*');

		$assertion = '(($this->a_handle->rewind()) !== true);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_listDataObject
	 *
	 * List the table as a data object
	 */
	public function a_listDataObject()
	{
		$this->labelBlock('List Data Object.', 40, '*');

		$assertion = '(($this->a_buffer = (string)$this->a_handle) !== false);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_buffer, 'DataObject:');
	}

	/**
	 * a_listData
	 *
	 * Select data and list
	 */
	public function a_listData()
	{
		$this->a_getFieldDescriptors();

		$recordNumber = 0;

		foreach($this->a_fields as $descriptor)
		{
			$fieldName = $descriptor->name;

			switch(substr($descriptor->type, 0, 3))
			{
				case 'int':
				default:
					$$fieldName = 0;
					break;

				case 'var':
					$$fieldName = '';
					break;

				case 'cha':
					$$fieldName = "";
					break;

				case 'dec':
					$$fieldName = 0.0;
					break;
			}
		}

		$sql = "Select ";
		$fieldList = "";
		foreach($this->a_fields as $column => $descriptor)
		{
			$fieldName = $descriptor->name;

			$$fieldName = $descriptor->default;

			if ($fieldList !== '')
			{
				$fieldList .= ', ';
			}

			$fieldList .= sprintf("`%s`", $fieldName);
		}

		$sql .= sprintf("%s from %s", $fieldList, $this->a_table);

		$this->a_prepareStatement($sql);

		$id = 0;
		foreach($this->a_fields as $column => $descriptor)
		{
			$fieldName = $descriptor->name;
			$this->a_bindColumn($column, $$fieldName);
		}

		$this->a_executeStatement();

		$this->a_data = true;
		$rowNumber = 0;

		while($this->a_data)
		{
			$this->a_fetch();
			if (! $this->a_data)
			{
				if ($rowNumber == 0)
				{
					$this->assertLogMessage("NO DATA RETURNED");
				}

				break;
			}

			$this->a_showData($rowNumber++, 'ROW');

			foreach($this->a_fields as $column => $descriptor)
			{
				$fieldName = $descriptor->name;
				$this->a_showData($$fieldName, sprintf("\t%s", $fieldName));
			}

			$this->assertLogMessage("----------");
		}

		$this->assertLogMessage("----------");
	}

	/**
	 * a_getFieldDescriptors
	 *
	 * Get the field names of the current table
	 */
	public function a_getFieldDescriptors()
	{
		$this->labelBlock('Get FieldDescriptors.', 40, '*');

		$assertion = '$this->a_fieldDescriptors = $this->a_handle->fieldDescriptors();';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_fieldDescriptors, 'dbFields');

		$this->a_fields = $this->a_fieldDescriptors->fields();

		$this->a_showData($this->a_fields, 'fieldDescriptors');
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

		$assertion = '(($this->a_data = $this->a_result->fetch()) !== null);';
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
	 * a_tableDescriptor
	 *
	 * Get the tableDescriptor property
	 * @param mixed $expected = (optional) expected result
	 */
	public function a_tableDescriptor($expected=null)
	{
		$this->labelBlock('Table Descriptor.', 40, '*');

		$this->a_expected = $expected;
		$this->a_showData($this->a_expected, 'expected');

		$assertion = '$this->a_data = $this->a_handle->tableDescriptor();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			;
		}

		$this->a_showData($this->a_data, 'a_data');
		$this->a_exceptionCaughtFalse();

		if ($this->a_expected !== null)
		{
			$this->a_compareExpected($this->a_expected);
		}
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
	 * a_openTableDriver
	 *
	 */
	public function a_openTableDriver($class, $dsn, $user, $password, $options=array())
	{
		$this->labelBlock('Open Table Driver.', 40, '*');

		$this->a_class = sprintf('Application\TVDB\DB\Db%s', $class);
		$this->a_dsn = $dsn;
		$this->a_user = $user;
		$this->a_password = $password;
		$this->a_options = $options;

		$this->a_showData($this->a_class,      'a_class');
		$this->a_showData($this->a_dsn,		   'a_dsn');
		$this->a_showData($this->a_user,	   'a_user');
		$this->a_showData($this->a_password,   'a_password');
		$this->a_showData($this->a_options,    'a_options');

		$assertion = sprintf('$this->a_handle = new %s($this->a_dsn, $this->a_user, $this->a_password, $this->a_options);', $this->a_class);
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_handle, 'a_handle');
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

	/**
	 * a_newDataClass
	 *
	 * Create a new data class object
	 * @param string $class = TVDB data class name to create
	 * @param string $url = url of the class api
	 */
	public function a_newDataClass($class, $url, $callback=null)
	{
		$this->labelBlock('New Data Class Test.', 60, '*');

		$this->a_class = $class;
		$this->a_url = $url;
		$this->a_callback = $callback;

		$this->a_showData($this->a_class, 'Data Class');
		$this->a_showData($this->a_url, 'URL');
		$this->a_showData($this->a_callback, 'callback');

		$assertion = sprintf('$this->a_dataLink = new \Application\TVDB\%s($this->a_url, $this->a_callback);', $this->a_class);
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, sprintf('Application\TVDB\%s', $this->a_class), get_class($this->a_dataLink));

		$this->a_showData($this->a_dataLink, 'Data Link');
		$this->a_showData((string)$this->a_dataLink);
	}

	/**
	 * a_records
	 *
	 * Get the xml records array
	 */
	public function a_records()
	{
		$this->labelBlock('records Test.', 40, '*');

		$assertion = '(($this->a_recordsArray = $this->a_dataLink->records()) !== false);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_recordsArray, 'recordsArray');
	}

	/**
	 * a_listRecordData
	 *
	 * List the records data
	 */
	public function a_listRecordData()
	{
		$this->labelBlock('List Record Data Test.', 50, '*');

		foreach($this->a_recordsArray as $element => $data)
		{
			$this->a_showData($data, $element);
		}
	}

}
