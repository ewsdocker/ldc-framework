<?php
namespace Tests\Factory;

/*
 *		InvokeFunctionTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * InvokeFunctionTest
 *
 * Stream factory tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Factory
 */
class InvokeFunctionTest extends \Application\Launcher\Testing\UtilityMethods
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

    	$this->a_testClass = 'Library\Factory\InvokeFunction';
		$this->a_testFunction = 'fscanf';
		$this->a_absoluteFileName('Tests/FileIO/Files/TestCSV.txt');

		$this->a_openFile();

		$this->a_newFunctionTest($this->a_testClass, $this->a_testFunction);

		$this->a_readFile();
		@rewind($this->a_file);

		$format = "%[^,],%[^,],%[^,\n]";
		$this->a_invokeFunction($format, sscanf($this->a_data, $format));

		@rewind($this->a_file);

		$field0 = 'first name';
		$field1 = 'last name';
		$field2 = 'birthday';

		for($index=0; $index < 6; $index++)
		{
			$this->a_invokeFunctionReference($format, $field0, $field1, $field2, 3);
			$this->assertLogMessage(sprintf('field0 = "%s", field1 = "%s", field2 = "%s"', $field0, $field1, $field2));
		}

		@rewind($this->a_file);

		$this->a_invokeFunctionReferenceFalse($format, $field0, $field1, $field2, 3);

		unset($this->a_methodTest);
		unset($this->a_instance);
    }

    /**
     * a_invokeFunctionReferenceFalse
     *
     * Test invoking function with pass-by-reference, except final arguement
     * @param string $format = format string
     * @param string $field0 = location of first  field results
     * @param string $field1 = location of second field results
     * @param string $field2 = location of third  field results
     * @param integer $expected = expected return value
     */
    public function a_invokeFunctionReferenceFalse($format, &$field0, &$field1, $field2, $expected)
    {
    	$this->labelBlock('InvokeFunctionReference False.', 40, '*');

    	$this->a_arguements = array($this->a_file, $format, &$field0, &$field1, $field2);
    	$assertion = '$this->a_data = $this->a_function->invokeArgs($this->a_arguements);';

   		if (! $this->assertExceptionTrue($assertion, sprintf("invokeArgs False - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtTrue();

		$this->a_compareExpected($expected);
    }

    /**
     * a_invokeFunctionReference
     *
     * Test invoking function with pass-by-reference
     * @param string $format = format string
     * @param string $field0 = location of first  field results
     * @param string $field1 = location of second field results
     * @param string $field2 = location of third  field results
     * @param integer $expected = expected return value
     */
    public function a_invokeFunctionReference($format, &$field0, &$field1, &$field2, $expected)
    {
    	$this->labelBlock('InvokeFunctionReference.', 40, '*');

    	$this->a_arguements = array($this->a_file, $format, &$field0, &$field1, &$field2);

    	$assertion = '$this->a_data = $this->a_function->invokeArgs($this->a_arguements);';
   		if (! $this->assertExceptionTrue($assertion, sprintf("invokeArgs TRUE - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpected($expected);
    }

    /**
     * a_invokeFunction
     *
     * Test invoking function with pass-by-value
     * @param string $format = format string
     */
    public function a_invokeFunction($format, $expected)
    {
    	$this->labelBlock('InvokeFunction.', 40, '*');

    	$this->a_format = $format;
    	$assertion = '$this->a_localArray = $this->a_function->invoke($this->a_file, $this->a_format);';
		if (! $this->assertExceptionTrue($assertion, sprintf("invoke - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareArray($expected, true);
    }

    /**
     * a_readFile
     *
     * Get the next line from the file
     */
    public function a_readFile()
    {
    	$this->labelBlock('Read File.', 40, '*');

    	$assertion = '$this->a_data = fgets($this->a_file);';
		if (! $this->assertTrue($assertion, sprintf("Read File - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->assertLogMessage("Read data: " . $this->a_data);
    }

    /**
     * a_openFile
     *
     * Open the input file to test with fscanf
     */
    public function a_openFile()
    {
    	$this->labelBlock('OpenFile.', 40, '*');

    	$assertion = '$this->a_file = fopen($this->a_fileName, "r", false);';
		if (! $this->assertTrue($assertion, sprintf("openFile - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_newFunctionTest
     *
     * Create a new function test instance
     */
    public function a_newFunctionTest($class, $function)
    {
    	$this->labelBlock('Creating NEW Function test object.', 40, '*');

    	$assertion = sprintf('(get_class($this->a_function = new %s("%s")) == "%s");', $class, $function, $class);
		if (! $this->assertExceptionTrue($assertion, sprintf("NEW FunctionTest %s - Asserting: %s", $class, $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

}
