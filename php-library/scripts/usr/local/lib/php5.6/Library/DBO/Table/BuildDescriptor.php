<?php
namespace Library\DBO\Table;

use Library\DBO\Table\Descriptor as TableDescriptor;
use Library\DBO\Table\Column\Descriptor as ColumnDescriptor;
use Library\DBO\Table\Key\Descriptor as KeyDescriptor;
use Library\Error;
use Library\Properties;

/*
 *		Library\DBO\Table\BuildDescriptor is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Library\DBO\Table\BuildDescriptor
 *
 * Build a Table Descriptor
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package DBO
 * @subpackage Table
 */
class BuildDescriptor
{
	/**
	 * tableDescriptor
	 *
	 * The table descriptor
	 * @var object $tableDescriptor
	 */
	protected $tableDescriptor;

	/**
	 * tableProperties
	 *
	 * Array of table parameters for the tableDescriptor
	 * @var array $tableProperties
	 */
	protected $tableProperties;

	/*
	 * Field Descriptors
	 *
	 * List of field names and associated type/size
	 * @var array $fieldDescriptors;
	 */
	protected $fieldDescriptors;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param object $tableProperties = (optional) properties describing the table
	 * @param array $fieldDescriptors = (optional) fieldDescriptors array
	 * @throws Library\DBO\Table\Exception
	 */
	public function __construct($tableProperties=null, $fieldDescriptors=null)
	{
		try
		{
			$this->buildDescriptor($tableProperties, $fieldDescriptors);
		}
		catch(Exception $exception)
		{
			if ($exception->getCode() !== Error::code('MissingRequiredProperties'))
			{
				throw $exception;
			}
		}
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
		if ($this->tableDescriptor)
		{
			return (string)$this->tableDescriptor;
		}

		return '';
	}

	/**
	 * buildDescriptor
	 *
	 * Build a table descriptor
	 * @param object $tableProperties = (optional) tableProperties describing the table
	 * @param array $fieldDescriptors = (optional) fieldDescriptors array
	 * @throws Exception, DBO\Exception, DBO\Table\Exception, \mysqli_exception
	 */
	public function buildDescriptor($tableProperties=null, $fieldDescriptors=null)
	{
		$tableProperties  = $this->tableProperties($tableProperties);
		$fieldDescriptors = $this->fieldDescriptors($fieldDescriptors);

		if (($tableProperties == null) || ($fieldDescriptors == null))
		{
			throw new Exception(Error::code('MissingRequiredProperties'));
		}

		$this->tableDescriptor = new TableDescriptor($tableProperties->properties());
		$this->tableDescriptor->keyValue('recordNumber', new KeyDescriptor('primary', 'recordNumber', array('recordNumber' => null)));

		$column = 0;
		foreach($fieldDescriptors as $field => $descriptor)
		{
			$this->tableDescriptor->column($field, new ColumnDescriptor($column++, array('name' => $descriptor->name,
								  														 'type' => $descriptor->type,
								  														 'null' => $descriptor->null ? '' : "NO",
								  														 )));
		}

		return $this->tableDescriptor;
	}

	/**
	 * fieldDescriptors
	 *
	 * Set/get the fieldDescriptors array property
	 * @param object $fieldDescriptors = (optional) instance object containing field descriptions, null to get only
	 * @return object $fieldDescriptors = current fieldDescriptors properties
	 * @throws Library\DBO\Table\Exception
	 */
	public function fieldDescriptors($fieldDescriptors=null)
	{
		if ($fieldDescriptors !== null)
		{
			if (is_array($fieldDescriptors))
			{
				$fieldDescriptors = new Properties($fieldDescriptors);
			}

			if (! is_object($fieldDescriptors))
			{
				throw new Exception(Error::code('ObjectExpected'));
			}

			$this->fieldDescriptors = $fieldDescriptors;
		}

		return $this->fieldDescriptors;
	}

	/**
	 * tableProperties
	 *
	 * Set/get the tableProperties array
	 * @param array $tableProperties = (optional) array containing table properties, null to get only
	 * @return array $tableProperties = current tableProperties array
	 * @throws Library\DBO\Table\Exception
	 */
	public function tableProperties($tableProperties=null)
	{
		if ($tableProperties !== null)
		{
			if (is_array($tableProperties))
			{
				$tableProperties = new Properties($tableProperties);
			}

			if ((! is_object($tableProperties)) || (get_class($tableProperties) != 'Library\Properties'))
			{
				throw new Exception(Error::code('MissingParametersArray'));
			}

			$this->tableProperties = $tableProperties;
		}

		return $this->tableProperties;
	}

	/**
	 * tableDescriptor
	 *
	 * Get/set table descriptor
	 * @param object $tableDescriptor = table descriptor, null to query only
	 * @return object $tableDescriptor = table descriptor
	 * @throws Exception if not a table descriptor instance
	 */
	public function tableDescriptor($tableDescriptor=null)
	{
		if ($tableDescriptor !== null)
		{
			if ((! is_object($tableDescriptor)) || (get_class($tableDescriptor) !== 'Library\DBO\Table\Descriptor'))
			{
				throw new Exception(Error::code('ObjectExpected'));
			}

			$this->tableDescriptor = $tableDescriptor;
		}

		return $this->tableDescriptor;
	}

	/**
	 * tableName
	 *
	 * Set/get the table name
	 * @param string $tableName = (optional) table name to set, null to query only
	 * @returns string $tableName = current table name
	 */
	public function tableName($tableName=null)
	{
		if ($tableName !== null)
		{
			$this->tableName = $tableName;
		}

		return $this->tableName;
	}

}
