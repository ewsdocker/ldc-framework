<?php
namespace Library\Autoload;

/*
 * 		Autoload\Simple is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 * 					 or from http://opensource.org/licenses/academic.php
 */
/**
 * Autoload\Simple.
 *
 * A simple autoloader partially adherent to the PSR-0 autoload standard to search include path directories.
 * Refer to https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library.
 * @subpackage Autoload.
 */
class Simple
{
	/**
	 * me
	 *
	 * Reference to the instantiated singleton class
	 * @var object $me
	 */
	private	static	$me = null;

	/**
	 * separator
	 *
	 * The current separator
	 * @var string $separator
	 */
	private			$separator;

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
	 * Instantiate a singleton Autoload class if not already instantiated.
	 * @return object $me
	 */
	public static function instantiate()
	{
		if (self::$me == null)
		{
			self::$me = new Simple();
			self::$me->getSeparator();

			if (! spl_autoload_register(array(self::$me, 'loader'), false, false))
			{
				throw new Exception('Simple autoloader failed.', 1);
			}
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

	/**
	 * loadClassFile
	 *
	 * The autoload class file loader.
	 * @param string $class = the class file to load
	 * @param array $directories = directories to search for the file in
	 * @return boolean true = successful, false = failed.
	 */
	protected function loadClassFile($class, $directories)
	{
  		foreach($directories as $directory)
  		{
		  	$classFile = str_replace(array('\\', '\\\\', '//'), '/', sprintf('%s%s%s.php', $directory, $this->separator, $class));

		  	if (file_exists($classFile))
		  	{
  				if (include_once $classFile)
  				{
  					return true;
  				}
	  	  	}
  		}

	  	return false;
	}

  	/**
     * getSeparator.
     *
     * Sets the separator character on the first call and returns the
     * current value of the directory separator.
     * @return string $separator = current directory path separator
     */
  	public function getSeparator()
  	{
  		if ($this->separator == '')
  		{
  			$this->separator = (substr(strtolower(trim(php_uname())), 0, 3) == 'win') ? '/' : DIRECTORY_SEPARATOR;
  		}

  		return $this->separator;
  	}

	/**
	 * convertUnderscores
	 *
	 * convert all underscores to DIRECTORY_SEPARATORs
	 * @param string $class = class name to convert
	 * @return string $class = converted class name
	 */
	public function convertUnderscores($class)
	{
		return str_replace('_', $this->separator, $class);
	}

}
