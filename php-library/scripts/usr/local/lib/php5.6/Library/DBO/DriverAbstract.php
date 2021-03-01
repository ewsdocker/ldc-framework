<?php
namespace Library\DBO;

use Library\Error;

/*
 * 		DriverAbstract is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * DBO\DriverAbstract
 *
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage DBO
 */
abstract class DriverAbstract
{
	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param string $dsn = Data Source Name
	 * @param string $user = (optional) user name
	 * @param string $password = (optional) password
	 * @param array $attributes = (optional) attributes array
	 * @throws \Library\DBO\Exception
	 *
	abstract public function __construct($dsn);

	/**
	 * beginTransaction
	 *
	 * Begin a transaction
	 * @return boolean result = true if successful, false if not
	 */
	abstract public function beginTransaction();

	/**
	 * commit
	 * 
	 * Commits a transaction
	 * @return boolean result = true if successful, false if not
	 */
	abstract public function commit();

	/**
	 * errorCode 
	 *
	 * Returns the current SQL_STATE error code
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
	 * exec
	 *
	 * Execute the sql statement and return the number of affected rows
	 * @param string $statement = sql statement to execute
	 * @return integer $rows = number of rows affected.
	 */
	abstract public function exec($statement);

	/**
	 * getAttribute
	 *
	 * Returns a database connection attribute
	 * @param string $attribute = One of the PDO::ATTR_* constants.
	 * @return mixed $attributeValue
	 */
	abstract public function getAttribute($attribute);

	/**
	 * inTransaction
	 *
	 * Checks if inside a transaction
	 * @return boolean true = inside a transaction, false = not
	 */
	abstract public function inTransaction();

	/**
	 * lastInsertId
	 *
	 * Returns the last insert id or sequence
	 * @param string $name = (optional) name of the sequence object
	 * @return string $id
	 */
	abstract public function lastInsertId($name=null);

	/**
	 * prepare
	 *
	 * Prepares a statement for execution and returns a statement object.
	 * @param string $statement = sql statement to prepare
	 * @return object $statementObject, or false if failed
	 * @throws \Library\DB\Exception
	 */
	abstract public function prepare($statement);

	/**
	 * query
	 *
	 * Executes an SQL statement, returning a result set as a DBO statement object
	 * @param string $statement = sql statement to execute as a query
	 * @return object|boolean $result = DBOStatement, or false if failed
	 */
	abstract public function query($statement);

	/**
	 * quote
	 *
	 * Quotes a string for use in a query.
	 * @param string $string = string to quote
	 * @param integer $parameter_type = data type hint for drivers
	 * @return string $string = quoted string, false if failed
	 */
	abstract public function quote($string, $parameter_type = PDO::PARAM_STR);

	/**
	 * rollBack
	 *
	 * Rolls back a transaction
	 * @return boolean true = success, false = failed
	 */
	abstract public function rollBack();

	/**
	 * setAttribute
	 *
	 * Set an attribute
	 * @param integer $attribute = attribute name to set
	 * @param mixed $value = value of the attribute
	 * @return boolean true = success, false = failed
	 */
	abstract public function setAttribute($attribute, $value);

	/**
	 * driverHandle
	 *
	 * Returns the database driver handle
	 * @return object $driverHandle
	 *
	abstract public function driverHandle();

	/**
	 * getAvailableDrivers
	 *
	 * Returns an array of available PDO drivers
	 * @return array $drivers
	 */
	public static function getAvailableDrivers()
	{
		return array();
	}

	/*
	 * ********************************************************
	 * 
	 * 		Iterator Implementation
	 * 
	 * ********************************************************
	 */

	/**
	 * rewind.
	 * 
	 * Moves to the first record in the result
	 */
	public function rewind()
	{
	}

	/**
	 * current.
	 * 
	 * Returns the current data record.
	 * @return array $record = data record
	 */
	public function current()
	{
		return array();
	}

	/**
	 * key.
	 * 
	 * Returns the current key value.
	 * @return $key = current key value.
	 */
	public function key()
	{
		return null;
	}

	/**
	 * next.
	 * 
	 * Moves current to the next data record.
	 * @return null.
	 */
	public function next()
	{
		return null;
	}

	/**
	 * valid.
	 * 
	 * Returns the validity of the current record
	 * @return boolean true = the current record is valid.
	 */
	public function valid()
	{
		return false;
	}

	/*
	 * ********************************************************
	 * 
	 * 		Countable Implementation
	 * 
	 * ********************************************************
	 */

	/**
	 * count
	 *
	 * Returns the number of records in the result set
	 * @return integer $count
	 */
	public function count()
	{
		return 0;
	}

}
