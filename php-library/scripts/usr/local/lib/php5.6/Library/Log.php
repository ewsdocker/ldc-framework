<?php
namespace Library;
use Library\Error;

/*
 *		Library\Log is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License provided with the source, 
 *         or from http://opensource.org/licenses/academic.php.
 */
/**
 * Library\Log.
 *
 * Convenience singleton class for STATIC access to Library\Log functions
 * @author Jay Wheeler
 * @version 1.0
 * @copyright © 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Log
 */
class Log extends Log\Base
{
	/**
	 * Log level constants - based upon the BSD syslog protocol,
	 * which is described in RFC-3164. The names and corresponding
	 * priority numbers are also compatible with Zend_Log and PEAR Log.
	 */

	/**
	 * LOG_BSD_EMERG
	 *
	 * Emergency: system is unusable
	 */
	const LOG_BSD_EMERG   = 0;

	/**
	 * LOG_BSD_ALERT
	 *
	 * Alert: action must be taken immediately
	 */
	const LOG_BSD_ALERT   = 1;

	/**
	 * LOG_BSD_CRIT
	 *
	 * Critical: critical conditions
	 */
	const LOG_BSD_CRIT    = 2;

	/**
	 * LOG_BSD_ERR
	 *
	 * Error: error conditions
	 */
	const LOG_BSD_ERR     = 3;

	/**
	 * LOG_BSD_WARN
	 *
	 * Warning: warning conditions
	 */
	const LOG_BSD_WARN    = 4;

	/**
	 * LOG_BSD_NOTICE
	 *
	 * Notice: normal but significant condition
	 */
	const LOG_BSD_NOTICE  = 5;

	/**
	 * LOG_BSD_INFO
	 *
	 * Informational: informational messages
	 */
	const LOG_BSD_INFO    = 6;

	/**
	 * LOG_BSD_DEBUG
	 *
	 * Debug: debug messages
	 */
	const LOG_BSD_DEBUG   = 7;

	/**
	 * LOG_BSD_USER
	 *
	 * User: user defined errors start here
	 */
	const LOG_BSD_USER   = 8;

	/**
	 * $instance
	 *
	 * copy of the current class instance
	 * @var object $instance
	 */
	private static $instance = null;

	/**
	 * $logs
	 *
	 * Active log object array
	 * @var array $logs
	 */
	private $logs;

	/**
	 * logTypes
	 *
	 * Array containing message level to log
	 * @var array $logTypes
	 */
	private $logTypes;

	/**
	 * defaults
	 *
	 * An instance of the Library\Properties class to manage default property settings
	 * @var object $defaults
	 */
	private $defaults;

	/**
	 * __construct
	 *
	 * class constructor
	 */
	public function __construct()
	{
		$traceback = new Debug\Traceback(2);
		
		if (($traceback->className() != get_class()) && ($traceback->methodName() !== 'getInstance'))
		{
			throw new Exception(Error::code('RestrictedUseOfNew'));
		}

		parent::__construct(self::defaultProperties());
		$this->logs = array();
		$this->defaults = self::defaultLogProperties();
		$this->logTypes = array('error' => self::LOG_BSD_ERR);
	}

	/**

	 * __destruct
	 *
	 * destroy the current class object
	 * @return null
	 */
	public function __destruct()
	{
		self::stopLogs();
	}

	/**
	 * getInstance
	 *
	 * get, or create and get if it doesn't exist, the current instance object
	 * @return Library\Log instance object
	 */
	public static function getInstance()
	{
		if (! self::$instance instanceof self)
		{
			self::$instance = new Log();
		}

		return self::$instance;
	}

	/**
	 * deleteInstance
	 *
	 * delete the current instance, if it exists
	 * @return null
	 */
	public static function deleteInstance()
	{
		unset(self::$instance);
		self::$instance = null;
	}

	/**
	 * message
	 *
	 * Log a message to the current log device
	 * @param string $message      = message to write to the log file
	 * @param mixed $logParameters = (optional)	'Log_Level'      => <(optional) error level (default = Library\Log::LOG_BSD_ERR)>,
	 *                              			'Log_LineNumber' => <linenumber>,
	 *                              			'Log_Method'     => <(optional) name of the calling method>,
	 *                              			'Log_Class'      => <(optional) name of the calling class>,
	 *                              			'Log_Program'    => <(optional) name of the program to log>
	 * @param timestamp $timestamp = (optional) timestamp of the event
	 * @return boolean true if successful, false if not
	 * @throws Library\Log\Exception
	 */
	public static function message($message, $logProperties=null)
	{
		$instance = self::getInstance();

		if (count($instance->logs) == 0)
		{
			return false;
		}

		$instance->logProperties($logProperties);

		foreach($instance->logs as $logName => $properties)
		{
			if ($instance->logProperties->Log_Level <= $properties->Log_Level)
			{
				$properties->Log_Object->log($message, $instance->logProperties);
			}
		}

		return true;
	}

