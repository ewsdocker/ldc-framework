<?php
namespace Library\Autoload;

/*
 * 		Autoload\Generic is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
 */
/**
 * Autoload\Generic.
 *
 * An implementation of the PSR-0 autoload standard to search non-standard directories.
 * Refer to https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
 * @author Jay Wheeler.
 * @version 2.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Autoload.
 */
class Generic extends BaseClass
{
	/**
	 * me
	 *
	 * Reference to the instantiated singleton class
	 * @var object $me
	 */
	private	static	$me = null;

	/**
	 * directories
	 *
	 * An array containing the various installation root directory paths to follow
 	 * @var array[string] $directories
	 */
	protected		$directories = array();

	/**
	 * __construct
	 *
	 * Singleton class constructor.
	 */
	private function __construct()
	{
	}

	/**
	 * instantiate
	 *
	 * Instantiate a singleton Generic autoloader class if not already instantiated.
	 * @param array $directories = (optional) directory array
	 * @param boolean $replace = (optional) replace flag (true = replace if already exists, false = don't)
	 * @param boolean $prepend = (optional) prepend loader to front of list if true, don't if false
	 * @return object $me
	 */
	public static function instantiate($directories=null, $replace=null, $prepend=null)
	{
		if (self::$me == null)
		{
			self::$me = new Generic();
			if ($directories !== null)
			{
				self::$me->setdirectories($directories);
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
  		return $this->loadClassFile($this->convertUnderscores($class), $this->directories);
	}

	/**
     * setDirectories.
     *
     * get/set the directory array variable.
     * @param array[string] $directories = array of directory roots to check, in provided sequence
     * @return array[string] $directories = directories array
     */
  	public function setDirectories($directories=null)
  	{
  		if ($directories != null)
  		{
  			if (! is_array($directories))
  			{
  				$directories = array($directories);
  			}

			$this->directories = $directories;
  		}

  		return $this->directories;
  	}

}
