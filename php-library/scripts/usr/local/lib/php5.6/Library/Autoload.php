<?php
namespace Library;

/*
 * 		Autoload is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
 */
/**
 * Autoload.
 *
 * An implementation of the PSR-0 autoload standard to search include path directories.
 * Refer to https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
 * @author Jay Wheeler.
 * @version 2.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Autoload.
 */
class Autoload extends Autoload\BaseClass
{
	/**
	 * me
	 *
	 * Reference to the instantiated singleton class
	 * @var object $me
	 */
	private	static	$me = null;

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
	 * @param boolean $replace = (optional) replace flag (true = replace if already exists, false = don't)
	 * @param boolean $prepend = (optional) prepend loader to front of list if true, don't if false
	 * @return object $me
	 */
	public static function instantiate($replace=null, $prepend=null)
	{
		if (self::$me == null)
		{
			self::$me = new Autoload();
			self::$me->getSeparator();

			self::$me->register(array(self::$me, 'loader'), $replace, $prepend);
		}

		return self::$me;
	}

	/**
	 * loadClass
	 *
	 * Static function to load the requested class file
	 * @param string $class = class file name
	 * @return boolean true = successful, false = not successful
	 */
	public static function loadClass($class)
	{
		return self::instantiate()->loader($class);
	}

    /**
     * loader.
     *
     * load the required class file from an include directory.
     * @param string $class = the class name to load in PSR-0 format
     * @return boolean true = successful, false = not successful
     */
  	public function loader($class)
  	{
  		$directories = explode(PATH_SEPARATOR, get_include_path());
  	  	if (! in_array('.', $directories))
  	  	{
  	  	  	array_push($directories, '.');
  	  	}

  	  	return $this->loadClassFile($this->convertUnderscores($class), $directories);
  	}

}
