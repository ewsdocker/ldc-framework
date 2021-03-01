<?php
namespace Library\FileIO;
use Library\Error;

/*
 *		FileIO\FileInfo is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * FileIO\FileInfo.
 *
 * The FileIO\FileInfo class incorporates all of the methods in SPLFileInfo
 * @author Jay Wheeler
 * @version 1.0
 * @copyright © 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage FileIO
 */
class FileInfo
{
	/**
	 * fileName
	 *
	 * The name of the file associated with this class instance
	 * @var string $fileName
	 */
	private		$fileName;

	/**
	 * fileClassName
	 * 
	 * The class name to use by openFile method
	 * @var string $fileClassName
	 */
	private		$fileClassName;
	
	/**
	 * infoClassName
	 * 
	 * The class name to use by getFileInfo and getPathInfo
	 * @var string $infoClassName
	 */
	private		$infoClassName;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $fileName = name of the file associated with this class instance
	 */
	public function __construct($fileName)
	{
		$this->fileName = $fileName;
		$this->fileClassName = '\Library\FileIO\FileObject';
		$this->infoClassName = '\Library\FileIO\FileInfo';
	}

	/**
	 * __destruct
	 *
	 * Class destrctor
	 */
	public function __destruct()
	{
	}

	/**
	 * __toString
	 *
	 * Get file path as a string
	 * @return string $path
	 * @throws Library\FileIO\Exception
	 */
	public function __toString()
	{
		return $this->fileName;
	}

	/**
	 * getATime
	 *
	 * Get file last access time
	 * @return int $lastAccessTime
	 * @throws Library\FileIO\Exception, \Exception
	 */
	public function getATime()
	{
		clearstatcache();
		if ((! file_exists($this->fileName)) || (! $ftime = @fileatime($this->fileName)))
		{
			throw new Exception(Error::code('FileDoesNotExist'));
		}

		return $ftime;
	}

	/**
	 * getBasename
	 *
	 * Gets the base name of the file
	 * @param string $suffix = (optional) suffix to delete from the basename
	 * @return string $basename
	 */
	public function getBasename($suffix=null)
	{
		if ($suffix)
		{
			return basename($this->fileName, $suffix);
		}

		return basename($this->fileName);
	}

	/**
	 * getCTime
	 *
	 * Get the inode change time
	 * @return int $inodeChangeTime
	 * @throws Library\FileIO\Exception, \Exception
	 */
	public function getCTime()
	{
		clearstatcache();
		if ((! file_exists($this->fileName)) || (! $ftime = @filectime($this->fileName)))
		{
			throw new Exception(Error::code('FileDoesNotExist'));
		}

		return $ftime;
	}

	/**
	 * getExtension
	 *
	 * Get the file extension - returns empty string if no extension
	 * @return string $extension
	 */
	public function getExtension()
	{
		return pathinfo($this->fileName, PATHINFO_EXTENSION);
	}

	/**
	 * getFileInfo
	 *
	 * This method gets a FileInfo object for the referenced file
	 * @return FileInfo $object
	 * @throws \Exception
	 */
	public function getFileInfo()
	{
		return new $this->infoClassName($this->fileName);
	}

	/**
	 * getFileName
	 *
	 * Get the file name without path info, returns empty string if a directory is specified
	 * @return string $fileName
	 * @throws Library\FileIO\Exception
	 */
	public function getFileName()
	{
		$pathInfo = pathinfo($this->fileName);
		return $pathInfo['basename'];
	}

	/**
	 * getGroup
	 *
	 * Get the file group
	 * @return integer $fileGroup
	 * @throws Library\FileIO\Exception
	 */
	public function getGroup()
	{
		if (! $group = @filegroup($this->fileName))
		{
			throw new Exception(Error::code('FileGroupError'));
		}

		return $group;
	}

	/**
	 * getInode
	 *
	 * Get the file inode number
	 * @return integer $fileInode
	 * @throws Library\FileIO\Exception
	 */
	public function getInode()
	{
		if (! $inode = @fileinode($this->fileName))
		{
			throw new Exception(Error::code('FileInodeError'));
		}

		return $inode;
	}

	/**
	 * getLinkTarget
	 *
	 * Get the target of a filesystem link.
	 * @return string $linkTarget
	 * @throws Library\FileIO\Exception
	 */
	public function getLinkTarget()
	{
		if ((! @is_link($this->fileName)) || (! $path = @readlink($this->fileName)))
		{
			throw new Exception(Error::code('LinkTargetError'));
		}

		return $path;
	}

	/**
	 * getMTime
	 *
	 * Gets the last modified time
	 * @return int $modifiedTime
	 * @throws Library\FileIO\Exception, \Exception
	 */
	public function getMTime()
	{
		clearstatcache();
		if ((! file_exists($this->fileName)) || (! $ftime = @filemtime($this->fileName)))
		{
			throw new Exception(Error::code('FileDoesNotExist'));
		}

		return $ftime;
	}

