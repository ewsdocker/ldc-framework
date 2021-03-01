<?php
namespace Library\DBO\Table;

use Library\DBO\Table\Exception;
use Library\Error;
use Library\Properties;

/*
 * 		DB\Table\Descriptor is copyright � 2012, 2015. EarthWalk Software.
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
 * @subpackage DBO.
 */
class Descriptor extends Properties implements \Iterator, \Countable
{
	/**
	 * storageEngines
	 *
	 * array of storageEngines type names and index type priority
	 * @var array $storageEngines
	 */
	protected $storageEngines;

	/**
	 * name
	 *
	 * Name of  the table
	 * @var string $name
	 *
	protected $name;

	/**
	 * ifNotExists
	 *
	 * true = create ONLY if it doesn't already exist, false to ALWAYS create
	 * @var boolean ifNotExists
	 *
	protected $ifNotExists;

	/**
	 * storageEngine
	 *
	 * The name of the storage engine to use for the table
	 * @var string $storageEngine
	 *
	protected $storageEngine;

	/**
	 * collation
	 *
	 * collate table type
	 * @var string collation
	 *
	protected $collation;

	/**
	 * collateDefault
	 *
	 * collation should be considered default if set
	 * @var boolean $collateDefault
	 *
	protected $collateDefault;

	/**
	 * charSet
	 *
	 * global character set
	 * @var string $charSet
	 *
	protected $charSet;

	/**
	 * charSetDefault
	 *
	 * charSet should be considered default if set
	 * @var boolean $charSetDefault
	 *
	protected $charSetDefault;

	/**
	 * autoIncrement
	 *
	 * auto increment if not null, value = start number
	 * @var integer $autoIncrement
	 *
	protected $autoIncrement;

	/**
	 * comment
	 *
	 * table comment
	 * @var string $comment
	 *
	protected $comment;

	/**
	 * $columns
	 *
	 * array of column descriptors
	 * @var array $columns
	 *
	protected $columns;

	/**
	 * keys
	 *
	 * array of table key descriptors
	 * @var array $keys
	 *
	protected $keys;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param array|object $properties = (optional) array/properties object containing property settings
	 */
	public function __construct($properties)
	{
		$this->storageEngines = array('myisam' 		=> 'MyISAM',
							  	   	  'mrg_myisam'	=> 'MRG_MYISAM',
							  	   	  'csv'			=> 'CSV',
							  	   	  'innodb'		=> 'innoDB',
								   	  'memory'		=> 'MEMORY',
								   	  'mongo'		=> 'MongoDB');

		$defaultProperties = array('name' 			=> '',
						  		   'ifNotExists' 	=> true,
						  		   'storageEngine' 	=> 'innoDB',
						  		   'collation' 		=> 'utf8_unicode_ci',
						  		   'collateDefault' => true,
						  		   'charSet'		=> 'utf8',
						  		   'charSetDefault' => true,
						  		   'autoIncrement' 	=> null,
						  		   'comment' 		=> null,
						  		   'columns' 		=> array(),
						  		   'keys'			=> array(),
						  		   );

		parent::__construct($defaultProperties);
		
		if ($properties)
		{
			parent::setProperties($properties, true);
		}
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
		$buffer = 'CREATE TABLE ';
		if ($this->ifNotExists)
		{
			$buffer .= 'IF NOT EXISTS ';
		}

		$buffer .= $this->name . ' (';
		$fieldBuffer = '';

		foreach($this->columns as $name => $descriptor)
		{
			if (strlen($fieldBuffer) > 0)
			{
				$fieldBuffer .= ', ';
			}

			$fieldBuffer .= sprintf('%s', $descriptor);
		}

		$buffer .= $fieldBuffer;
		if ($fieldBuffer && $this->keys)
		{
			$buffer .= ', ';
		}

		$fieldBuffer = '';
		foreach($this->keys as $index => $descriptor)
		{
			if (strlen($fieldBuffer) > 0)
			{
				$fieldBuffer .= ', ';
			}

			$fieldBuffer .= sprintf('%s', $descriptor);
		}

		$buffer .= sprintf('%s) ENGINE=%s',
						   $fieldBuffer,
						   $this->storageEngines[$this->storageEngine]);

		$buffer .= sprintf(' %sCHARSET=%s',
						   $this->charSetDefault ? 'DEFAULT ' : '',
						   $this->charSet);

		$buffer .= sprintf(' %sCOLLATE=%s',
						   $this->collateDefault ? 'DEFAULT ' : '',
						   $this->collation);

		if ($this->autoIncrement !== null)
		{
			$buffer .= sprintf(' AUTO_INCREMENT=%u', $this->autoIncrement);
		}

		if ($this->comment !== null)
		{
			$buffer .= sprintf(" COMMENT='%s'", $this->comment);
		}

		return $buffer;
	}

