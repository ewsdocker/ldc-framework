<?php
namespace Library\Std;
use Library\Std\Exception;
use Library\Error;

/*
 * 		Std\Convert is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * StdConvert
 *
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Std
 */
class Convert
{
	/**
	 * toArray
	 *
	 * Static class to convert a stdClass object to an array
	 * @param object $object = an stdClass (or stdClass subclass) object to convert to an array
	 * @param boolean $recurse = (optional) boolean indicating ok to recurse if true, else not ok
	 * @return array $array
	 */
	static public function toArray($object, $recurse=true)
	{
		if (! is_object($object))
		{
			throw new Exception('ObjectExpected');
		}

		if (! ($object instanceof \stdClass))
		{
			throw new Exception('InvalidClassObject');
		}

		$array = (array)$object;
		
		if ($recurse)
		{
			foreach($array as $name => $value)
			{
				if (is_object($value) && ((get_class($value) == 'stdClass') || is_subclass_of($value, 'stdClass')))
				{
					$array[$name] = self::toArray($value);
				}
			}
		}

		return $array;
	}

	/**
	 * toStd
	 *
	 * Static class to convert an array to an stdClass object
	 * @param array $array = an array to convert to a stdClass
	 * @param boolean $recurse = (optional) boolean indicating ok to recurse if true, else not ok
	 * @return object $class
	 */
	static public function toStd($array, $recurse=true)
	{
		if (! is_array($array))
		{
			throw new Exception('ArrayVariableExpected');
		}

		if ($recurse)
		{
			foreach($array as $name => $value)
			{
				if (is_array($value))
				{
					$array[$name] = self::toStd($value);
				}
			}
		}

		return (object)$array;
	}

	/**
	 * fromObject
	 *
	 * Static class to convert an object to a stdClass object
	 * @param object $object = an object to convert to an stdClass
	 * @return object $stdClass = an stdClass object with all properties accessible
	 */
	static public function fromObject($object)
	{
		if (! is_object($object))
		{
			throw new Exception(Error::code('ObjectExpected'));
		}

		$stdClass = new \stdClass();
		$reflection = new \ReflectionObject($object);
		$properties = $reflection->getProperties();
		foreach($properties as $property)
		{
			if ($property->isProtected() || $property->isPrivate())
			{
				$property->setAccessible(true);
			}
			
			$stdClass->{$property->getName()} = $property->getValue($object);
		}
		
		return $stdClass;
	}

	/**
	 * fromStd
	 *
	 * Static class to convert an stdClass object to a specified object
	 * @param object $stdClass = stdClass to convert to a specified object
	 * @param object|string $object = class instance or name of the object to create
	 * @return object $class
	 */
	static public function fromStd($stdClass, $object)
	{
		if (! is_object($stdClass))
		{
			throw new Exception(Error::code('ObjectExpected'));
		}
		
		if ((get_class($stdClass) != 'stdClass') && (! is_subclass_of($stdClass, 'stdClass')))
		{
			throw new Exception(Error::code('stdClassObjectExpected'));
		}

		if (! is_object($object))
		{
			if (! is_string($object))
			{
				throw new Exception(Error::code('StringOrObjectExpected'));
			}
			
			$object = new $object();
		}

		$reflection = new \ReflectionObject($object);
		$source = new \ReflectionObject($stdClass);
		
		$properties = $source->getProperties();
		foreach($properties as $property)
		{
			if ($reflection->hasProperty($property->getName()))
			{
				$destination = $reflection->getProperty($property->getName());

				$destination->setAccessible(true);
				$destination->setValue($object, $property->getValue($stdClass));
			}
			else
			{
				$object->{$property->getName()} = $property->getValue($stdClass);
			}
		}
		
		return $object;
	}

}
