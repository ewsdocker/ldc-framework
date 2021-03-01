<?php
namespace Library\MySql;

use Library\DBO\DBOConstants;
use Library\Error;
use Library\Properties;

/*
 * 		MySql\Query is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * MySql\Query
 *
 * @author Jay Wheeler.
 * @version 2.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage MySql
 */
class Query extends Result
{
	/**
	 * __construct
	 *
	 * Class constructor
	 * @param object $driver = calling class instance (MySql\Driver class)
	 * @param string $sql = sql statement to execute
	 * @param string $resultMode = (optional) result mode, default = MYSQLI_STORE_RESULT
	 */
	public function __construct($driver, $sql, $resultMode=MYSQLI_STORE_RESULT)
	{
		parent::__construct($driver);

		$this->sql = $this->driver->quote($sql);
		$this->resultMode = $resultMode;

		try
		{
			if (($this->handle = $this->dbLink->query($sql)) === false)
			{
				$this->setErrorInfo();
				throw new Exception(Error::code('DbQueryExecuteFailed'));
			}
			elseif ($this->handle === true)
			{
				$this->handle = null;
				$this->resultRows = $this->dbLink->affected_rows;
			}
			else
			{
				if ($this->setRowCount() < 0)
				{
					$this->dbLink->setErrorInfo();
					throw new Exception(Error::code('DbError'));
				}

				if ($this->columnCount())
				{
					$this->getColumnMeta('all');
				}

				$this->rowNumber = 0;
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

	}

}
