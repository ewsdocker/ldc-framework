<?php
namespace Library\Autoload;
use Library\Error;
/*
 * 		    Autoload\Spl is copyright © 2012, 2013. EarthWalk Software.
 * 		    Licensed under the Academic Free License version 3.0.
 * 			Refer to the file named License.txt provided with the source, 
 * 			   or from http://opensource.org/licenses/academic.php
 */
/**
 * Autoload\Spl
 *
 * Static convenience class wrapper for the spl_autoload functions
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Autoload
 */
class Spl
{
	/**
	 * stack
	 *
	 * Stack class object to manage the stack array.
	 * @var object $stack
	 */
	private static	$stack = null;

	/**
	 * register
	 *
	 * Register the handler
	 * @param string|array $handler = name of the class and method to register or and array containing class and method in the array
	 * @param boolean $replace = true to replace if already exists, false to not replace existing (default)
	 * @param boolean $prepend = true to add to front of registered list, false to add to back (default)
	 * @throws \Library\Autoload\Exception
	 */
	public static function register($handler, $replace=false, $prepend=false)
	{
		if (self::exists($handler))
		{
			if (! $replace)
			{
				return;
			}

			self::unRegister($handler);
		}

		if (! spl_autoload_register($handler, false, $prepend))
		{
			throw new Exception(Error::code('AutoloadRegisterFailed'));
		}

		self::getRegistered();
	}

	/**
	 * unRegister
	 *
	 * unregister the specified handler if it exists
	 * @param string|array $handler = string or array containing the name(s) of the handler to unregister
	 * @throws \Library\Autoload\Exception
	 */
	public static function unRegister($handler)
	{
		if (self::exists($handler))
		{
			spl_autoload_unregister($handler);
		}

		self::getRegistered();
	}

	/**
	 * exists
	 *
	 * returns true if handler exists in the spl autoload stack
	 * @param string|array $handler = handler to check for
	 * @return boolean $result = true if exists, false if doesn't
	 * @throws \Library\Autoload\Exception
	 */
	public static function exists($handler)
	{
		if (self::existsAt($handler) === false)
		{
			return false;
		}

		return true;
	}

	/**
	 * existsAt
	 *
	 * returns array key if handler exists in the spl autoload stack
	 * @param string|array $handler = handler to check for
	 * @return integer|boolean $location if exists, false if doesn't
	 * @throws \Library\Autoload\Exception
	 */
	public static function existsAt($handler)
	{
		if (is_array($handler) && (count($handler) != 2))
		{
			throw new Exception(Error::code('InvalidArraySize'));
		}

		self::getRegistered();
		return self::allocateStack()->inStack($handler);
	}

	/**
	 * getRegistered
	 *
	 * get an array of registered functions
	 * @return array|boolean $stack = array of function names, false if non set
	 */
	public static function getRegistered()
	{
		$stack = self::allocateStack();
		if ($functions = spl_autoload_functions())
		{
			$stack->stack($functions);
		}

		return $stack->stack();
	}

	/**
	 * allocateStack
	 *
	 * allocate a new stack, if required, and return a pointer to it
	 * @return object $stack
	 */
	private static function allocateStack()
	{
		if (self::$stack === null)
		{
			self::$stack = new \Library\Stack\Extension();
		}

		return self::$stack;
	}

	/**
	 * getPrintableStack
	 *
	 * get a string containing the autoloader stack in printable format
	 * @return string $buffer
	 */
	public static function printableStack()
	{
		return (string)self::allocateStack();
	}

}
