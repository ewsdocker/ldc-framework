<?php
namespace Library\Error;

use Library\Error;

/*
 *		AddMessages is copyright � 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * AddMessages.
 *
 * Add messages to the current Error\Messages message list
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Error
 */
class AddMessages
{
	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $messageGroup = name of the message group being added
	 * @param array $errorList = error messages array
	 */
	public function __construct($messageGroup, $errorList)
	{
		if (! Error::inRegistry($messageGroup, false))
		{
			$this->registerErrors($this->errorList);
			Error::inRegistry($messageGroup, true);
		}
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 */
	public function __destruct()
	{
	}

	/**
	 * registerErrors
	 *
	 * Register the error Messages in the list
	 * @param array $errorList = array containing the error name as the key and message as the value.
	 */
	protected function registerErrors($errorList)
	{
		foreach($errorList as $name => $message)
		{
			Error::register($name, $message, true);
		}
	}

}
