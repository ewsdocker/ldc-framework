<?php
namespace Tests\MySql;

use Library\CliParameters;
use Library\DBO\DBOConstants;

/*
 * 		MySql\DriverTest is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * MySql\DriverTest.
 *
 * MySql Driver class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage MySql.
 */

class DriverTest extends \Application\Launcher\Testing\UtilityMethods
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
		$table = 'Users';

		$dsn = sprintf('mysql:host=%s;port=3306;charset=UTF8;dbname=%s', $host, $db);

		$user = CliParameters::parameterValue('user', 'phplibuser');
		$password = CliParameters::parameterValue('password', 'phplibpwd');

		$this->a_newDriver($dsn, $user, $password);
		
		$this->a_id = 0;
		$this->a_lastname = '';
		$this->a_firstname = '';
		$this->a_middlename = '';
		$this->a_sex = '';
		$this->a_birthdate = '';

		$autocommit = DBOConstants::ATTR_AUTOCOMMIT;
		$this->a_getAttribute($autocommit, '1');

		$this->a_unPreparedTests();
		$this->a_runTransactionTests($autocommit);
		$this->a_preparedStatementTests();
	}

	/**
	 * a_preparedStatementTests
	 * 
	 * Run a group of prepared statement tests
	 */
	public function a_preparedStatementTests()
	{
		$this->a_id = 0;
		$this->a_lastname = 'moriarity';
		$this->a_firstname = 'professor';
		$this->a_middlename = 'j';
		$this->a_sex = 'f';
		$this->a_birthdate = '1859-10-01';

		$sql = "Insert into `Users` (`lastname`, `firstname`, `middlename`, `sex`, `birthdate`) values (?,?,?,?,?)";
		$this->a_prepareStatement($sql);

		$this->a_bindParam('lastname', $this->a_lastname, DBOConstants::TYPE_STRING);
		$this->a_bindParam('firstname', $this->a_firstname, DBOConstants::TYPE_STRING);
		$this->a_bindParam('middlename', $this->a_middlename, DBOConstants::TYPE_STRING);
		$this->a_bindParam('sex', $this->a_sex, DBOConstants::TYPE_STRING);
		$this->a_bindParam('birthdate', $this->a_birthdate, DBOConstants::TYPE_STRING);

		$this->a_executeStatement();

		$this->a_id = 0;
		$this->a_lastname = 'Lynch';
		$this->a_firstname = 'Frances';
		$this->a_middlename = 'Lee';
		$this->a_sex = 'f';
		$this->a_birthdate = '1949-07-30';

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
	 * bindAndExecute
	 * 
	 * Bind vars and execute the query
	 * @param string $lastname
	 * @param string $firstname
	 * @param string $middlename
	 * @param string $sex
	 * @param string $birthdate
	 */
	public function a_bindAndExecute()
	{
		$this->a_bindColumn(0, $this->a_id);
		$this->a_bindColumn(1, $this->a_lastname);
		$this->a_bindColumn(2, $this->a_firstname);
		$this->a_bindColumn(3, $this->a_middlename);
		$this->a_bindColumn(4, $this->a_sex);
		$this->a_bindColumn(5, $this->a_birthdate);

		$this->a_executeStatement();
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

		$assertion = '$this->a_data = $this->a_result->fetch();';
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
	 * a_bindValues
	 * 
	 * Bind an array of values to the prepared statement prior to execution
	 * @param array $values = array of values to be bound
	 */
	public function a_bindValues($values)
	{
		$this->labelBlock('Bind Values.', 40, '*');

		$type = array_shift($values);
		foreach($values as $column => $value)
		{
			$this->a_value = $value;
			$this->a_showData($this->a_value, 'value');

			$assertion = sprintf('$this->a_data = $this->a_result->bindValue("%s", "%s", "%s");', $column, $value, $type);
			if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
			{
				$this->a_outputAndDie();
			}

			$this->a_exceptionCaughtFalse();
		}
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
	 * a_runTransactionTests
	 * 
	 * Run a group of transaction tests
	 */
	public function a_runTransactionTests($autocommit)
	{
		$this->a_setAttribute($autocommit, false);
		$this->a_getAttribute($autocommit, '0');
		
		$this->a_beginTransaction();
		
		$sql = "INSERT INTO `Users`(`lastname`, `firstname`, `middlename`, `sex`, `birthdate`) VALUES ('wheeler','edward','a','m','1980-11-01')";
		$this->a_exec($sql, 1);
		
		$this->a_getInsertId();
		$id = $this->a_data;
		
		$this->a_rollback();
		$this->a_nextQuery("Select * from `Users` where (`lastname` like 'wheeler`) and (`firstname` like 'edward')", false, true);

		// ============================== //

		$this->a_beginTransaction();
		
		$sql = "INSERT INTO `Users`(`lastname`, `firstname`, `middlename`, `sex`, `birthdate`) VALUES ('wheeler','edward','a','m','1980-11-01')";
		$this->a_exec($sql, 1);
		
		$this->a_getInsertId();
		$id = $this->a_data;
		
		$this->a_commit();
		$this->a_nextQuery("Select * from `Users` where (`lastname` like 'wheeler') and (`firstname` like 'edward')", true, false);

		// ============================== //

		$this->a_setAttribute($autocommit, true);
		$this->a_getAttribute($autocommit, '1');

		$sql = "INSERT INTO `Users`(`lastname`, `firstname`, `middlename`, `sex`, `birthdate`) VALUES ('wheeler','edward','a','m','1980-11-01')";
		$this->a_exec($sql, 1);
		
		$this->a_getInsertId();
		$id = $this->a_data;
		
		$this->a_nextQuery('Select * from Users');

		$sql = "DELETE from `Users` where (`lastname` like 'wheeler') and (`firstname` like 'edward')";
		$this->a_exec($sql, 2);

		$this->a_nextQuery('Select * from Users');
	}

	/**
	 * unPreparedTests
	 * 
	 * Run a sequence of standard SQL (non-prepared) statements
	 * 
	 */
	public function a_unPreparedTests()
	{
		$this->labelBlock('unPreparedTests.', 50, '*');

		$sql = "DELETE from `Users` where `lastname` like 'moriarity'";
		$this->a_exec($sql);
		
		$sql = 'Select * from Users';
		$this->a_nextQuery($sql);

		$sql = "DELETE from `Users` where (`lastname` like 'wheeler') and (`firstname` like 'edward')";
		$this->a_exec($sql);
		
		$sql = 'Select * from Users';
		$this->a_nextQuery($sql);

		$sql = "INSERT INTO `Users`(`lastname`, `firstname`, `middlename`, `sex`, `birthdate`) VALUES ('moriarity','professor','j','m','1852-01-01')";
		$this->a_exec($sql, 1);
		$this->a_getInsertId();
		
		$sql = 'Select * from Users';
		$this->a_nextQuery($sql);
		
		$sql = "DELETE from `Users` where `lastname` like 'moriarity'";
		$this->a_exec($sql, 1);
		
		$sql = 'Select * from Users';
		$this->a_nextQuery($sql);
	}

	/**
	 * a_beginTransaction
	 *
	 * Begin a new transaction
	 */
	public function a_beginTransaction()
	{
		$this->labelBlock('Begin Transaction.', 60, '*');

		$assertion = '$this->a_data = $this->a_handle->beginTransaction();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
   			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_commit
	 *
	 * Commit the transaction
	 */
	public function a_commit()
	{
		$this->labelBlock('Commit Transaction.', 45, '*');

		$assertion = '$this->a_data = $this->a_handle->commit();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
   			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_rollback
	 *
	 * Rollback (cancel) the current transaction
	 */
	public function a_rollback()
	{
		$this->labelBlock('Rollback.', 40, '*');

		$assertion = '$this->a_data = $this->a_handle->rollBack();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
   			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_nextQuery
	 *
	 * Send a query to be executed by the database.  Expects a db_result object to be returned
	 * @param string $sql = SQL statement to use for query
	 */
	public function a_nextQuery($sql, $expectTrue=true, $exceptionTrue=false)
	{
		$this->labelBlock('NextQuery.', 45, '*');

		$this->a_query($sql, $expectTrue, $exceptionTrue);
		if (($expectTrue === false) || ($exceptionTrue === true))
		{
			return;
		}

		$mode = DBOConstants::FETCH_ASSOC;
		$this->a_setFetchMode($mode);
		$this->a_showResults();

		$mode = DBOConstants::FETCH_NUM;
		$this->a_setFetchMode($mode);
		$this->a_showResults();
	}

	/**
	 * a_showResults
	 * 
	 * Iterate through the result set using foreach construct(s)
	 */
	public function a_showResults()
	{
		$this->labelBlock('ShowResults.', 40, '*');

		foreach($this->a_result as $row => $record)
		{
			$this->a_showData($row, 'row');
			foreach($record as $column => $data)
			{
				$this->assertLogMessage(sprintf("\t%s = %s", $column, $data));
			}

			$this->assertLogMessage("-------------------------------");
		}
	}

	/**
	 * a_getInsertId
	 *
	 * Get the Id generated for the last autogenerated id field
	 */
	public function a_getInsertId()
	{
		$this->labelBlock('Get InsertId.', 40, '*');

		$assertion = '$this->a_data = $this->a_handle->lastInsertId();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
   			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

   		$this->a_showData($this->a_data, 'a_data');
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
		
//$this->a_data = $this->a_handle->getAttribute($this->a_attribute);
//return;
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

//$this->a_result = $this->a_handle->exec($this->a_sql);
//return;
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
	 * a_query
	 *
	 * Send a query to be executed by the database.  Expects a db_result object to be returned
	 * @param string $sql = SQL statement to use for query
	 * @param boolean $expectTrue = true if a true result is expected, false if not
	 * @param boolean $exceptionTrue = true if exception expected, false if not
	 */
	public function a_query($sql, $expectTrue=true, $exceptionTrue=false)
	{
		$this->labelBlock('Query.', 45, '*');
		
		$this->a_sql = $sql;
		$this->a_expectTrue = $expectTrue;
		$this->a_exceptionTrue = $exceptionTrue;

		$this->a_showData($this->a_sql, 'a_sql');
		$this->a_showData($this->a_expectTrue, 'expectTrue');
		$this->a_showData($this->a_exceptionTrue, 'exceptionTrue');
		
		$assertion = '$this->a_result = $this->a_handle->query($this->a_sql);';

		if ($this->a_expectTrue)
		{
			$this->a_assertResult = $this->assertExceptionTrue($assertion, sprintf("Asserting TRUE: %s", $assertion));
		}
		else 
		{
			$this->a_assertResult = $this->assertExceptionFalse($assertion, sprintf("Asserting FALSE: %s", $assertion));
		}

		if (! $this->a_assertResult)
		{
			$this->a_outputAndDie();
		}

		if ($this->a_exceptionTrue)
		{
			$this->a_exceptionCaughtTrue();
		}
		else
		{
			$this->a_exceptionCaughtFalse();
		}
		
		$this->a_showData($this->a_result, 'a_result');
	}

	/**
	 * a_newDb
	 *
	 * Open the Db object handle
	 * @param string $dsn = Data Source Name
	 * @param array $options = array of dsn options
	 */
	public function a_newDriver($dsn, $user, $password, $attributes=array())
	{
		$this->labelBlock('New Driver.', 60, '*');

		$this->a_dsn = $dsn;
		$this->a_user = $user;
		$this->a_password = $password;
		$this->a_attributes = $attributes;

   		$this->a_showData($this->a_dsn,		'a_dsn');
   		$this->a_showData($this->a_user,	   'a_user');
   		$this->a_showData($this->a_password,   'a_password');
   		$this->a_showData($this->a_attributes, 'a_attributes');

		$assertion = '$this->a_handle = new \Library\MySql\Driver($this->a_dsn, $this->a_user, $this->a_password, $this->a_attributes);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		
		$this->a_showData($this->a_handle, 'a_handle');
	}

	/**
	 * 
 	 * @param array $params
	 *
	public function a_fetchAll(array $params)
	{
		$this->labelBlock('Fetch All.', 40, '*');

		$this->a_params = array();
		foreach($params as &$reference)
		{
			$this->a_params[] = &$reference;
		}

		$this->a_showData($this->a_params, 'params');
		
		$assertion = '$this->a_array = $this->a_result->fetchAllResults($this->a_params);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_array, 'Row data');
	}
*/
}
