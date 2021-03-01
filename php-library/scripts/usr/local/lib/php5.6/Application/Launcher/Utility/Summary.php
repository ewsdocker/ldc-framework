<?php
namespace Application\Launcher\Utility;

use Application\Utility\Support;

use Library\CliParameters;
use Library\Directory\Exception as DirectoryException;
use Library\Error;
use Library\Exception\Descriptor;
use Library\FileIO;
use Library\PrintU;
use Library\Properties;
use Library\Utilities\FormatVar;

/*
 *		Summary is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	    Refer to the file named License.txt provided with the source, 
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * Summary
 *
 * Summary class
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Launcher
 * @subpackage Utility
 */
class Summary
{
	/**
	 * processStatsFields
	 * 
	 * List of field names
	 * @var array $processStatsFields
	 */
	public 	$processStatsFields;

	/**
	 * exceptionDescriptor
	 * 
	 * Exception\Descriptor instance, or null
	 * @var object $exceptionDescriptor
	 */
	public	$exceptionDescriptor;

	/**
	 * properties
	 * 
	 * Library\Properties instance
	 * @var object $properties = Library\Properties instance
	 */
	public	$properties;

	/**
	 * csvFile
	 * 
	 * A file object for writting to the CSV summary file
	 * @param object csvFile
	 */
	public	$csvFile;

	/**
	 * logger
	 * 
	 * A Testing\Logger object
	 * @var object $logger
	 */
	protected	$logger;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param object $properties = Library\Properties instance
	 */
	public function __construct($properties, $logger=null)
	{
		$this->exceptionDescriptor = null;
		$this->logger = $logger;
		$this->properties = $properties;
		$this->csvFile = null;

		$this->processStatsFields = 
			array('processRecord'			=> 'Process record',
				  'programName'				=> 'Program name',
				  'cliParameters'			=> 'CLI parameters',
				  'processName'				=> 'Process name',
				  'processNumber'			=> 'Process number',
				  'subProcess'				=> 'Sub process number',
				  'logName'					=> 'Log name',
				  'subLogName'				=> 'Log sub-category',
				  'serializeName'			=> 'Serialize name',
				  'started'					=> 'Start time (msec)',
				  'ended'					=> 'End time (msec)',
				  'exceptionDescriptor'		=> 'Exception descriptor',
				  'errorQueue'				=> 'Error(s)',
				  );
	}

	/**
	 * __destruct
	 *
	 * Destroy the current class instance
	 */
	public function __destruct()
	{
	}

	/**
	 * summarizeProcesses
	 * 
	 * Summarize the processes
	 * @param string $directoryName = relative path to the directory
	 * @param string $pattern = pattern to search for
	 * @return boolean $result = false if nothing to summarize, true otherwise
	 * @throws Exception
	 */
	public function summarizeProcesses($directoryName, $pattern, $ignoreFields=array('errorQueue'))
	{
		$this->logger->logBlockMessage('Statistics');

		if ($this->properties->Config_Tree->Summary->Enabled != 0)
		{
			if (! $this->openCsv())
			{
				return false;
			}

			$this->writeCsv(array_keys($this->processStatsFields));
		}

		$statsDirectory = Support::absolutePath($directoryName);

		$contents = Support::getDirectoryContents($statsDirectory, $pattern);
		if (count($contents) == 0)
		{
			return false;
		}

		if (! sort($contents, SORT_STRING))
		{
			throw new Exception(Error::code('ArraySortFailed'));
		}

		foreach($contents as $statsFileName)
		{
			$statsPath = $statsDirectory . $statsFileName;

			Support::writeLog('');
			Support::writeLog($statsFileName . ': ');
			Support::writeLog('');

			if ($processDescriptor = Support::unserializeProcess($statsPath))
			{
				$statsArray = $processDescriptor->properties();
				$csvArray = array();

				if ($processDescriptor->exists('started') && $processDescriptor->exists('ended'))
				{
					Support::writeLog(sprintf("\tElapsed: %f seconds.", ($processDescriptor->ended - $processDescriptor->started)));
				}

				foreach($this->processStatsFields as $field => $fieldDescription)
				{
					if ($processDescriptor->exists($field))
					{
						$data = rtrim($this->showData($processDescriptor->$field));
						array_push($csvArray, $data);

						if (! in_array($field, $ignoreFields))
						{
							Support::writeLog(sprintf("\t%s: %s", $fieldDescription, $data));
						}
					}
					else
					{
						array_push($csvArray, '<unknown>');
					}
				}

				$this->writeCsv($csvArray);
			}
			else 
			{
				Support::writeLog('Not Found!');
			}

			Support::writeLog('');
			Support::writeLog('******************************');
		}

		return true;
	}

	/**
	 * writeCsv
	 * 
	 * Write the array to a CSV file for loading into a spreadsheet
	 * @param array $csvArray
	 * @return boolean $result = false if failed, otherwise true
	 */
	public function writeCsv($csvArray)
	{
		if ($this->csvFile)
		{
			if ($this->csvFile->fputcsv($csvArray) === false)
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * openCsv
	 * 
	 * Open a CSV file for output
	 * @return boolean $result = false if failed, otherwise not false
	 */
	public function openCsv()
	{
		$configTree = $this->properties->Config_Tree;

		$csvFileName = sprintf('%s%s.%s', Support::absolutePath($configTree->InstallFolders->TestSummary),
											$configTree->Summary->FileDestination,
											$configTree->Summary->FileType);

		if (($this->csvFile = new FileIO($configTree->Summary->FileAdapter, 
										 $csvFileName,
										 $configTree->Summary->FileMode)) === false)
		{
			return false;
		}

		return true;
	}

    /**
     * showData
     * 
     * Return the value of the data in printable format
     * @param mixed $value = value to output
     * @return string $buffer
     */
    public function showData($value)
    {
    	return FormatVar::format($value, null);
    }

	/**
	 * logger
	 * 
	 * Set/get the logger object
	 * @param object $logger
	 * @return object $logger
	 */
	public function logger($logger=null)
	{
		if ($logger != null)
		{
			$this->logger = $logger;
		}

		return $this->logger;
	}

}
