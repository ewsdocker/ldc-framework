<?php
namespace Library\PDO;

use Library\Error;
use Library\DBO\StatementAbstract;
use Library\Utilities\FormatVar;

/*
 * 		Statement is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * Statement
 *
 * @author Jay Wheeler.
 * @version 1.1
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage PDO.
 */
class Statement extends StatementAbstract
{
	/**
	 * dbStatement
	 * 
	 * The mysqli_statement object instance
	 * @var object $dbStatement
	 */
	protected		$dbStatement;

	/**
	 * dbResult
	 * 
	 * The mysqli_result object instance
	 * @var object $dbResult
	 */
	protected		$dbResult;

	/**
	 * bindColumn
	 * 
	 * An array of columns and variable references to bind before excuting a fetch
	 * @var array $bindColumn
	 */
	protected		$bindColumn;

	/**
	 * columnsBound
	 * 
	 * True if columns have been bound, otherwise false
	 * @var boolean $columnsBound
	 */
	private			$columnsBound;

	/**
	 * fieldTypes
	 * 
	 * STATIC array containing mysqli data type to pdo param mapping
	 * @var array $fieldTypes
	 */
	private static	$fieldTypes = array(MYSQLI_TYPE_DECIMAL			=>	PDO::PARAM_INT,		// Field is defined as DECIMAL
				  						MYSQLI_TYPE_NEWDECIMAL		=>	PDO::PARAM_INT,		// Precision math DECIMAL or NUMERIC field
				  						MYSQLI_TYPE_BIT				=>	PDO::PARAM_BOOL,	// Field is defined as BIT
				  						MYSQLI_TYPE_TINY			=>	PDO::PARAM_INT,		// Field is defined as TINYINT
				  						MYSQLI_TYPE_SHORT			=>	PDO::PARAM_INT,		// Field is defined as SMALLINT
				  						MYSQLI_TYPE_LONG			=>	PDO::PARAM_INT,		// Field is defined as INT
				  						MYSQLI_TYPE_FLOAT			=>	PDO::PARAM_INT,		// Field is defined as FLOAT
				  						MYSQLI_TYPE_DOUBLE			=>	PDO::PARAM_INT,		// Field is defined as DOUBLE
				  						MYSQLI_TYPE_NULL			=>	PDO::PARAM_NULL,	// Field is defined as DEFAULT NULL
				  						MYSQLI_TYPE_TIMESTAMP		=>	PDO::PARAM_INT,		// Field is defined as TIMESTAMP
				  						MYSQLI_TYPE_LONGLONG		=>	PDO::PARAM_INT,		// Field is defined as BIGINT
				  						MYSQLI_TYPE_INT24			=>	PDO::PARAM_INT,		// Field is defined as MEDIUMINT
				  						MYSQLI_TYPE_DATE			=>	PDO::PARAM_INT,		// Field is defined as DATE
				  						MYSQLI_TYPE_TIME			=>	PDO::PARAM_INT,		// Field is defined as TIME
				  						MYSQLI_TYPE_DATETIME		=>	PDO::PARAM_INT,		// Field is defined as DATETIME
				  						MYSQLI_TYPE_YEAR			=>	PDO::PARAM_INT,		// Field is defined as YEAR
				  						MYSQLI_TYPE_NEWDATE			=>	PDO::PARAM_INT,		// Field is defined as DATE
				  						MYSQLI_TYPE_INTERVAL		=>	PDO::PARAM_INT,		// Field is defined as INTERVAL
				  						MYSQLI_TYPE_ENUM			=>	PDO::PARAM_INT,		// Field is defined as ENUM
				  						MYSQLI_TYPE_SET				=>	PDO::PARAM_INT,		// Field is defined as SET
				  						MYSQLI_TYPE_TINY_BLOB		=>	PDO::PARAM_LOB,		// Field is defined as TINYBLOB
				  						MYSQLI_TYPE_MEDIUM_BLOB		=>	PDO::PARAM_LOB,		// Field is defined as MEDIUMBLOB
				  						MYSQLI_TYPE_LONG_BLOB		=>	PDO::PARAM_LOB,		// Field is defined as LONGBLOB
				  						MYSQLI_TYPE_BLOB			=>	PDO::PARAM_LOB,		// Field is defined as BLOB
				  						MYSQLI_TYPE_VAR_STRING		=>	PDO::PARAM_STR,		// Field is defined as VARCHAR
				  						MYSQLI_TYPE_STRING			=>	PDO::PARAM_STR,		// Field is defined as STRING
				  						MYSQLI_TYPE_CHAR			=>	PDO::PARAM_STR,		// Field is defined as CHAR
				  						MYSQLI_TYPE_GEOMETRY		=>	PDO::PARAM_INT,		// Field is defined as GEOMETRY
				  						);

