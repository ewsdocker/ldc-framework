<?php
namespace Library\MySql;

use Library\DBO\DBOConstants;
use Library\Error;

/*
 * 		MySql\Result is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * MySql\Result
 *
 * @author Jay Wheeler.
 * @version 2.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage MySql
 */
class Result implements \Iterator, \Countable
{
	/**
	 * driver
	 *
	 * Calling instance
	 * @var object $driver
	 */
	protected $driver;

	/**
	 * dbLink
	 *
	 * Database link
	 * @var resource $dbLink
	 */
	protected $dbLink;

	/**
	 * query
	 *
	 * Sql query string
	 * @var string $query
	 */
	protected $query;

	/**
	 * handle
	 *
	 * Query result handle (mysqli_result)
	 * @var object $handle
	 */
	protected $handle;

	/**
	 * fetchMode
	 *
	 * A value indicating the type of fetch to perform (see DBOConstants)
	 * @var integer $fetchMode
	 */
	protected $fetchMode;

	/**
	 * orientation
	 *
	 * Cursor orientation
	 * @var integer $orientation
	 */
	protected $orientation;

	/**
	 * rowNumber
	 *
	 * The current row number
	 * @var integer $rowNumber
	 */
	protected $rowNumber;

	/**
	 * rowMetaData
	 *
	 * An array containing the meta-data returned for the columns in the result set
	 * @var array $rowMetaData
	 */
	protected $rowMetaData;

	/**
	 * resultColumns
	 *
	 * The number of columns in the result set
	 */
	protected $resultColumns;

	/**
	 * resultRows
	 *
	 * Number of rows in the result set
	 * @var integer $resultRows
	 */
	protected $resultRows;

	/**
	 * resultMode
	 *
	 * Result mode (MYSQLI_STORE_RESULT or MYSQLI_USE_RESULT)
	 * @var integer $resultMode
	 */
	protected $resultMode;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param object $driver = driver class instance (calling class)
	 */
	public function __construct($driver)
	{
		$this->driver = $driver;
		$this->dbLink = $driver->getAttribute(DBOConstants::ATTR_DRIVER_HANDLE);

		$this->rowNumber = 0;
		$this->resultRows = 0;

		$this->rowMetaData = array();
		$this->resultColumns = 0;

		$this->resultMode = MYSQLI_STORE_RESULT;
		$this->fetchMode = DBOConstants::FETCH_ASSOC;
		$this->orientation = DBOConstants::FETCH_ORI_NEXT;

		$this->handle = null;
	}

	/**
	 * __call
	 *
	 * Trap invalid method calls and throw exception
	 * @param string $name = method name
	 * @param array $parameters = parameters
	 * @throws Exception
	 */
	public function __call($name, $parameters)
	{
		throw new Exception(Error::code('DbInvalidClassMethod'));
	}

	/**
	 * getColumnMeta
	 *
	 * Get the meta data object for the column supplied
	 * @param string $column = column to get meta data for
	 * @return object $metaDataObject = object containing the description of the column
	 */
	public function getColumnMeta($column)
	{
		if (! $this->rowMetaData)
		{
			if (! ($this->rowMetaData = $this->handle->fetch_fields()))
			{
				throw new Exception(Error::code('DbFetchResultMetaFailed'));
			}
		}

		if (strtolower($column) === 'all')
		{
			return $this->rowMetaData;
		}

		return $this->rowMetaData[$column];
	}

	/**
	 * rowCount
	 *
	 * Return the number of rows affected by the last query
	 * @return integer $rowCount
	 */
	public function rowCount()
	{
		$this->checkHandle();
		return $this->resultRows;
	}

	/**
	 * setRowCount
	 *
	 * Store the last operation row count in the resultRows property
	 * @return integer $resultRows = number of rows in result set
	 */
	protected function setRowCount()
	{
		$this->checkHandle();
		$this->resultRows = $this->handle->num_rows;

		return $this->resultRows;
	}

	/**
	 * columnCount
	 *
	 * sets the resultColumns property to the CURRENT field_count returned from the db driver
	 * @return $resultColumns = current result field count
	 * @throws Exception if the result handle is invalid
	 */
	protected function columnCount()
	{
		$this->checkHandle();
		if ($this->resultColumns == 0)
		{
			$this->resultColumns = $this->dbLink->field_count;
		}

		return $this->resultColumns;
	}

	/**
	 * fetch
	 *
	 * Fetches the next row from a result set
	 * @param integer $fetchMode = Controls how the next row will be returned to the caller (refer to DBOConstants).
	 * @param integer $orientation = (optional) determines which row will be returned.
	 * @param integer $offset = (optional) cursor offset to row to fetch
	 * @return mixed $result = result
	 * @throws MySql\Exception
	 */
	public function fetch($fetchMode=null, $orientation=null, $offset=null)
	{
		$this->checkHandle();
		$fetchResult = false;

		if ($fetchMode === null)
		{
			$fetchMode = $this->fetchMode;
		}

		if ($offset)
		{
			$this->seekRow($offset, $orientation);
		}

		switch ($fetchMode)
		{
		case DBOConstants::FETCH_ASSOC:
			$fetchResult = $this->handle->fetch_array(MYSQLI_ASSOC);
			break;

		case DBOConstants::FETCH_NUM:
			$fetchResult = $this->handle->fetch_array(MYSQLI_NUM);
			break;

		case DBOConstants::FETCH_BOTH:
			$fetchResult = $this->handle->fetch_array(MYSQLI_BOTH);
			break;

		case DBOConstants::FETCH_OBJECT:
		case DBOConstants::FETCH_CLASS:
			$fetchResult = $this->fetchObject('stdClass');
			break;

		case DBOConstants::FETCH_INTO:
			$fetchResult = $this->fetchObject('stdClass');
			break;

		case DBOConstants::FETCH_LAZY:
			$fetchResult = $this->fetchObject('stdClass');
			break;

		default:
			break;
		}

		if (! $fetchResult)
		{
			throw new Exception(Error::code('DbFetchResultFailed'));
		}

		return $fetchResult;
	}

