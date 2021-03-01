<?php
namespace Application\Launcher\Testing;

use Application\Launcher\Utility\ArchiveLogs;
use Application\Launcher\Utility\Statistics;
use Application\Launcher\Utility\Summary;
use Application\Utility\Support;
use Application\Launcher\Utility\Logger;

use Library\Exception\Descriptor as ExceptionDescriptor;
use Library\Exception\Handler as ExceptionHandler;
use Library\Error;
use Library\CliParameters;
use Library\PrintU;
use Library\Records\LoadFile;
use Library\Stack\Queue as StackQueue;
use Library\Utilities\FormatVar;

/*
 *		Testing\Runner is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *		Refer to the file named License.txt provided with the source, 
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * Testing\Runner
 *
 * A programmable assertion test runner
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Launcher
 * @subpackage Testing
 */
class Runner
{
	/**
	 * Properties ($properties) structure contains at least the following fields:
	 * 
	 * 		Config_Adapter
	 *		Config_FileName
	 *		Config_Folder
	 *		Config_Instance
	 *		Config_IoAdapter
	 *		Config_IoAdapterType
	 *		Config_Mode
	 *		Config_Section
	 *		Config_Source
	 *		Config_Tree
	 *		Config_Tree->ErrorHandler
	 *		Config_Tree->ErrorHandler->DisplayErrors
	 *		Config_Tree->ErrorHandler->EnableExceptions
	 *		Config_Tree->ErrorHandler->FormatXML
	 *		Config_Tree->ErrorHandler->LogErrors
	 *		Config_Tree->ErrorHandler->LogLevel
	 *		Config_Tree->ErrorHandler->ReportErrors
	 *		Config_Tree->TimeZone
	 *		Config_Tree->Verbose
	 *
	 *		Exception_Descriptor
	 *		Exclude_Processes
	 *		Execute_Type
	 *
	 *		FileIO_Source
 	 *
	 *		Include_Processes
	 *		Init_ErrorHandler
	 *		Install_Path
	 *
	 *		Log_Format
	 *		Log_SubLog
	 *
	 *		NS_Path
	 *
	 *		Process_Name
	 *		Process_Namespace
	 *		Process_Program
	 *		Process_Script
	 *		Process_Type
	 *
	 *		Root_Path
	 *		Roots
	 *
	 *		SPL_AutoLoader
	 *		SPL_Libraryloader
	 *
	 *		timezone
	 *
	 *		Verbose
	 *
	 *   Plus any items in the global and 'testing' sections of the configuration file (Config_Tree->)
	 */

	/**
	 * properties
	 *
	 * \Library\Properties object
	 * @var object $properties
	 */
	protected $properties;

	/**
	 * processRecords
	 *
	 * Records\LoadFile class object containing the records to be processed
	 * @var object $processRecords
	 */
	protected $processRecords;

	/**
	 * verbose
	 *
	 * Verbose flag - 0 or false or null = no output, else output to current print device
	 * @var integer $verbose
	 */
	private $verbose;

	/**
	 * logger
	 * 
	 * The test logger
	 * @var object $logger
	 */
	private $logger;

	/**
	 * logFormat
	 * 
	 * A string containing the selected log format
	 * @var string $logFormat
	 */
	private $logFormat;

	/**
	 * processDescriptor
	 * 
	 * A processDescriptor class instance
	 * @var object $processDescriptor
	 */
	private $processDescriptor;

	/**
	 * stats
	 * 
	 * A Statistics class instance
	 * @var object $stats
	 */
	private $stats;

	/**
	 * defaultExceptionHandler
	 * 
	 * The default exception handler
	 * @var handler $defaultExceptionHandler
	 */
	private $defaultExceptionHandler;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param object $properties = reference to a Properties object instance
	 * @throws Exception
	 */
	public function __construct()
	{
		FormatVar::sort(false);
		FormatVar::sortValues(false);
		FormatVar::recurse(true);

		$this->properties = Support::properties();
		$configTree = $this->properties->Config_Tree;

		$this->stats = new Statistics($this->properties);
		$this->processDescriptor = null;

		if ($configTree->Archive->Enabled->Create)
		{
			$archive = new ArchiveLogs($this->properties);
			$archive->archiveDirectory($configTree->InstallFolders->TestLogs, $configTree->InstallFolders->TestArchives);
		}

		$this->logger = new Logger($this->properties, $this->properties->Log_SubLog);
		$this->logger->startLogger();
		$this->properties->Error_Handler->additionalLog($this->logger);

		Support::logger($this->logger);

		$this->stats->logger($this->logger);

		$this->defaultExceptionHandler = new ExceptionHandler();
		$this->processRecords = null;
	}

	/**
	 * __destruct
	 *
	 * Destroy the current class instance
	 */
	public function __destruct()
	{
		$this->stats = null;

		if ($this->logger !== null)
		{
			$this->logger->stopLogger();
			$this->logger = null;
		}
		
		$this->processRecords = null;

		$this->defaultExceptionHandler = null;
	}

