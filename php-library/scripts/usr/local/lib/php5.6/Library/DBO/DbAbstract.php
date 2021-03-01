<?php
namespace Library\DBO;

use Library\DBO\ParseDsn;
use Library\DBO\DBOConstants;
use Library\Error;
use Library\Properties;

/*
 * 		MySql\Db is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * MySql\Db
 *
 * @author Jay Wheeler.
 * @version 2.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage MySql
 */
abstract class DbAbstract
{
	/**
	 * dbLink
	 * 
	 * mysqli database link
	 * @var object $dbLink
	 */
	protected $dbLink;

	/**
	 * errorInfo
	 * 
	 * An array containing information about the last operation
	 * @var array $errorInfo
	 */
	protected $errorInfo;

	/**
	 * parseDsn
	 * 
	 * A DBO\ParseDsn instance
	 * @var object $parseDsn
	 */
	protected $parseDsn;

	/**
	 * user
	 * 
	 * The db username
	 * @var string $user
	 */
	protected $user;

	/**
	 * user
	 * 
	 * The db username
	 * @var string $user
	 */
	protected $password;

	/**
	 * options
	 * 
	 * Array containing the database connection options (driver specific)
	 * @var array $options
	 */
	protected $options;
	
	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param string $dsn = Data Source Name (mysql:host=<hostname>;port=<connection port>;dbname=<database name>)
	 * @param string $user = user name
	 * @param string $password = password
	 * @param array  $options = (optional) options array
	 * @throws \Library\MySql\Exception, \Library\DBO\Exception
	 *
	public function __construct($dsn, $user, $password, $options=array());

	/**
	 * __destruct
	 *
	 * Class destructor
	 *
	public function __destruct()
	{
	}

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
	 * Returns the SQL_STATE error information
	 * @return array $errorInfo
	 */
	abstract public function errorInfo();

	/**
	 * getAttribute
	 *
	 * Returns a database connection attribute
	 * @param string $attribute = dbLink-specific attribute.
	 * @return mixed $attributeValue
	 * @throws Exception
	 */
	abstract public function getAttribute($attribute);

	/**
	 * setAttribute
	 *
	 * Set an attribute
	 * @param integer $attribute = attribute name to set
	 * @param mixed $value = value of the attribute
	 * @return boolean true = success, false = failed
	 * @throws Exception, DBOException, mysqli_sql_exception
	 */
	abstract public function setAttribute($attribute, $value);

	/**
	 * setErrorInfo
	 * 
	 * Set the current error information into the errorInfo class array property
	 */
	abstract protected function setErrorInfo();

	/**
	 * clearErrorInfo
	 * 
	 * Set the error information array to default values indicating no error.
	 */
	abstract protected function clearErrorInfo();

	/**
	 * connectErrorInfo
	 * 
	 * Save connection error info in the errorInfo array
	 */
	abstract protected function connectErrorInfo();

	/**
	 * dbLink
	 * 
	 * Return the current database link
	 * @return resource $dbLink
	 */
	public function dbLink()
	{
		return $this->dbLink;
	}

	/**
	 * parseDsn
	 * 
	 * Return the parseDsn object
	 * @return object $parseDsn
	 */
	public function parseDsn()
	{
		return $this->parseDsn;
	}

}
