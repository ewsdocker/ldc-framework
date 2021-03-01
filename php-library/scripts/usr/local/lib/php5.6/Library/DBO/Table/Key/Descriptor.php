<?php
namespace Library\DBO\Table\Key;

use Library\DBO\Table\Exception;
use Library\Error;
use Library\Properties;

/*
 * 		DBO\Table\Key\Descriptor is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * Descriptor
 *
 * @author Jay Wheeler.
 * @version 2.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage DBO
 */
class Descriptor extends Properties implements \Iterator, \Countable
{
	/**
	 * indexTypes
	 *
	 * array of index type names and index type priority
	 * @var array $indexTypes
	 *
	protected $indexTypes;

	/**
	 * index
	 *
	 * Name of  the key
	 * @var string $index
	 *
	protected $index;

	/**
	 * indexType
	 *
	 * Type of index
	 * @var string $type
	 *
	protected $type;

	/**
	 * indexFields
	 *
	 * array containing key field descriptions (EWSLibrary_DB_Table_Column_Descriptor)
	 * @var array $fields
	 *
	protected $indexFields;

	/**
	 * indexSize
	 *
	 * array containing the size of the field to include in the key
	 * @var array $indexSize
	 *
	protected $indexSize;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $index = index name
	 * @param string $type = index type (PRIMARY, INDEX, UNIQUE, FULLTEXT)
	 * @param array $fields = array of table column descriptor (EWSLibrary_DB_Table_Column_Descriptor)
	 * @throws MySql\Table\Exception
	 */
	public function __construct($indexType='index', $indexName='', $indexFields=array())
	{
		$this->indexTypes = array('primary' 	=> false,
								  'index'		=> true,
								  'unique'		=> true,
								  'fulltext'	=> true);

		$this->checkArray($indexFields);

		parent::__construct(array('index'		=> $indexName,
								  'type'		=> $indexType,
								  'indexName'	=> $indexName,
								  'indexType'	=> $this->indexType($indexType),
								  'indexFields'	=> $indexFields,
								  ));
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 * @return null
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * __toString
	 *
	 * Convert fields to a formatted string and return result
	 * @return string $formatted
	 */
	public function __toString()
	{
		$buffer = sprintf('%s KEY %s(',
						  strtoupper($this->type),
		                  ($this->type == 'primary') ? '' : '`' . $this->index . '` ');

		$fieldBuffer = '';

		foreach($this->indexFields as $name => $size)
		{
			if (strlen($fieldBuffer) > 0)
			{
				$fieldBuffer .= ', ';
			}

			$fieldBuffer .= sprintf('`%s`', $name);
			if ($size !== null)
			{
				$fieldBuffer .= sprintf('(%u)', $size);
			}
		}

		$buffer .= $fieldBuffer . ')';

		return $buffer;
	}

	/**
	 * indexType
	 *
	 * get/set the index type
	 * @param string $type = (optional) index type name, null to query only
	 * @return string $type
	 * @throws EWSLibrary_DB_Table_Exception
	 */
	public function indexType($type=null)
	{
		if (($type = strtolower($type)) !== null)
		{
			if (! array_key_exists($type, $this->indexTypes))
			{
				throw new Exception(Error::code('DbUnknownIndex'));
			}

			$this->type = $type;
		}

		return $this->type;
	}

	/**
	 * resizeColumn
	 *
	 * set the amount of data (size) to use from a column
	 * @param string $name = name of the column
	 * @param integer $size = amount of data to use from the column
	 * @throws EWSLibrary_DB_Table_Exception
	 */
	public function resizeColumn($name, $size)
	{
		if (! array_key_exists($name, $this->indexFields))
		{
			throw new Exception(Error::code('DbUnknownIndex'));
		}

		$this->indexFields[$name] = $size;
	}

	/**
	 * rewind.
	 *
	 * Moves the current field pointer to the first element in the field array.
	 * @return null.
	 */
	public function rewind()
	{
		return reset($this->indexFields);
	}

	/**
	 * current.
	 *
	 * Returns the current field element.
	 * @return mixed $value = value of the current field in the stack
	 */
	public function current()
	{
		return current($this->indexFields);
	}

	/**
	 * key.
	 *
	 * Returns the current stack field key value.
	 * @return mixed $key = current field key value.
	 */
	public function key()
	{
		return key($this->indexFields);
	}

	/**
	 * next.
	 *
	 * advances the key to the next element and return its' value.
	 * @return mixed $next = next element in the field array.
	 */
	public function next()
	{
		return next($this->indexFields);
	}

	/**
	 * valid.
	 *
	 * Returns the validity of the current field pointer
	 * @return boolean true = the current field pointer is valid.
	 */
	public function valid()
	{
		if (current($this->indexFields) === false)
		{
			return false;
		}

		return true;
	}

	/**
	 * count.
	 *
	 * returns the number of elements in the descriptor array
	 * @return integer $count = number of elements in the descriptor array
	 */
	public function count()
	{
		return count($this->indexFields);
	}

	/**
	 * checkArray
	 * 
	 * Check that the passed parameter is an array
	 * @param array $array = variable to be tested
	 * @throw Exception if not an array
	 */
	private function checkArray($array)
	{
		if (! is_array($array))
		{
			throw new Exception(Error::code('ArrayVariableExpected'));
		}
	}

}
