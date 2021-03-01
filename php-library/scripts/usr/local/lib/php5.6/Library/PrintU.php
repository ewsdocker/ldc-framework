<?php
namespace Library;

/*
 *		PrintU is copyright © 2012, 2013. EarthWalk Software.
 *		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 * PrintU.
 *
 * Unified printing static interface convenience class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage PrintU.
 */
class PrintU
{
	/**
	 * INTERFACE_UNDEFINED
	 *
	 * Undefined interface
	 * @var INTERFACE_UNDEFINED
	 */
	const			INTERFACE_UNDEFINED	= 0;

  	/**
  	 * INTERFACE_CONSOLE
  	 *
  	 * Console interface.
  	 * @var INTERFACE_CONSOLE
  	 */
	const			INTERFACE_CONSOLE	= 1;

  	/**
  	 * INTERFACE_WEB
  	 *
  	 * Web interface.
  	 * @var INTERFACE_WEB
  	 */
	const			INTERFACE_WEB		= 2;

	/**
	 * BUFFER
	 *
	 * Name to use for the internal buffer
	 * @var BUFFER
	 */
	const			BUFFER				= 3;

	/**
	 * FILE
	 * 
	 * An output disk file.
	 * @var FILE
	 */
	const			FILE				= 4;

	/**
	 * instance
	 * 
	 * Class instance for this static class
	 * @var object $instance
	 */
	private	static	$instance = null;

	/**
	 * output
	 * 
	 * PrintU\Output instance object
	 * @var object $output
	 */
	private static	$output = null;

	/**
	 * __construct
	 * 
	 * Class constructor - private to limit access to this class only
	 */
	private function __construct()
	{
		self::$output = new PrintU\Output();
	}

	/**
	 * __callStatic
	 *
	 * Trap the failed static method call and re-direct to the current instance object
	 * @param string $method = name of the method being called
	 * @param array $arguments = array of class arguments
	 * @return mixed $result
	 */
	public static function __callStatic($method, $arguments)
	{
		return call_user_func_array(array(self::instance(), $method), $arguments);
	}

	/**
	 * instance
	 *
	 * Get the instance object, create a new instance if null
	 * @return object $instance
	 */
	public static function instance()
	{
		if (! self::$instance)
		{
			self::$instance = new PrintU();
		}

		return self::$output;
	}

}