	/**
	 * __construct
	 * 
	 * Class constructor
	 */
	public function __construct($dbStatement=null, $dbResult=null)
	{
		$this->dbStatement = $dbStatement;
		$this->dbResult = $dbResult;

		$this->bindColumn = array();
		$this->columnsBound = false;
	}

	/**
	 * __destruct
	 * 
	 * Class destructor
	 */
	public function __destruct()
	{
		$this->clearCursonr();
		$this->dbStatement = null;
	}

	/**
	 * bindColumn
	 *
	 * Bind a column to a PHP variable
	 * @param mixed $column = column number (in the result set) to bind to the variable
	 * @param mixed $variable = Reference to the variable to bound to
	 * @param integer $type = (optional) Data type of the parameter, specified by the PDO::PARAM_* constants
	 * @param integer $maxlen = (optional) pre-allocation hint
	 * @param mixed $driverdata = (optional) driver parameters
	 * @return boolean result = true if successful, false if not
	 */
	public function bindColumn($column, &$param)
	{
		$this->columnsBound = false;
		$this->bindColumn[$column] = &$param;
		return true;
	}

	/**
	 * bindParam
	 *
	 * Binds a parameter to the specified variable name
	 * @param mixed $parameter = parameter identifier
	 * @param mixed $variable = Reference to the variable to be bound to
	 * @param integer $type = (optional) Data type of the parameter, specified by the PDO::PARAM_* constants
	 * @param integer $maxlen = (optional) pre-allocation hint
	 * @param mixed $driverdata = (optional) driver parameters
	 * @return boolean result = true if successful, false if not
	 */
	public function bindParam($parameter, &$variable)
	{
		$arguments = func_get_args();
		$this->columnsBound = false;

		if ((! $this->dbStatement) || (! call_user_func_array(array($this->dbStatement, 'bind_param'), $arguments)))
		{
			return false;
		}
		
		return true;
	}

	/**
	 * bindValue
	 *
	 * Binds a value to a parameter
	 * @param mixed $parameter = parameter identifier
	 * @param mixed $variable = Reference to the variable to be bound to
	 * @param integer $type = (optional) Data type of the parameter, specified by the PDO::PARAM_* constants
	 * @return boolean result = true if successful, false if not
	 */
	public function bindValue($parameter, $value)
	{
		$arguments = func_get_args();
		$this->columnsBound = false;

		if ((! $this->dbStatement) || (! call_user_func_array(array($this->dbStatement, 'bind_value'), $arguments)))
		{
			return false;
		}
		
		return true;
	}

	/**
	 * closeCursor
	 *
	 * Closes the cursor, enabling the statement to be executed again.
	 * @return boolean result = true if successful, false if not
	 */
	public function closeCursor()
	{
		if ($this->dbResult)
		{
			if ($this->dbResultMeta)
			{
				$this->dbResultMeta->free();
				$this->dbResultMeta = null;
			}

			$this->dbResult->free();
			$this->dbResult = null;
		}

		$this->columnsBound = false;
		return true;
	}

	/**
	 * columnCount
	 *
	 * Returns the number of columns in the result set
	 * @return integer $count = number of columns in the result set, or 0 on error or result set empty
	 */
	public function columnCount()
	{
		if (! $this->getResultMeta())
		{
			return 0;
		}

		return $this->dbResultMeta->field_count;
	}

	/**
	 * getResult
	 *
	 * Populates the dbResult property
	 */
	private function getResult()
	{
		if (! $this->dbResult)
		{
			return false;
		}

		return $this->dbResult;
	}

	/**
	 * getResultMeta
	 *
	 * Populates the dbResultMeta property
	 */
	private function getResultMeta()
	{
		if (! $this->dbResultMeta)
		{
			if (! $this->dbStatement)
			{
				return false;
			}
			
			$this->dbResultMeta = $this->dbStatement->result_metadata;
		}

		return $this->dbResultMeta;
	}

