<?php
namespace Library\Directory;

/*
 *		Library\Directory\SetPaths is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * Library\Directory\SetPaths
 *
 * Class to set search and root directories.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Directory
 */
class SetPaths
{
	/**
	 * nsPath
	 *
	 * Namespace path
	 * @var string $nsPath
	 */
	public $nsPath;

	/**
	 * installPath
	 *
	 * Installation path
	 * @var string $installPath
	 */
	public $installPath;

	/**
	 * rootPath
	 *
	 * Root paths
	 * @var string $rootPath
	 */
	public $rootPath;

	/**
	 * searchRoots
	 *
	 * Search root paths in an array
	 * @var array $searchRoots
	 */
	public $searchRoots;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $nsPath = Namespace Path
	 * @param string $installPath = Installation path
	 * @param string $rootPath = Root path
	 */
	public function __construct(&$nsPath, &$installPath, &$rootPath)
	{
		$this->nsPath =& $nsPath;
		$this->installPath =& $installPath;
		$this->rootPath =& $rootPath;
		$this->setInstallPath();
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
	 * setInstallPath
	 *
	 * Set the install path by 'educated-guess' if not provided
	 * @return string $rootPath = copy of rootPath param
	 */
	public function setInstallPath()
	{
		if (! $this->installPath || ! $this->nsPath || ! $this->rootPath)
		{
			$path = explode(DIRECTORY_SEPARATOR, realpath(__FILE__));
			array_pop($path);

			if (! $this->nsPath)
			{
				$this->nsPath = implode(DIRECTORY_SEPARATOR, $path);
			}

			if (! $this->installPath)
			{
				$this->installPath = implode(DIRECTORY_SEPARATOR, $path);
			}

			array_pop($path);

			if (! $this->rootPath)
			{
				$this->rootPath = implode(DIRECTORY_SEPARATOR, $path);
			}
		}

		return $this->rootPath;
	}

	/**
	 * searchPaths
	 *
	 * Set search paths
	 * @return boolean true = successful, false = failed
	 */
	public function searchPaths()
	{
	  	$dirs = array();
		$dirs = explode(PATH_SEPARATOR, get_include_path());

		if ((! $this->removeDir($dirs, '.')) ||
			(! $this->removeDir($dirs, $this->installPath)) ||
			(! $this->removeDir($dirs, $this->nsPath)) ||
			(! $this->removeDir($dirs, $this->rootPath)))
		{
			return false;
		}

		array_unshift($dirs, '.');
		array_unshift($dirs, $this->nsPath);
		array_unshift($dirs, $this->rootPath);

		if (($this->nsPath != $this->installPath) && ($this->rootPath != $this->installPath))
		{
			array_unshift($dirs, $this->installPath);
		}

		set_include_path(implode(PATH_SEPARATOR, $dirs));

		return true;
	}

	/**
	 * addRoots
	 *
	 * Add additional directory roots to the search paths
	 * @param array $searchRoots = array containing directories to add to the search paths
	 * @param array $skipPaths = (optional) array containing directories to skip in the review of search paths
	 * @return array $roots = new search path array
	 */
	public function addRoots($searchRoots, $skipPaths=array('.'))
	{
		$this->searchRoots = array();

		if ($searchRoots && (count($searchRoots) > 0))
		{
			foreach($searchRoots as $key => $value)
			{
				array_push($this->searchRoots, $value);
				if (in_array($value, $skipPaths))
				{
					continue;
				}

				$dirs = explode(PATH_SEPARATOR, get_include_path());
				if ((count($dirs) > 0) && in_array($value, $dirs))
				{
					continue;
				}

				set_include_path(get_include_path() . PATH_SEPARATOR . $value);
			}

		}

		return $this->searchRoots;
	}

	/**
	 * removeDir
	 *
	 * Remove the requested directory name from the dirs array, if it exists
	 * @param array $dirs = pointer to array containing directory names
	 * @param string $dirToRemove = directory name to remove
	 * @return boolean true = successful, false = unable to remove requested directory name
	 */
	public function removeDir(&$dirs, $dirToRemove)
	{
		if ($dirs && is_array($dirs) && in_array($dirToRemove, $dirs))
		{
			$elements = count($dirs);
			for($element = 0; $element < $elements; $element++)
			{
				if ($dirs[$element] == $dirToRemove)
				{
					if (! $this->removeElement($dirs, $element))
					{
						return false;
					}

					break;
				}
			}
		}

		return true;
	}

	/**
	 * removeElement
	 *
	 * Remove the requested element index and value from the array
	 * @param array $array = pointer to the array (pass-by-address)
	 * @param integer $element = index of the element to be removed
	 * @return boolean true = successful, false = unsuccessful
	 */
	public function removeElement(&$array, $element)
	{
		if ((! is_int($element)) || (! $array) || (! is_array($array)) || (! array_key_exists($element, $array)))
		{
			return false;
		}

		if ($element == 0)
		{
			$array = array_slice($array, 1);
		}
		elseif ($element == count($array) - 1)
		{
			$array = array_slice($array, 0, $element);
		}
		else
		{
			$array = array_merge(array_slice($array, 0, $element), array_slice($array, $element + 1));
		}

		return true;
	}

}
