<?php
namespace Library\Error;

/*
 * 		Error\Descriptor is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
 */

/**
 * Error\Descriptor.
 *
 * Error descriptor class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Error
 */

class Descriptor extends \Library\Properties
{
	/**
	 * __construct
	 *
	 * Create an error object instance
	 * @param Exception $exception = exception to record
	 * @param mixed $properties = (optional) array or Library\Properties object containing additional information to record
	 */
	public function __construct($properties)
	{
		parent::__construct($properties);
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * __toString
	 *
	 * Return a printable string of the exception information.
	 * @return string $buffer
	 */
	public function __toString()
	{
		$fields = array('timeStamp', 'errorNumber', 'errorType', 'message', 'scriptLineNumber', 'script', 'context');

		$buffer = "<ErrorDescriptor>\n";
		
		foreach($fields as $line => $field)
		{
			if ($this->exists($field))
			{
				$buffer .= sprintf("\t<%s>%s</%s>\n", $field, $this->$field, $field);
			}
		}

		$buffer .= '</ErrorDescriptor>';
		return $buffer;
	}

}
