<?php
namespace Tests\Select;

use \Application\Launcher\Testing\UtilityMethods;

use Library\Select\Descriptor;

/*
 *		DescriptorTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *		Refer to the file named License.txt provided with the source, 
 *			or from http://opensource.org/licenses/academic.php
*/
/**
 * Select\DescriptorTest
 *
 * Select\Storage class tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Select
 */
class DescriptorTest extends UtilityMethods
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

		$this->a_newDescriptor(array('name' 	=> 'stdin',
								     'type'		=> 'read',
									 'resource'	=> STDIN,
									 'enabled'	=> false,
									 'ready'	=> false,
									 'callback'	=> null,
									 'buffer'	=> '',
									 'index'	=> 0,
									 ));
		$this->a_getName('stdin');
		$this->a_getField('name', 'stdin');
		
		$this->a_setField('buffer', 'This is a buffered message.');
		$this->a_setField('ready', true);
		
		$this->a_displayDescriptor($this->a_descriptor);
		
		$this->a_deleteDescriptor();
	}

	/**
	 * a_displayDescriptor
	 * 
	 * Display the current descriptor's
	 * @param object $descriptor = descriptor to display
	 */
	public function a_displayDescriptor($descriptor)
	{
		$this->labelBlock('Display Descriptor', 40);
		
		$this->a_localDescriptor = $descriptor;
		$this->a_type = $descriptor->type;
		
		$assertion = '$this->a_buffer = (string)$this->a_descriptor;';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_showData($this->a_buffer, 'Descriptor');
	}

	/**
	 * a_getName
	 * 
	 * Get the Descriptor name
	 * @param string $expected = expected name
	 */
	public function a_getName($expected)
	{
		$this->labelBlock('Get Name', 40);

		$this->a_expected = $expected;
		$this->a_showData($this->a_expected, 'Expected');

		$assertion = '$this->a_name = $this->a_descriptor->name;';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, $this->a_expected, $this->a_name);

		$this->a_showData($this->a_name, "Name");
	}

	/**
	 * a_setField
	 * 
	 * Set the Descriptor field to the specified value
	 * @param string $field = field name
	 * @param string $value = field value
	 */
	public function a_setField($field, $value)
	{
		$this->labelBlock('Set Field', 40);

		$this->a_field = $field;
		$this->a_value = $value;

		$this->a_showData($this->a_field, 'Field');
		$this->a_showData($this->a_value, 'Value');
		
		$assertion = '(($this->a_descriptor->{$this->a_field} = $this->a_value) == $this->a_value);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_getField($this->a_field, $this->a_value);
	}

	/**
	 * a_getField
	 * 
	 * Get the Descriptor field
	 * @param string $field = field name
	 * @param mixed $expected = expected field
	 */
	public function a_getField($field, $expected)
	{
		$this->labelBlock('Get Field', 40);

		$this->a_field = $field;
		$this->a_expected = $expected;

		$this->a_showData($this->a_field, 'Field');
		$this->a_showData($this->a_expected, 'Expected');

		$assertion = '$this->a_{$this->a_field} = $this->a_descriptor->{$this->a_field};';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, $this->a_expected, $this->a_{$this->a_field});

		$this->a_showData($this->a_{$this->a_field}, ucfirst($this->a_field));
	}

	/**
	 * a_deleteDescriptor
	 * 
	 * Delete the Descriptor object
	 */
	public function a_deleteDescriptor()
	{
		$this->labelBlock('Delete Descriptor', 40);

		$assertion = '(($this->a_descriptor = null) === null);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_newDescriptor
	 * 
	 * Create a new Descriptor object
	 * @param array $defaults = (optional) defaults array, empty array = default
	 */
	public function a_newDescriptor($defaults=array())
	{
		$this->labelBlock('New Descriptor', 40);

		$this->a_localArray = $defaults;
		$this->a_showData($this->a_localArray, 'Local Array (defaults)');

		$assertion = '(($this->a_descriptor = new \Library\Select\Descriptor($this->a_localArray)) !== false);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, 'Library\Select\Descriptor', get_class($this->a_descriptor));

		$this->a_showData((string)$this->a_descriptor, "Descriptor");
	}

}
