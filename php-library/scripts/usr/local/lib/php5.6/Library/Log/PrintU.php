<?php
namespace Library\Log;

/*
 * 	 	  Log\PrintU is copyright © 2012, 2013. EarthWalk Software.
 * 		  Licensed under the Academic Free License version 3.0.
 *        Refer to the file named License provided with the source, 
 *         or from http://opensource.org/licenses/academic.php.
 */
/**
 * Log\PrintU.
 *
 * An adapter to allow PrintU to be used to write a log file
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Log.
 */
class PrintU extends Format
{
	/**
	 * __construct
	 *
	 * Create a new instance of the Library\Log\PrintU class
	 * @param object $properties = \Library\Properties object containing required properties and associated values
	 */
	public function __construct($properties)
	{
		parent::__construct($properties);
		\Library\PrintU::selectInterface(\Library\PrintU::INTERFACE_CONSOLE);
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
	 */
	public function log($message, $logParameters=null)
	{
		$descriptor = null;
		try
		{
			parent::log($message, $logParameters);
			\Library\PrintU::printMessage($this->logBuffer);
		}
		catch(Exception $exception)
		{
			$descriptor = new \Library\Exception\Descriptor($exception);
		}
		catch(\Library\PrintU\Exception $exception)
		{
			$descriptor = new \Library\Exception\Descriptor($exception);
		}
		catch(\Exception $exception)
		{
			$descriptor = new \Library\Exception\Descriptor($exception);
		}

		$this->properties->Exception_Descriptor = $descriptor;
	}

}