	/**
	 * startLog
	 *
	 * start (open) the requested log and prepare it for logging activities
	 * @param object|array $properties = properties in a Library\Properties class or associative array
	 * @throws Library\Log\Exception
	 */
	public static function startLog($properties)
	{
		$instance = self::getInstance();

		switch(gettype($properties))
		{
			case 'object':

				if (! $properties instanceof \Library\Properties)
				{
					throw new Exception(Error::code('InvalidClassObject'));
				}

				$properties->setProperties($instance->defaults->properties(), false);

				break;

			case 'array':

				if (count($properties) == 0)
				{
					throw new Exception(Error::code('MissingPropertiesArray'));
				}

				$origProperties = $properties;
				$properties = new \Library\Properties($instance->defaults->properties());
				$properties->setProperties($origProperties, true);

				break;

			default:
				throw new Exception(Error::code('MissingRequiredProperties'));
		}

		if (array_key_exists($properties->Log_Name, $instance->logs))
		{
			self::stopLog($properties->Log_Name);
		}

		$properties->Log_Started = false;

		if ($properties->exists('Log_Error'))
		{
			$properties->delete('Log_Error');
		}

		if (! $properties->exists('Log_Level'))
		{
			$properties->Log_Level = self::LOG_BSD_ERR;
		}

		if (($log = \Library\Log\Factory::instantiateClass($properties->Log_Adapter, $properties)) === null)
		{
			throw new Exception(Error::code('ObjectNotInitialized', $properties));
		}

		$properties->Log_Object = $log;
		$properties->Log_Started = true;

		$instance->logs[$properties->Log_Name] = $properties;
	}

	/**
	 * stopLog
	 *
	 * Stop a current logger by deleting it's entry
	 * @param string $logName = case sensitive name of log to stop
	 * @return boolean true = successful, false = not found
	 */
	public static function stopLog($logName)
	{
		$instance = self::getInstance();

		if ((count($instance->logs) == 0) || (! array_key_exists($logName, $instance->logs)))
		{
			return false;
		}

		$properties = $instance->logs[$logName];

		unset($properties->Log_Object);

		$properties->delete('Log_Object');
		$properties->Log_Started = false;

		unset($instance->logs[$logName]);

		return true;
	}

	/**
	 * stopLogs
	 *
	 * Stop all logs
	 */
	public static function stopLogs()
	{
		$instance = self::getInstance();

		if (count($instance->logs) > 0)
		{
			while($properties = array_pop($instance->logs))
			{
				self::stopLog($properties->Log_Name);
			}
		}

		$instance->logs = array();

	}

	/**
	 * findLogError
	 *
	 * find the first log error
	 * @return array|boolean $properties = found log parameters, false = none found
	 */
	public static function findLogError()
	{
		$instance = self::getInstance();

		if (count($instance->logs) == 0)
		{
			return false;
		}

		foreach($instance->logs as $logName => $properties)
		{
			if ($properties->exists('Log_Exception') && $properties->Log_Exception)
			{
				return $properties;
			}
		}

		return false;
	}

	/**
	 * getProperties
	 *
	 * get the parameters associated with the specified log
	 * @param string $logName = name of the log to fetch parameters for
	 * @return array|boolean $properties = requested log parameters, false if not found
	 */
	public static function getProperties($logName)
	{
		$instance = self::getInstance();

		if ((count($instance->logs) == 0) || (! array_key_exists($logName, $instance->logs)))
		{
			return false;
		}

		return $instance->logs[$logName];
	}

	/**
	 * setLogType
	 *
	 * Add the logging name and level to the logTypes array
	 * @param string $name  = symbolic name of the log level
	 * @param integer $level = log level to associate with the name
	 */
	public static function setLogType($name, $level)
	{
		self::getInstance()->logTypes[$name] = $level;
	}

	/**
	 * unsetLogType
	 *
	 * Delete (unset) the symbolic name in the logTypes array
	 * @param string $name = symbolic name to delete
	 */
	public static function unsetLogType($name)
	{
		$instance = self::getInstance();

		if (array_key_exists($name, $instance->logTypes))
		{
			unset($instance->logTypes[$name]);
		}
	}

	/**
	 * clearLogTypes
	 *
	 * clear all of the symbolic names and levels from logTypes array
	 */
	public static function clearLogTypes()
	{
		self::getInstance()->logTypes = array();
	}

	/**
	 * lookupLogType
	 *
	 * Lookup the log type name in the logType array
	 * @param string $name = log type name to look up
	 * @return integer|boolean $type = log type index, false if not found
	 */
	public static function lookupLogType($name)
	{
		$instance = self::getInstance();

		if (array_key_exists($name, $instance->logTypes))
		{
			return $instance->logTypes[$name];
		}

		return false;
	}

	/**
	 * defaultLogProperties
	 *
	 * Returns a Properties class instance initialized with default log properties.
	 * @returns object $logProperties
	 */
	public static function defaultLogProperties()
	{
		return new \Library\Properties(array('Log_SkipLevels'		=> 2,
										 	 'Log_Program'			=> 'Library',
										 	 'Log_Exception'		=> \Library\Error::code('NoError'),
										 	 'Log_Error'			=> \Library\Error::code('NoError'),
										 	 'Log_Adapter'			=> 'fileio',
										 	 'Log_Name'				=> null,
										 	 'Log_Level'			=> self::LOG_BSD_DEBUG,
										 	 'Log_Format'			=> 'log',

										 	 'Log_FileAdapter'		=> 'fileobject',
										 	 'Log_FileDestination'	=> 'log',
										 	 'Log_FileMode'			=> 'w',
										 	 ));
	}

	/**
	 * defaultProperties
	 *
	 * Returns a Properties class instance initialized with default properties.
	 * @returns object $properties
	 */
	public static function defaultProperties()
	{
		return new \Library\Properties(array('Log_SkipLevels'		=> 2,
										 	 'Log_Program'			=> 'Library',
										 	 'Log_Level'			=> self::LOG_BSD_DEBUG,
										 	 'Log_Format'			=> 'log',
										 	 ));
	}

}