	/**
	 * getOwner
	 *
	 * get the id of the file owner
	 * @return integer $fileOwner
	 * @throws Library\FileIO\Exception
	 */
	public function getOwner()
	{
		if (! $owner = @fileowner($this->fileName))
		{
			throw new Exception(Error::code('FileOwnerError'));
		}

		return $owner;
	}

	/**
	 * getPath
	 *
	 * get path to the file (directory structure only)
	 * @return string $path
	 */
	public function getPath()
	{
		return pathinfo($this->fileName, PATHINFO_DIRNAME);
	}

	/**
	 * getPathInfo
	 *
	 * Gets a FileInfo object for the path.
	 * @param string $className = (optional) derived class name, null to use \Library\FileIO\FileInfo
	 * @return object $fileInfo
	 */
	public function getPathInfo($className=null)
	{
		if (! $className)
		{
			$className = '\Library\FileIO\FileInfo';
		}

		return new $className($this->getPath());
	}

	/**
	 * getPathname
	 *
	 * get path name of the file
	 * @return string $pathName
	 */
	public function getPathname()
	{
		$paths = pathinfo($this->fileName);

		return $paths['dirname'] . DIRECTORY_SEPARATOR . $paths['basename'] . ((substr($this->fileName, strlen($this->fileName) - 1, 1) == DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : '');
	}

	/**
	 * getPerms
	 *
	 * get file permissions
	 * @return integer $permissions
	 * @throws Library\FileIO\Exception
	 */
	public function getPerms()
	{
		clearstatcache();
		return @fileperms($this->fileName);
	}

	/**
	 * getRealPath
	 *
	 * get real path name of the file
	 * @return string $pathName
	 * @throws Library\FileIO\Exception
	 */
	public function getRealPath()
	{
		if (($path = @realpath($this->fileName)) === false)
		{
			throw new Exception(Error::code('FileRealPathFailed'));
		}

		return $path;
	}

	/**
	 * getSize
	 *
	 * get file size
	 * @return integer $fileSize
	 * @throws Library\FileIO\Exception
	 */
	public function getSize()
	{
		clearstatcache();

		if (($size = @filesize($this->fileName)) === false)
		{
			throw new Exception(Error::code('FileSizeFailed'));
		}

		return $size;
	}

	/**
	 * getType
	 *
	 * get file type
	 * @return string $fileType
	 * @throws Library\FileIO\Exception
	 */
	public function getType()
	{
		if (($type = @filetype($this->fileName)) === false)
		{
			throw new Exception(Error::code('FileTypeFailed'));
		}

		return $type;
	}

	/**
	 * isDir
	 *
	 * returns true if the file is a directory
	 * @return boolean $directory = true if directory, false if not
	 */
	public function isDir()
	{
		return @is_dir($this->fileName);
	}

	/**
	 * isExecutable
	 *
	 * returns true if the file is executeable
	 * @return boolean $executeable = true if executeable, false if not
	 */
	public function isExecutable()
	{
		return @is_executable($this->fileName);
	}

	/**
	 * isFile
	 *
	 * returns true if the file is a regular file
	 * @return boolean $file = true if a regular file, false if not
	 */
	public function isFile()
	{
		return @is_file($this->fileName);
	}

	/**
	 * isLink
	 *
	 * returns true if the file is a linked file
	 * @return boolean $linked = true if a linked file, false if not
	 */
	public function isLink()
	{
		return @is_link($this->fileName);
	}

	/**
	 * isReadable
	 *
	 * returns true if the file is readable
	 * @return boolean $readable = true if readable, false if not
	 */
	public function isReadable()
	{
		return @is_readable($this->fileName);
	}

	/**
	 * isWritable
	 *
	 * returns true if the file is writable
	 * @return boolean $writable = true if writable, false if not
	 */
	public function isWritable()
	{
		return @is_writable($this->fileName);
	}

	/**
	 * openFile
	 *
	 * open the file by creating a FileIO object
	 * @param string $mode = (optional) file open mode (default = 'r')
	 * @param bool $useIncludePath = (optional) true = search include path for file, false = don't
	 * @param resource $context = (optional) stream context
	 * @return FileIO $object
	 * @throws Library\FileIO\Exception, \Exception
	 */
	public function openFile($mode='r', $useIncludePath=false, $context=null)
	{
		return new $this->fileClassName($this->fileName, $mode, $useIncludePath, $context);
	}

	/**
	 * setFileClass
	 *
	 * set the class name used by openFile when creating a FileIO object
	 * @param string $className = FileIO derived class
	 */
	public function setFileClass($className)
	{
		$this->fileClassName = $className;
	}

	/**
	 * setInfoClass
	 *
	 * Set the class used with getFileInfo and getPathInfo
	 * @param string $className = FileInfo derived class
	 */
	public function setInfoClass($className)
	{
		$this->infoClassName = $className;
	}

}
