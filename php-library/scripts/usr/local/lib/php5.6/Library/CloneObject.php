<?php
namespace Library;
use Library\Error;

/*
 * 		CloneObject is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 * Library\CloneObject
 *
 * A simple clone replacement to perform a "deep copy" of the specified object.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage CloneObject.
 */
class CloneObject
{
	/**
	 * copy
	 *
	 * The specified object is copied in depth, converting references to variables
	 * @param object $object = object to copy
	 * @return object $object
	 * @throws Library\Clone\Exception
	 */
	public static function copy($object)
	{
		if (! is_object($object))
		{
			throw new CloneObject\Exception('', Error::code(MissingClassObject));
		}

		return unserialize(serialize($object));
	}

}