	/**
	 * errorCode
	 *
	 * Fetch the SQLSTATE associated with the last operation on the statement handle
	 * @return string $sqlState
	 */
	public function errorCode()
	{
		if (! $this->dbStatement)
		{
			return 'HY000';
		}

		return $this->dbStatement->$sqlstate;
	}

	/**
	 * errorInfo
	 *
	 * Returns the extended error code
	 * @return array $errorInfo
	 */
	public function errorInfo()
	{
		if (! $this->dbStatement)
		{
			return array('00000', Error::code('NotInitialized'), Error::message('NotInitialized'));
		}

		return array($this->dbStatement->$sqlstate, $this->dbStatement->$errno, $this->dbStatement->$error);
	}

	/**
	 * execute
	 *
	 * An array of values with as many elements as there are bound parameters
	 * @param array $input_parameters = An array of values with as many elements as there are bound parameters
	 * @return boolean result = true if successful, false if not
	 */
	public function execute($input_parameters)
	{
		if (! $this->dbStatement)
		{
			return false;
		}

		if ($input_parameters)
		{
			if (! is_array($input_parameters))
			{
				return false;
			}
			
			foreach($input_parameters as $name => $value)
			{
				if (! $this->bindParam($name, $value))
				{
					return false;
				}
			}
		}

		return $this->dbStatement->execute();
	}

	/**
	 * fetch
	 *
	 * Fetches the next row from a result set
	 * @param integer $fetch_style = Controls how the next row will be returned to the caller. 
	 * @param integer $cursor_orientation = (optional) For a statement object representing a scrollable cursor, determines which row will be returned.
	 * @param integer $cursor_offset = (optional) cursor offset to row to fetch
	 * @param mixed $result, or false on error
	 */
	public function fetch($fetch_style, $cursor_orientation=PDO::FETCH_ORI_NEXT, $cursor_offset=0)
	{
		$result = false;
		if ($this->dbStatement)
		{
			switch ($fetch_style)
			{
			case \PDO::FETCH_ASSOC:
				if ($dbResult = $this->getResult())
				{
					$result = $dbResult->fetch_array(MYSQLI_ASSOC);
				}
			
				break;

			case \PDO::FETCH_NUM:
				if ($dbResult = $this->getResult())
				{
					$result = $dbResult->fetch_array(MYSQLI_NUM);
				}

				break;

			case \PDO::FETCH_BOTH:
				if ($dbResult = $this->getResult())
				{
					$result = $dbResult->fetch_array(MYSQLI_BOTH);
				}

				break;

			case \PDO::FETCH_BOUND:
				if (! $this->bindColumn)
				{
					if ($this->attributes[\PDO::ATTR_ERRMODE] === \PDO::ERRMODE_EXCEPTION)
					{
						$exception = new Exception($this->dbStatement->error, $this->errorCode());
						$exception->errorinfo = $this->errorInfo();
						throw $exception;
					}

					break;
				}

				if (! $this->columnsBound)
				{
					foreach($this->bindColumn as $column => $variable)
					{
						if ((! $this->dbStatement->bind_result($column, $variable)) &&
						    ($this->attributes[\PDO::ATTR_ERRMODE] === \PDO::ERRMODE_EXCEPTION))
						{
							$exception = new Exception($this->dbStatement->error, $this->errorCode());
							$exception->errorinfo = $this->errorInfo();
							throw $exception;
						}

						break;
					}

					$this->columnsBound = true;
				}

				$result = $this->dbStatement->fetch();
				$this->dbResult = $result;

				break;

			case \PDO::FETCH_OBJECT:
			case \PDO::FETCH_CLASS:
				$result = $this->fetch_object('stdClass');
				break;

			case (\PDO::FETCH_CLASS | \PDO::FETCH_CLASS_TYPE):
				if ((! $meta = $this->dbStatement->getColumnMeta(0)) || (! isset($meta['name'])))
				{
					$meta['name'] = 'stdClass';
				}

				$result = $this->fetch_object($meta['name']);
				break;

			case \PDO::FETCH_INTO:
				$result = $this->fetch_object('stdClass');
				break;

			case \PDO::FETCH_LAZY:
				$result = $this->fetch_object('stdClass');
				break;

			default:
				break;
			}
		}

		if (! $result)
		{
			if ($this->attributes[\PDO::ATTR_ERRMODE] === \PDO::ERRMODE_EXCEPTION)
			{
				if ($this->dbStatement && $this->dbStatement->error)
				{
					$exception = new Exception($this->dbStatement->error, $this->errorCode());
				}
				else
				{
					$exception = new Exception(Error::code('DbError'), $this->errorCode());
				}

				$exception->errorinfo = $this->errorInfo();
				throw $exception;
			}

			return false;
		}

		return $result;
	}

