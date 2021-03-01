<?php
namespace Application\Launcher\Utility;

use Library\Exception\Descriptor as ExceptionDescriptor;
use Library\Error;
use Library\Log;
use Library\PrintU;

/*
 *		Logger is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *		Refer to the file named License.txt provided with the source, 
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * Logger
 *
 * An assertion test Logger
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Launcher
 * @subpackage Utility
 */
class Logger
{
	/**
	 * logProperties
	 * 
	 * Library\Properties class instance containing active log parameters
	 * @var object $logProperties
	 */
	protected $logProperties;

	/**
	 * defaultLogProperties
	 * 
	 * Library\Properties class instance containing default log parameters
	 * @var object $defaultLogProperties
	 */
	protected $defaultLogProperties;

	/**
	 * properties
	 *
	 * \Library\Properties object
	 * @var object $properties
	 */
	protected $properties;

	/**
	 * subLog
	 * 
	 * The log sub-category name
	 * @var string $subLog
	 */
	protected $subLog;

	/**
	 * configTree
	 * 
	 * A Library\Properties::Config_Tree instance
	 * @var object $configTree
	 */
	protected $configTree;

	/**
	 * logStarted
	 * 
	 * True if log has been started, false if not
	 * @var boolean $logStarted
	 */
	protected $logStarted;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param object $properties = reference to a Properties object instance
	 * @param string $subLog = (optional) log subcategory name
	 */
	public function __construct(&$properties, $subLog='Launcher')
	{
		if (($properties == null) || (! is_object($properties)) || (get_class($properties) !== 'Library\Properties'))
		{
			throw new Exception(Error::code('InvalidClassObject'));
		}

		$this->properties =& $properties;
		$this->configTree = $properties->Config_Tree;

		$this->subLog = $subLog;

		$this->defaultLogProperties = $this->setupLogDefaults($subLog);
		$this->setupLogProperties();
		$this->logStarted = false;
	}

	/**
	 * __destruct
	 *
	 * Destroy the current class instance
	 */
	public function __destruct()
	{
		if ($this->logStarted)
		{
			$this->stopLogger();
		}

		$this->properties->Logger_Object = null;
	}

	/**
	 * __clone
	 * 
	 * Magic function to perform adjustments on the shallow copy of an object
	 */
	public function __clone()
	{
		$this->subLog = null;
		$this->logStarted = false;
	}

	/**
	 * startLogger
	 *
	 * start a minimal output test logger
	 * @param string $subLog = (optional) log sub-category, default = null
	 * @param string $format = (optional) output format name, null to not assign
	 * @throws Exception
	 */
	public function startLogger($subLog=null, $format=null)
	{
		if ($this->logStarted || ($subLog && $this->subLog && ($this->subLog != $subLog)))
		{
			$this->stopLogger($subLog);
		}

		if (($this->subLog !== null) && ($subLog !== null) && ($this->subLog !== $subLog))
		{
			$this->setupLogProperties($subLog);
		}

   		if ($format !== null)
		{
			$this->logProperties->Log_Format = $format;
		}

		$descriptor = null;

		try
		{
			Log::startLog($this->logProperties);
		}
		catch(\Library\Log\Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}
		catch(\Library\FileIO\Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}
		catch(\Library\Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}

		if ($descriptor)
		{
			printf('Unable to create TesterLog... "%s"%s', $exception->getMessage(), PHP_EOL);
			print 'logging has been disabled.' . PHP_EOL;
			return false;
		}

		return $this->logStarted = true;
	}

	/**
	 * stopLogger
	 * 
	 * Stop the current logger
	 */
	public function stopLogger()
	{
		if (! $this->logStarted)
		{
			return;
		}

		Log::stopLog($this->logName());
		$this->logStarted = false;
	}

	/**
	 * setupLogProperties
	 * 
	 * Setup log properties for requested log
	 * @param string $subLog
	 */
	public function setupLogProperties($subLog=null)
	{
		if ($this->logStarted)
		{
			$this->stopLogger();
		}

		$subLog = $this->subLog($subLog);

		if ($this->defaultLogProperties == null)
		{
			$this->defaultLogProperties = $this->setupLogDefaults($subLog);
		}

		$this->logProperties = new \Library\Properties($this->defaultLogProperties);
		$this->logProperties->setProperties(array('Log_SkipLevels'		=> $this->configTree->Log->$subLog->SkipLevels,
								 				  'Log_Program'			=> '\Library\phpTest',
										 		  'Log_Exception'		=> \Library\Error::code($this->configTree->Log->$subLog->Exception),
										 		  'Log_Error'			=> \Library\Error::code($this->configTree->Log->$subLog->Error),
										 		  'Log_Adapter'			=> $this->configTree->Log->$subLog->Adapter,
										 		  'Log_Name'			=> $this->configTree->Log->$subLog->Name,
										 		  'Log_Level'			=> $this->configTree->Log->$subLog->Level,
										 		  'Log_Format'			=> $this->configTree->Log->$subLog->Format,

										 		  'Log_FileAdapter'		=> $this->configTree->Log->$subLog->FileAdapter,
										 		  'Log_FileDestination'	=> $this->logPath(),
										 		  'Log_FileMode'		=> $this->configTree->Log->$subLog->FileMode,
										 		  ));

		$this->properties->Logger_Properties = $this->logProperties;
		$this->properties->Logger_Object = null;
	}

