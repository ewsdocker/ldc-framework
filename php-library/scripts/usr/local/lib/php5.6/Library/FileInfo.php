<?php
namespace Library;

/*
 *		FileInfo is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * FileInfo.
 *
 * Create the FileInfo class.
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
	 * fileIO
	 *
	 * The current FileInfo class object
	 * @var object $fileIO
	 */
	protected		$fileIO;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $adapterName = (optional) name of the FileIO adapter class to use, default = 'fileinfo'
	 * @param string $fileName = name of the file associated with this class instance
	 * @throws Library\FileIO\Exception
	 */
	public function __construct($adapterName='fileinfo', $fileName)
	{
		$this->fileIO = FileIO\Factory::instantiateClass($adapterName, $fileName);
		
		$className = get_class($this->fileIO);
		$factoryName = FileIO\Factory::getInstance()->className($adapterName);
		
    	if ($className !== $factoryName)
    	{
    		if ((substr($className, 1) != $factoryName) && (substr($factoryName, 1) != $className))
    		{
    			throw new FileIO\Exception('FactoryInvalidInstance');
    		}
    	}
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
		return $this->fileIO->__toString();
	}

	/**
	 * getATime
	 *
	 * Get file last access time
	 * @return int $lastAccessTime
	 * @throws Library\FileIO\Exception
	 */
	public function getATime()
	{
		return $this->fileIO->getATime();
	}

	/**
	 * getBasename
	 *
	 * Gets the base name of the file
	 * @param string $suffix = (optional) suffix to delete from the basename, null to not delete
	 * @return string $basename
	 */
	public function getBasename($suffix=null)
	{
		return $this->fileIO->getBasename($suffix);
	}

	/**
	 * getCTime
	 *
	 * Get the inode change time
	 * @return int $inodeChangeTime
	 * @throws Library\FileIO\Exception
	 */
	public function getCTime()
	{
		return $this->fileIO->getCTime();
	}

	/**
	 * getExtension
	 *
	 * Get the file extension - returns empty string if no extension
	 * @return string $extension
	 */
	public function getExtension()
	{
		return $this->fileIO->getExtension();
	}

	/**
	 * getFileInfo
	 *
	 * This method gets a FileInfo object for the referenced file
	 * @return FileInfo $object
	 * @throws Library\FileIO\Exception
	 */
	public function getFileInfo()
	{
		return $this->fileIO->getFileInfo();
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
		return $this->fileIO->getFileName();
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
		return $this->fileIO->getGroup();
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
		return $this->fileIO->getInode();
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
		return $this->fileIO->getLinkTarget();
	}

	/**
	 * getMTime
	 *
	 * Gets the last modified time
	 * @return int $modifiedTime
	 * @throws Library\FileIO\Exception
	 */
	public function getMTime()
	{
		return $this->fileIO->getMTime();
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
		return $this->fileIO->getOwner();
	}

	/**
	 * getPath
	 *
	 * get path to the file (directory structure only)
	 * @return string $path
	 */
	public function getPath()
	{
		return $this->fileIO->getPath();
	}

	/**
	 * getPathInfo
	 *
	 * Gets a FileInfo object for the path.
	 * @param string $className = SplFileInfo derived class name
	 * @return object $fileInfo
	 */
	public function getPathInfo($className)
	{
		return $this->fileIO->getPathInfo($className);
	}

	/**
	 * getPathname
	 *
	 * get path name of the file
	 * @return string $pathName
	 */
	public function getPathname()
	{
		return $this->fileIO->getPathname();
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
		return $this->fileIO->getPerms();
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
		return $this->fileIO->getRealPath();
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
		return $this->fileIO->getSize();
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
		return $this->fileIO->getType();
	}

	/**
	 * isDir
	 *
	 * returns true if the file is a directory
	 * @return boolean $directory = true if directory, false if not
	 */
	public function isDir()
	{
		return $this->fileIO->isDir();
	}

	/**
	 * isExecutable
	 *
	 * returns true if the file is executeable
	 * @return boolean $executeable = true if executeable, false if not
	 */
	public function isExecutable()
	{
		return $this->fileIO->isExecutable();
	}

	/**
	 * isFile
	 *
	 * returns true if the file is a regular file
	 * @return boolean $file = true if a regular file, false if not
	 */
	public function isFile()
	{
		return $this->fileIO->isFile();
	}

	/**
	 * isLink
	 *
	 * returns true if the file is a linked file
	 * @return boolean $linked = true if a linked file, false if not
	 */
	public function isLink()
	{
		return $this->fileIO->isLink();
	}

	/**
	 * isReadable
	 *
	 * returns true if the file is readable
	 * @return boolean $readable = true if readable, false if not
	 */
	public function isReadable()
	{
		return $this->fileIO->isReadable();
	}

	/**
	 * isWritable
	 *
	 * returns true if the file is writeable
	 * @return boolean $writeable = true if writeable, false if not
	 */
	public function isWritable()
	{
		return $this->fileIO->isWritable();
	}

	/**
	 * openFile
	 *
	 * open the file by creating a FileIO object
	 * @param string $mode = (optional) file open mode (default = 'r')
	 * @param bool $useIncludePath = (optional) true = search include path for file, false = don't
	 * @param resource $context = (optional) stream context
	 * @return FileIO $object
	 * @throws Library\FileIO\Exception
	 */
	public function openFile($mode='r', $useIncludePath=false, $context=null)
	{
		//
		// the following test is a hack to make work with SplFileInfo in PHP 5.3.6
		//
		if ($context)
		{
			return $this->fileIO->openFile($mode, $useIncludePath, $context);
		}

		return $this->fileIO->openFile($mode, $useIncludePath);
	}

	/**
	 * setFileClass
	 *
	 * set the class name used by openFile when creating a FileIO object
	 * @param string $className = FileIO derived class
	 */
	public function setFileClass($className)
	{
		$this->fileIO->setFileClass($className);
	}

	/**
	 * setInfoClass
	 *
	 * Set the class used with getFileInfo and getPathInfo
	 * @param string $className = FileInfo derived class
	 */
	public function setInfoClass($className)
	{
		$this->fileIO->setInfoClass($className);
	}

}