	/**
	 * fetchAll
	 *
	 * Returns an array containing all of the result set rows
	 * @param integer $fetch_style = Controls how the next row will be returned to the caller. 
	 * @param mixed $fetch_argument = (optional) 
	 * @param array $ctor_args = (optional) Arguments of custom class constructor when the fetch_style parameter is PDO::FETCH_CLASS
	 * @return array containing all of the remaining rows in the result set
	 */
	public function fetchAll($fetch_style)
	{
		$resultSet = array();
		try
		{
			while ($result = $this->fetch($fetch_style))
			{
				array_push($resultSet, $result);
			}
		}
		catch(Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}

		return $resultSet;
	}

	/**
	 * fetchColumn
	 *
	 * Returns a single column from the next row of a result set
	 * @param integer $column_number = (optional) column to retrieve, default = 0
	 * @return mixed $value = column value, null if empty or null or error
	 */
	public function fetchColumn($column_number=0)
	{
		if (! $record = $this->fetch(\PDO::FETCH_NUM))
		{
			return false;
		}

		if ($column_number >= count($record))
		{
			return false;
		}

		return $record[$column_number];
	}

	/**
	 * fetchObject
	 *
	 * Fetches the next row and returns it as an object.
	 * @param string $class_name = (optional) name of the created class, defaults to stdClass
	 * @param array $ctor_args = (optional arguments to pass to the created class
	 */
	public function fetchObject($class_name='stdClass', $arguments=null)
	{
		if ($arguments)
		{
			$object = $this->dbResult->fetch_object($class_name, $arguments);
		}
		else
		{
			$object = $this->dbResult->fetch_object($class_name);
		}

		if (! $object)
		{
			return false;
		}
		
		return $object;
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
		return $this->dbStatement->attr_get($attribute);
	}

	/**
	 * getColumnMeta
	 *
	 * Returns metadata for a column in a result set
	 * @param int $column = column in the result set to get meta data for
	 * @return array $metaData
	 */
	public function getColumnMeta($column)
	{
		$resultMeta = array();
		if (($column <= $this->columnCount()) && (! $meta = $this->dbResult->fetch_field_direct($column)))
		{
			$resultMeta['name'] 				= $meta->orgname;
			$resultMeta['table'] 				= property_exists($meta, 'orgtable') ? $meta->orgtable : null;

			$resultMeta['driver:decl_type']		= $meta->type;
			$resultMeta['native_type'] 			= $meta->type;
			$resultMeta['pdo_type'] 			= $this->mysqliToPDO($meta->type);

			$resultMeta['flags'] 				= $meta->flags;
			$resultMeta['len'] 					= $meta->max_length;
			$resultMeta['precision'] 			= 0;
		}

		return $resultMeta;
	}

	/**
	 * mysqliToPDO
	 *
	 * map the mysqli type to the corresponding PDO::PARAM_* type
	 * @param integer $type = type to map
	 * @return integer $type = PDO::PARAM_* type
	 */
	private function mysqliToPDO($type)
	{
		if (! array_key_exists($type, self::$fieldTypes))
		{
			return PDO::PARAM_NULL;
		}
		
		return self::$fieldTypes[$type];
	}

	/**
	 * nextRowset
	 *
	 * Advances to the next rowset in a multi-rowset statement handle
	 * @return boolean result = true if successful, false if not
	 */
	public function nextRowset()
	{
		return false;
	}

	/**
	 * rowCount
	 *
	 * Returns the number of rows affected by the last SQL statement
	 * @return integer $count = the number of rows in the result set
	 */
	public function rowCount()
	{
		return $this->dbStatement->num_rows;
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
		return $this->dbStatement->attr_set($attribute, $value);
	}

	/**
	 * setFetchMode
	 *
	 * Set the default fetch mode for this statement
	 * @param integer $mode = fetch mode
	 * @return boolean $result = 1 if successful, otherwise false
	 */
	public function setFetchMode($mode)
	{
		return false;
	}

}
