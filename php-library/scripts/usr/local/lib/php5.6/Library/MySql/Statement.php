<?php
namespace Library\MySql;

use Library\DBO\DBOConstants;
use Library\Error;

/*
 * 		MySql\Statement is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *		 or from http://opensource.org/licenses/academic.php
 */
/**
 * Statement
 *
 * @author Jay Wheeler.
 * @version 2.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage MySql.
 */
class Statement extends Result
{
	/**
	 * resultColumns
	 * 
	 * An array of columns and variable references to bind before excuting a fetch
	 * @var array $resultColumns
	 */
	protected $resultColumns;

	/**
	 * parameters
	 * 
	 * Array containing parameters to be bound by reference to the query input
	 * @var array $parameters
	 */
	protected $parameters;
	
	/**
	 * parameterValues
	 * 
	 * Array containing value parameters to bound to the parameters array and the query input
	 * @var array $parameterValues
	 */
	protected $parameterValues;

	/**
	 * resultVars
	 * 
	 * Array of variable values to be bound to the query at query execute
	 * @var array $resultVars
	 */
	protected $resultVars;

	/**
	 * prepared
	 * 
	 * Statement has been prepared if true
	 * @var boolean $prepared
	 */
	public $prepared;

	/**
	 * bound
	 * 
	 * True if $parameters is not empty and the variables have been bound
	 * @var boolean $bound
	 */
	public $bound;

	/**
	 * outputBound
	 * 
	 * True if $resultColumns is not empty and the columns have been bound
	 * @var boolean $bound
	 */
	public $outputBound;

	/**
	 * statement
	 * 
	 * mysqli_stmt object instance
	 * @var object $statement
	 */
	public $statement;

	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param object $driver = calling class instance (MySql\Driver class)
	 * @param string $query  = sql statement to execute
	 * @param string $resultMode = (optional) result mode, default = MYSQLI_STORE_RESULT
	 */
	public function __construct($driver, $query, $resultMode=MYSQLI_STORE_RESULT)
	{
		parent::__construct($driver);

		$this->query = $query;
		$this->resultMode = $resultMode;

		$this->bound = false;
		$this->outputBound = false;
		$this->executed = false;

		$this->parameters = array();
		$this->parameterValues = array();
		$this->resultColumns = array();

		$this->resultVars = array();

		try 
		{
			$this->statement = $this->dbLink->stmt_init();
			if (! $this->statement->prepare($this->query))
			{
				throw new Exception(Error::code('DbPrepareStmtError'));
			}
		}
		catch(mysqli_sql_exception $exception)
		{
			$this->dbLink->setErrorInfo();
			throw new Exception($exception);
		}
	}

	/**
	 * __destruct
	 * 
	 * Class destructor
	 */
	public function __destruct()
	{
		$this->closeCursor();
		if ($this->statement)
		{
			$this->statement->close();
			$this->statement = null;
		}

		$this->handle = null;
	}

	/**
	 * bindResults
	 *
	 * Bind a query result column to a PHP variable
	 * @param mixed $column = column (in the result set) to bind to the variable
	 * @param mixed $variable = Reference to the variable to be bound to
	 * @return boolean result = true if successful, false if not
	 * @throws Exception
	 */
	public function bindResults($column, &$variable)
	{
		$this->checkStatement();

		$this->resultColumns[$column] = &$variable;
		return true;
	}

	/**
	 * bindParam
	 *
	 * Binds a parameter to the specified variable name
	 * @param mixed $parameter = parameter identifier
	 * @param mixed $variable = Reference to the variable to be bound to
	 * @param integer $type = (optional) Data type of the parameter (defaults to STRING)
	 * @param integer $maxlen = (optional) pre-allocation hint
	 * @param mixed $driverdata = (optional) driver parameters
	 * @return boolean result = true if successful, false if not
	 * @throws MySql\Exception on failure
	 */
	public function bindParam($parameter, &$variable, $type=DBOConstants::TYPE_STRING)
	{
		$this->checkStatement();

		$this->parameters[$parameter] = &$variable;
		return true;
	}

