<?php
namespace Library\DBO;

use Library\DBO\DriverFactory;
use Library\Error;

/*
 * 		Library\DBO is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * Library\DBO
 *
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage DBO
 */
class DBO
{
	/**
	 * driver
	 * 
	 * Database driver name
	 * @var string $driver
	 */
	protected $driver;

	/**
	 * dbHandle
	 * 
	 * Database driver (mysql, pdo, etc.) handle, if the database host is connected
	 * @var resource $dbHandle
	 */
	protected $dbHandle;

	/**
	 * instance
	 * 
	 * A copy of the DBO object for use in static calls
	 * @var object $instance
	 */
	protected static $instance;

	/**
	 * __construct
	 * 
	 * Class constructor
	 */
	public function __construct($driver, $dsn)
	{
		$this->driver = $driver;

		$args = func_get_args();
		$this->dbHandle = call_user_func_array(array('\Library\DBO\DriverFactory', 'instantiateClass'), $args);
		self::$instance = $this;
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 */
	public function __destruct()
	{
		$this->dbHandle = null;
	}

	/**
	 * __call
	 *
	 * Trap failed method call and re-direct to the current dbHandle object
	 * @param string $method = name of the method being called
	 * @param array $arguments = array of class arguments
	 * @return mixed $result
	 */
	public function __call($method, $arguments)
	{
		return call_user_func_array(array($this->dbHandle, $method), $arguments);
	}

	/**
	 * __callStatic
	 *
	 * Trap failed static method call and re-direct to the current dbHandle object
	 * @param string $method = name of the method being called
	 * @param array $arguments = array of class arguments
	 * @return mixed $result
	 */
	public static function __callStatic($method, $arguments)
	{
		if ((! self::$instance) || (! self::$instance->dbHandle))
		{
			throw new Exception(Error::code('NotInitialized'));
		}

		return call_user_func_array(array(self::$instance->dbHandle, $method), $arguments);
	}

	/**
	 * __get
	 *
	 * Returns the value of the specified property
	 * @param string $name = property to get value for
	 * @return mixed $value
	 */
	public function __get($name)
	{
		return $this->dbHandle->__get($name);
	}

	/**
	 * __set
	 *
	 * Sets the value of the specified property\
	 * @param string $name = property to set value for
	 * @param mixed $value = value of the property
	 */
	public function __set($name, $value)
	{
		$this->dbHandle->__set($name, $value);
	}

	/**
	 * getAvailableDrivers
	 *
	 * Returns an array containing the names of the available drivers
	 * @return array $drivers
	 */
	public static function getAvailableDrivers()
	{
		return DriverFactory::getInstance()->availableKeys();
	}

}
