<?php
namespace Tests\DBO\Table;

use Library\CliParameters;
use Library\DBO\DBOConstants;

/*
 * 		DBO\Table\BuildDescriptorTest is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * DBO\Table\BuildDescriptorTest.
 *
 * DBO Table Descriptor class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage DBO.
 */

class BuildDescriptorTest extends \Application\Launcher\Testing\UtilityMethods
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

		$fieldTypes = array('id' 			=> 'int(7)',
							'BannerPath'	=> 'varchar(1024)',
							'ThumbnailPath'	=> 'varchar(1024)',
							'VignettePath'	=> 'varchar(1024)',
							'BannerType'	=> 'varchar(16)',
							'BannerType2'	=> 'varchar(256)',
							'Language'		=> 'char(2)',
							'Season'		=> 'int(3)',
							'Rating'		=> 'decimal(6,4) UNSIGNED',
							'RatingCount'	=> 'int(7)',
							'SeriesName'	=> 'tinyint(1)',
							'Colors'		=> 'varchar(16)',
							);

		$fieldNullAllowed = array('id'				=> false,
								  'BannerPath'		=> false,
								  'ThumbnailPath'	=> true,
								  'VignettePath'	=> true,
								  'BannerType'		=> false,
								  'BannerType2'		=> true,
								  'Language'		=> false,
								  'Season'			=> true,
								  'Rating'			=> true,
								  'RatingCount'		=> false,
								  'SeriesName'		=> true,
								  'Colors'			=> true,
								  );

		$this->a_newDescriptor($properties, $fieldTypes, $fieldNullAllowed);
		$this->a_showDescriptor($this->a_buildDescriptor);

		$this->a_getTableDescriptor('Library\DBO\Table\Descriptor');
		$this->a_showDescriptor($this->a_tableDescriptor);

		$this->a_newDescriptor($properties);
		$this->a_setFieldTypes($fieldTypes);
		$this->a_setFieldNulls($fieldNullAllowed);

		$this->a_buildDescriptor();
		$this->a_showDescriptor($this->a_buildDescriptor);

		$this->a_tableDescriptor = null;

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
	 * a_setProperties
	 *
	 * Set the Properties array
	 */
	public function a_setProperties($properties)
	{
		$this->labelBlock('Set Properties', 40, '*');

		$this->a_properties = $properties;
		$this->a_showData($this->a_properties, 'Properties');

		$assertion = '$this->a_data = $this->a_buildDescriptor->properties($this->a_properties);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_setFieldTypes
	 *
	 * Set the FieldTypes array
	 */
	public function a_setFieldTypes($fieldTypes)
	{
		$this->labelBlock('Set Field Types', 40, '*');

		$this->a_fieldTypes = $fieldTypes;
		$this->a_showData($this->a_fieldTypes, 'Field Types');

		$assertion = '$this->a_data = $this->a_buildDescriptor->fieldTypes($this->a_fieldTypes);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_setFieldNulls
	 *
	 * Set the FieldTypes array
	 */
	public function a_setFieldNulls($fieldNullAllowed)
	{
		$this->labelBlock('Set Field Nulls', 40, '*');

		$this->a_fieldNullAllowed = $fieldNullAllowed;
		$this->a_showData($this->a_fieldNullAllowed, 'Field Null Allowed');

		$assertion = '$this->a_data = $this->a_buildDescriptor->fieldNullAllowed($this->a_fieldNullAllowed);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_getTableDescriptor
	 *
	 * Get the Table Descriptor class instance
	 * @param string $expected = class expected to be returned
	 */
	public function a_getTableDescriptor($expected)
	{
		$this->labelBlock('Get Table Descriptor', 50, '*');

		$this->a_expected = $expected;
		$this->a_showData($this->a_expected, 'a_expected');

		$assertion = '$this->a_tableDescriptor = $this->a_buildDescriptor->tableDescriptor();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}


	/**
	 * a_buildDescriptor
	 *
	 * Build the DBO\Table Descriptor
	 * @param array $properties = array of properties to pass to the constructor
	 */
	public function a_buildDescriptor($properties=null, $fieldTypes=null, $fieldNullAllowed=null)
	{
		$this->labelBlock('Table Descriptor', 60, '*');

		$this->a_properties = $properties;
		$this->a_fieldTypes = $fieldTypes;
		$this->a_fieldNullAllowed = $fieldNullAllowed;

		$this->a_showData($this->a_properties, 'a_properties');
		$this->a_showData($this->a_fieldTypes, 'a_fieldTypes');
		$this->a_showData($this->a_fieldNullAllowed, 'a_fieldNullAllowed');

$this->a_tableDescriptor = $this->a_buildDescriptor->buildDescriptor($this->a_properties, $this->a_fieldTypes, $this->a_fieldNullAllowed);
/*
		$assertion = '$this->a_tableDescriptor = $this->a_buildDescriptor->buildDescriptor($this->a_properties, $this->a_fieldTypes, $this->a_fieldNullAllowed);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
*/
	}

	/**
	 * a_newDescriptor
	 *
	 * Create a new DBO\Table Descriptor class instance
	 * @param array $properties = array of properties to pass to the constructor
	 */
	public function a_newDescriptor($properties=null, $fieldTypes=null, $fieldNullAllowed=null)
	{
		$this->labelBlock('Table Descriptor', 60, '*');

		$this->a_properties = $properties;
		$this->a_fieldTypes = $fieldTypes;
		$this->a_fieldNullAllowed = $fieldNullAllowed;

		$this->a_showData($this->a_properties, 'a_properties');
		$this->a_showData($this->a_fieldTypes, 'a_fieldTypes');
		$this->a_showData($this->a_fieldNullAllowed, 'a_fieldNullAllowed');

$this->a_buildDescriptor = new \Library\DBO\Table\BuildDescriptor($this->a_properties, $this->a_fieldTypes, $this->a_fieldNullAllowed);
/*
		$assertion = '$this->a_buildDescriptor = new \Library\DBO\Table\BuildDescriptor($this->a_properties, $this->a_fieldTypes, $this->a_fieldNullAllowed);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
*/
		$this->a_showData($this->a_buildDescriptor, 'a_buildDescriptor');
	}

}
