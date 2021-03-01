<?php
namespace Application\TVDB\DB;

use Library\DBO\DBOConstants as DBOConstants;
use Library\Error;
use Library\MySql\Table;
use Library\Utilities\FormatVar;

/*
 *		Application\TVDB\DB\DbTable is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\DB\DbTable
 *
 * TVDB Banner Data Record(s)
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage DB
 */
class DbTable extends Table
{
	/**
	 * dataLink
	 *
	 * Link to the xml object
	 * @var object $dataLink
	 */
	public $dataLink;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $table = Table name
	 * @param string $dsn = Data Source Name (mysql:host=<hostname>;port=<connection port>;dbname=<database name>)
	 * @param string $user = user name
	 * @param string $password = password
	 * @param array  $options = (optional) options array
	 * @throws \Library\MySql\Exception, \Library\DBO\Exception
	 */
	public function __construct($table, $dsn, $user, $password, $options=array())
	{
		if ($options && is_array($options) && in_array('DBO_OPTION_CREATE_TABLE', $options))
		{
			$createTable = true;
			unset($options[array_search('DBO_OPTION_CREATE_TABLE', $options)]);
		}
		else
		{
			$createTable = false;
		}

		$this->table = $table;

		parent::__construct($dsn, $user, $password, $options);

		$this->dataLink = null;

		$this->tableName = $table;
		if ((! $this->tableExists($this->tableName)) && $createTable)
		{
			$this->tableProperties = array('name' 			=> $this->tableName,
						 			       'ifNotExists' 	=> true,
									 	   'storageEngine'	=> 'innodb',
									       );
/*
			$fieldClass = sprintf("Application\TVDB\DB\Fields\%s", $this->tableName);
			$this->fieldDescriptors = new $fieldClass();
*/
			$this->getFieldDescriptors();

			$tableDescriptor = $this->buildTableDescriptor();
			$this->createTable($tableDescriptor);
		}

	}

