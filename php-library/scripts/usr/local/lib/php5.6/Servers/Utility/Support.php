<?php
namespace Servers\Utility;

use Exception as UtilityException;

use Library\Error;
use Library\Properties;
use Library\Directory\Search as DirectorySearch;
use Library\Serialize as Serializer;

/*
 *		Servers\Utility\Support is copyright � 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	    Refer to the file named License.txt provided with the source,
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * Support
 *
 * STATIC Utility support classes for servers
 * @author Jay Wheeler
 * @version 1.0
 * @phpversion 5.6.16
 * @copyright � 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Servers
 * @subpackage Utility
 */
class Support
{
	/**
	 * properties
	 *
	 * A Library\Properties instance
	 * @var object $properties
	 */
	private static $properties = null;

	/**
	 * exceptionDescriptor
	 *
	 * A Library\Exception\Descriptor object containing the last Exception caught (if any)
	 * @var object $exceptionDescriptor
	 */
	private static $exceptionDescriptor = null;

	/**
	 * serverRoot
	 *
	 * The current server root directory path, if not null
	 * @var string $serverRoot
	 */
	private static $serverRoot = null;

	/**
	 * logger
	 *
	 * Logger object
	 * @var object $logger
	 */
	private static $logger = null;

	/**
	 * serializeProcess
	 *
	 * Serialize (write) the processDescriptor object to a disk file for latter processing
	 * @param object $process = processDescriptor object to serialize
	 * @throws Library\Serialize\Exception
	 */
	public static function serializeProcess($process)
	{
		if ($process->serialized)
		{
			throw new Exception(Error::code('UnableToSerialize'));
		}

		self::$properties->Serialize_Source = $process->serializeName;

		$serializer = new Serializer('file', self::checkProperties());
		$serializer->save($process, $process->serializeName);

		$process->serialized = true;
		$serializer = null;
	}

	/**
	 * unserializeProcess
	 *
	 * Unserialize the process file and return the processDescriptor object
	 * @param string $processFileName
	 * @return object $processDescriptor = processDescriptor object
	 * @throws Library\Serialize\Exception, Library\FileIO\Exception
	 */
	public static function unserializeProcess($processFileName)
	{
		self::$exceptionDescriptor = null;

		$properties = self::checkProperties();
		$properties->Serialize_Source = $processFileName;

		$serializer = new Serializer('file', $properties);
		$serializer->load($processDescriptor, $processFileName);

		return $processDescriptor;
	}

	/**
	 * getDirectoryContents
	 *
	 * Select directory contents matching to the specified pattern
	 * @param string $directoryName = path to the directory
	 * @param string $pattern = (optional) pattern to search for, null for complete directory
	 * @return array $contents = Directory\Search class instance
	 * @throws Library\Directory\Exception
	 */
	public static function getDirectoryContents($directoryName, $pattern=null)
	{
		self::$exceptionDescriptor = null;

		try
		{
			$directory = new DirectorySearch($directoryName);
		}
		catch(DirectoryException $exception)
		{
			self::$exceptionDescriptor = $exception;
		}

		if ($pattern == null)
		{
			return $directory->directory();
		}

		return $directory->select($pattern);
	}

	/**
     * absoluteFileName
     *
     * Takes a workspace relative path name and returns an absolute path
     * @param string $name = workspace relative path name
     * @return string $name = absolute path
     * @throws Exception if $properties is not initialized, or $name is not a local stream
     */
    public static function absoluteFileName($name)
    {
   		return self::absolutePath($name, true);
    }

	/**
     * absolutePath
     *
     * Takes a workspace relative path name and returns an absolute path
     * @param string $name = workspace relative path name
     * @param boolean $filePath = (optional) true => compute absolute file path, false => compute absolute folder path
     * @return string $name = absolute path
     * @throws Exception if $properties is not initialized, or $name is not a local stream
     */
    public static function absolutePath($name, $filePath=false)
    {
		$properties = self::checkProperties();

    	if (! stream_is_local($name))
    	{
    		throw new Exception(Error::code('StreamNotLocal'));
    	}

    	$name = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $name);

    	if (substr($name, 0, 1) != DIRECTORY_SEPARATOR)
    	{
    		$name = sprintf("%s%s%s", $properties->Root_Path, DIRECTORY_SEPARATOR, $name);
    	}