	/**
	 * storageEngines
	 * 
	 * Get/set an array of acceptable storage engines
	 * @param array $storageEngines = (optional) storage engines array to set, null to query only
	 * @return array $storageEngines
	 */
	public function storageEngines($storageEngines=null)
	{
		if ($storageEngines !== null)
		{
			$this->storageEngines = $storageEngines;
		}

		return $this->storageEngines;
	}

	/**
	 * storageEngine
	 *
	 * get/set the storage engine
	 * @param string $storageEngine = name of the storage engine, null to query
	 * @return string $storageEngine = name of the storage engine
	 * @throws \Library\DB\Exception
	 */
	public function storageEngine($storageEngine=null)
	{
		if (($storageEngine = strtolower($storageEngine)) !== null)
		{
			if (! array_key_exists($storageEngine, $this->storageEngines))
			{
				throw new Exception(Error::code('DbUnknownStorageEngine'));
			}

			$this->storageEngine = $storageEngine;
		}

		return $this->storageEngines[$this->storageEngine];
	}

	/**
	 * collation
	 *
	 * set/get the global table collate string
	 * @param string $collation = (optional) collation string, null to query
	 * @return string $collation
	 */
	public function collation($collation=null, $default=null)
	{
		if ($collation !== null)
		{
			$this->collation = $collation;
		}

		if ($default !== null)
		{
			$this->collateDefault = $default;
		}

		return $this->collation;
	}

	/**
	 * charSet
	 *
	 * get/set the charSet string
	 * @param string $charSet = (optional) character set string, null to query
	 * @return string $charSet
	 */
	public function charSet($charSet=null, $default=null)
	{
		if ($charSet !== null)
		{
			$this->charSet = $charSet;
		}

		if ($default !== null)
		{
			$this->charSetDefault = $default;
		}

		return $this->charSet;
	}

	/**
	 * column
	 *
	 * get/set the column value
	 * @param string $column = column field name
	 * @param \Library\DB\Table\Column\Descriptor $value = (optional) field descriptor, null to query
	 * @return \Library\DB\Table\Column\Descriptor
	 * @throws \Library\DB\Exception
	 */
	public function column($column, $value=null)
	{
		if ($value !== null)
		{
			$this->columns[$column] = $value;
		}
		elseif (! array_key_exists($column, $this->columns))
		{
			throw new Exception(Error::code('DbUnknownColumn'));
		}

		return $this->columns[$column];
	}

	/**
	 * keyValue
	 *
	 * get/set the key value
	 * @param string $key = key index name
	 * @param \Library\DB\Table\Descriptor $value = (optional) key value, null to query
	 * @return \Library\DB\Table\Descriptor
	 * @throws \Library\DB\Exception
	 */
	public function keyValue($key, $value=null)
	{
		if ($value !== null)
		{
			$this->keys[$key] = $value;
		}
		elseif (! array_key_exists($key, $this->keys))
		{
			throw new Exception(Error::code('DbUnknownIndex'));
		}

		return $this->keys[$key];
	}

	/**
	 * rewind.
	 *
	 * Moves the current column pointer to the first element in the column array.
	 * @return null.
	 */
	public function rewind()
	{
		return reset($this->columns);
	}

	/**
	 * current.
	 *
	 * Returns the current column element.
	 * @return mixed $value = value of the current column in the stack
	 */
	public function current()
	{
		return current($this->columns);
	}

	/**
	 * key.
	 *
	 * Returns the current stack column key value.
	 * @return mixed $key = current column key value.
	 */
	public function key()
	{
		return key($this->columns);
	}

	/**
	 * next.
	 *
	 * advances the key to the next element and return its' value.
	 * @return mixed $next = next element in the column array.
	 */
	public function next()
	{
		return next($this->columns);
	}

	/**
	 * valid.
	 *
	 * Returns the validity of the current column pointer
	 * @return boolean true = the current column pointer is valid.
	 */
	public function valid()
	{
		if (current($this->columns) === false)
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
		return count($this->columns);
	}

}
