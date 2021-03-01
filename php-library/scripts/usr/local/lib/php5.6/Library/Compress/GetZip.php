<?php
namespace Library\Compress;

use Library\Error;
use Library\Directory;

/*
 *		Library\Compress\GetZip is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Library\Compress\GetZip
 *
 * File to download source zip to destination zip and provide processing for it's content
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Compress
 */
class GetZip extends \ZipArchive
{
	/**
	 * errorsLoaded
	 *
	 * True if already loaded, false if not
	 * @var boolean $errorsLoaded
	 */
	public static $errorsLoaded=false;

	/**
	 * zipErrors
	 *
	 * The ZipArchive error code translation vector
	 * @var array $zipErrors
	 */
	protected static $zipErrors;

	/**
	 * result
	 *
	 * The result of the url copy or the last ZipArchive operation
	 * @var boolean|integer $result = true if successful, else error code
	 */
	protected $result;

	/**
	 * sourceUrl
	 *
	 * Url of the source zip file
	 * @var string $sourceUrl
	 */
	protected $sourceUrl;

	/**
	 * destinationUrl
	 *
	 * Url of the destination zip file (place to put it + file name)
	 * @var string $destinationUrl
	 */
	protected $destinationUrl;

	/**
	 * extractUrl
	 *
	 * Url of the extract directory
	 * @var string $extractUrl
	 */
	protected $extractUrl;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $fsource = url of the zip file to get
	 * @param string $fdestination = url of the location to store the file (path + file name)
	 * @param string $fextract = (optional) url of the location to extract the zip to, null to not extract
	 * @throws Library\Compress\Exception if zip download or zip extract fails
	 */
	public function __construct($fsource, $fdestination, $fextract=null)
	{
		if (! self::$errorsLoaded)
		{
			$this->setupErrors();
		}

		$this->result = 0;

		$this->sourceUrl = $fsource;
		$this->destinationUrl = $fdestination;
		$this->extractUrl = $fextract;

		$pathNames = explode('/', $this->destinationUrl);
		array_pop($pathNames);
		$destinationFolder = implode('/', $pathNames);

		$this->makeDirectory($destinationFolder);

		if (! ($this->result = file_put_contents($this->destinationUrl, file_get_contents($this->sourceUrl))))
		{
			throw new Exception(Error::code('FileCopyError'));
		}

		$this->result = $this->open($this->destinationUrl);
		if ($this->result !== true)
		{
			throw new Exception(Error::code($this->translateZipResult()));
		}

		if ($this->extractUrl)
		{
			$this->extractAll();
		}
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 */
	public function __destruct()
	{
	}

	/**
	 * __toString
	 *
	 * Returns a printable list of properties and values
	 * @return string $buffer = list
	 */
	public function __toString()
	{
		FormatVar::sort(false);
		FormatVar::sortValues(false);
		FormatVar::recurse(false);
		FormatVar::formatted(true);

		FormatVar::showData(get_object_vars($this), 'GetZip');
	}

	/**
	 * extractAll
	 *
	 * Extract all files from the zip file to the supplied url
	 * @param string $url = (optional) url to extract to, null to use current
	 * @throws Library\Compress\Exception if extract fails
	 */
	public function extractAll($url=null)
	{
		$url = $this->extractUrl($url);

		$this->makeDirectory($url);

		if (! $this->extractTo($url))
		{
			throw new Exception(Error::code('FileExtractError'));
		}
	}

	/**
	 * makeDirectory
	 *
	 * Check if directory exists and if not, attempt to create
	 * @param string $path = path to check/create
	 * @throws Exception if not present and unable to create
	 */
	public function makeDirectory($path)
	{
		if (! Directory::exists($path))
		{
			if (! Directory::make($path,0775, true))
			{
				throw new Exception(Error::code('DirectoryNotCreated'));
			}
		}
	}

	/**
	 * sourceUrl
	 *
	 * Return the zip source url
	 * @return string $sourceUrl
	 */
	public function sourceUrl()
	{
		return $this->sourceUrl;
	}

	/**
	 * destinationUrl
	 *
	 * Return the zip Destination url
	 * @return string $destinationUrl
	 */
	public function destinationUrl()
	{
		return $this->destinationUrl;
	}

	/**
	 * extractUrl
	 *
	 * Set/get the zip Extract url
	 * @param string $extractUrl = (optional) extract directory location, null to query only
	 * @return string $extractUrl
	 */
	public function extractUrl($extractUrl=null)
	{
		if ($extractUrl !== null)
		{
			$this->extractUrl = $extractUrl;
		}

		return $this->extractUrl;
	}

	/**
	 * result
	 *
	 * Return the result of the (__constructor) file download, or of the last ZipArchive operation
	 * @return boolean|integer $result = ture if successful, false if download failed, or ZipArchive result code
	 */
	public function result()
	{
		return $this->result;
	}

	/**
	 * translateZipResult
	 *
	 * Static function interface to translate the result and return the proper library error code
	 * @return string $errorCode = translated result code
	 */
	public function translateZipResult()
	{
		return self::translateResult($this->result);
	}

	/**
	 * translateResult
	 *
	 * STATIC method
	 *
	 * Translate the result and return the proper library error code
	 * @param integer|boolean $result = result code to translate
	 * @return string $errorCode = translated result code
	 */
	public static function translateResult($result)
	{
		if ($result === true)
		{
			return 'NoError';
		}

		if ($result === false)
		{
			return 'FileCopyError';
		}

		if (! array_key_exists($result, self::$zipErrors))
		{
			return 'UnknownError';
		}

		return self::$zipErrors[$result];
	}

	/**
	 * setupErrors
	 *
	 * Create the zipErrors property array and
	 *   add the TVDB error messages and codes to the Error Message system
	 */
	private function setupErrors()
	{
		if (! self::$errorsLoaded)
		{
			self::$errorsLoaded = true;

			self::$zipErrors = array(\ZipArchive::ER_EXISTS	=> 'ZipArchiveExists',
           				       		 \ZipArchive::ER_INCONS	=> 'ZipArchiveInconsistent',
							   		 \ZipArchive::ER_MEMORY	=> 'ZipArchiveMemory',
							   		 \ZipArchive::ER_NOENT	=> 'ZipArchiveFileNotFound',
							   		 \ZipArchive::ER_NOZIP	=> 'ZipArchiveNotZipFile',
						   			 \ZipArchive::ER_OPEN	=> 'ZipArchiveFileNotOpened',
						   			 \ZipArchive::ER_READ	=> 'ZipArchiveReadError',
						   			 \ZipArchive::ER_SEEK	=> 'ZipArchiveSeekError',
					   				 );

			$errors = array('ZipArchiveExists' 			=> 'File already exists',
    	        		    'ZipArchiveInconsistent'	=> 'Zip archive inconsistent',
							'ZipArchiveMemory' 			=> 'Malloc failure',
							'ZipArchiveFileNotFound'	=> 'No such file',
							'ZipArchiveNotZipFile'  	=> 'Not a zip archive',
							'ZipArchiveFileNotOpened'	=> 'Unable to open file',
							'ZipArchiveReadError'   	=> 'Read error',
							'ZipArchiveSeekError'   	=> 'Seek error',
							);

			foreach($errors as $name => $message)
			{
				Error::register($name, $message, true);
			}
		}
	}

}