	/**
	 * execute
	 *
	 * Run the requested tests
	 * @return integer $result = exit code
	 */
	public function execute()
	{
		$this->logTesterProperties();
		$this->logger->startTestsMessage();

		if ($result = $this->getProcessRecords())
		{
			return $result;
		}

		$testCount = count($this->processRecords);

		$this->logger->logMessage(sprintf("execute is running %u test%s.", ($testCount > 1) ? $testCount : 1, ($testCount == 0) ? '' : 's'), 'debug');

		$this->stats->result = 0;

		foreach($this->processRecords as $queueIndex => $processRecord)
		{
			$this->stats->processesRun++;

			$processNumber = $this->processRecords[$queueIndex] + 1;
			$this->properties->Run_ProcessNumber = $processNumber;

			if (($this->stats->result = $this->runTest($this->properties->Run_ProcessNumber, $processRecord)) !== 0)
			{
				$this->logger->logMessage(sprintf('execute failed for test # %u(subtest #%u). Result = %u', 
												  $this->properties->Run_ProcessNumber,
												  $this->properties->Run_SubProcessNumber,
												  $this->stats->result), 'error');
				break;
			}
		}

		$this->logger->logMessage(' ', 'debug');
		$this->logger->logMessage('******** TESTS COMPLETED ********', 'debug');
		$this->logger->logMessage(' ', 'debug');

		if ($this->stats->result == 0)
		{
			$this->logger->logMessage(sprintf('Successfully completed %u test%s of %u.', 
											  $this->stats->processesRun, 
											  ($this->stats->processesRun > 1) ? 's' : '',
					                          $testCount), 'debug');
		}
		else
		{
			--$this->stats->processesRun;
			$this->logger->logMessage(sprintf('Successfully completed %u test%s of %u.', 
										 	  $this->stats->processesRun, 
											  ($this->stats->processesRun != 1) ? 's' : '',
											  $testCount), 'debug');
		}

		$this->stats->ended();

		$this->logger->logMessage(' ', 'debug');
		$this->logger->logMessage(sprintf('Elapsed time: %f seconds', $this->stats->elapsed()));
		$this->logger->logMessage(' ', 'debug');

		$this->logger->stopTestsMessage();

		$this->logger->logMessage(' ', 'debug');
		$this->logger->logMessage('******** TEST STATISTICS ********', 'debug');
		$this->logger->logMessage(' ', 'debug');

		$this->stats->logger($this->logger);
		$this->stats->serializeStats($this->logger);

		if ($this->properties->Config_Tree->Summary->Enabled)
		{
			$summary = new Summary($this->properties, $this->logger);
			$summary->summarizeProcesses($this->properties->Config_Tree->InstallFolders->TestLogs, '*.ser');
		}

		$this->stats->active(false);
		return $this->stats->result;
	}

	/**
	 * runTest
	 *
	 * Run the requested test
	 * @param integer $processNumber = test number being run
	 * @param string $processRecord = information about the test being run
	 * @return integer $result = 0 if no error, otherwise an error code
	 */
	public function runTest($processNumber, $processRecord)
	{
 		$this->processDescriptor = $this->stats->newProcess($processRecord);
 		$this->processDescriptor->processNumber = $processNumber;
		$this->processDescriptor->exceptionDescriptor = null;
		$this->processDescriptor->programName = $this->properties->Process_Name;

		$processRecord = $this->properties->Process_Name . ' ' . $processRecord;

		$this->getParameters($processNumber, $processRecord);

		$this->logger->logMessage(sprintf('Test #% 3u = "%s"', $processNumber, $this->processDescriptor->processName), 'debug');

		$result = 0;
		
		try
		{
			$assertTests = new $this->processDescriptor->processName();
			$assertTests->properties($this->properties);
		
			$this->logger->logMessage('	STARTED', 'debug');
		
			$assertTests->logger(new AssertLog($this->properties, $this->processDescriptor->subLogName));
		
			$this->processDescriptor->logName = $assertTests->logger()->logPath();
			$this->processDescriptor->serializeName = $assertTests->logger()->logPath(null, 'ser');
		
			$assertTests->testName($this->processDescriptor->processName);
			$assertTests->verbose($this->verbose);
			$assertTests->testNumber(1);
			$assertTests->processDescriptor($this->processDescriptor);
		
			$this->processDescriptor->started = microtime(true);
				
			$this->properties->Error_Handler->queueDevice(new StackQueue('$this->processDescriptor->processName'));
			$this->processDescriptor->errorQueue = $this->properties->Error_Handler->queueDevice();
		
			$this->properties->Error_Handler->queueErrors(true);
		
			$assertTests->assertionTests('Launcher', $this->logFormat);
		}
		catch(Exception $exception)
		{
			$this->processDescriptor->exceptionDescriptor = new ExceptionDescriptor($exception);
		}
		catch(\Exception $exception)
		{
			$this->processDescriptor->exceptionDescriptor = new ExceptionDescriptor($exception);
		}
		
		if ($this->processDescriptor->exceptionDescriptor !== null)
		{
			$this->logger->logMessage(sprintf('***  Test failed: %s', $this->processDescriptor->exceptionDescriptor));
			$result = $this->processDescriptor->exceptionDescriptor->code;
		}

		$this->processDescriptor->ended = microtime(true);

		$this->properties->Error_Handler->queueErrors(false);
		$this->processDescriptor->errorQueue = $this->properties->Error_Handler->queueDevice();
		$this->properties->Error_Handler->queueDevice(null, true);

		$assertLogger = $assertTests->logger();
		$assertLogger->stopLogger(sprintf('Elapsed time: %f seconds.', 
										  $this->processDescriptor->ended - $this->processDescriptor->started));

		$assertTests = null;
		$assertLogger = null;

		$this->logger->logMessage('	ENDED', 'debug');

		if ($result == 0)
		{
			$this->logger->logMessage(sprintf('Test #% 3u = "%s" Completed Successfully. Elapsed time: %f seconds.', 
											  $processNumber, 
											  $this->properties->Process_Name,
											  $this->processDescriptor->ended - $this->processDescriptor->started), 'debug');
		}

		return $result;
	}

