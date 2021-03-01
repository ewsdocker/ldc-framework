<?php
namespace Library\PLLError;

/*
 * 		Accept\Exception is copyright � 2015, 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 			or from http://opensource.org/licenses/academic.php
 */

/**
 * Accept\Exception.
 *
 * Accept Exception class.
 * @author Jay Wheeler.
 * @version 2.0
 * @copyright � 2015, 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Accept
 */

class Exception extends \Library\Exception
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
		parent::__construct($message, (int)$code, $previous);
	}

}