	/**
	 * setupLogDefaults
	 *
	 * setup and start the disk file and console logger
	 * @param string $subLog = log subcategory name (e.g. - phpTest), null to use current $subLog
	 */
	protected function setupLogDefaults($subLog=null)
	{
		if ($this->logStarted)
		{
			$this->stopLogger();
		}

		$subLog = $this->subLog($subLog);

		Log::clearLogTypes();

   		Log::setLogType('debug',  \Library\Log::LOG_BSD_DEBUG);
   		Log::setLogType('errors', \Library\Log::LOG_BSD_ERR);

   		$logDefaults = new \Library\Properties(Log::defaultLogProperties());
   		$logDefaults->setProperties
				   (array('Log_SkipLevels'		=> $this->configTree->Log->$subLog->SkipLevels,
						  'Log_Program'			=> \Library\CliParameters::applicationName(),
						  'Log_Adapter'			=> $this->configTree->Log->$subLog->Adapter,
   						  'Log_Name'			=> $this->configTree->Log->$subLog->Name,
   						  'Log_Format'			=> $this->configTree->Log->$subLog->Format,
   						  'Log_Level'			=> $this->configTree->Log->$subLog->Level,
   						  'Log_FileDestination'	=> $this->logPath($subLog),
   					 ));

		return $logDefaults;
	}

	/**
	 * logMessage
	 *
	 * Output the message to the current logging stream
	 * @param string $message = message to output
	 * @param mixed $level = log level or log parameters array or object
	 */
	public function logMessage($message, $level='debug')
	{
		$this->logIsStarted();
   		if (trim($message) != '')
   		{
   			Log::message($message, $level);
   		}
   		
   		if ($this->properties->Verbose > 0)
   		{
   			PrintU::printLine($message);
   		}
	}

	/**
	 * logBlockMessage
	 * 
	 * Output a message block to the logger
	 * @param string $message
	 */
	public function logBlockMessage($message)
	{
		$this->logMessage('********************************');
		$this->logMessage('*');
		$this->logMessage('*		 ' . $message);
		$this->logMessage('*');
		$this->logMessage('********************************');
	}

	/**
	 * startTestsMessage
	 *
	 * Log the Start Tests message
	 */
	public function startTestsMessage()
	{
		$this->logBlockMessage('START TESTS');
	}

	/**
	 * stopTestsMessage
	 * 
	 * Log the Stop Tests message
	 */
	public function stopTestsMessage()
	{
		$this->logBlockMessage('END TESTS');
	}

	/**
	 * properties
	 * 
	 * Set/get the $properties Library\Property object
	 * @param object $properties
	 */
	public function properties(&$properties=null)
	{
		if ($properties != null)
		{
			$this->properties = $properties;
		}

		return $this->properties;
	}

	/**
	 * logProperties
	 * 
	 * Set/get the $logProperties Library\Property object
	 * @param object $logProperties = Library\Property object
	 * @return object $logProperties
	 */
	public function logProperties(&$logProperties=null)
	{
		if ($logProperties != null)
		{
			$this->logProperties = $logProperties;
		}

		return $this->logProperties;
	}

	/**
	 * logName
	 * 
	 * Get the internal log name
	 * @return string $logName
	 * @throws Library\Testing\Exception
	 */
	public function logName()
	{
		if ((! $this->logProperties) || (! $this->logProperties->exists('Log_Name')))
		{
			throw new Exception(Error::code('NotInitialized'));
		}

		return $this->logProperties->Log_Name;
	}

	/**
	 * logPath
	 * 
	 * create the path to the log file
	 * @param string $subLog = (optional) sub-log name (e.g. 'phpTest'), null to use current $subLog
	 * @param string $type = (optional) log type
	 * @return string $logPath = log file path
	 */
	public function logPath($subLog=null, $type=null)
	{
		$subLog = $this->subLog($subLog);
		if ($type == null)
		{
			$type = $this->configTree->Log->$subLog->FileType;
		}

		return sprintf("%s/%s/%s.%s", $this->configTree->InstallFolders->Root,
								   	  $this->configTree->InstallFolders->TestLogs,
								   	  $this->configTree->Log->$subLog->FileDestination,
				   					  $type);
	}

	/**
	 * subLog
	 * 
	 * Set/Get the log sub-category name
	 * @param string $subLog = sub category name, null to query
	 * @return string $subLog
	 */
	public function subLog($subLog=null)
	{
		if ($subLog != null)
		{
			$this->subLog = $subLog;
		}

		return $this->subLog;
	}

	/**
	 * logStarted
	 * 
	 * Set/get the logStarted flag: true if started, false if not, null to query only
	 * @param boolean $logStarted = true if log started, false if not, null to query
	 * @return boolean $logStarted
	 */
	public function logStarted($logStarted=null)
	{
		if ($logStarted !== null)
		{
			$this->logStarted = $logStarted;
		}

		return $this->logStarted;
	}

	/**
	 * isLogStarted
	 * 
	 * Checks if the log has been started - throws exception on not, otherwise returns
	 * @throws Exception
	 */
	private function logIsStarted()
	{
		if (! $this->logStarted)
		{
			throw new Exception(Error::code('LogNotStarted'));
		}
	}

}
