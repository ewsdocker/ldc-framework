<?php
namespace Library\Serialize;

/*
 * 		Library\Serialize\Exception is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 * Serialize\Exception.
 *
 * Serialize Exception class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Serialize
 */
class Exception extends \Library\Exception
{
	/**
	 * __construct
	 *
	 * Create an exception object instance
	 * @param string $message = (optional) exception message
	 * @param integer $code = (optional) exception code
	 * @param Exception $previous = (optional) previous exception
	 * @return \Library\Serialize\Exception object instance
	 */
	public function __construct($message='', $code=0, Exception $previous=null)
	{
		parent::__construct($message, (int)$code, $previous);
	}
}
