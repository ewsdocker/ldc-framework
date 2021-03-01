<?php
namespace Library\Utilities;
use Library\Error;

/*
 *		Utilities\EldestParent is copyright � 2012, 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * Utilities\EldestParent
 *
 * Returns the top parent (ancestor) of the provided class object
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Utilities
 * @subpackage EldestParent
 */
class EldestParent
{
    /**
     * getEldestParent
     *
     * Get the top parent in the heirarchy
     * @param mixed $object = class name or object to get eldest parent for
     * @return string $class = eldest parent, false if no parent
     * @throws \Library\Utilities\Exception
     */
    public static function get($object)
    {
    	if (is_object($object))
    	{
    		$class = get_class($object);
    	}
    	elseif (is_string($object))
    	{
    		$class = $object;
    	}
    	else
    	{
    		throw new Exception(Error::code('StringOrObjectExpected'));
    	}

    	while($parent = get_parent_class($class))
    	{
    		$class = $parent;
    	}

    	return $class;
    }

}
