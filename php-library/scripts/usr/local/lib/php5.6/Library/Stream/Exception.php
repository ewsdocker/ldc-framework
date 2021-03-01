<?php
namespace Library\Stream;

/*
 * 		Exception is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 * Stream\Exception.
 *
 * Class Exception class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Stream
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
	 */
	public function __construct($message='', $code=0, Exception $previous=null)
	{
		parent::__construct($message, (int)$code, $previous);
	}
}
