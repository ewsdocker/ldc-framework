<?php
namespace Application\Launcher;

use Library\Properties;

/*
 *		Application\Launcher\ProcessDescriptor is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\Launcher\ProcessDescriptor
 *
 * Process descriptor class
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Application
 * @subpackage Launcher
 */
class ProcessDescriptor extends Properties
{
	/*
	 *	Fields:
	 *
	 * 		processRecord
	 * 
	 * 		programName
	 * 		cliParameters
	 * 
	 * 		processName
	 * 		processNumber
	 * 		subTest
	 * 
	 * 		logName
	 * 		subLogName
	 * 
	 * 		serializeName
	 * 
	 * 		started
	 * 		ended
	 * 
	 * 		exceptionDescriptor
	 * 		errorQueue
	 */

	/**
	 * __construct
	 *
	 * Class constructor
	 */
	public function __construct($properties)
    {
    	parent::__construct($properties);
    }

	/**
	 * __destruct
	 *
	 * Destroy the current class instance
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

}
