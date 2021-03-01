<?php
namespace Library;

/*
 *		Directory is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *		Refer to the file named License.txt provided with the source, 
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * Directory
 *
 * STATIC directory functions class
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2013 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Directory
 */
class Directory
{
	/**
	 * exists
	 * 
	 * STATIC method - returns true if the directory path is valid
	 * @param string $path = path to the directory
	 * @return boolean $exists
	 */
	public static function exists($path)
	{
		return file_exists($path);
	}

	/**
	 * pathInfo
	 * 
	 * STATIC method - returns information about the supplied path
	 * @param string $path = path to the directory
	 * @param integer $options = (optional) path options
	 */
	public static function pathInfo($path, $options=null)
	{
		if ($options !== null)
		{
			return pathinfo($path, $options);
		}

		return pathinfo($path);
	}

	/**
	 * baseName
	 * 
	 * STATIC method - returns the trailing component of the path
	 * @param string $path = path to get baseName of
	 * @param string $suffix = (optional) suffix to remove from baseName if it exists
	 * @return string $baseName
	 */
	public static function baseName($path, $suffix=null)
	{
		if ($suffix == null)
		{
			return basename($path);
		}

		return basename($path, $suffix);
	}

	/**
	 * dirName
	 * 
	 * STATIC method - returns the path to the parent of $path
	 * @param string $path = directory path to get parent of
	 * @return string $parent
	 */
	public static function dirName($path)
	{
		return dirname($path);
	}

	/**
	 * isDir
	 *
	 * STATIC method - returns true if $path is a directory name, false if it is not
	 * @param string $path = path to the directory to check
	 * @return boolean $isdir
	 */
	public static function isDir($path)
	{
		self::clearStatCache();
		return @is_dir($path);
	}
	
	/**
	 * make
	 *
	 * STATIC method to make a new directory
	 * @param string $path = path to make
	 * @param integer $mode = (optional) (octal) directory mode
	 * @param boolean $recursive = (optional) true to make missing directories in the path, false to not
	 * @param resource $context = (optional) stream context
	 * @return boolean $result
	 */
	public static function make($path, $mode=0777, $recursive=false, $context=null)
	{
		if ($context == null)
		{
			return @mkdir($path, $mode, $recursive);
		}

		return @mkdir($path, $mode, $recursive, $context);
	}
	
	/**
	 * remove
	 *
	 * STATIC method to remove the specified directory
	 * @param string $path = path to remove
	 * @param resource $context = (optional) context resource
	 * @return boolean $result
	 */
	public static function remove($path, $context=null)
	{
		if ($context == null)
		{
			return @rmdir($path);
		}

		return @rmdir($path, $context);
	}

	/**
	 * rename
	 * 
	 * STATIC method to rename a directory
	 * @param string $path = current path name
	 * @param string $newPath = new path name
	 * @param resource $context = (optional) stream context
	 * @return boolean $result
	 */
	public static function rename($path, $newPath, $context=null)
	{
		if ($context == null)
		{
			return rename($path, $newPath);
		}

		return rename($path, $newPath, $context);
	}

	/**
	 * change
	 *
	 * STATIC method to change the current directory
	 * @param string $path = path to the directory to change to
	 * @return boolean $result = true if successful, false if failed
	 */
	public static function change($path)
	{
		return @chdir($path);
	}
	
	/**
	 * root
	 *
	 * STATIC method to change the root directory to the specified directory
	 * @param string $path = path to the directory to change to
	 * @return boolean $result = true if successful, false if not
	 */
	public static function root($path)
	{
		return @chroot($path);
	}
	
	/**
	 * workingDirectory
	 *
	 * STATIC method to return the current working directory path
	 * @return string $path = working directory, false on failure
	 */
	public static function workingDirectory()
	{
		return @getcwd();
	}

	/**
	 * clearStatCache
	 * 
	 * STATIC method to clear file status cache
	 * @param string $realPath = (optional) clear real path if true
	 * @param string $fileName = (optional) clear real path and stat cache for the file if $realPath is true
	 */
	public static function clearStatCache($realPath=null, $fileName=null)
	{
		clearstatcache($realPath, $fileName);
	}

}
