<?php
namespace Library\PDO;

use Library\Error;
use Library\Exception\Descriptor as ExceptionDescriptor;

/*
 * 		DriverMySql is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * PDO\DriverMySql
 *
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage PDO.
 */
class DriverMySql
{
	/**
	 * instance
	 * 
	 * Static class instance
	 * @var object $instance
	 */
	private	static		$instance;

	/**
	 * driverHandle
	 * 
	 * Database handle resource
	 * @var resource $driverHandle
	 */
	protected			$driverHandle;

	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param string $dsn = Data Source Name (DSN)
	 * @param string $user = (optional) database user name
	 * @param string $password = (optional) database user password
	 * @param array $attributes = (optional) database attributes array
	 */
	public function __construct($dsn)
	{
		$arguments = func_get_args();
		
		$className = '\PDO';
		$descriptor = null;

		try
		{
			$reflection = new \ReflectionClass($className);

			if (! $reflection->hasMethod('__construct'))
			{
				throw new Exception(Error::code('NoClassConstructor'));
			}
		
			$this->driverHandle = $reflection->newInstanceArgs($arguments);
		}
		catch(\PDOException $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
			$descriptor->errorInfo = $exception->errorinfo;
		}
		catch(\ReflectionException $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}
		catch(\Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}

		if ($descriptor)
		{
			$exception = new DBOException($descriptor->message, $descriptor->code);
			if ($descriptor->errorInfo)
			{
				$exception->errorinfo = $descriptor->errorInfo;
			}
			
			throw $exception;
		}

		self::$instance = $this;
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 */
	public function __destruct()
	{
		$this->driverHandle = null;
	}

	/**
	 * __call
	 *
	 * Trap failed method call and re-direct to the current driverHandle object
	 * @param string $method = name of the method being called
	 * @param array $arguments = array of class arguments
	 * @return mixed $result
	 */
	public function __call($method, $arguments)
	{
		try
		{
			return call_user_func_array(array($this->driverHandle, $method), $arguments);
		}
		catch(\PDOException $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
			$descriptor->errorInfo = $exception->errorinfo;
		}
	}

	/**
	 * __callStatic
	 *
	 * Trap failed static method call and re-direct to the current driverHandle object
	 * @param string $method = name of the method being called
	 * @param array $arguments = array of class arguments
	 * @return mixed $result
	 */
	public static function __callStatic($method, $arguments)
	{
		if ((! self::$instance) || (! self::$instance->driverHandle))
		{
			throw new Exception(Error::code('NotInitialized'));
		}

		return call_user_func_array(array(self::$instance->driverHandle, $method), $arguments);
	}

	/**
	 * driverHandle
	 *
	 * Returns the database handle
	 * @return resource $driverHandle
	 */
	public function driverHandle()
	{
		return $this->driverHandle;
	}

}