	/**
	 * bindValue
	 *
	 * Binds a value to a parameter
	 * @param mixed $parameter = parameter identifier
	 * @param mixed $value = Value to bind to the parameter identifier
	 * @param mixed $type = (optional) Data type of the parameter (defaults to STRING)
	 * @return boolean result = true if successful, false if not
	 * @throws MySql\Exception
	 */
	public function bindValue($parameter, $value, $type=StatementConstant::TYPE_STRING)
	{
		$this->parameterValues[$parameter] = $value;
		return $this->bindParam($parameter, $this->parameterValues[$parameter], $type);
	}

	/**
	 * closeCursor
	 *
	 * Closes the cursor, enabling the statement to be executed again.
	 * @return boolean result = true if successful, false if not
	 */
	public function closeCursor()
	{
		$this->checkStatement();
		if ($this->handle)
		{
			if ($this->rowMetaData)
			{
				$this->rowMetaData->free();
				$this->rowMetaData = null;
			}

			$this->handle->free();
			$this->handle = null;
		}

		$this->statement->reset();
		$this->bound = false;
		$this->outputBound = false;

		$this->parameters = array();
		$this->resultColumns = array();

		return true;
	}

	/**
	 * columnCount
	 *
	 * Returns the number of columns in the result set
	 * @return integer $count = number of columns in the result set
	 */
	public function columnCount()
	{
		return $this->dbLink->field_count;
	}

	/**
	 * errorCode
	 *
	 * Fetch the SQLSTATE associated with the last operation on the statement handle
	 * @return string $sqlState
	 */
	public function errorCode()
	{
		$this->checkStatement();
		return $this->statement->sqlstate;
	}

	/**
	 * errorInfo
	 *
	 * Returns the extended error code
	 * @return array $errorInfo
	 */
	public function errorInfo()
	{
		$this->checkStatement();
		return array($this->statement->sqlstate, $this->statement->errno, $this->statement->error);
	}

	/**
	 * execute
	 *
	 * Execute the prepared statement query
	 * @param array $inputParameters = An array of values with as many elements as there are bound parameters
	 * @return boolean result = true if successful, false if not
	 * @throws MySql\Exception
	 */
	public function execute($inputParameters=null)
	{
		if ($this->handle)
		{
			$this->closeCursor();
		}

		if ($inputParameters)
		{
			$this->bindArray($inputParameters);
			$this->bound = false;
		}

		if ($this->parameters && (! $this->bound))
		{
			$this->bindInputParameters();
		}

		if ($this->resultColumns && (! $this->outputBound))
		{
			$this->bindOutputResults();
		}

		if (! $this->statement->execute())
		{
			throw new Exception(Error::code('DbQueryExecuteFailed'));
		}

		$this->executed = true;

		if ($this->columnCount() > 0)
		{
			if (! $this->outputBound)
			{
				if (! ($this->handle = $this->statement->get_result()))
				{
					throw new Exception(Error::code('DbResultNotReturned'));
				}

				if ((! is_object($this->handle)) || (! $this->handle instanceof \mysqli_result))
				{
					throw new Exception(Error::code('DbQueryResultInvalid'));
				}

				$this->handle->setRowCount();
			}

		}

		return true;
	}

	/**
	 * bindInputParameters
	 * 
	 * Bind all input parameters
	 * 
	 */
	protected function bindInputParameters()
	{
		if ($this->parameters)
		{
			$parameterCount = count($this->parameters);
			if (($count = $this->statement->param_count) !== $parameterCount) 
			{
				$errorInfo = $this->errorInfo();
				throw new Exception(Error::code('DbParameterCountInvalid'));
			}

			$callArgs = array();
			foreach($this->parameters as $index => $arg) 
			{
				$callArgs[$index] = &$this->parameters[$index];
			}

			array_unshift($callArgs, str_repeat("s", count($this->parameters)));

			if (! call_user_func_array(array($this->statement, 'bind_param'), $callArgs))
			{
				throw new Exception(Error::code('DbBindParamFailed'));
			}

			$this->bound = true;
		}

		return true;
	}
	
	/**
	 * bindOutputResults
	 * 
	 */
	protected function bindOutputResults()
	{
		if (! $this->resultColumns)
		{
			return true;
		}

		if (! call_user_func_array(array($this->statement, 'bind_result'), $this->resultColumns))
		{
			throw new Exception(Error::code('DbBindColumnFailed'));
		}

		$this->outputBound = true;
		return true;
	}

