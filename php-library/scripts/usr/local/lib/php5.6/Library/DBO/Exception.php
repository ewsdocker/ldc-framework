<?php
namespace Library\DBO;

use Library\Error;

/*
 * 		DBO\Exception is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 			or from http://opensource.org/licenses/academic.php
 */
/**
 * DBO\Exception.
 *
 * DBO\Exception class to extend the PHP mysqli_sql_exception exception class
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage DBO
 */
class Exception extends \mysqli_sql_exception
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
