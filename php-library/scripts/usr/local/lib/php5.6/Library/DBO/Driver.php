<?php
namespace Library\DBO;

use Library\Error;
use Library\Properties;

/*
 * 		DBO\Driver is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * DBO\Driver
 *
 * @author Jay Wheeler.
 * @version 2.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage DBO
 */
class Driver
{
	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param string $dsn = Data Source Name (mysql:host=<hostname>;port=<connection port>;dbname=<database name>)
	 * @param string $user = user name
	 * @param string $password = password
	 * @param array  $options = (optional) options array
	 * @throws \Library\MySql\Exception, \Library\DBO\Exception
	 */
	public function __construct($dsn, $user, $password, $options=array())
	{
		$this->user = $user;
		$this->password = $password;
		$this->options = new Properties($options);

		$this->clearErrorInfo();

		$this->parseDsn = new ParseDsn($dsn);

		if ($this->parseDsn->driver != 'mysql')
		{
			throw new Exception(Error::code('DbInvalidDriver'));
		}

		if (! $this->parseDsn->exists('host'))
		{
			throw new Exception(Error::code('DbMissingHost'));
		}

		if (! $this->parseDsn->exists('port'))
		{
			$this->parseDsn->port = ini_get("mysqli.default_port") ;
		}

		if (! $user)
		{
			throw new Exception(Error::code('DbMissingUserName'));
		}

		if (! $this->parseDsn->exists('dbname'))
		{
			throw new Exception(Error::code('DbMissingName'));
		}

		$this->parseDsn->user = $user;
		$this->parseDsn->password = $password;

		$this->dbLink = mysqli_init();

		try
		{
			$this->setAttribute(DBOConstants::ATTR_REPORT_MODE, MYSQLI_REPORT_OFF);
			$this->setAttribute(DBOConstants::ATTR_REPORT_MODE, MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		}
		catch(mysqli_sql_exception $exception)
		{
			throw new Exception($exception);
		}

		foreach($this->options as $option => $value)
		{
			if (! $this->dbLink->options($option, $value))
			{
				throw new Exception(Error::code('DbOptionError'));
			}
		}

		if (! $this->dbLink->real_connect($this->parseDsn->host, $user, $password, $this->parseDsn->dbname, $this->parseDsn->port))
		{
			$this->connectErrorInfo();
			throw new Exception(Error::code('DbInitFailed'));
		}

		$this->transactionInProgress = false;

		$this->queryHandle = null;
		$this->statementHandle = null;

		$this->query = '';
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 */
	public function __destruct()
	{
		if ($this->transactionInProgress)
		{
			try 
			{
				$this->rollback();
			}
			catch(Exception $exception)
			{
				;
			}
			
			$this->transactionInProgress = false;
		}
		
		$this->statementHandle = null;
		$this->queryHandle = null;
	}

	/**
	 * beginTransaction
	 *
	 * Begin a transaction
	 * @return boolean $result = true if successful, false if not
	 */
	public function beginTransaction()
	{
		$this->clearErrorInfo();
		$this->checkDbLink();

		if ($this->transactionInProgress)
		{
			throw new Exception(Error::code('DbTransactionInProgress'));
		}

		$this->statementHandle = null;
		$this->queryHandle = null;

		if (! $this->dbLink->autocommit(false))
		{
			$this->setErrorInfo();
			throw new Exception(Error::code('DbAutocommitError'));
		}

		$this->transactionInProgress = true;

		return true;
	}

	/**
	 * commit
	 * 
	 * Commits a transaction
	 * @return boolean result = true if successful, false if not
	 */
	public function commit()
	{
		$this->clearErrorInfo();
		$this->checkDbLink();

		if (! $this->transactionInProgress)
		{
			throw new Exception(Error::code('DbNoTransaction'));
		}

		if (! $this->dbLink->commit())
		{
			$this->setErrorInfo();
			throw new Exception(Error::code('DbCommitFailed'));
		}

		$this->transactionInProgress = false;

		return true;
	}

	/**
	 * errorCode 
	 *
	 * Returns the current SQL_STATE error code
	 * @return string $sqlState
	 */
	public function errorCode()
	{
		$this->checkDbLink();
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
		return $this->errorInfo;
	}

	/**
	 * exec
	 *
	 * Execute a single (escaped) query and return the number of affected rows
	 * @param string $statement = (escaped/quoted) sql statement (that does not return a result set) to execute
	 * @return integer|boolean $rows = number of rows affected
	 * @throws MySql\Exception
	 */
	public function exec($query)
	{
		$this->clearErrorInfo();
		$this->checkDbLink();

		$this->query = $query;

		$this->statementHandle = null;
		$this->queryHandle = null;

		$this->queryHandle = new Execute($this, $query);

		return $this->queryHandle->rowCount();
	}

	/**
	 * getAttribute
	 *
	 * Returns a database connection attribute
	 * @param string $attribute = dbLink-specific attribute.
	 * @return mixed $attributeValue
	 * @throws Exception
	 */
	public function getAttribute($attribute)
	{
		switch($attribute)
		{
			case DBOConstants::ATTR_AUTOCOMMIT:

				if ($this->query('SELECT @@autocommit'))
				{
					return $this->queryHandle->fetchColumn(0);
				}

				throw new Exception(Error::code('DbAttributeError'));

			case DBOConstants::ATTR_CHARSET_DEFAULT:
				return $this->dbLink->character_set_name();

			case DBOConstants::ATTR_CHARSET_INFO:
				return $this->dbLink->get_charset();

			case DBOConstants::ATTR_CLIENT_INFO:
				return $this->dbLink->get_client_info();

			case DBOConstants::ATTR_CLIENT_VERSION:
				return $this->dbLink->client_version;

			case DBOConstants::ATTR_CONNECTION_STATS:
				if ($result = $this->dbLink->get_connection_stats())
				{
					return $result;
				}
				
				throw new Exception(Error::code('DbAttributeError'));

			case DBOConstants::ATTR_DRIVER_HANDLE:
				return $this->dbLink;

			case DBOConstants::ATTR_ERROR_INFO:
				return $this->errorInfo();

			case DBOConstants::ATTR_ERROR_LIST:
				return $this->dbLink->error_list;

			case DBOConstants::ATTR_FIELD_COUNT:
				return $this->dbLink->field_count;

			case DBOConstants::ATTR_HOST_INFO:
				return $this->dbLink->host_info;

			case DBOConstants::ATTR_QUERY_INFO:
				return $this->dbLink->info;

			case DBOConstants::ATTR_REPORT_MODE:
				$driver = new \mysqli_driver();
				return $driver->report_mode;
				
				case DBOConstants::ATTR_SERVER_INFO:
				return $this->dbLink->server_info;

			case DBOConstants::ATTR_SERVER_VERSION:
				return $this->dbLink->server_version;

			case DBOConstants::ATTR_STAT:
				return $this->dbLink->stat;

			case DBOConstants::ATTR_THREAD_SAFE:
				return $this->dbLink->thread_safe();

			case DBOConstants::ATTR_WARNING_COUNT:
				return $this->dbLink->warning_count;

			default:
				break;
		}

		return null;
	}

	/**
	 * getAvailableDrivers
	 *
	 * Returns an array of available MySql drivers
	 * @return array $drivers
	 */
	public static function getAvailableDrivers()
	{
		return array('mysqli');
	}

	/**
	 * inTransaction
	 *
	 * Checks if inside a transaction
	 * @return boolean true = inside a transaction, false = not
	 */
	public function inTransaction()
	{
		return $this->transactionInProgress;
	}

	/**
	 * lastInsertId
	 *
	 * Returns the last insert id or sequence
	 * @param string $name = (optional) name of the sequence object
	 * @return string $id
	 */
	public function lastInsertId($name=null)
	{
		$this->checkDbLink();
		return $this->dbLink->insert_id;
	}

	/**
	 * prepare
	 *
	 * Prepares a statement for execution and returns a statement object.
	 * @param string $query = sql statement to prepare
	 * @return object $statementHandle, or false if failed
	 * @throws \Library\DBO\Exception
	 */
	public function prepare($query)
	{
		$this->clearErrorInfo();

		$this->checkDbLink();
		$this->query = $query;

		$this->statementHandle = null;
		$this->queryHandle = null;

		if (! $this->statementHandle = new Statement($this, $query))
		{
			$this->setErrorInfo();
			throw new Exception(Error::code('DbPrepareStmtError'));
		}

		return $this->statementHandle;
	}

	/**
	 * query
	 *
	 * Executes an (escaped) SQL statement, returning a result set as a statement object
	 * @param string $query = (escaped) sql statement to execute as a query
	 * @return object $queryResult = query result class object
	 * @throws MySql\Exception
	 */
	public function query($query)
	{
		$this->clearErrorInfo();
		$this->checkDbLink();

		$this->query = $query;

		$this->statementHandle = null;
		$this->queryHandle = null;

		$this->queryHandle = new Query($this, $query);

		return $this->queryHandle;
	}

	/**
	 * quote
	 *
	 * Quotes a string for use in a query.
	 * @param string $string = string to quote
	 * @param integer $parameterType = (optional) data type hint for drivers
	 * @return string $string = quoted string, false if failed
	 * @throws Library\MySql\Exception
	 */
	public function quote($string, $parameterType=null)
	{
		$this->checkDbLink();
		return $this->dbLink->real_escape_string($string);
	}

	/**
	 * rollBack
	 *
	 * Rolls back a transaction
	 * @return boolean true = success, false = failed
	 */
	public function rollBack()
	{
		$this->checkDbLink();

		if (! $this->transactionInProgress)
		{
			$this->setErrorInfo();
			throw new Exception(Error::code('DbNotTransaction'));
		}

		$this->transactionInProgress = false;

		if (! $this->dbLink->rollback())
		{
			$this->setErrorInfo();
			throw new Exception(Error::code('DbRollbackFailed'));
		}

		return true;
	}

	/**
	 * setAttribute
	 *
	 * Set an attribute
	 * @param integer $attribute = attribute name to set
	 * @param mixed $value = value of the attribute
	 * @return boolean true = success, false = failed
	 * @throws Exception, DBOException, mysqli_sql_exception
	 */
	public function setAttribute($attribute, $value)
	{
		$this->checkDbLink();

		switch($attribute)
		{
			case DBOConstants::ATTR_AUTOCOMMIT:
				if (! $this->dbLink->autocommit($value))
				{
					throw new Exception(Error::code('DbAttributeSetError'));
				}

				break;

			case DBOConstants::ATTR_REPORT_MODE:
				$driver = new \mysqli_driver();
				$driver->report_mode = $value;

				break;
				
			default:
				throw new Exception(Error::code('DbInvalidAttribute'));
		}
		
		return true;
	}

	/**
	 * setErrorInfo
	 * 
	 * Set the current error information into the errorInfo class array property
	 */
	protected function setErrorInfo()
	{
		$this->checkDbLink();
		$this->errorInfo = array($this->dbLink->sqlstate, $this->dbLink->errno, $this->dbLink->error);
	}

	/**
	 * clearErrorInfo
	 * 
	 * Set the error information array to default values indicating no error.
	 */
	protected function clearErrorInfo()
	{
		$this->errorInfo = array('00000', null, null);
	}

	/**
	 * connectErrorInfo
	 * 
	 * Save connection error info in the errorInfo array
	 */
	protected function connectErrorInfo()
	{
		$this->checkDbLink();
		$this->errorInfo = array($this->dbLink->$sqlstate,
								 $this->dbLink->$connect_errno,
								 $this->dbLink->$connect_error);
	}

	/**
	 * checkHandle
	 * 
	 * Check if dbLink is valid
	 * @throws Exception
	 */
	protected function checkDbLink()
	{
		if (! $this->dbLink)
		{
			throw new Exception(Error::code('DbNotConnected'));
		}
	}

}
