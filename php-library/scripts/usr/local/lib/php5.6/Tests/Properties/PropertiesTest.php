<?php
namespace Tests\Properties;

/*
 *		Properties\PropertiesTest is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 *
 * Library\Properties Tests
 *
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Properties
 */
class PropertiesTest extends \Application\Launcher\Testing\UtilityMethods
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
     * @param string $format = (optional) format for logging
     */
    public function assertionTests($logger=null, $format=null)
    {
    	parent::assertionTests($logger, $format);

		$this->a_newProperties();
		$this->a_properties();

		$this->a_updateProperty('Log_Value', 10);
		$this->a_updateProperty('Log_Name', '/home/jay/Development/PHPProjectLib/Tests/Log/TestLog.log');
		$this->a_updateProperty('Log_Format', 'log');
		$this->a_updateProperty('Log_SkipLevels', 4);

		$this->a_propertyValue('Log_Format', 'log');

		$this->a_exists('log_format', false);
		$this->a_exists('Log_Format', true);

		$this->a_listProperties();

		$this->a_updateProperty('Log_Format', 'timestamp');
		$this->a_listProperties();
		$this->a_printProperties();

		$properties = array('Log_Value'		=> 200,
		                    'File_Adapter'	=> 'fileobject',
							'File_Mode'		=> 'r');

		$this->a_updateProperties($properties);
		$this->a_printProperties();

		$this->a_deleteProperty('Log_Name');
		$this->a_printProperties();

		$properties = array('Log_Value',
		                    'File_Adapter',
							'File_Mode');

		$this->a_unsetProperties($properties);
		$this->a_printProperties();

		$this->a_properties();

		$this->a_updateArrayProperty('Log_Value', 999);
		$this->a_printProperties();

		$this->a_getArrayProperty('Log_Format', 'timestamp');
		
		$oldProperties = $this->a_properties;

		$this->a_newProperties($oldProperties);
		$this->a_properties($oldProperties->properties());

		$this->a_newProperties($oldProperties->properties());
		$this->a_properties($oldProperties->properties());
		
		$this->a_updateArrayProperty('New_Data', 'new data');
		$this->a_properties($oldProperties->properties(), false);

		$this->a_deleteAll();
		$this->a_printProperties();
    }

	/**
	 * a_getArrayProperty
	 *
	 * Get a property's value
	 * @param string $property = name of the property to update
	 * @param mixed $expected = value expected
	 */
	public function a_getArrayProperty($property, $expected)
	{
    	$this->labelBlock('Get Array Property Tests.', 60, '*');

    	$assertion = sprintf('$this->a_data = $this->a_properties["%s"];', $property);
		if (! $this->assertTrue($assertion, sprintf('Get Array Property - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
	}

	/**
	 * a_updateArrayProperty
	 *
	 * Update/Create a property's value
	 * @param string $property = name of the property to update
	 * @param mixed $value = value of the property to update
	 */
	public function a_updateArrayProperty($property, $value)
	{
    	$this->labelBlock('Update Array Property Tests.', 60, '*');

    	$this->a_property = $property;
    	$this->a_data = $value;

    	$assertion = sprintf('$this->a_properties["%s"] = $this->a_data;', $property);
		if (! $this->assertTrue($assertion, sprintf('Update Array Property - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

	}

	/**
	 * a_exists
	 *
	 * Check if the property exists
	 * @param string $property = name of the property to check for
	 * @param boolean $type = (optional) type of comparison, default = true
	 */
	public function a_exists($property, $type=true)
	{
    	$this->labelBlock('Exists Tests.', 60, '*');

    	$this->a_property = $property;

    	$assertion = sprintf('$this->a_properties->exists("%s");', $this->a_property);
    	if ($type)
    	{
			if (! $this->assertTrue($assertion, sprintf('Exists (true) - Asserting: %s', $assertion)))
			{
				$this->a_outputAndDie();
			}
    	}
    	else
    	{
			if (! $this->assertFalse($assertion, sprintf('Exists (false) - Asserting: %s', $assertion)))
			{
				$this->a_outputAndDie();
			}
    	}
    	
		$this->a_printArray($this->a_array);
	}

	/**
	 * a_properties
	 *
	 * Get an array containing all of the properties and their values
	 */
	public function a_properties($expected=null, $type=true)
	{
    	$this->labelBlock('Get Properties Tests.', 60, '*');

    	$assertion = '(($this->a_array = $this->a_properties->properties()) !== false);';
		if (! $this->assertTrue($assertion, sprintf('Get Properties - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		if ($expected)
		{
			$this->a_compareArray($this->a_array, $type, $expected);
		}

		$this->a_printArray($this->a_array);
	}

	/**
	 * a_unsetProperties
	 *
	 * Delete (unset) multiple properties from an array of property names
	 * @param array $array
	 */
	public function a_unsetProperties($array)
	{
    	$this->labelBlock('Unset Properties Tests.', 60, '*');

    	$this->a_array = $array;
    	$assertion = '$this->a_properties->unsetProperties($this->a_array);';
		if (! $this->assertExceptionFalse($assertion, sprintf('Unset Properties - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_deleteAll
	 *
	 * Delete all properties
	 */
	public function a_deleteAll()
	{
    	$this->labelBlock('Delete All Properties Tests.', 60, '*');

    	$assertion = '$this->a_properties->deleteAll();';
		if (! $this->assertFalse($assertion, sprintf('Delete All Properties - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_deleteProperty
	 *
	 * Delete property
	 * @param string $property
	 */
	public function a_deleteProperty($property)
	{
    	$this->labelBlock('Delete Properties Tests.', 60, '*');

    	$assertion = sprintf('$this->a_properties->delete("%s");', $property);
		if (! $this->assertFalse($assertion, sprintf('Delete Property - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_updateProperties
	 *
	 * Update/Create multiple properties from an associative array
	 * @param array $array
	 */
	public function a_updateProperties($array)
	{
    	$this->labelBlock('Update Properties Tests.', 60, '*');

    	$this->a_array = $array;
    	$assertion = '$this->a_properties->setProperties($this->a_array);';
		if (! $this->assertExceptionFalse($assertion, sprintf('Update Properties - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_printProperties
	 *
	 * Print all of the properties and their values
	 */
	public function a_printProperties()
	{
    	$this->labelBlock('Print Properties Tests.', 60, '*');

    	$assertion = '$this->a_data = sprintf("%s", $this->a_properties);';
		if (! $this->assertTrue($assertion, sprintf('Print Properties - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->assertLogMessage($this->a_data);
	}

	/**
	 * a_listProperties
	 *
	 * List all of the properties and their values
	 */
	public function a_listProperties()
	{
    	$this->labelBlock('List Properties Tests.', 60, '*');

    	$assertion = '$this->a_array = get_object_vars($this->a_properties);';
		if (! $this->assertTrue($assertion, sprintf('List Properties - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_printArray($this->a_array);
	}

	/**
	 * a_propertyValue
	 *
	 * Get a property's value
	 * @param string $property = name of the property
	 * @param mixed $expected = expected value
	 */
	public function a_propertyValue($property, $expected)
	{
    	$this->labelBlock('Property Value Tests.', 60, '*');

    	$this->a_property = $property;
    	$assertion = sprintf('$this->a_data = $this->a_properties->%s;', $property);
		if (! $this->assertTrue($assertion, sprintf('Property Value - Asserting: %s', $assertion)))
		{
			if ($this->a_data !== $expected)
			{
				$this->a_outputAndDie();
			}
		}

		$this->a_compareExpected($expected);
	}

	/**
	 * a_updateProperty
	 *
	 * Update/Create a property's value
	 * @param string $property = name of the property to update
	 * @param mixed $value = value of the property to update
	 */
	public function a_updateProperty($property, $value)
	{
    	$this->labelBlock('Update Property Tests.', 60, '*');

    	$this->a_property = $property;
    	$this->a_data = $value;

    	$assertion = sprintf('$this->a_properties->%s = $this->a_data;', $property);
		if (! $this->assertTrue($assertion, sprintf('Update Property - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

	}

	/**
	 * a_newProperties
	 *
	 * Create a new properties class object
	 */
	public function a_newProperties($properties=null)
	{
    	$this->labelBlock('NEW Properties Class Tests.', 60, '*');

    	$this->a_fromProperties = $properties;

    	$assertion = '$this->a_properties = new \Library\Properties($this->a_fromProperties);';
		if (! $this->assertTrue($assertion, sprintf('NEW Properties Class - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, 'Library\Properties', get_class($this->a_properties));
	}

}
