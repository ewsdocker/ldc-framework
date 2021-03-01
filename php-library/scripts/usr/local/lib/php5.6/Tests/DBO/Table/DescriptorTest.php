<?php
namespace Tests\DBO\Table;

use Library\CliParameters;
use Library\DBO\DBOConstants;

/*
 * 		DBO\Table\DescriptorTest is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * DBO\Table\DescriptorTest.
 *
 * DBO Table Descriptor class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage DBO.
 */

class DescriptorTest extends \Application\Launcher\Testing\UtilityMethods
{
	/**
	 * __construct
	 *
	 * Class constructor - pass parameters on to parent class
	 */
	public function __construct()
	{
		parent::__construct();
		$this->assertSetup(1, 0, 0, array('Application\Launcher\Testing\Base', 'assertCallback'));
	}

	/**
	 * __destruct
	 *
	 * Class destructor.
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * assertionTests
	 *
	 * run the current assertion test steps
	 * @parm string $logger = (optional) name of the logger to use, null for none
	 */
	public function assertionTests($logger=null, $format=null)
	{
		parent::assertionTests($logger, $format);

		$table = CliParameters::parameterValue('table', 'Users');

		$properties = array('name' 				=> $table,
							'ifNotExists' 		=> true,
							'storageEngine' 	=> 'innodb',
						    );

		$this->a_tableDescriptor($properties);
		$this->a_showDescriptor($this->a_tableDescriptor);

		$this->a_createColumns();
		$this->a_showDescriptor($this->a_tableDescriptor);

		$this->a_createKeys();
		$this->a_showDescriptor($this->a_tableDescriptor);
	}

	/**
	 * a_createKeys
	 *
	 * Create the keys for the table
	 */
	public function a_createKeys()
	{
		$this->labelBlock('Create Keys', 60, '*');

		$this->a_keyDescriptor('primary', 'id', array('id' => null));
		$this->a_addKey('id', $this->a_keyDescriptor);
		$this->a_showDescriptor($this->a_tableDescriptor);

		$this->a_keyDescriptor('unique', 'fullname', array('lastname' => null, 'firstname' => null, 'middlename' => null));
		$this->a_addKey('fullname', $this->a_keyDescriptor);
	}

	/**
	 * a_addKey
	 *
	 * Add the DBO\Table\Key Descriptor to the table descriptor
	 * @param mixed $name = key name
	 * @param object $descriptor = key descriptor to add
	 */
	public function a_addKey($name, $descriptor)
	{
		$this->labelBlock('Add Key', 40, '*');

		$this->a_name = $name;
		$this->a_addDescriptor = $descriptor;

   		$this->a_showData($this->a_name, 'a_name');
   		$this->a_showData($this->a_addDescriptor, 'a_addDescriptor');

		$assertion = '$this->a_data = $this->a_tableDescriptor->keyValue($this->a_name, $this->a_addDescriptor);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType(true, $this->a_addDescriptor);
	}

	/**
	 * a_charSet
	 *
	 * Set the charSet property
	 * @param string $charSet = (optional) charSet value, null to query
	 * @param string $defaultCharSet = (optional) default charSet, or null
	 */
	public function a_charSet($charSet=null, $defaultCharSet=null)
	{
		$this->labelBlock('charSet', 40, '*');

		$this->a_charSet = $charSet;
		$this->a_defaultCharSet = $defaultCharSet;

   		$this->a_showData($this->a_charSet,			'charSet');
   		$this->a_showData($this->a_defaultCharSet,	'defaultCharSet');

		$assertion = '$this->a_data = $this->a_tableDescriptor->charSet($this->a_charSet, $this->a_defaultCharSet);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_data, 'a_data');
		$this->a_compareExpectedType(true, $this->a_charSet);
	}

	/**
	 * a_showDescriptor
	 *
	 * Display the contents of the requested descriptor
	 * @param object $descriptor
	 */
	public function a_showDescriptor(&$descriptor)
	{
		$this->labelBlock('Show Descriptor', 40, '*');

		$this->a_showData($descriptor, get_class($descriptor));
		$this->a_showData((string)$descriptor);
	}

