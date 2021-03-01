<?php
namespace Library\MySql;

use Library\DBO\Table\BuildDescriptor;
use Library\MySql\Exception;

use Library\Error;

/*
 * 		MySql\Table is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * MySql\Table
 *
 * @author Jay Wheeler.
 * @version 2.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage MySql.
 */
class Table extends Driver
{
	/**
	 * tableDescriptor
	 *
	 * Current table descriptor created from tableData, columnData and keyData
	 * @var \Library\Table\Descriptor $tableDescriptor
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
	 * table
	 *
	 * Name of the table - SET PRIOR TO CALLING CONSTRUCTOR - do not init in the constructor
	 * @var string $table = table name
	 */
	protected $table;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $dsn = Data Source Name (mysql:host=<hostname>;port=<connection port>;dbname=<database name>)
	 * @param string $user = user name
	 * @param string $password = password
	 * @param array  $options = (optional) options array
	 * @throws \Library\MySql\Exception, \Library\DBO\Exception
	 */
	public function __construct($dsn, $user, $password, $options=array())
	{
		parent::__construct($dsn, $user, $password, $options);

		$this->tableDescriptor = null;
		$this->tableProperties = array('name' => $this->table, 'ifNotExists' => true, 'storageEngine' => 'innodb');
		$this->fields = array();
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
	 * createTable
	 *
	 * create a database table from a DBO\Table\Descriptor instance
	 * @param object $tableDescriptor = table descriptor
	 * @return boolean true
	 * @throws \Library\DBO\Table\Exeption, Library\MySql\Exception, \mysqli_exception
	 */
	public function createTable($tableDescriptor=null)
	{
		if ($this->tableDescriptor($tableDescriptor) == null)
		{
			throw new Exception(Error::code('InvalidClassObject'));
		}

		$this->exec((string)$this->tableDescriptor);
		return true;
	}

	/**
	 * dropTable
	 *
	 * Drop the requested table
	 * @param string $tableName = (optional) table name, null to use current tableDescriptor->name
	 * @return boolean true
	 * @throws Exception
	 */
	public function dropTable($tableName=null)
	{
		if ($tableName == null)
		{
			if ($this->tableDescriptor == null)
			{
				if ($this->table == null)
				{
					throw new Exception(Error::code('DbMissingTableName'));
				}
			}

			$tableName = $this->tableDescriptor->name;
		}

		if (! is_array($tableName))
		{
			$tableName = array($tableName);
		}

		$sql = sprintf('DROP TABLE IF EXISTS %s', implode(',', $tableName));

		$this->exec($sql);

		return true;
	}

	/**
	 * tableExists
	 *
	 * Check if the requested table exists in the current database
	 * @param string $tableName = name of the table to check for
	 * @return boolean $result = true if table exists, false if not
	 */
	public function tableExists($tableName)
	{
		$sql = sprintf('SELECT 1 FROM `%s` LIMIT 0', $tableName);
		try
		{
			if ($this->query($sql))
			{
				return true;
			}
		}
		catch(\mysqli_exception $exception)
		{
			;
		}
		catch(\Exception $exception)
		{
			;
		}

		return false;
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
			if (! is_object($tableDescriptor))
			{
				throw new Exception(Error::code('ObjectExpected'));
			}

			if (get_class($tableDescriptor) !== 'Library\DBO\Table\Descriptor')
			{
				throw new Exception(Error::code('DbTableDescriptorExpected'));
			}

			$this->tableDescriptor = $tableDescriptor;
		}

		return $this->tableDescriptor;
	}

	/**
	 * buildTableDescriptor
	 *
	 * Build a table descriptor for use in create table
	 * @param array $tableProperties = table properties array
	 * @param array $fieldDescriptors = field types array
	 * @return object $tableDescriptor = table descriptor created from parameters
	 * @throws Exception
	 */
	public function buildTableDescriptor($tableProperties=null, $fieldDescriptors=null)
	{
		if (($tableProperties = $this->tableProperties($tableProperties)) == null)
		{
			throw new Exception(Error::code('DbMissingTableProperties'));
		}

		if (($fieldDescriptors = $this->fieldDescriptors($fieldDescriptors)) == null)
		{
			throw new Exception(Error::code('DbTableColumnsMissing'));
		}

		$descriptor = new BuildDescriptor($tableProperties, $fieldDescriptors);
		return $this->tableDescriptor($descriptor->tableDescriptor());
	}

	/**
	 * fields
	 *
	 * Get/set the fields array property
	 * @param  $fields = (optional) fields array property, null to query only
	 * @return array $fields = current fields array property
	 */
	public function fields($fieldDescriptors=null)
	{
		if ($fields !== null)
		{
			$this->fields = $fields;
		}

		return $this->fields;
	}

	/**
	 * fieldDescriptors
	 *
	 * Get/set the fieldDescriptors array property
	 * @param  $fieldDescriptors = (optional) fieldDescriptors array property, null to query only
	 * @return array $fieldDescriptors = current fieldDescriptors array property
	 */
	public function fieldDescriptors($fieldDescriptors=null)
	{
		if ($fieldDescriptors !== null)
		{
			$this->fieldDescriptors = $fieldDescriptors;
		}

		return $this->fieldDescriptors;
	}

	/**
	 * tableProperties
	 *
	 * Get/set the tableProperties property
	 * @param  $tableProperties = (optional) tableProperties array property, null to query only
	 * @return array $tableProperties = current tableProperties array property
	 */
	public function tableProperties($tableProperties=null)
	{
		if ($tableProperties !== null)
		{
			$this->tableProperties = $tableProperties;
		}

		return $this->tableProperties;
	}

}