   		$name = str_replace(sprintf('%s%s', DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR, $name);

       	if ((! $filePath) && (substr($name, strlen($name) -1, 1) != DIRECTORY_SEPARATOR))
    	{
    		$name .= DIRECTORY_SEPARATOR;
    	}

   		return $name;
    }

    /**
     * serverRoot
     *
     * Set/Determine the server's root directory and returns the result, sets $serverRoot if it is not already set
     * @param string $serverRoot = (optional) server root (path to folder), null to auto determine
     * @return string $serverRoot = current server root
     */
	public static function serverRoot($serverRoot=null)
	{
		if ($serverRoot !== null)
		{
			self::$serverRoot = $serverRoot;
		}

		if (self::$serverRoot)
		{
			return self::$serverRoot;
		}

		$altdirs = false;
		$dirs = array();

		if ((! $dirs = explode(PATH_SEPARATOR, $_SERVER['SCRIPT_FILENAME'])) ||	(count($dirs) < 2))
		{
			if ((! $dirs = explode('/', $_SERVER['SCRIPT_FILENAME'])) || (count($dirs) < 2))
			{
				$altdirs = true;
				if (! $dirs = explode('/', dirname(__FILE__)))
				{
					$dirs[0] = '.';
					$dirs[1] = '.';
				}
			}
		}

		array_pop($dirs);
		if (! $altdirs)
		{
			array_pop($dirs);
		}

		self::$serverRoot = implode('/', $dirs);

		return self::$serverRoot;
	}

    /**
	 * getProcessTime
	 *
	 * Returns the time the log file was created
	 * @param string $directory = log file directory
	 * @param string $subLog = (optional) log sub-category name
	 * @return string $time = a formatted date string
	 * @throws Exception if subLog file not found
	 */
	public static function getProcessTime($directory, $subLog='Launcher')
	{
		$properties = self::checkProperties();

		$configSubLog = $properties->Config_Tree->Log->$subLog;
		$processLog = sprintf('%s%s.%s', $directory,
										 $configSubLog->FileDestination,
										 $configSubLog->FileType);
		clearstatcache();
		if (! file_exists($processLog))
		{
			throw new UtilityException(Error::code('FileDoesNotExist'));
		}

		return date('Ymd-His', @filemtime($processLog));
	}

    /**
	 * exceptionDescriptor
	 *
	 * Return the current exception descriptor
	 * @param ExceptionDescriptor $exceptionDescriptor = exception descriptor to set, null to query only
	 * @return object $exceptionDescriptor = current exception descriptor
	 */
	public static function exceptionDescriptor($exceptionDescriptor=null)
	{
		if ($exceptionDescriptor !== null)
		{
			self::$exceptionDescriptor = $exceptionDescriptor;
		}

		return self::$exceptionDescriptor;
	}

    /**
	 * properties
	 *
	 * Get/Set $properties setting
	 * @param object $properties = (optional) properties object to set, null to query only
	 * @return object $properties = current setting of $properties
	 * @throws Exception if $properties is not null but is not valid
	 */
	public static function properties(&$properties=null)
	{
		if ($properties !== null)
		{
			self::$properties =& $properties;
			self::checkProperties();
		}

		return self::$properties;
	}

    /**
     * checkProperties
     *
     * Throws exception if $properties is not valid
	 * @return object $properties = current setting of $properties
     * @throws Exception
     */
	private static function checkProperties()
	{
    	if ((! self::$properties) || (! is_object(self::$properties)) || (get_class(self::$properties) !== 'Library\Properties'))
    	{
    		throw new Exception(Error::code('NotInitialized'));
    	}

    	return self::$properties;
	}

	/**
	 * writeLog
	 *
	 * Write the message to the open logs
	 * @param string $message = message to output
	 * @param string $level = logging level (default = 'debug')
	 */
	public static function writeLog($message, $level='debug')
	{
		self::checkLogger();
		self::$logger->logMessage($message, $level);
	}

	/**
	 * logger
	 *
	 * Set/get the logger object
	 * @param object $logger
	 */
	public static function logger($logger=null)
	{
		if ($logger !== null)
		{
			self::$logger = $logger;
			self::checkLogger();
		}

		return self::$logger;
	}

    /**
     * checkLogger
     *
     * Throws exception if $logger is not valid
     * @throws Exception
     */
	private static function checkLogger()
	{
    	if ((! self::$logger) || (! is_object(self::$logger)) || (get_class(self::$logger) !== 'Application\Launcher\Utility\Logger'))
    	{
    		throw new Exception(Error::code('NotInitialized'));
    	}
	}

}
