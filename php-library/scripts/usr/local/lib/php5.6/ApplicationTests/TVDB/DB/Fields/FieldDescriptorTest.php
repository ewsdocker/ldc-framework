<?php
namespace ApplicationTests\TVDB\DB\Fields;

use Library\CliParameters;
use Library\DBO\DBOConstants;

/*
 * 		TVDB\DB\Fields\FieldDescriptorTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * TVDB\DB\Fields\FieldDescriptorTest.
 *
 * TVDB DB Fields\FieldDescriptor class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage TVDB.
 */

class FieldDescriptorTest extends \Application\Launcher\Testing\UtilityMethods
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

		$table = "NewTest";
		$this->a_newFieldDescriptor('index', 'int(7)', true, null, 0);
		$this->a_iterateFields();
		$this->a_fieldArray();

		$this->a_showData($this->a_fieldDescriptor, 'Field Descriptor');
		$this->a_fieldDescriptor = null;

		$this->a_newFieldDescriptor('data', 'varchar(2048)', false, 'Hello there', 1);
		$this->a_iterateFields();
		$this->a_fieldArray();
		
		$this->a_showData($this->a_fieldDescriptor, 'Field Descriptor');
		$this->a_fieldDescriptor = null;
	}

	/**
	 * a_fieldArray
	 *
	 * Get the fieldDescriptor class instance as an array
	 */
	public function a_fieldArray()
	{
		$this->labelBlock('Field Array.', 40, '*');

		$assertion = '$this->a_fieldArray = $this->a_fieldDescriptor->properties();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		if (! is_array($this->a_fieldArray))
		{
			$this->a_outputAndDie("Returned result from properties method is not an array");
		}

		$this->a_showData($this->a_fieldArray, 'Field Array');
	}

	/**
	 * a_iterateFields
	 *
	 * Iterate the fields in the fieldDescriptor
	 */
	public function a_iterateFields()
	{
		$this->labelBlock('Iterate Fields', 50, '*');

		foreach($this->a_fieldDescriptor as $field => $value)
		{
			$this->a_showData($field, sprintf("\t" . '%s', $value));
		}
	}

	/**
	 * a_newFieldDescriptor
	 *
	 * Create a new TVDB database table fieldDescriptor class instance
	 * @param string  $name      = field name
	 * @param string  $definition = field definition string
	 * @param bool    $null       = true if null allowed, false if not
	 * @param mixed   $default    = default field value (initial value)
	 * @param integer $column     = column number
	 */
	public function a_newFieldDescriptor($name, $definition, $null, $default, $column)
	{
		$this->labelBlock('New Field Descriptor.', 60, '*');

		$this->a_name      	= $name;
		$this->a_definition = $definition;
		$this->a_null       = $null;
		$this->a_default    = $default;
		$this->a_column		= $column;

		$this->a_showData($this->a_name,		'name');
		$this->a_showData($this->a_definition,	'definition');
		$this->a_showData($this->a_null,		'null');
		$this->a_showData($this->a_default, 	'default');
		$this->a_showData($this->a_column,		'column');

		$assertion = '$this->a_fieldDescriptor = new \Application\TVDB\DB\Fields\FieldDescriptor($this->a_name, $this->a_definition, $this->a_null, $this->a_default, $this->a_column);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_fieldDescriptor, 'Field Descriptor');
	}

}
