<?php
namespace Tests\Factory\TestClasses;

/*
 *		EchoBack is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, 
 *            or from http://opensource.org/licenses/academic.php
*/
/**
 * EchoBack
 *
 * EchoBack class.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright © 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Factory
 */
class EchoBack
{
	/**
	 * __construct
	 *
	 * Class constructor - pass parameters on to parent class
	 * @param array $parameters = address of the parameters array
	 */
	public function __construct()
	{
	}

	/**
	 * __destruct
	 *
	 * Class destructor.
	 */
	public function __destruct()
	{
	}

	/**
	 * echoMe
	 *
	 * Simple method to echo back the provided message
	 * @param string $message
	 */
	public function echoMe($message)
	{
		return $message;
	}

	/**
	 * echoReference
	 *
	 * Simple method to echo back the provided message in a reference variable
	 * @param string $message
	 * @param string $reflected = address of the string to echo back in
	 * @return boolean $result = true
	 */
	public function echoReference($message, &$reflected)
	{
		$reflected = $message;
		return true;
	}

}
