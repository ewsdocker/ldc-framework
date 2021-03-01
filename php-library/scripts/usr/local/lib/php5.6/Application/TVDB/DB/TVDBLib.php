<?php
namespace Application\TVDB\DB;

use Library\MySql\Exception as MySqlException;
use Library\MySql\Table;

/*
 * 		TVDB\DB\TVDBLib is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * TVDB\DB\TVDBLib.
 *
 * TVDB DB Library methods.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 */

class TVDBLib extends DbTable
{
	/**
	 * queryResult
	 *
	 * Query object, or null if none
	 * @var mixed $dbQuery
	 */
	protected $dbQuery;

	/**
	 * dbObject
	 *
	 * Data/result returned from last fetchObject, or null/false
	 * @var mixed $dbObject = object/result returned from last fetch, or a g.p. storage value
	 */
	protected $dbObject;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $table = Table name
	 * @param string $dsn = Data Source Name (mysql:host=<hostname>;port=<connection port>;dbname=<database name>)
	 * @param string $user = user name
	 * @param string $password = password
	 * @param array  $options = (optional) options array
	 * @throws \Library\MySql\Exception, \Library\DBO\Exception
	 */
	public function __construct($table, $dsn, $user, $password, $options=array())
	{
		parent::__construct($table, $dsn, $user, $password, $options);
		$this->queryResult = null;
		$this->dbObject = null;
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
	 * __toString
	 *
	 * Select data as a class object and list
	 * @return string $buffer = printable buffer containing description of each row in the result set
	 */
	public function __toString()
	{
		$buffer = '';
		$rowNumber = 0;

		try
		{
			$sql = $this->quote($this->createSqlSelect());
			$this->queryResult = $this->query($sql);

			while(true)
			{
				if (! $this->dbObject = $this->fetchObject('\Application\TVDB\DB\DbObject'))
				{
					break;
				}

				$buffer .= sprintf("Record %u:\n%s\n", $rowNumber++, (string)$this->dbObject);
			}
		}
		catch(MySqlException $exception)
		{
			;
		}

		return $buffer;
	}

	/**
	 * deleteRecords
	 *
	 * Delete the records matching the $whereClause parameter
	 * @param string $whereClause = where clause to match against
	 */
	public function deleteRecords($whereClause)
	{
		$sql = sprintf("Delete from `%s` where %s", $this->table, $whereClause);
		$result = $this->exec($sql);

		return $result;
	}

	/**
	 * createSqlSelect
	 *
	 * Create a SQL select statement to select all fields of the current table
	 * @param string $where = (optional) where clause (without the 'WHERE' verb)
	 * @return string $sql = sql select statement
	 */
	public function createSqlSelect($where=null)
	{
		$sql = "Select ";

		$class = sprintf('Application\TVDB\DB\Fields\%s', $this->table);

		$fieldDescriptors = new $class();
		$fields = $fieldDescriptors->fields();

		$fieldList = "";
		foreach($fields as $column => $descriptor)
		{
			$fieldName = $descriptor->name;

			if ($fieldList !== '')
			{
				$fieldList .= ', ';
			}

			$fieldList .= sprintf("`%s`", $fieldName);
		}

		$sql .= sprintf("%s FROM `%s`", $fieldList, $this->table);

		if ($where !== null)
		{
			$sql .= sprintf(" WHERE %s", $where);
		}

		return $sql;
	}

	/**
	 * prepareSqlSelect
	 *
	 * Prepare a SQL select statement to select all fields of the current table
	 * @param string $where = (optional) where clause (without the 'WHERE' verb)
	 * @return string $sql = sql select statement
	 */
	public function prepareSqlSelect($where=null)
	{
		$sql = "Select ";

		$class = sprintf('Application\TVDB\DB\Fields\%s', $this->table);

		$fieldDescriptors = new $class();
		$fields = $fieldDescriptors->fields();

		$fieldList = "";
		foreach($fields as $column => $descriptor)
		{
			$fieldName = $descriptor->name;

			if ($fieldList !== '')
			{
				$fieldList .= ', ';
			}

			$fieldList .= sprintf("`%s`", $fieldName);
		}

		$sql .= sprintf("%s FROM `%s`", $fieldList, $this->table);

		if ($where !== null)
		{
			$sql .= sprintf(" WHERE %s", $where);
		}

		return $sql;
	}

	/**
	 * fetchObject
	 *
	 * Fetch the next query result into an object
	 * @param string $class = (optional) class name to instantiate, 'stdClass' if none supplied
	 * @param mixed $params = (optional) list of parameters
	 * @return boolean|object $result = false if object is null (end of data), otherwise a class object instance
	 */
	public function fetchObject($class='stdClass')
	{
		if (! $this->checkQueryResult())
		{
			$this->dbObject = null;
			return $this->dbObject;
		}

		try
		{
			$arguments = null;

			if (func_num_args() > 1)
			{
				$arguments = func_get_args();
				array_shift($arguments);
			}

			if ($arguments)
			{
				$this->dbObject = $this->queryResult->fetchObject($class, implode(',', $arguments));
			}
			else
			{
				$this->dbObject = $this->queryResult->fetchObject($class);
			}
		}
		catch(MySqlException $exception)
		{
			$this->dbObject = null;
		}

		return $this->dbObject;
	}

	/**
	 * queryResult
	 *
	 * Get the $queryResult property
	 * @return mixed $queryResult = current queryResult property value
	 */
	public function queryResult()
	{
		return $this->queryResult;
	}

	/**
	 * dbObject
	 *
	 * Get the $dbObject property
	 * @return mixed $dbObject = current dbObject property value
	 */
	public function dbObject()
	{
		return $this->dbObject;
	}

	/**
	 * checkDbObject
	 *
	 * Check if the dbObject property is not an object
	 * @return boolean $isObject = true if an object, false if not
	 */
	public function checkDbObject()
	{
		return is_object($this->dbObject);
	}

	/**
	 * checkQueryResult
	 *
	 * check if the queryResult property is not an object
	 * @return boolean $isObject = true if an object, false if not
	 */
	public function checkQueryResult()
	{
		return is_object($this->queryResult);
	}

}
