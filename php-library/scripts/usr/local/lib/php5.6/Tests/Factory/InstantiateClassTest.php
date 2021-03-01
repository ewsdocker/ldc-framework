<?php
namespace Tests\Factory;
use Library\Factory\InstantiateClass;

/*
 *		InstantiateClassTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * InstantiateClassTest
 *
 * Stream factory tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Factory
 */
class InstantiateClassTest extends \Application\Launcher\Testing\UtilityMethods
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

    	$this->a_instantiateClassName = 'Library\Factory\InstantiateClass';
    	$this->a_instantiateClass = null;

    	$this->a_newInstantiateClass();

		$this->a_class = null;
		$this->a_testClass = 'Library\Stack\Queue';

		$this->a_instantiate($this->a_testClass, "myQueue");
		$this->a_className(get_class($this->a_class));

		$this->a_class = null;
		$this->a_testClass = 'Library\Stack';

		$this->a_instantiate($this->a_testClass);
		$this->a_className(get_class($this->a_class));

		$this->a_class = null;
		$this->a_testClass = '';

		$this->a_instantiate($this->a_testClass, null, false);
		$this->a_className(get_class($this->a_class));

		$this->a_class = null;
		$this->a_testClass = 'Tests\Factory\TestClasses\NoConstructor';

		$this->a_instantiate($this->a_testClass);
		$this->a_className(get_class($this->a_class));

		$this->a_class = null;
		$this->a_instantiateClass = null;
    }

    /**
     * a_className
     *
     * Get the class name from the InstantiateClass object
     * @param string $expected = expected class name
     */
    public function a_className($expected)
    {
    	$this->labelBlock('Class Name.', 40, '*');

    	$assertion = '$this->a_data = $this->a_instantiateClass->className();';
		if (! $this->assertTrue($assertion, sprintf("className() - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
    }

    /**
     * a_instantiate
     *
     * Instantiate another class with possible arguements
     * @param string $class = class name
     * @param mixed $arg = (optional) arguements
     */
    public function a_instantiate($class, $arg=null, $type=true)
    {
    	$this->labelBlock('Instantiate Class.', 40, '*');

    	if ($arg !== null)
    	{
	    	$this->a_arguement = $arg;
    		$assertion = sprintf('$this->a_class = $this->a_instantiateClass->instantiate("%s", $this->a_arguement);', $class);
$this->a_class = $this->a_instantiateClass->instantiate($class, $this->a_arguement);
    	}
    	else
    	{
    		$assertion = sprintf('$this->a_class = $this->a_instantiateClass->instantiate("%s");', $class);
$this->a_class = $this->a_instantiateClass->instantiate($class);
    	}

		if (! $this->assertExceptionTrue($assertion, sprintf("Creating new class - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType($type, $class, get_class($this->a_class));
    }

    /**
     * a_newInstantiateClass
     *
     * Create a new InstantiateClass class object
     */
    public function a_newInstantiateClass()
    {
    	$this->labelBlock('NEW InstantiateClass.', 40, '*');

    	$assertion = sprintf('(get_class($this->a_instantiateClass = new %s()) == "%s");', $this->a_instantiateClassName, $this->a_instantiateClassName);
		if (! $this->assertExceptionTrue($assertion, sprintf("NEW InstantiateClass - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

}
