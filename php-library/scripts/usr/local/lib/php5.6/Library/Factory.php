<?php
namespace Library;
use Library\Error;

/*
 *		Factory is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * Factory.
 *
 * A general purpose factory base class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Factory.
 */
class Factory extends Factory\InstantiateClass
{
	/**
	 * classes
	 *
	 * Array containing the class internal key to external name mapping
	 * @var array $classes
	 */
	protected 		$classes;

	/**
	 * key
	 *
	 * The current class key to look-up
	 * @var string $key
	 */
	protected		$key;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param array $classes = associative array of class key to class-name pairs
	 * @return Factory $class
	 * @throws \Library\Factory\Exception
	 */
	public function __construct($classes)
	{
		if (! is_array($classes))
		{
			throw new Exception(Error::code('ArrayVariableExpected'));
		}

		parent::__construct();

		$this->classes = $classes;
		$this->key = '';
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 */
	public function __destruct()
	{
	}

  	/**
  	 * key.
  	 *
  	 * get/set the key to look-up
  	 * @param string $key = (optional) class key, null to query.
  	 * @return string $key = class key.
  	 */
  	public function key($key=null)
  	{
  		if ($key != null)
  		{
  			if (is_array($key))
  			{
  				$this->key = $key[0];
  			}
  			else
  			{
  	  			$this->key = $key;
  			}
  		}

  		return $this->key;
  	}

  	/**
  	 * className.
  	 *
  	 * Return the name of the associated class, if one exists.
  	 * @param string $key = (optional) class key
  	 * @return string $className = name of the class
  	 * @throws Factory\Exception
  	 */
	public function className($key=null)
	{
		if ((! $key = $this->key($key)) || (! array_key_exists($key, $this->classes)))
		{
			throw new Exception(Error::code('MissingClassName'));
		}

		return parent::className($this->classes[$key]);
	}

  	/**
  	 * availableClasses.
  	 *
  	 * get an associative array with the keys and class names of the available classes.
  	 * @return array $classes
  	 */
	public function availableClasses()
	{
		return $this->classes;
	}

	/**
	 * availableKeys
	 *
	 * get the keys from the classes array
	 * @return array $keys
	 */
	public function availableKeys()
	{
		return array_keys($this->classes);
	}

  	/**
  	 * classAvailable.
  	 *
  	 * Returns true if the requested class key is available.
  	 * @param string $key = internal class key of the class to check for.
  	 * @return boolean true = available, false = not supported.
  	 */
	public function classAvailable($key)
	{
		return array_key_exists($key, $this->classes);
	}

	/**
	 * availableClassName
	 *
	 * get the class name associated with the requested key
	 * @param string $key = (optional) class key, default = current key
	 * @return string $className = class name, false if not valid
	 */
	public function availableClassName($key=null)
	{
		$key = $this->key($key);

		if ($this->classAvailable($key))
		{
			return $this->classes[$key];
		}

		return false;
	}

  	/**
  	 * classNameAvailable.
  	 *
  	 * Returns true if the requested class name is available (supported), false if not.
  	 * @param string $name = name of the class to check for.
  	 * @return boolean true if supported, false = not supported.
  	 */
	public function classNameAvailable($name)
	{
		return array_search($name, $this->classes);
	}

	/**
	 * clearClass
	 *
	 * clear the class vector
	 */
	public function clearClass()
	{
		if ($this->class)
		{
			unset($this->class);
			$this->class = null;
		}
	}

	/**
	 * addClasses
	 *
	 * Add new class entries from the supplied array to the current classes array.
	 * @param array $classes = an array containing class key / class name pairs.
  	 * @throws Factory\Exception
	 */
	protected function addClasses($classes)
	{
		if ((! $classes) || (! is_array($classes)))
		{
			throw new Exception(Error::code('MissingParametersArray'));
		}

		$this->classes = array_merge($this->classes, $classes);
	}

	/**
	 * newClasses
	 *
	 * Replace current class names with new class names.
	 * @param array $classes = an array containing class key / class name pairs.
  	 * @throws Factory\Exception
	 */
	protected function newClasses($classes)
	{
		if ((! $classes) || (! is_array($classes)))
		{
			throw new Exception(Error::code('MissingParametersArray'));
		}

		$this->classes = $classes;
	}

	/**
	 * clearClasses
	 *
	 * Replace current class names with an empty array.
	 */
	protected function clearClasses()
	{
		$this->classes = array();
	}

}
