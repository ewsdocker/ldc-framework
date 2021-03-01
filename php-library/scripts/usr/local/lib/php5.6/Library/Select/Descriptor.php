<?php
namespace Library\Select;

use Library\Properties;
use Library\Utilities\FormatVar;

/*
 *		Library\Select\Descriptor is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * Library\Select\Descriptor
 *
 * Select descriptor class
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Select
 */
class Descriptor extends \Library\Properties
{
	/*
	 *	Fields:
	 *
	 *		name		= select name
	 *		type		= select type ('read', 'write', 'except')
	 *		resource	= stream resource
	 *
	 *		enabled		= select enabled if true
	 *		ready		= selected (ready to process)
	 *		complete	= operation has completed
	 *
	 *		callback	= select complete callback processor
	 *
	 *		buffer		= select i/o buffer
	 *		index 		= index into the i/o buffer (for write operations)
	 */

	/**
	 * __construct
	 *
	 * Class constructor
	 */
	public function __construct($properties=null)
    {
    	parent::__construct($properties);
    }

	/**
	 * __destruct
	 *
	 * Destroy the current class instance
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * __toString
	 * 
	 * 
	 */
	public function __toString()
	{
		$buffer = "\n";

		FormatVar::sort(false);
		FormatVar::sortValues(false);
		FormatVar::recurse(true);

		foreach(get_object_vars($this) as $name => $value)
		{
			$buffer .= FormatVar::format($value, $name);
		}

		return $buffer;
	}

}
