<?php
namespace Application\TVDB;

use Application\TVDB\DB\Fields\Control as FieldsControl;
use Application\TVDB\Records\Control as ControlRecord;

use Library\Utilities\FormatVar;

/*
 *		Application\TVDB\Control is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\Control
 *
 * DB Control record
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage TVDB
 */
class Control implements \ArrayAccess, \Iterator, \Countable
{
	/**
	 * records
	 *
	 * An array of Control records (never more than a single record)
	 * @var array $records
	 */
	public $records;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @throws Application\TVDB\Exception
	 * @param array $properties = (optional) array of properties/values
	 */
	public function __construct($properties=null)
	{
		$fieldsControl = new FieldsControl();

		$defaults = array();
		foreach($fieldsControl->fields() as $fieldNumber => $descriptor)
		{
			$defaults[$descriptor->name] = $descriptor->default;
		}

		$controlRecord = new ControlRecord($defaults);

		if ($properties !== null)
		{
			$controlRecord->setProperties($properties);
		}

		$this->records = array($controlRecord);
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
	 * Returns a printable list of properties and values
	 * @return string $buffer = list
	 */
	public function __toString()
	{
		FormatVar::sort(false);
		FormatVar::sortValues(false);
		FormatVar::recurse(false);
		FormatVar::formatted(true);

		return (string)$this->records[0];
	}

	/**
	 * __get
	 *
	 * "Magic" method to trap unknown properties and redirect to $records[0] for property value
	 * @param string $name = property name
	 * @return mixed $value = property value
	 */
	public function __get($name)
	{
		if ($this->records[0]->exists($name))
		{
			return $this->records[0]->{$name};
		}

		return null;
	}

	/**
	 * __set
	 *
	 * "Magic" method to trap unknown properties and redirect to $records[0] for storing
	 * @param string $name = property name
	 * @param mixed $value = property value
	 */
	public function __set($name, $value)
	{
		$this->records[0]->{$name} = $value;
	}

	/**
	 * records
	 *
	 * Return the records array
	 * @param array $properties = (optional) properties object/array, null to query
	 * @return array $properties = array of Property objects
	 */
	public function records($properties=null)
	{
		if ($properties !== null)
		{
			$this->records[0]->setProperties($properties, true);
		}

		return $this->records[0]->properties();
	}

	/**
	 * ******************************************
	 *
	 * Iterator Implementation
	 *
	 * ******************************************
	 */

	/**
	 * rewind.
	 *
	 * Moves the current record array pointer to the first item in the array.
	 */
	public function rewind()
	{
		reset($this->records);
	}

	/**
	 * current.
	 *
	 * Returns the current array element data.
	 */
	public function current()
	{
		return current($this->records);
	}

	/**
	 * key.
	 *
	 * Returns the current array key value.
	 * @return $key = current array key value.
	 */
	public function key()
	{
		return key($this->records);
	}

	/**
	 * next.
	 *
	 * Moves current to the next array element.
	 * @return null.
	 */
	public function next()
	{
		return next($this->records);
	}

	/**
	 * valid.
	 *
	 * Returns the validity of the current array pointer
	 * @return boolean true = the current node pointer is valid.
	 */
	public function valid()
	{
		if (($key = $this->key()) !== null)
		{
			return array_key_exists($this->key(), $this->records);
		}

		return false;
	}

	/**
	 * ******************************************
	 *
	 * Countable Implementation
	 *
	 * ******************************************
	 */

	/**
	 * count
	 *
	 * Returns the number of unique nodes in the tree
	 * @return integer $count
	 */
	public function count()
	{
		return count($this->records);
	}

	/**
	 * ******************************************
	 *
	 * Array Access Implementation
	 *
	 * ******************************************
	 */

	/**
	 * offsetSet
	 *
	 * Set the value at the indicated offset
	 * @param mixed $offset = offset to the entry
	 * @param mixed $value = value of entry at $offset
	 */
	public function offsetSet($offset, $value)
	{
		$this->records[$offset] = $value;
	}

	/**
	 * offsetGet
	 *
	 * Returns the value at the indicated offset
	 * @param mixed $offset = offset to the entry
	 * @returns mixed $value = value of entry at $offset, null if not found
	 */
	public function offsetGet($offset)
	{
		if (array_key_exists($offset, $this->records))
		{
			return $this->records[$offset];
		}

		return null;
	}

	/**
	 * offsetExists
	 *
	 * Returns true if the offset exists, false if not
	 * @param mixed $offset = offset (property) to test for existence
	 */
	public function offsetExists($offset)
	{
		return array_key_exists($offset, $this->records);
	}

	/**
	 * offsetUnset
	 *
	 * Unset the provided offset
	 * @param mixed $offset = offset to unset
	 */
	public function offsetUnset($offset)
	{
		unset($this->records[$offset]);
	}

}