	/**
	 * fetch
	 *
	 * Fetches the next row from a result set
	 * @param integer $fetchMode = Controls how the next row will be returned to the caller. 
	 * @param integer $orientation = (optional) For a statement object representing a scrollable cursor, determines which row will be returned.
	 * @param integer $offset = (optional) cursor offset to row to fetch
	 * @return mixed $result = result (true = data returned, null = no data available, false = error <== handled by fetch())
	 * @throws MySql\Exception
	 */
	public function fetch($fetchMode=null, $orientation=null, $offset=null)
	{
		$this->checkStatement();
		
		if ($this->handle)
		{
			return parent::fetch($fetchMode, $orientation, $offset);
		}

		if (($fetchResult = $this->statement->fetch()) === false)
		{
			$errorInfo = $this->errorInfo();
			throw new Exception(Error::code('DbFetchResultFailed'));
		}

		return $fetchResult;
	}

	/**
	 * getAttribute
	 *
	 * Retrieve a statement attribute
	 * @param integer $attribute = attribute to get
	 * @return mixed $value
	 */
	public function getAttribute($attribute)
	{
		$this->checkStatement();

		$value = null;

		switch($attribute)
		{
			case DBOConstants::ATTR_RESULT_HANDLE:
				return $this->handle;

			case DBOConstants::ATTR_STATEMENT_HANDLE:
				return $this->statement;

			case DBOConstants::ATTR_GET_RESULT:
				if (! ($this->handle = $this->statement->get_result()))
				{
					throw new Ecxeption(Error::code('DbQueryResultInvalid'));
				}

				return $this->handle;

			case DBOConstants::ATTR_RESULT_COLUMNS:
				return $this->resultColumns();

			default:
				if (! ($value = $this->statement->attr_get($attribute)))
				{
					$errorInfo = $this->errorInfo();
					throw new Exception(Error::code('DbAttributeError'));
				}

				break;
		}

		return $value;
	}

	/**
	 * nextRowset
	 *
	 * Advances to the next rowset in a multi-rowset statement handle
	 * @return boolean result = true if successful, false if not
	 * @throws MySql\Exception
	 */
	public function nextRowset()
	{
		throw new Exception(Error::code('MethodNotImplemented'));
	}

	/**
	 * setAttribute
	 *
	 * Set a statement attribute
	 * @param integer $attribute = attribute to fetch value for
	 * @param mixed $value = value of the attribute
	 * @return boolean result = true if successful, false if not
	 */
	public function setAttribute($attribute, $value)
	{
		$this->checkStatement();
		
		if ($this->handle)
		{
			if ($attribute == DBOConstants::ATTR_FETCH_STYLE)
			{
				$this->fetchMode = $value;
			}
		}

		if (! $this->statement->attr_set($attribute, $value))
		{
			throw new Exception(Error::code('DbAttributeError'));
		}

		return true;
	}

	/**
	 * bindArray
	 * 
	 * @param array $inputParameters = An array of values with as many elements as there are bound parameters
	 * @throws MySql\Exception
	 */
	protected function bindArray($parameters)
	{
		if (! is_array($parameters))
		{
			throw new Exception(Error::code('ArrayVariableExpected'));
		}
			
		foreach($parameters as $name => &$value)
		{
			if (! $this->bindParam($name, $value))
			{
				throw new Exception(Error::code('DbBindParamFailed'));
			}
		}
	}

	/**
	 * checkStatement
	 * 
	 * Checks validity of the statement property
	 * @throws Exception if invalid
	 */
	protected function checkStatement()
	{
		if ((! $this->statement) || (get_class($this->statement) !== 'mysqli_stmt'))
		{
			throw new Exception(Error::code('DbStatementInvalid'));
		}
	}

	/**
	 * checkResult
	 * 
	 * Checks validity of the result property
	 * @throws Exception if invalid
	 */
	protected function checkResult()
	{
		$this->checkStatement();
		if ((! $this->handle) || (! $this->handle instanceof \mysqli_result))
		{
			throw new Exception(Error::code('DbQueryResultInvalid'));
		}
	}

}
