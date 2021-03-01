<?php
namespace Library\Log;

/*
 * 	 	  Log\Printer is copyright © 2012, 2013. EarthWalk Software.
 * 		  Licensed under the Academic Free License version 3.0.
 *        Refer to the file named License provided with the source, 
 *         or from http://opensource.org/licenses/academic.php.
 */
/**
 * Log\Printer.
 *
 * An adapter to allow PHP's print function to be used to write a log file
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Log.
 */
class Printer extends Format
{
	/**
	 * __construct
	 *
	 * Create a new instance of the Library\Log\Print class
	 * @param object $properties = \Library\Properties object containing required properties and associated values
	 */
	public function __construct($properties)
	{
		parent::__construct($properties);
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 * @return null
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * log
	 *
	 * Log a message to the current log device
	 * @param string $message = message to write to the log file
	 * @param mixed $logProperties = (optional):	'Log_Level'      => <(optional) error level (default = Library\Log::LOG_BSD_ERR)>,
	 * 												'Log_Format'	 => <(optional) format type: 'none', 'timestamp', 'log' (default = 'none')
	 *                              				'Log_LineNumber' => <linenumber>,
	 *                              				'Log_Method'     => <(optional) name of the calling method>,
	 *                              				'Log_Class'      => <(optional) name of the calling class>,
	 *                              				'Log_Program'    => <(optional) name of the program to log>
	 *                              				'Log_SkipLevels' => <(optional) number of levels to skip in traceback stack
	 *                              				'Log_Timestamp'  => <(optional) timestamp of the event>
	 * @throws Library\Log\Exception
	 */
	public function log($message, $logProperties=null)
	{
		try
		{
			print parent::log($message, $logProperties);
		}
		catch(Exception $exception)
		{
			$this->properties->Exception_Descriptor = new \Library\Exception\Descriptor($exception);
		}
	}

}
