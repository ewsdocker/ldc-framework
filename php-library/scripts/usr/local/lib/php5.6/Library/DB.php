<?php
namespace Library;
use Error;

/*
 * 		DB is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * DB
 *
 * Database access interface
 * @author Jay Wheeler.
 * @version 1.1
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage DB.
 */
class DB
{
	/**
	 * LIMIT_MAX
	 * 
	 * The largest number usable by the DB server
	 * @const LIMIT_MAX
	 */
  	const				LIMIT_MAX = 2147483648;

	/**
	 * driverName
	 *
	 * The database driver type name
	 * @var string $driverNaem
	 */
  	protected			$driverName;

  	/**
  	 * driver
  	 * 
  	 * The database driver instance
  	 * @var object $driver
  	 */
  	protected			$driver;

  	/**
  	 * instance
  	 * 
  	 * A static copy of this instance object reference
  	 * @var object $instance
  	 */
  	protected static	$instance = null;

  	/**
  	 * __construct
  	 *
  	 * class constructor
  	 * @var string $driverName = name of the database driver to use
  	 * @throws \Library\DB\Exception
  	 */
  	public function __construct($driverName)
  	{
  		$this->driverName = $driverName;
		$this->driver = call_user_func_array(array('\Library\DB\Factory', 'instantiateClass'), func_get_args());

		self::$instance = $this;
  	}

  	/**
  	 * __destruct
  	 *
  	 * class destructor
  	 */
  	public function __destruct()
  	{
  		if ($this->driver)
  		{
    		$this->driver = null;
  		}
  	}

	/**
	 * __call
	 *
	 * Trap failed method call and re-direct to the current driver object
	 * @param string $method = name of the method being called
	 * @param array $arguments = array of class arguments
	 * @return mixed $result
	 */
	public function __call($method, $arguments)
	{
		return call_user_func_array(array($this->driver, $method), $arguments);
	}

	/**
	 * __callStatic
	 *
	 * Trap failed static method call and re-direct to the current driver object
	 * @param string $method = name of the method being called
	 * @param array $arguments = array of class arguments
	 * @return mixed $result
	 * @throws \Library\DB\Exception
	 */
	public static function __callStatic($method, $arguments)
	{
		if ((! self::$instance) || (! self::$instance->driver))
		{
			throw new DB\Exception(Error::code('NotInitialized'));
		}

		return call_user_func_array(array(self::$instance->driver, $method), $arguments);
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
		if (($name == 'driver') || ($name == 'driverName'))
		{
			return $this->{$name};
		}

		if ($this->driver)
		{
			return $this->driver->__get($name);
		}
		
		return null;
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
		if ($this->driver)
		{
			$this->driver->__set($name, $value);
		}
	}

	/**
	 * getAvailableDrivers
	 *
	 * Returns an array containing the names of the available drivers
	 * @return array $drivers
	 */
	public static function getAvailableDrivers()
	{
		return DB\Factory::getInstance()->availableKeys();
	}

}
