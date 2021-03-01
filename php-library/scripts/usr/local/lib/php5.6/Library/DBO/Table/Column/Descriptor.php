<?php
namespace Library\DBO\Table\Column;

use Library\DBO\Table\Exception;
use Library\Error;
use Library\Properties;

/*
 * 		DBO\Table\Column\Descriptor is copyright � 2012, 2015. EarthWalk Software.
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
class Descriptor extends Properties
{
	/**
	 * column
	 *
	 * column number
	 * @var integer $column
	 *
	protected $column;

	/**
	 * name
	 *
	 * column name
	 * @var string $name = column name
	 *
	protected $name;

	/**
	 * type
	 *
	 * column type
	 * @var mixed $type = column type
	 *
	protected $type;

	/**
	 * null
	 *
	 * column null allowed setting
	 * @var mixed $nullAllowed = column nullAllowed setting
	 *
	protected $null;

	/**
	 * default
	 *
	 * column default value
	 * @var string $default = column default value
	 *
	protected $default;

	/**
	 * key
	 *
	 * key value
	 * @var string $key = key value
	 *
	protected $key;

	/**
	 * extra
	 *
	 * column extra value
	 * @var string $extra = column extra value
	 *
	protected $extra;

	/**
	 * attributes
	 *
	 * column attributes
	 * @var string $attributes = column attributes
	 *
	protected $attributes;

	/**
	 * charset
	 *
	 * string containing the character set to use
	 * @var string $charset
	 *
	protected $charset;

	/**
	 * collation
	 *
	 * String collation value
	 * @var string $collation
	 *
	protected $collation;

	/**
	 * onUpdate
	 *
	 * Action to take on updating the field
	 * @var string $onUpdate
	 *
	protected $onupdate;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param integer $column = column number
	 * @param array $record = record array containing field parameters
	 * @throws \Library\DB\Exception
	 */
	public function __construct($column, $record)
	{
		parent::__construct(array('column' 			=> null,
								  'name'			=> null,
								  'type'			=> null,
								  'null'			=> null,
								  'default'			=> null,
								  'key'				=> null,
								  'extra'			=> null,
								  'attributes'		=> null,
								  'charset'			=> null,
								  'collation'		=> null,
								  'onupdate'		=> null,
								  ));

		$columnFields = array('column'		=> false,
							  'name'		=> true,
		               		  'type'		=> true,
		    				  'null'		=> false,
							  'default'		=> false,
							  'onupdate'	=> false,
							  'extra'		=> false,
							  'collation'	=> false,
							  'charset'		=> false,
							  'attributes'	=> false,
							  'key'			=> false);

		$fields = array();
		foreach($record as $name => $value)
		{
			$name = strtolower($name);

			if (! array_key_exists($name, $columnFields))
			{
				throw new Exception(Error::code('DbUnknownColumnProperty'));
			}

			$fields[$name] = $value;
		}

		foreach($columnFields as $field => $value)
		{
			if (! array_key_exists($field, $fields))
			{
				if ($value)
				{
					throw new Exception(Error::code('MissingRequiredProperties'));
				}

				continue;
			}

			switch($field)
			{
				case 'name':
					$this->name = $fields['name'];
					break;

				case 'type':
					$this->type = $fields['type'];
					break;

				case 'null':
					$this->null = $fields['null'];
					break;

				case 'default':
					$this->default = $fields['default'];
					break;

				case 'onupdate':
					$this->onupdate = $fields['onupdate'];
					break;

				case 'extra':
					$this->extra = $fields['extra'];
					break;

				case 'charset':
					$this->charset = $fields['charset'];
					break;

				case 'collation':
					$this->collation = $fields['collation'];
					break;

				case 'attributes':
					$this->attributes = $fields['attributes'];
					break;

				case 'key':
					$this->key = $fields['key'];
					break;
			}
		}

		$this->column = $column;
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
		$buffer = sprintf('`%s` %s', $this->name, $this->type);

		if ($this->key)
		{
			$buffer .= sprintf(" KEY %s", $this->key);
		}

		if ($this->extra)
		{
			$buffer .= sprintf(' %s', strtoupper($this->extra));
		}

		if ($this->attributes)
		{
			$buffer .= sprintf(' %s', $this->attributes);
		}

		if ($this->charset)
		{
			$buffer .= sprintf(" CHARACTER SET %s", $this->charset);
		}

		if ($this->collation)
		{
			$buffer .= sprintf(' COLLATE %s', $this->collation);
		}

		if ($this->null == 'NO')
		{
			$buffer .= ' NOT NULL';
		}

		if ($this->default)
		{
			$buffer .= ' DEFAULT ';
			if (strtolower($this->default) == 'current_timestamp')
			{
				$buffer .= 'CURRENT_TIMESTAMP';
			}
			else
			{
				$buffer .= sprintf("'%s'", $this->default);
			}
		}

		if ($this->onupdate)
		{
			$buffer .= sprintf(" ON UPDATE %s", $this->onupdate);
		}

		return $buffer;
	}

}