	/**
	 * a_createColumns
	 *
	 * Create an array of column descriptors to pass to the table descriptor
	 */
	public function a_createColumns()
	{
		$this->labelBlock('Create Columns', 60, '*');

		$this->a_createColumn(0, 'id', array('name' 		=> 'id',
											 'type' 		=> 'int(4)',
											 'null' 		=> 'NO',
											 'attributes'	=> 'AUTO_INCREMENT',
							  				 )
							  );
		$this->a_showDescriptor($this->a_tableDescriptor);

		$this->a_createColumn(1, 'lastname', array('name' 	=> 'lastname',
												   'type' 	=> 'varchar(36)',
												   'null' 	=> 'NO',
												   )
							  );

		$this->a_showDescriptor($this->a_tableDescriptor);

		$this->a_createColumn(2, 'firstname', array('name' => 'firstname',
													'type' 	=> 'varchar(24)',
													'null' 	=> 'NO',
													)
							  );

		$this->a_showDescriptor($this->a_tableDescriptor);

		$this->a_createColumn(3, 'middlename', array('name'	=> 'middlename',
													 'type'		=> 'varchar(24)',
													 'null' 	=> 'NO',
													 )
							  );

		$this->a_showDescriptor($this->a_tableDescriptor);

		$this->a_createColumn(4, 'sex', array('name' 	=> 'sex',
											  'type' 	=> 'char(1)',
											  'null' 	=> 'NO',
											  )
							  );

		$this->a_showDescriptor($this->a_tableDescriptor);

		$this->a_createColumn(5, 'birthdate', array('name' => 'birthdate',
													'type' => 'char(10)',
													'null' => 'NO',
													)
							  );

	}

	/**
	 * a_createColumn
	 *
	 * Create a new column descriptor and add to the table descriptor
	 * @param integer $columnNumber = column number
	 * @param string  $columnName = name of the column field
	 * @param array   $properties = array of column properties to set
	 */
	public function a_createColumn($columnNumber, $columnName, $properties)
	{
		$this->labelBlock('Create Column', 50, '*');

		$this->a_columnDescriptor($columnNumber, $properties);
		$this->a_showDescriptor($this->a_columnDescriptor);

		$this->a_addColumn($columnName, $this->a_columnDescriptor);
	}

	/**
	 * a_addColumn
	 *
	 * Add the DBO\Table\Column Descriptor to the table descriptor
	 * @param mixed $column = column name
	 * @param object $descriptor = column descriptor to add
	 */
	public function a_addColumn($column, $descriptor)
	{
		$this->labelBlock('Add Column', 40, '*');

		$this->a_column = $column;
		$this->a_addDescriptor = $descriptor;

   		$this->a_showData($this->a_column, 'a_column');
   		$this->a_showData($this->a_addDescriptor, 'a_addDescriptor');

		$assertion = '$this->a_data = $this->a_tableDescriptor->column($this->a_column, $this->a_addDescriptor);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType(true, $this->a_addDescriptor);
	}

	/**
	 * a_keyDescriptor
	 *
	 * Create a new DBO\Table\Key Descriptor class instance
	 * @param string $indexType = (optional) type of indes
	 * @param string $indexName = (optional) index name - if null, created from indexFields
	 * @param string $indexFields = (optional) array of field names and lengths making up the index (key)
	 */
	public function a_keyDescriptor($indexType='index', $indexName='', $indexFields=array())
	{
		$this->labelBlock('Key Descriptor', 40, '*');

		$this->a_indexType = $indexType;
		$this->a_indexName = $indexName;
		$this->a_indexFields = $indexFields;

   		$this->a_showData($this->a_indexType,	'a_indexType');
   		$this->a_showData($this->a_indexName,	'a_indexName');
   		$this->a_showData($this->a_indexFields,	'a_indexFields');

		$assertion = '$this->a_keyDescriptor = new \Library\DBO\Table\Key\Descriptor($this->a_indexType, $this->a_indexName, $this->a_indexFields);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_keyDescriptor, 'a_keyDescriptor');
	}

	/**
	 * a_columnDescriptor
	 *
	 * Create a new DBO\Table\Column Descriptor class instance
	 * @param mixed $column = column name
	 * @param array $records = (optional) array of column values
	 */
	public function a_columnDescriptor($column, $records=array())
	{
		$this->labelBlock('Column Descriptor', 40, '*');

		$this->a_column = $column;
		$this->a_records = $records;

   		$this->a_showData($this->a_column,	'a_column');
   		$this->a_showData($this->a_records,	'a_records');

		$assertion = '$this->a_columnDescriptor = new \Library\DBO\Table\Column\Descriptor($this->a_column, $this->a_records);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_columnDescriptor, 'a_columnDescriptor');
	}

	/**
	 * a_tableDescriptor
	 *
	 * Create a new DBO\Table Descriptor class instance
	 * @param array $properties = array of properties to pass to the constructor
	 */
	public function a_tableDescriptor($properties)
	{
		$this->labelBlock('Table Descriptor', 60, '*');

		$this->a_properties = $properties;

		$this->a_showData($this->a_properties, 'a_properties');

//$this->a_descriptor = new \Library\DBO\Table\Descriptor($this->a_properties);
//return;
		$assertion = '$this->a_tableDescriptor = new \Library\DBO\Table\Descriptor($this->a_properties);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_tableDescriptor, 'a_tableDescriptor');
	}

}
