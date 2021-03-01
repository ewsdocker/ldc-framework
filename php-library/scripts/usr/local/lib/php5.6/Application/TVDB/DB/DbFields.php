<?php
namespace Application\TVDB\DB;

use Application\TVDB\DB\Fields\FieldDescriptor;
use Library\MySql\Table;

/*
 *		Application\TVDB\DB\DbFields is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\DB\DbFields
 *
 * TVDB Series Data Record(s)
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage DB
 */
class DbFields implements \Iterator, \Countable, \ArrayAccess
{
	/**
	 * table
	 *
	 * Table name
	 * @var string $table = name of the table
	 */
	protected $table;

	/**
	 * fields
	 *
	 * Array of column number to field descriptor mapping
	 * @param array $fields = array containing field descriptors, in the order they were entered
	 * @var array $fields
	 */
	protected $fields;

	/**
	 * columns
	 *
	 * Array of column numbers to field name mapping
	 * @param array $columns = array containing column number to field name association
	 * @var array $columns = array of column numbers to field name mapping
	 */
	protected $columns;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $table = the name of the table to define fields for
	 * @throws \Library\Exception, \Application\TVDB\DB\Exception
	 */
	public function __construct($table)
	{
		$this->table = $table;

		$this->fields = array();
		$this->columns = array();
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
	 * Return printable string
	 * @return string $buffer
	 */
	public function __toString()
	{
		$buffer = sprintf("Table name: %s\n", $this->table);
		if ($this->fields)
		{
			foreach($this->fields as $column => $fieldDescriptor)
			{
				$buffer .= sprintf("Column: %u\n\t%s\n", $column, $fieldDescriptor);
			}
		}

		return $buffer;
	}

	/**
	 * add
	 *
	 * Add a new field
	 * @param string $name = field name
	 * @param string $definition = field definition string
	 * @param bool $null = true if null allowed, false if not
	 * @param mixed $default = default field value (initial value)
	 * @return integer $column = assigned field column number
	 */
	public function add($name, $definition, $null, $default)
	{
		$column = count($this->columns);

		array_push($this->fields, new FieldDescriptor($name, $definition, $null, $default, $column));
		array_push($this->columns, $name);

		return $column;
	}

	/**
	 * table
	 *
	 * Return the table name
	 * @return string $table = current table property
	 */
	public function table()
	{
		return $this->table;
	}

	/**
	 * fields
	 *
	 * Return the fields array property
	 * @return array $fields = current fields array property
	 */
	public function fields()
	{
		return $this->fields;
	}

	/**
	 * field
	 *
	 * Get the requested field value
	 * @param string $name = name of the field to get the field value for
	 * @return mixed $value = value stored in the fieldName entry of the field array property, null if $name is invalid
	 */
	public function field($name)
	{
		if (($column = array_search($name, $this->columns)) === false)
		{
			return null;
		}

		return $this->fields[$column];
	}

	/**
	 * columns
	 *
	 * Return the columns array property
	 * @return array $columns = current columns array property
	 */
	public function columns()
	{
		return $this->columns;
	}

	/**
	 * column
	 *
	 * Return the value in the requested column
	 * @param integer $column = column to get value from
	 * @return mixed $value = column value, null if column is invalid
	 */
	public function column($column)
	{
		if (! array_key_exists($column, $this->columns))
		{
			return null;
		}

		return $this->columns[$column];
	}

	/*
	 * ********************************************************
	 *
	 * 	Countable Implementation
	 *
	 * ********************************************************
	 */

	/**
	 * count
	 *
	 * Returns count of elements in the fields array property
	 * @return integer $count = field count
	 */
	public function count()
	{
		return count($this->fields);
	}

	/*
	 * ********************************************************
	 *
	 * 	Iterator Implementation
	 *
	 * ********************************************************
	 */

	/**
	 * rewind.
	 *
	 * Moves the current array pointer to the first element.
	 */
	public function rewind()
	{
		reset($this->fields);
	}

	/**
	 * current.
	 *
	 * Returns the current array element value.
	 * @return mixed $value = current array element value
	 */
	public function current()
	{
		return current($this->fields);
	}

	/**
	 * key.
	 *
	 * Returns the current array index
	 * @return $key = current array index.
	 */
	public function key()
	{
		return key($this->fields);
	}

	/**
	 * next.
	 *
	 * Moves current to the next array element.
	 */
	public function next()
	{
		next($this->fields);
	}

	/**
	 * valid.
	 *
	 * Returns the validity of the key
	 * @return boolean true = the current key (array index) is valid.
	 */
	public function valid()
	{
		return (key($this->fields) !== null);
	}

	/*
	 * ********************************************************
	 *
	 * 	ArrayAccess Implementation
	 *
	 * ********************************************************
	 */

	/**
	 * offsetSet
	 *
	 * Set the value at the indicated offset
	 * @param mixed $offset = offset to the entry (property name)
	 * @param mixed $value = value of property
	 */
	public function offsetSet($offset, $value)
	{
		$this->fields[$offset] = $value;
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
		return $this->fields[$offset];
	}

	/**
	 * offsetExists
	 *
	 * Returns true if the offset exists, false if not
	 * @param mixed $offset = offset (property) to test for existence
	 */
	public function offsetExists($offset)
	{
		return array_key_exists($offset, $this->fields);
	}

	/**
	 * offsetUnset
	 *
	 * Unset the provided offset
	 * @param mixed $offset = offset to unset
	 */
	public function offsetUnset($offset)
	{
		if (offsetExists($offset))
		{
			unset($this->fields[$offset]);
		}
	}

}