	/**
	 * fetchObject
	 *
	 * Fetches the next row and returns it as an object.
	 * @param string $className = (optional) name of the created class, defaults to stdClass
	 * @param array $arguments = (optional) arguments to pass to the created class
	 * @return $object = created class instance
	 * @throws MySql\Exception
	 */
	public function fetchObject($className='stdClass')
	{
		$this->checkHandle();

		$arguments = null;

		if (func_num_args() > 1)
		{
			$arguments = func_get_args();
			array_shift($arguments);
		}

		if ($arguments)
		{
			$object = $this->handle->fetch_object($className, implode(',', $arguments));
		}
		else
		{
			$object = $this->handle->fetch_object($className);
		}

		return $object;
	}

	/**
	 * seekRow
	 *
	 * Seek to the specified offset, if orientation is FETCH_ORI_ABS,
	 *   or the amount specified if orientation is FETCH_ORI_REL
	 * @param integer $offset
	 * @throws Exception
	 */
	protected function seekRow($offset, $orientation=null)
	{
		if (! is_numeric($offset))
		{
			throw new Exception(Error::code('NumericVariableExpected'));
		}

		if ($orientation === null)
		{
			$orientation = $this->orientation;
		}

		if ($orientation == DBOConstants::FETCH_ORI_REL)
		{
			$offset += $this->rowNumber;
		}

		if (($offset < 0) || ($offset > $this->resultRows))
		{
			throw new Exception(Error::code('DbSeekRangeError'));
		}

		if (! $this->handle->data_seek($offset))
		{
			throw new Exception(Error::code('DbDataSeekError'));
		}

		$this->rowNumber = $offset;
	}

	/**
	 * fetchAll
	 *
	 * Returns an array containing all of the result set rows
	 * @param integer $fetchMode = Controls how the next row will be returned to the caller.
	 * @return array containing all of the remaining rows in the result set
	 * @throws MySql\Exception
	 */
	public function fetchAll($fetchMode=null)
	{
		$resultSet = array();

		while ($result = $this->fetch($fetchMode))
		{
			$resultSet[] = $result;
		}

		return $resultSet;
	}

	/**
	 * fetchColumn
	 *
	 * Returns a single column from the next row of a result set
	 * @param integer $columnNumber = (optional) column to retrieve, default = 0
	 * @return mixed $value = column value, null if empty or null or error
	 * @throws MySql\Exception
	 */
	public function fetchColumn($columnNumber=0)
	{
		$this->checkHandle();
		$record = $this->fetch(DBOConstants::FETCH_NUM);

		if ((! is_numeric($columnNumber)) || ($columnNumber < 0) || ($columnNumber >= $this->handle->field_count))
		{
			throw new Exception(Error::code('DbUnknownColumn'));
		}

		return $record[$columnNumber];
	}

	/**
	 * setFetchMode
	 *
	 * Set the fetchMode property
	 * @param integer $mode
	 */
	public function setFetchMode($mode)
	{
		$this->fetchMode = $mode;
	}

	/**
	 * errorCode
	 *
	 * Returns the current SQL_STATE error code
	 * @return string $sqlState
	 */
	public function errorCode()
	{
		return $this->dbLink->sqlstate;
	}

	/**
	 * errorInfo
	 *
	 * Returns the SQL_STATE error information
	 * @return array $errorInfo
	 */
	public function errorInfo()
	{
		return array($this->dbLink->sqlstate, $this->dbLink->errno, $this->dbLink->error);
	}

	/**
	 * checkHandle
	 *
	 * Check the validity of the handle property, throw exception if invalid
	 * @throws Exception
	 */
	protected function checkHandle()
	{
		if (! $this->handle instanceof \mysqli_result)
		{
			throw new Exception(Error::code('DbQueryResultInvalid'));
		}
	}

	/*
	 * ********************************************************
	 *
	 * 	Countable Implementation
	 *
	 * ********************************************************
	 */

	/**
	 * count
	 *
	 * Returns count of rows in the result set
	 * @return integer $count = rows in result set
	 */
	public function count()
	{
		return $this->resultRows;
	}

	/*
	 * ********************************************************
	 *
	 * 	Iterator Implementation
	 *
	 * ********************************************************
	 */

	/**
	 * rewind.
	 *
	 * Moves the current node pointer to the first item in the tree.
	 * @throws Exception if seek fails
	 */
	public function rewind()
	{
		$this->seekRow(0, DBOConstants::FETCH_ORI_ABS);
	}

	/**
	 * current.
	 *
	 * Returns the current row.
	 * @return mixed $row = next row returned in the fetchMode format
	 * @throws Exception if current row is invalid
	 */
	public function current()
	{
		return $this->fetch();
	}

	/**
	 * key.
	 *
	 * Returns the current record number
	 * @return $key = current record number.
	 */
	public function key()
	{
		return $this->rowNumber;
	}

	/**
	 * next.
	 *
	 * Moves current to the next node in the tree in In-Order Traversal.
	 * @return null.
	 */
	public function next()
	{
		$this->rowNumber++;
	}

	/**
	 * valid.
	 *
	 * Returns the validity of the row number
	 * @return boolean true = the current row number is valid.
	 */
	public function valid()
	{
		if ($this->rowNumber >= $this->resultRows)
		{
			return false;
		}

		return true;
	}

}
