<?php
namespace Application\Launcher\Testing;
use Application\Launcher\Utility\Exception as UtilityException;
use Application\Launcher\Utility\Logger;

use Library\CliParameters;
use Library\Utilities\EldestParent;
use Library\Utilities\FormatVar;

/*
 *		AssertLog is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * AssertLog
 *
 * AssertLog class for assertion logging support
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Launcher
 * @subpackage Testing
 */
class AssertLog extends Logger
{
	/**
	 * __construct
	 *
	 * Class constructor
	 * @param object $properties = reference to a Properties object instance
	 * @param string $subLog = (optional) log subcategory name, default = 'Test'
	 */
	public function __construct(&$properties, $subLog='Test')
    {
    	parent::__construct($properties, $subLog);
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

	/**
     * logMessage
     *
     * Output a message + newline to the current output device
     * @param string $message = message to output
     * @param integer $level = (optional) log level ('debug', 'error', <user defined>) DEFAULT: debug
     */
    public function logMessage($message, $level='debug')
    {
		parent::logMessage(sprintf("Test #%u, Subtest #%u - %s",
							    	$this->properties->Run_ProcessNumber,
							    	$this->properties->Run_SubtestNumber,
							    	$message),
					    	array("Log_Level"      => $level,
					 	    	  "Log_Program"    => $this->properties->Process_Name,
					 	    	  "Log_Method"     => substr(__METHOD__, strpos(__METHOD__, "::") + 2),
					 	    	  "Log_Class"      => EldestParent::get($this),
					 	    	  "Log_SkipLevels" => 7));
    }

    /**
     * startLogger
     *
     * Create a test-relative log file and start the logger
     * @param string $subLog = (optional) log sub-category, default = null
     * @param string $format = (optional) output format name, null to not assign
     * @return boolean $result = true if successful, false if not
     */
    public function startLogger($subLog='Test', $format='timestamp')
    {
    	$descriptor = null;

    	try 
    	{
			parent::startLogger($subLog, $format);
    	}
    	catch(UtilityException $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
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
			Log::message(sprintf('Unable to open disk file log: %s', $this->logProperties->Log_FileDestination), 'error');
			Log::message('Logging to disk file has been disabled', 'error');
			return false;
		}

		$this->logMessage(sprintf('Test Program: %s, Log File: %s',
								  $this->properties->Process_Name,
								  $this->logProperties->Log_FileDestination));

		if (CliParameters::parameterCount() == 0)
		{
			$this->logMessage('    NO CLI Parameters');
		}
		else
		{
			$this->logMessage(FormatVar::format(CliParameters::parameters(), 'CLI Parameters'));
		}

		$this->properties->Process_Start = date('Y-m-d H:i:s') . substr((string)microtime(), 1, 6);
		$this->properties->Process_End   = null;
		$this->logMessage('Start-of-test @ ' . $this->properties->Process_Start);

		return true;
	}

	/**
	 * stopLogger
	 *
	 * Stop the logger, if it is running
	 * @param string $append = (optional) message to append to the end of the message
	 */
	public function stopLogger($append='')
	{
		if ($this->logStarted)
		{
			$this->properties->Process_End = date('Y-m-d H:i:s') . substr((string)microtime(), 1, 6);
			$this->logMessage(sprintf('End-of-test @ %s. %s', $this->properties->Process_End, $append));

			parent::stopLogger();
		}
	}

	/**
	 * logPath
	 * 
	 * Create a new sub-category log file name
	 * @param string $subLog = (optional) external name of the file, null to create new test log file name
	 * @param string $type = (optional) file type
	 * @return string $modifiedName
	 */
	public function logPath($subLog=null, $type='log')
	{
		if (! $subLog)
		{
			$testName = $this->properties->Process_Name;
			if (substr($testName, 0, 1) == '\\')
			{
				$testName = substr($testName, 1);
			}

			$subLog = 
				sprintf("%s/%s/%04u-%s.%s", $this->configTree->InstallFolders->Root,
										    $this->configTree->InstallFolders->TestLogs,
										    $this->properties->Run_ProcessNumber,
										    str_replace(array("\\", "//", '_'), '-', $testName),
										    $type);
		}
		
		return $subLog;
	}

}
