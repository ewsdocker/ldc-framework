<?php
namespace Library;

/*
 * 		Exception is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
 */
/**
 * Exception.
 *
 * Library\Exception class to extend the PHP Exception class
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Exception
 */
class Exception extends \Exception
{
	/**
	 * __construct
	 *
	 * Create an exception object instance
	 * @param string $message     = message to send
	 * @param integer $code       = exception code
	 * @param Exception $previous = (optional) previous exception
	 * @return object instance
	 */
	public function __construct($message, $code=0, Exception $previous=null)
	{
		if (is_numeric($message))
		{
			$code = (int)$message;
			$message = '';
		}

		if ($message == '')
		{
			$message = Error::message($code);
		}

		parent::__construct($message, (int)$code, $previous);
	}
}