	/**
	 * getFieldDescriptors
	 *
	 * Load the field descriptors for the current table
	 * @return object $fieldDescriptors = Fields/<tableName> class instance containing field descriptors
	 */
	public function getFieldDescriptors()
	{
		$fieldClass = sprintf("Application\TVDB\DB\Fields\%s", $this->tableName);
		$this->fieldDescriptors = new $fieldClass();

		return $this->fieldDescriptors;
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * __toString
	 *
	 * Return printable string
	 * @return string $buffer
	 */
	public function __toString()
	{
		return FormatVar::format(get_object_vars($this), get_class($this));
	}

	/**
	 * updateData
	 *
	 * Update the database
	 * @param mixed $SeriesId = TVDB series id
	 * @param object $dataLink = (optional) data xml array object, use current setting if null
	 * @throws Exception
	 */
	public function updateData($SeriesId=null, $dataLink=null)
	{
		if (($dataLink = $this->dataLink($dataLink)) == null)
		{
			throw new Exception(Error::code('MissingClassObject'));
		}

		$this->getFieldDescriptors();

		$sql = sprintf("Update `%s` set ", $this->tableName);

		$fieldCount = 0;
		foreach($this->fieldDescriptors as $field => $descriptor)
		{
			$fieldName = $descriptor->name;

			if ($fieldName === 'recordNumber')
			{
				continue;
			}

			if (($fieldName === 'seriesid') && ($this->tableName == 'Series'))
			{
				$fieldName = 'SeriesId';
			}

			if ($fieldName === 'SeriesId')
			{
				if ($SeriesId === null)
				{
					continue;
				}
			}

			if ($fieldCount > 0)
			{
				$sql .= ', ';
			}

			$sql .= sprintf("`%s`=?", $fieldName);

			$fieldCount++;
		}

		$sql .= " where recordNumber=?";

		foreach($this->fieldDescriptors as $field => $descriptor)
		{
			$fieldName = $descriptor->name;

			if ($fieldName === 'SeriesId')
			{
				if (($this->tableName !== 'Series') || ($SeriesId === null))
				{
					continue;
				}
			}

			$$fieldName = $descriptor->default;
		}

		$statement = $this->prepare($sql);

		foreach($this->fieldDescriptors as $field => $descriptor)
		{
			$fieldName = $descriptor->name;
			if ($fieldName === 'recordNumber')
			{
				continue;
			}

			switch(substr($descriptor->type, 0, 3))
			{
				case 'int':
				default:
					$statement->bindParam($fieldName, $$fieldName, DBOConstants::TYPE_INTEGER);
					break;

				case 'var':
					$statement->bindParam($fieldName, $$fieldName, DBOConstants::TYPE_STRING);
					break;

				case 'cha':
					$statement->bindParam($fieldName, $$fieldName, DBOConstants::TYPE_STRING);
					break;

				case 'dec':
					$statement->bindParam($fieldName, $$fieldName, DBOConstants::TYPE_DOUBLE);
					break;
			}

		}

		$statement->bindParam('recordNumber', $recordNumber, DBOConstants::TYPE_INTEGER);

		foreach($dataLink as $index => $record)
		{
			foreach($this->fieldDescriptors as $field => $descriptor)
			{
				$fieldName = $descriptor->name;

				if (($fieldName === 'SeriesId') && (($SeriesId === null) || ($this->tableName !== 'Series')))
				{
					continue;
				}

				if ($record->exists($fieldName))
				{
					$$fieldName = $record[$fieldName];
				}
				else
				{
					$$fieldName = $descriptor->default;
				}
			}

			$statement->execute();
		}

		$statement->closeCursor();
		$dataLink = null;
	}

	/**
	 * InsertData
	 *
	 * Update record(s) into the database
	 * @param mixed $SeriesId = TVDB series id
	 * @param object $dataLink = (optional) data xml array object, use current setting if null
	 * @throws Exception
	 */
	public function insertData($SeriesId=null, $dataLink=null)
	{
		if (($dataLink = $this->dataLink($dataLink)) == null)
		{
			throw new Exception(Error::code('MissingClassObject'));
		}

		$this->getFieldDescriptors();

		$sql = sprintf("Insert into `%s` (", $this->tableName);

		$fieldList = '';
		$fieldCount = 0;
		foreach($this->fieldDescriptors as $field => $descriptor)
		{
			$fieldName = $descriptor->name;

			if ($fieldName === 'recordNumber')
			{
				continue;
			}

			if (($fieldName === 'seriesid') && ($this->tableName == 'Series'))
			{
				$fieldName = 'SeriesId';
			}

			if ($fieldName === 'SeriesId')
			{
				if ($SeriesId === null)
				{
					continue;
				}
			}

			if ($fieldCount > 0)
			{
				$fieldList .= ', ';
			}

			$fieldList .= sprintf("`%s`", $fieldName);

			$fieldCount++;
		}

		$sql .= sprintf("%s) values ", $fieldList);
		$sql .= "(?" . str_repeat(",?", $fieldCount - 1) . ")";

		foreach($this->fieldDescriptors as $field => $descriptor)
		{
			$fieldName = $descriptor->name;

			if ($fieldName === 'SeriesId')
			{
				if (($this->tableName !== 'Series') || ($SeriesId === null))
				{
					continue;
				}
			}

			$$fieldName = $descriptor->default;
		}

		$statement = $this->prepare($sql);

		foreach($this->fieldDescriptors as $field => $descriptor)
		{
			$fieldName = $descriptor->name;
			if ($fieldName === 'recordNumber')
			{
				continue;
			}

			switch(substr($descriptor->type, 0, 3))
			{
				case 'int':
				default:
					$statement->bindParam($fieldName, $$fieldName, DBOConstants::TYPE_INTEGER);
					break;

				case 'var':
					$statement->bindParam($fieldName, $$fieldName, DBOConstants::TYPE_STRING);
					break;

				case 'cha':
					$statement->bindParam($fieldName, $$fieldName, DBOConstants::TYPE_STRING);
					break;

				case 'dec':
					$statement->bindParam($fieldName, $$fieldName, DBOConstants::TYPE_DOUBLE);
					break;
			}

		}

		foreach($dataLink as $index => $record)
		{
			foreach($this->fieldDescriptors as $field => $descriptor)
			{
				$fieldName = $descriptor->name;

				if (($fieldName === 'SeriesId') && (($SeriesId === null) || ($this->tableName !== 'Series')))
				{
					continue;
				}

				if ($record->exists($fieldName))
				{
					$$fieldName = $record[$fieldName];
				}
				else
				{
					$$fieldName = $descriptor->default;
				}
			}

			$statement->execute();
		}

		$statement->closeCursor();
		$dataLink = null;
	}

	/**
	 * dataLink
	 *
	 * Get/set dataLink records object
	 * @param object $dataLink = XML data object class instance, null to query only
	 * @return object $dataLink = XML data object class instance
	 * @throws Exception if not a XML data object instance
	 */
	public function dataLink($dataLink=null)
	{
		if ($dataLink !== null)
		{
			$this->dataLink = $dataLink;
		}

		return $this->dataLink;
	}

}
