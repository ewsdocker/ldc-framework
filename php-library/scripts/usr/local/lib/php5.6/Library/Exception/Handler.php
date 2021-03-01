<?php
namespace Library\Exception;

/*
 * 		Exception\Handler is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
 */

/**
 * Exception\Handler.
 *
 * Default exception handler class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Exception
 */

class Handler
{
	/**
	 * oldHandler
	 * 
	 * A copy of the original handler
	 * @var handler $oldHandler
	 */
	private $oldHandler;

	/**
	 * __construct
	 *
	 * Create a default exception object instance
	 */
	public function __construct()
	{
		$this->oldHandler = set_exception_handler(array($this, 'exceptionHandler'));
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 */
	public function __destruct()
	{
		if ($this->oldHandler)
		{
			set_exception_handler($this->oldHandler);
		}
	}

	/**
	 * exceptionHandler
	 * 
	 * Default exception handler
	 * @paran object $exception = Exception object
	 */
	public function exceptionHandler(Exception $exception)
	{
		$descriptor = new Descriptor($exception);
		PrintU::printLine("Uncaught exception:\n" . $descriptor);
		set_exception_handler($this->oldHandler);
	}

}
