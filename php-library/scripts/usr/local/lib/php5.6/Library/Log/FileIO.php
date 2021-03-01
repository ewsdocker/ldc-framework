<?php
namespace Library\Log;
use Library\FileIO\Factory as FileIOFactory;
use Library\Exception\Descriptor as ExceptionDescriptor;

/*
 * 	 	  Log\FileIO is copyright � 2012, 2015. EarthWalk Software.
 * 		  Licensed under the Academic Free License version 3.0.
 *        Refer to the file named License provided with the source, 
 *         or from http://opensource.org/licenses/academic.php.
*/
/**
 * Log\FileIO.
 *
 * An adapter to allow FileIO classes (FileIO\FileObject and FileIO\SplFileObject) to be used to write a log file
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Log.
 */
class FileIO extends Format
{
	/**
	 * adapter
	 *
	 * The object returned by FileIO\Factory
	 * @var object $adapter
	 */
	protected		$adapter;

	/**
	 * __construct
	 *
	 * Create a new instance of the Library\Log\FileIO class
	 * @param object $properties = \Library\Properties object containing required properties and associated values
	 */
	public function __construct($properties)
	{
		parent::__construct($properties);
		$this->adapter = FileIOFactory::instantiateClass($properties->Log_FileAdapter,
														 $properties->Log_FileDestination,
														 $properties->Log_FileMode);
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 */
	public function __destruct()
	{
		parent::__destruct();
		unset($this->adapter);
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
	public function log($message, $logProperties=null)
	{
		$descriptor = null;

		try
		{
			parent::log($message, $logProperties);
			$this->adapter->fwrite($this->logBuffer);
		}
		catch(Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}
		catch(\Library\FileIO\Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}
		catch(\Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}

		$this->properties->Exception_Descriptor = $descriptor;
	}

}
