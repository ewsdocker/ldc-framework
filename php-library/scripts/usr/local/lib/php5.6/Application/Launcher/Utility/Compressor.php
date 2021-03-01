<?php
namespace Application\Launcher\Utility;

use Application\Utility\Support;

use Library\Directory;
use Library\Exception\Descriptor as ExceptionDescriptor;
use Library\FileIO;
use Library\Properties;
use Library\Error;
use Library\CliParameters;
use Library\PrintU;

/*
 *		Compressor is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	    Refer to the file named License.txt provided with the source, 
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * Compressor
 *
 * Compressor class
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Launcher
 * @subpackage Utility
 */
class Compressor
{
	/**
	 * properties
	 * 
	 * A Library\Properties instance
	 * @var object $properties
	 */
	protected 	$properties;

	/**
	 * compressAndArchive
	 * 
	 * True to create a compressed archive in the backup folder, false to just move files to the backup folder
	 * @var boolean $compressAndArchive
	 */
	protected 	$compressAndArchive;

	/**
	 * deleteSource
	 * 
	 * True to delete compressed source file, false to leave alone
	 * @var integer $deleteSource
	 */
	protected	$deleteSource;

	/**
	 * compressor
	 * 
	 * A compression adapter class instance
	 * @var object $compressor
	 */
	protected	$compressor;

	/**
	 * configTree
	 * 
	 * LLRBNode configuration tree base
	 * @var object $configTree
	 */
	protected 	$configTree;

	/**
	 * processTime
	 * 
	 * A string containing a formatted date/time string
	 * @var string $processTime
	 */
	protected 	$processTime;

	/**
	 * exceptionDescriptor
	 * 
	 * An Exception\Descriptor object containing last exception encountered, or null
	 * @var object $exceptionDescriptor
	 */
	protected	$exceptionDescriptor;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param object $properties = Library\Properties instance
	 */
	public function __construct($properties)
	{
		$this->properties = $properties;
		$this->exceptionDescriptor = null;

		$this->configTree = $this->properties->Config_Tree;

		$this->compressAndArchive = $this->configTree->Archive->Compress->Enabled;
		$this->deleteSource = $this->configTree->Archive->DeleteSource;
	}

	/**
	 * __destruct
	 *
	 * Destroy the current class instance
	 */
	public function __destruct()
	{
		if ($this->compressor)
		{
			$this->closeCompressor();
		}
	}

	/**
	 * archiveDirectory
	 *
	 * Archive the specified directory name
	 * @param string $directoryName = relative path to the directory
	 * @return boolean $result = false if nothing to archive, true otherwise
	 */
	public function archiveDirectory($directoryName)
	{
		$sourceDirectory = Support::absolutePath($directoryName);
		$backupDirectory = Support::absolutePath($this->configTree->InstallFolders->TestArchives);

		try
		{
			$this->processTime = Support::getProcessTime($sourceDirectory);
		}
		catch(Exception $exception)
		{
			return false;
		}

		$contents = Support::getDirectoryContents($sourceDirectory);
		if (count($contents) == 0)
		{
			return false;
		}

		if ($this->compressAndArchive)
		{
			$this->openCompressor();
		}

		foreach($contents as $fileName)
		{
			if (($fileName == '.') || ($fileName == '..'))
			{
				continue;
			}

			$sourceFile = sprintf('%s%s%s', $sourceDirectory, DIRECTORY_SEPARATOR, $fileName);
			$backupFile = sprintf('%s%s%s-%s', $backupDirectory, DIRECTORY_SEPARATOR, $this->processTime, $fileName);

			clearstatcache();
			if (! is_file($sourceFile))
			{
				continue;
			}

			if ($this->compressAndArchive)
			{
				if (! $this->compress($sourceFile))
				{
					return false;
				}

				if ($this->deleteSource)
				{
					if (! @unlink($sourceFile))
					{
						return false;
					}
				}

			}
			else
			{
				if (! Directory::rename($sourceFile, $backupFile))
				{
					return false;
				}
			}

		}

		$this->closeCompressor();
		return true;
	}

	/**
	 * compress
	 * 
	 * Compress the given file and write to the compression library
	 * @param string $file
	 */
	protected function compress($file)
	{
		$configArchive = $this->configTree->Archive;

		try
		{
			$fileIO = new FileIO($configArchive->Adapter,
								 $file,
								 $configArchive->Mode);

			$fileRecords = array();
			foreach($fileIO as $key => $line)
			{
				array_push($fileRecords, rtrim($line));
			}
		 
			$buffer = implode("\n", $fileRecords);

			unset($fileIO);
			$fileIO = null;
		}
		catch(\Library\FileIO\Exception $exception)
		{
			$this->exceptionDescriptor = new ExceptionDescriptor($exception);
			return false;
		}

		try
		{
			$file = basename($file);
			$compressBuffer = sprintf('%03u%s', strlen($file), $file);
			$this->compressor->fwrite($compressBuffer, strlen($compressBuffer));

			$compressBuffer = sprintf('%05u%s', strlen($buffer), $buffer);
			$this->compressor->fwrite($compressBuffer, strlen($compressBuffer));
		}
		catch(\Library\Compress\Exception $exception)
		{
			$this->exceptionDescriptor = new ExceptionDescriptor($exception);
			return false;
		}

		return true;
	}

	/**
	 * closeCompressor
	 * 
	 * Close the compression file
	 */
	protected function closeCompressor()
	{
		if ($this->compressor)
		{
			$this->compressor->fclose();
			unset($this->compressor);
		}
	}

	/**
	 * openCompressor
	 * 
	 * Open a compression file
	 */
	protected function openCompressor()
	{
		$config = $this->configTree->Archive->Compress;
		$compressFile = sprintf('%s%s-%s.%s', Support::absolutePath($this->configTree->InstallFolders->TestArchives),
											    $this->processTime,
											    $config->Destination,
											    $config->Type);

		$this->compressor = \Library\Compress\Factory::instantiateClass($config->Adapter);

		try
		{
			$this->compressor->open($compressFile, $config->Mode);
		}
		catch(\Library\Compress\Exception $exception)
		{
			unset($this->compressor);
			$this->compressAndArchive = false;
		}
	}

}
