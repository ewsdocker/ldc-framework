<?php
namespace Library\Autoload;

/*
 * 		Autoload\Libraries is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
 */
/**
 * Autoload\Libraries.
 *
 * An implementation of the PSR-0 autoload standard to search non-standard directories.
 * Refer to https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Autoload.
 */
class Libraries extends BaseClass
{
	/**
	 * me
	 *
	 * Reference to the instantiated singleton class
	 * @var object $me
	 */
	private	static	$me = null;

	/**
	 * libraries
	 *
	 * An array containing the various library installation root directory paths
 	 * @var array[string] $libraries
	 */
	protected		$libraries = array();

	/**
	 * __construct
	 *
	 * Singleton class constructor.  Initializes the spl_autoload queue.
	 */
	private function __construct()
	{
	}

	/**
	 * instantiate
	 *
	 * Instantiate a singleton Autoload class if not already instantiated.
	 * @param array $libraries = (optional) library array
	 * @param boolean $replace = (optional) replace flag (true = replace if already exists, false = don't)
	 * @param boolean $prepend = (optional) prepend loader to front of list if true, don't if false
	 * @return object $me
	 */
	public static function instantiate($libraries=null, $replace=null, $prepend=null)
	{
		if (self::$me == null)
		{
			self::$me = new Libraries();
			if ($libraries !== null)
			{
				self::$me->setLibraries($libraries);
			}

			self::$me->getSeparator();
			self::$me->register(array(self::$me, 'loader'), $replace, $prepend);
		}

		return self::$me;
	}

	/**
	 * loadClass
	 *
	 * The autoload class loader.
	 * @param string $class
	 * @return boolean true = successful, false = failed.
	 */
	public static function loadClass($class)
	{
		return self::instantiate()->loader($class);
	}

    /**
     * loader.
     *
     * load the required class file from a library directory.
     * @param string $class = the class name to load in PSR-0 format
     * @return boolean true = successful, false = not successful
     */
  	public function loader($class)
  	{
  		if ($this->loadClassFile($class, $this->libraries))
  		{
  			return true;
  		}

  		return $this->loadClassFile($this->convertUnderscores($class), $this->libraries);
	}

	/**
     * setLibraries.
     *
     * get/set the library array variable.
     * @param array[string] $libraries = array of library roots to check, in provided sequence
     * @return array[string] $libraries = libraries array
     */
  	public function setLibraries($libraries=null)
  	{
  		if ($libraries != null)
  		{
			$this->libraries = $libraries;
  		}

  		return $this->libraries;
  	}

}
