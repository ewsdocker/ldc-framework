<?php
namespace Library\DB;
use Library\Error;

/*
 * 		StatementAbstract is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * DB\StatementAbstract
 *
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage DB.
 */
abstract class StatementAbstract implements StatementConstants
{

	/**
	 * __construct
	 * 
	 * Class constructor
	 *
	abstract public function __construct();

	/**
	 * Bind a column to a PHP variable
	 *
	 * Bind a column to a PHP variable
	 * @param mixed $column = column number (in the result set) to bind to the variable
	 * @param mixed $param = Reference to the variable to be bound
	 * @param integer $type = (optional) Data type of the parameter
	 * @param integer $maxlen = (optional) pre-allocation hint
	 * @param mixed $driverdata = (optional) driver parameters
	 * @return boolean result = true if successful, false if not
	 */
	abstract public function bindColumn($column, &$param);

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
	abstract public function bindParam($parameter, &$variable, $type='s');

	/**
	 * bindValue
	 *
	 * Binds a value to a parameter
	 * @param mixed $parameter = parameter identifier
	 * @param mixed $variable = Reference to the variable to be bound to
	 * @param integer $type = (optional) Data type of the parameter, specified by the PDO::PARAM_* constants
	 * @return boolean result = true if successful, false if not
	 */
	abstract public function bindValue($parameter, $value, $type='s');

	/**
	 * closeCursor
	 *
	 * Closes the cursor, enabling the statement to be executed again.
	 * @return boolean result = true if successful, false if not
	 */
	abstract public function closeCursor();

	/**
	 * columnCount
	 *
	 * Returns the number of columns in the result set
	 * @return integer $count = number of columns in the result set, or 0 on error or result set empty
	 */
	abstract public function columnCount();

	/**
	 * debugDumpParams
	 *
	 * Dump an SQL prepared command
	 */
	abstract public function debugDumpParams();

	/**
	 * errorCode
	 *
	 * Fetch the SQLSTATE associated with the last operation on the statement handle
	 * @return string $sqlState
	 */
	abstract public function errorCode();

	/**
	 * errorInfo
	 *
	 * Returns the extended error code
	 * @return array $errorInfo
	 */
	abstract public function errorInfo();

	/**
	 * execute
	 *
	 * An array of values with as many elements as there are bound parameters
	 * @param array $input_parameters = An array of values with as many elements as there are bound parameters
	 * @return boolean result = true if successful, false if not
	 */
	abstract public function execute($input_parameters);

	/**
	 * fetch
	 *
	 * Fetches the next row from a result set
	 * @param integer $fetch_style = Controls how the next row will be returned to the caller. 
	 * @param integer $cursor_orientation = (optional) For a statement object representing a scrollable cursor, determines which row will be returned.
	 * @param integer $cursor_offset = (optional) cursor offset to row to fetch
	 * @param mixed $result, or false on error
	 */
	abstract public function fetch($fetch_style, $cursor_orientation=PDO::FETCH_ORI_NEXT, $cursor_offset=0);

	/**
	 * fetchAll
	 *
	 * Returns an array containing all of the result set rows
	 * @param integer $fetch_style = Controls how the next row will be returned to the caller. 
	 * @param mixed $fetch_argument = (optional) 
	 * @param array $ctor_args = (optional) Arguments of custom class constructor when the fetch_style parameter is PDO::FETCH_CLASS
	 * @return array containing all of the remaining rows in the result set
	 */
	abstract public function fetchAll($fetch_style);

	/**
	 * fetchColumn
	 *
	 * Returns a single column from the next row of a result set
	 * @param integer $column_number = (optional) column to retrieve, default = 0
	 */
	abstract public function fetchColumn($column_number=0);

	/**
	 * fetchObject
	 *
	 * Fetches the next row and returns it as an object.
	 * @param string $class_name = (optional) name of the created class, defaults to stdClass
	 * @param array $ctor_args = (optional arguments to pass to the created class
	 */
	abstract public function fetchObject($class_name='stdClass');

	/**
	 * getAttribute
	 *
	 * Retrieve a statement attribute
	 * @param integer $attribute = attribute to get
	 * @return mixed $value
	 */
	abstract public function getAttribute($attribute);

	/**
	 * getColumnMeta
	 *
	 * Returns metadata for a column in a result set
	 * @param int $column = column in the result set to get meta data for
	 * @return array $metaData
	 */
	abstract public function getColumnMeta($column);

	/**
	 * nextRowset
	 *
	 * Advances to the next rowset in a multi-rowset statement handle
	 * @return boolean result = true if successful, false if not
	 */
	abstract public function nextRowset();

	/**
	 * rowCount
	 *
	 * Returns the number of rows affected by the last SQL statement
	 * @return integer $count = the number of rows in the result set
	 */
	abstract public function rowCount();

	/**
	 * setAttribute
	 *
	 * Set a statement attribute
	 * @param integer $attribute = attribute to fetch value for
	 * @param mixed $value = value of the attribute
	 * @return boolean result = true if successful, false if not
	 */
	abstract public function setAttribute($attribute, $value);

	/**
	 * setFetchMode
	 *
	 * Set the default fetch mode for this statement
	 * @param integer $mode = fetch mode
	 * @return boolean $result = 1 if successful, otherwise false
	 */
	abstract public function setFetchMode($mode);

}
