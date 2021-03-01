<?php
namespace Library\Autoload;

/*
 * 		    Autoload\BaseClass is copyright © 2012, 2013. EarthWalk Software.
 * 		    Licensed under the Academic Free License version 3.0.
 * 			Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
 */
/**
 * Autoload\BaseClass.
 *
 * An abstract base class for all PSR-0 compliant autoloaders
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Autoload
 */
abstract class BaseClass
{
	/**
	 * Separator
	 *
	 * The current separator
	 * @var string $separator
	 */
	protected		$separator='';

	/**
	 * loader
	 *
	 * The autoload class loader.
	 * @param string $class
	 * @return boolean true = successful, false = failed.
	 */
	abstract public function loader($class);

	/**
	 * loadClassFile
	 *
	 * The autoload class file loader.
	 * @param string $class
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
	 * register
	 *
	 * Register the handler
	 * @param string $handler = name of the method in the current class instance to register
	 * @param boolean $replace = true to replace if already exists, false to not replace existing (default)
	 * @param boolean $prepend = true to add
	 *  to front of registered list, false to add to back (default)
	 * @return boolean true = successful, false = not
	 */
	protected function register($handler, $replace=false, $prepend=false)
	{
		return Spl::register($handler, $replace, $prepend);
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
  			$this->separator = (substr(strtolower(trim(php_uname())), 0, 3) == 'win') ? "\\" : DIRECTORY_SEPARATOR;
  		}

  		return $this->separator;
  	}

}
