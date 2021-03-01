<?php
namespace Library;

use Library\Error;
use Library\Utilities\FormatVar;

/*
 *		Library\Properties is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
*/
/**
 * Library\Properties.
 *
 * General purpose properties class.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Properties
 */
class Properties implements \Countable, \ArrayAccess
{
	/**
	 * __construct
	 *
	 * Class constructor
	 * @param array|object $properties = (optional) properties to initialize in constructor
	 */
	public function __construct($properties=null)
	{
		if ($properties)
		{
#			if (is_object($properties) && (get_class($properties) === '\Library\Properties'))
			if (is_object($properties) && (($properties instanceof \Library\Properties) || (is_subclass_of($properties, '\Library\Properties'))))
			{
				$properties = $properties->properties();
			}
			elseif (! is_array($properties))
			{
				if (is_object($properties))
				{
					$properties = (array)$properties;
				}
				else
				{
					$properties = array('default' => $properties);
				}
			}

			$this->setProperties($properties);
		}
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
	 * __toString
	 *
	 * Print list of properties and settings
	 * @return string $buffer
	 */
	public function __toString()
	{
		FormatVar::sort(false);
		FormatVar::sortValues(false);
		FormatVar::recurse(true);

		return FormatVar::format(get_object_vars($this), get_class($this));
	}

	/**
	 * __get
	 *
	 * Magic function to get a property, if it exists, null if not
	 * @param string $property = name of the property to get
	 * @return mixed $value = value of the property, null if it does not exist
	 */
	public function __get($property)
	{
		if ($this->exists($property))
		{
			return $this->{$property};
		}

		return null;
	}

	/**
	 * __set
	 *
	 * Magic function to dynamically set a property to a given value
	 * @param string $property = name of the property to set
	 * @param mixed $value = value to set property to
	 */
	public function __set($property, $value)
	{
		$this->{$property} = $value;
	}

	/**
	 * count.
	 *
	 * returns the number of elements in the stack
	 * @return integer $count = number of elements in the stack
	 */
	public function count()
	{
		return count(get_object_vars($this));
	}

	/**
	 * deleteAll
	 *
	 * unset all of the property names
	 */
	public function deleteAll()
	{
		$vars = get_object_vars($this);
		foreach($vars as $name => $value)
		{
			$this->delete($name);
		}
	}

	/**
	 * delete
	 *
	 * unset the property
	 * @param $property = name of the property to unset
	 */
	public function delete($property)
	{
		if ($this->exists($property))
		{
			unset($this->{$property});
		}
	}

	/**
	 * unsetProperties
	 *
	 * Unset property values from an array of property names
	 * @param array $properties = array containing property names to unset
	 * @throws \Library\Properties\Exception
	 */
	public function unsetProperties($properties)
	{
		if ((! is_array($properties)) || (count($properties) == 0))
		{
			throw new Exception(Error::code('MissingPropertiesArray'));
		}

		foreach($properties as $index => $property)
		{
			$this->delete($property);
		}
	}

	/**
	 * setProperties
	 *
	 * Set property values from an associative array
	 * @param array $properties = array containing property names and values to set
	 * @param boolean $replace = (optional) replace existing values if true, don't if false (default = true)
	 * @throws \Library\Properties\Exception
	 */
	public function setProperties($properties, $replace=true)
	{
		if (is_object($properties) && ($properties instanceof Library\Properties))
		{
			$properties = get_object_vars($properties);
		}

		if ((! is_array($properties)) || (count($properties) == 0))
		{
			throw new Exception(Error::code('MissingPropertiesArray'));
		}

		foreach($properties as $name => $value)
		{
			if ((! $exists = $this->exists($name)) || ($exists && $replace))
			{
				$this->{$name} = $value;
			}
		}
	}

	/**
	 * exists
	 *
	 * Return true if property is set, false if not
	 * @param string $property = name of property to test
	 * @return boolean $value = true if set, false if not
	 */
	public function exists($property)
	{
		return property_exists($this, $property);
	}

	/**
	 * properties
	 *
	 * Returns an associative array of property names and associated values
	 * @return array $properties
	 */
	public function properties()
	{
		return get_object_vars($this);
	}


	/**
	 * offsetSet
	 *
	 * Set the value at the indicated offset
	 * @param mixed $offset = offset to the entry (property name)
	 * @param mixed $value = value of property
	 */
	public function offsetSet($offset, $value)
	{
		$this->$offset = $value;
	}

	/**
	 * offsetGet
	 *
	 * Get the value at the indicated offset
	 * @param mixed $offset = offset to the entry (property name)
	 * @returns mixed $value = value of property, null if not found
	 */
	public function offsetGet($offset)
	{
		return $this->$offset;
	}

	/**
	 * offsetExists
	 *
	 * Returns true if the offset exists, false if not
	 * @param mixed $offset = offset (property) to test for existence
	 */
	public function offsetExists($offset)
	{
		return $this->exists($offset);
	}

	/**
	 * offsetUnset
	 *
	 * Unset the provided offset
	 * @param mixed $offset = offset to unset
	 */
	public function offsetUnset($offset)
	{
		$this->delete($offset);
	}

}
