<?php
namespace Tests\Factory;

/*
 *		InvokeClassMethodTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * InvokeClassMethodTest
 *
 * Perform InvokeClassMethod class tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Factory
 */
class InvokeClassMethodTest extends \Application\Launcher\Testing\UtilityMethods
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

    	$this->a_testClass = 'Library\Factory\InvokeClassMethod';
		$this->a_echoClass = 'Tests\Factory\TestClasses\EchoBack';

		$this->a_newInstanceClass($this->a_echoClass);
		$this->a_newMethodTest($this->a_testClass, $this->a_invoke, 'echoMe');

		$this->a_message = "We're Here!!!";

		$this->a_invokeMethod($this->a_message);

		$this->a_methodTest = null;
		$this->a_newMethodTest($this->a_testClass, $this->a_invoke, 'echoReference');

		$result = 'x';
		$this->a_invokeMethodReference($this->a_message, $result);
		$this->a_compareExpectedType(true, $this->a_message, $result);

		unset($this->a_methodTest);
		unset($this->a_invoke);
    }

    /**
     * a_invokeMethodReference
     *
     * Test invoking method with pass-by-reference
     * @param string $message = message to send to echoBack
     * @param string $reference = place for echoBack to store the message
     */
    public function a_invokeMethodReference($message, &$reference)
    {
    	$this->labelBlock('Invoke Method Reference.', 40, '*');

    	$this->a_reference =& $reference;

    	$this->a_localArray = array($this->a_message, &$this->a_reference);

    	$assertion = '$this->a_data = $this->a_methodTest->invokeArgs($this->a_localArray);';
		if (! $this->assertTrue($assertion, sprintf("invoke - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, $message, $reference);
    }

    /**
     * a_invokeMethod
     *
     * Test invoking method with pass-by-value
     * @param string $message = message to send to echoBack
     */
    public function a_invokeMethod($message)
    {
    	$this->labelBlock('Invoke Method.', 40, '*');

    	$assertion = '$this->a_data = $this->a_methodTest->invoke($this->a_invoke, $this->a_message);';
		if (! $this->assertTrue($assertion, sprintf("invoke - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($message);
    }

     /**
     * a_newInstanceClass
     *
     * Create a new instanceClass test object
     */
    public function a_newInstanceClass($class)
    {
    	$this->labelBlock('Creating NEW instanceClass test object.', 40, '*');

    	$this->a_invokeClass = $class;
    	$assertion = sprintf('(get_class($this->a_invoke = new %s()) == "%s");',
    						 $this->a_invokeClass,
    						 $this->a_invokeClass);

		if (! $this->assertExceptionTrue($assertion, sprintf("NEW %s - Asserting: %s", $this->a_invokeClass, $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_newMethodTest
     *
     * Create a new class method object
     */
    public function a_newMethodTest($class, $instance, $method)
    {
    	$this->labelBlock('Creating NEW methodTest object.', 40, '*');

    	$this->a_testInstance = $instance;

    	$assertion = sprintf('(get_class($this->a_methodTest = new %s($this->a_testInstance, "%s")) == "%s");', $class, $method, $class);
		if (! $this->assertExceptionTrue($assertion, sprintf("NEW MethodTest %s - Asserting: %s", $class, $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

}