	/**
	 * getParameters
	 *
	 * Get parameters from the 'test record'
	 * @param integer $processNumber = test number being run
	 * @param string $processRecord = information about the test being run
	 * @return integer $result = 0 if no error, otherwise an error code
	 */
	protected function getParameters($processNumber, $processRecord)
	{
		$processDescriptor = $this->processDescriptor;

		try
		{
			CliParameters::initialize($processRecord);
		}
		catch(\Library\CliParameters\Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
			$this->logger->logMessage(sprintf('%s', $descriptor));
			$processDescriptor->exceptionDescriptor = $descriptor;
			return $descriptor->code;
		}
		
		$processDescriptor->cliParameters = CliParameters::parameters();
		
		$this->verbose = CliParameters::parameterValue('verbose', $this->properties->Verbose);
		$this->logFormat = CliParameters::parameterValue('format', $this->properties->Log_Format);

		$this->properties->Process_Name	   = CliParameters::parameterValue('test');
		$this->properties->Process_Number	   = 1;
		$this->properties->Process_LoggerName = CliParameters::parameterValue('logger', 'Launcher');

		$processDescriptor->processName = $this->properties->Process_Name;
		$processDescriptor->subLogName = $this->properties->Process_LoggerName;
	}

	/**
	 * getProcessRecords
	 * 
	 * Load the test records from the test script file
	 * @return integer $result = result of load request (0 = successful, non-zero = error code)
	 * @throws Exception if load records fails
	 */
	protected function getProcessRecords()
	{
		if (! $this->stats->processScript = $this->properties->Process_Script)
		{
			$this->logger->logMessage(Error::code('MissingFileName'), 'debug');
			return Error::code('MissingFileName');
		}

		if ($this->properties->Process_Type == 'Application')
		{
			$processType = 'ApplicationTests';
		}
		else 
		{
			$processType = 'Tests';
		}

		$this->properties->Process_ScriptPath = Support::absolutePath(sprintf("%s%s%s", $this->properties->Config_Tree->InstallFolders->$processType, 
										  					                            DIRECTORY_SEPARATOR, 
										  					                            $this->properties->Process_Script), true);

		$descriptor = null;

		try 
		{
			$this->processRecords = new LoadFile($this->properties->Process_ScriptPath, $this->properties->Include_Processes, $this->properties->Exclude_Processes);
		}
		catch(Records\Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}
		catch(Parse\Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}
		
		if ($descriptor)
		{
			$this->logger->logMessage(sprintf('%s', $descriptor));
			throw new Exception($descriptor->message, $descriptor->code, $descriptor->previous);
		}

		$this->properties->Process_Records = $this->processRecords;

   		foreach($this->processRecords as $queueIndex => $processRecord)
		{
			$this->logger->logMessage(sprintf('% 4u %s', $this->processRecords[$queueIndex] + 1, trim($processRecord)));
		}

	}

	/**
	 * logTesterProperties
	 * 
	 * Output the program name, copyright and properties class to the current logger
	 */
	public function logTesterProperties()
	{
		$subLog = $this->properties->Log_SubLog;
		$this->logger->logBlockMessage($this->properties->Config_Tree->Log->$subLog->Program);
		$this->logger->logMessage($this->properties->Config_Tree->copyright);
		$this->logger->logMessage(Formatvar::format(CliParameters::parameters(), 'CLI Parameters'));
	}

}
