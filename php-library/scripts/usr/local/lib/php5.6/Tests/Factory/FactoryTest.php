<?php
namespace Tests\Factory;
use Library\Factory;
use Library\PrintU;

/*
 *		FactoryTest.php is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * FactoryTest
 *
 * Class factory tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Factory
 */
class FactoryTest extends \Application\Launcher\Testing\UtilityMethods
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

		$this->a_object = null;
		$this->a_factory = null;
		$this->a_classes = array('queue'	=> 'Library\Stack\Queue',
								 'stack'	=> 'Library\Stack');

		$this->a_newFactory();

		$this->a_getFactoryClasses($this->a_classes);

		$this->a_classAvailable('queue');
		
		$this->a_availableClassName('queue', $this->a_classes['queue']);

		$this->a_instantiate('queue', $this->a_classes['queue']);

		$queue = $this->a_object;
		$this->a_instantiate('stack', $this->a_classes['stack']);

		$this->a_compareExpectedType(false, get_class($queue), get_class($this->a_object));

		$this->a_classInstance($this->a_classes['stack']);

		$this->a_unsetClass();
		
		$this->a_classInstance($this->a_classes['stack'], false);

		unset($queue);
		unset($this->a_object);

		$this->a_classAvailable('printer', false);
	}

	/**
	 * a_classInstance
	 *
	 * Get class instance
	 * @param string $class = class name to match
	 * @param boolean $expected = true if expected match, false if not expected to match
	 */
	public function a_classInstance($class, $expected=true)
	{
    	$this->labelBlock('Class Instance.', 40, '*');

		$assertion = '$this->a_data = $this->a_factory->classInstance();';

		if ($expected)
		{
			if (! $this->assertTrue($assertion, sprintf("Class instance: %s", $assertion)))
			{
				$this->a_outputAndDie();
			}
		}
		else
		{
			if (! $this->assertFalse($assertion, sprintf("Asserting falsely: %s", $assertion)))
			{
				$this->a_outputAndDie();
			}
			
			if (! $class)
			{
				return;
			}
		}
		
		$this->a_compareExpectedType($expected, $class, get_class($this->a_data));
	}

	/**
	 * a_clearClasses
	 *
	 * Clear factory class array
	 */
	public function a_unsetClass()
	{
    	$this->labelBlock('Unset Class.', 40, '*');

		$assertion = '$this->a_factory->clearClass();';
		if (! $this->assertFalse($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_instantiate
	 *
	 * Instantiate requested class
	 * @param string $class = internal class name
	 * @param string $expected = expected class name associated with $name
	 */
	public function a_instantiate($class, $expected)
	{
    	$this->labelBlock('Instantiate Class.', 40, '*');

    	$assertion = sprintf('$this->a_object = $this->a_factory->instantiate("%s");', $class);
		if (! $this->assertExceptionTrue($assertion, sprintf("Instantiating class - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType(true, $expected, get_class($this->a_object));
	}

	/**
	 * a_availableClassName
	 * 
	 * Get class name associated with internal name
	 * @param string $name = internal name
	 * @param string $expected = expected class name associated with $name
	 */
	public function a_availableClassName($name, $expected)
	{
    	$this->labelBlock('Available Class Name.', 40, '*');

		$this->a_data = $name;

    	$assertion = sprintf('$this->a_data = $this->a_factory->availableClassName("%s");', $this->a_data);
		if (! $this->assertTrue($assertion, sprintf("Available class name: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
	}

	/**
	 * a_classAvailable
	 *
	 * Check for class in the factory class array
	 * @param string $class = internal class name to check for
	 * @param boolean $expected = (optional) expected comparison type, default = true
	 */
	public function a_classAvailable($class, $expected=true)
	{
    	$this->labelBlock('Class Available.', 40, '*');

		$this->a_data = $class;

    	$assertion = sprintf('$this->a_factory->classAvailable("%s");', $this->a_data);
    	if ($expected)
    	{
			if (! $this->assertTrue($assertion, sprintf("Class available true: %s", $assertion)))
			{
				$this->a_outputAndDie();
			}
    	}
    	else
    	{
			if (! $this->assertFalse($assertion, sprintf("Class available false: %s", $assertion)))
			{
				$this->a_outputAndDie();
			}
    	}
	}

	/**
	 * a_getFactoryClasses
	 *
	 * Get the class array from the factory object
	 */
	public function a_getFactoryClasses($expected)
	{
    	$this->labelBlock('Get Factory Classes.', 40, '*');

		$this->a_factoryClasses = array();

		$assertion = '$this->a_factoryClasses = $this->a_factory->availableClasses();';
		if (! $this->assertTrue($assertion, sprintf("Get Factory Classes: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_printArray($this->a_factoryClasses, 'Factory Classes');
		
		$this->a_compareArray($expected, true, $this->a_factoryClasses);
	}

	/**
     * a_newFactory
     *
     * Create a new factory object
     */
	public function a_newFactory()
	{
    	$this->labelBlock('New Factory.', 40, '*');

		$assertion = '$this->a_factory = new \Library\Factory($this->a_classes);';
		if (! $this->assertTrue($assertion, sprintf("New factory object: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

}
