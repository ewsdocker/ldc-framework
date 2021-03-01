<?php
namespace Library;
use Library\Error\Messages as ErrorMessages;

/*
 *		Error is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		      or from http://opensource.org/licenses/academic.php
*/
/**
 * Error.
 *
 * The Error static class provides custom error codes and associated messages, 
 * and utility methods to access them.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Error
 */
class Error
{
	/**
	 * errors
	 *
	 * Error name to error number mapping array
	 * @var array $errors
	 */
	private static $errors 	= array();

	/**
	 * errorMessage
	 *
	 * Array of registered error Messages
	 * @var array $errorMessage
	 */
	private static $errorMessage = array();

	/**
	 * registry
	 * 
	 * Registry is an array of all list names currently registered.
	 * @var array $registry
	 */
	private static $registry = array();

	/**
	 * nextError
	 *
	 * The next error number to assign
	 * @var integer $nextError
	 */
	private static $nextError = 0;

	/**
	 * instance
	 * 
	 * Instance class variables
	 * @var object $instance
	 */
	private static $instance = null;
	
	/**
	 * __construct
	 * 
	 * Class constructor.
	 */
	private function __construct()
	{
	}

	/**
	 * __destruct
	 * 
	 * Class destructor.
	 */
	public function __destruct()
	{
		self::$instance = null;
	}

	/**
	 * initialize
	 *
	 * initialize class variables
	 * @param array $additionalErrors = (optional) error array to be added to the default array
	 */
	protected function initialize($additionalErrors=null)
	{
		self::$nextError = 0;
		self::$errorMessage = array();
		self::$errors = array();
		
		new ErrorMessages($additionalErrors);
		array_push(self::$registry, 'Errors');
	}

	/**
	 * instance
	 *
	 * Returns the current (or new, if none exists) class instance
	 * @param array $additionalErrors = (optional) error array to be added to the default array
	 * @return object $instance
	 */
	public static function instance($additionalErrors=null)
	{
		if (self::$instance === null)
		{
			self::$instance = new Error();
			self::$instance->initialize($additionalErrors);
		}
		
		return self::$instance;
	}

	/**
	 * register
	 *
	 * Register an error Message
	 * @param string $errorName = a name to refer to the error by
	 * @param string $message   = error Message to register
	 * @param boolean $replace  = (optional) allow existing error Message to be replaced if true, defaults to false
	 * @return integer|boolean $error = error number assigned to the Message, false = unable to replace existing Message
	 */
	public static function register($errorName, $message, $replace=false)
	{
		self::instance();
		if (array_key_exists($errorName, static::$errors))
		{
			if (! $replace)
			{
				return false;
			}
		}
		else
		{
			static::$errors[$errorName] = static::$nextError++;
		}

		static::$errorMessage[static::$errors[$errorName]] = $message;

		return static::$errors[$errorName];
	}
	
	/**
	 * inRegistry
	 * 
	 * Check (add optionally add) for listName in the registry
	 * @param string $listName = name to check for
	 * @param boolean $okayToAdd = (optional) flag to indicate all right to create entry (true), or not ok (false) (default=false)
	 * @return boolean = true for name found/added, or false for not found/not added
	 */
	public static function inRegistry($listName, $okayToAdd=false)
	{
		if (! array_key_exists($listName, self::$registry))
		{
			if (! $okayToAdd)
			{
				return false;
			}

			array_push(self::$registry, $listName);
		}
		
		return true;
	}

	/**
	 * message
	 *
	 * Lookup an error message by name or by number
	 * @param  string|integer $errorName = registered error name or number
	 * @return string $errorMessage = registered error Message
	 */
	public static function message($errorName)
	{
		self::instance();

		if (is_numeric($errorName) && array_key_exists($errorName, static::$errorMessage))
		{
			return static::$errorMessage[$errorName];
		}

		if (! array_key_exists($errorName, static::$errors))
		{
			return sprintf("Unknown error name: %s", $errorName);
		}

		return static::$errorMessage[static::$errors[$errorName]];
	}

	/**
	 * code
	 *
	 * get error code from the error name, or error name from the error code
	 * @param  string $errorName = registered error name or code
	 * @return string|integer $error = error name, error code or null if not found
	 */
	public static function code($errorName)
	{
		self::instance();

		if (is_numeric($errorName) && (($key = array_search($errorName, static::$errors)) !== false))
		{
			return $key;
		}

		if (! array_key_exists($errorName, static::$errors))
		{
			return null;
		}

		return static::$errors[$errorName];
	}

	/**
	 * nextError
	 *
	 * get the next error number
	 * @return integer $nextError = next error number
	 */
	public static function nextError()
	{
		self::instance();

		return static::$nextError;
	}

}
