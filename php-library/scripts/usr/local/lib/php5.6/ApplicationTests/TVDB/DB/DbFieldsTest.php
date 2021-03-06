<?php
namespace ApplicationTests\TVDB\DB;

use Library\CliParameters;
use Library\DBO\DBOConstants;

use ApplicationTests\TVDB\TVDBTestLib;

/*
 * 		TVDB\DB\DbFieldsTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * TVDB\DB\DbFieldsTest.
 *
 * TVDB DB DbFields class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage TVDB.
 */

class DbFieldsTest extends TVDBTestLib
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
		$table = "Banners";
		$this->a_xmlFile = '';

		parent::assertionTests($logger, $format);

		$this->a_newDbFields($table);

		$this->a_add('index', 'int(7)', true, null);
		$this->a_add('Series', 'int(7)', true, null);
		$this->a_add('data', 'varchar(2048)', false, 'Hello there');

		$this->a_iterateDbFields();
		$this->a_showData((string)$this->a_dbFields, 'DbFields');

		$this->a_columns();
		$this->a_column(1);

		$this->a_fields();
		$this->a_field('Series');

		$this->a_dbFields = null;
	}

	/**
	 * a_field
	 *
	 * Get the requested field from the fields array
	 */
	public function a_field($field)
	{
		$this->labelBlock('Field.', 40, '*');

		$this->a_field = $field;
		$this->a_showData($this->a_field, 'field');

		$assertion = '$this->a_field = $this->a_dbFields->field($this->a_field);';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_field, 'Field');
		$this->a_showData((string)$this->a_field);
	}

	/**
	 * a_fields
	 *
	 * Get the fields array
	 */
	public function a_fields()
	{
		$this->labelBlock('Fields.', 40, '*');

		$assertion = '$this->a_localArray = $this->a_dbFields->fields();';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_localArray, 'Fields');
	}

	/**
	 * a_column
	 *
	 * Get the requested column from the columns array
	 */
	public function a_column($column)
	{
		$this->labelBlock('Column.', 40, '*');

		$this->a_column = $column;
		$this->a_showData($this->a_column, 'column');

		$assertion = '$this->a_column = $this->a_dbFields->column($this->a_column);';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_column, 'Column');
		$this->a_showData((string)$this->a_column);
	}

	/**
	 * a_columns
	 *
	 * Get the columns array
	 */
	public function a_columns()
	{
		$this->labelBlock('Columns.', 40, '*');

		$assertion = '$this->a_localArray = $this->a_dbFields->columns();';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_localArray, 'Columns');
	}

	/**
	 * a_add
	 *
	 * Add a new field
	 * @param string $field = field name
	 * @param string $definition = field definition string
	 * @param bool $null = true if null allowed, false if not
	 * @param mixed $default = default field value (initial value)
	 * @return integer $column = assigned field column number
	 */
	public function a_add($field, $definition, $null, $default)
	{
		$this->labelBlock('Add Field.', 40, '*');

		$this->a_field      = $field;
		$this->a_definition = $definition;
		$this->a_null       = $null;
		$this->a_default    = $default;

		$this->a_showData($this->a_field,		'field');
		$this->a_showData($this->a_definition,	'definition');
		$this->a_showData($this->a_null,   		'null');
		$this->a_showData($this->a_default, 	'default');

		$assertion = '(($this->a_data = $this->a_dbFields->add($this->a_field, $this->a_definition, $this->a_null, $this->a_default)) !== null);';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_data, 'Column');
	}

	/**
	 * a_iterateDbFields
	 *
	 * Iterate the fields in the fieldDescriptor
	 */
	public function a_iterateDbFields()
	{
		$this->labelBlock('Iterate DbFields START', 50, '*');

		foreach($this->a_dbFields as $column => $value)
		{
			$this->a_showData($column, sprintf("\t" . '%s', $value));
		}

		$this->labelBlock('Iterate DbFields END', 50, '*');
	}

	/**
	 * a_newDbFields
	 *
	 * Create a new DbFields Descriptor class instance
	 * @param string $table = table name
	 */
	public function a_newDbFields($table)
	{
		$this->labelBlock('New DbFields Descriptor.', 60, '*');

		$this->a_table = $table;
   		$this->a_showData($this->a_table, 'table');

		$assertion = '$this->a_dbFields = new \Application\TVDB\DB\DbFields($this->a_table);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_dbFields, 'DbFields Descriptor');
	}

}
