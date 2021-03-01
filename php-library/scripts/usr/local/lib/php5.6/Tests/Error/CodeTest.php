<?php
namespace Tests\Error;
use Library\PrintU;

/*
 *		Tests\Error\CodeTest.php is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * Error\CodeTest.
 *
 * The Error_Code tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Error_Code
 */
class CodeTest extends \Application\Launcher\Testing\UtilityMethods
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

   		$this->a_loadClass('\Library\Error');

		$this->a_getCode('AutoloadFailed', 1);
		$this->a_getCode('AutoloadFailed', 2, false);

		$this->a_getCode(1, 'AutoloadFailed');

		$this->a_getMessage('PhpExtensionNotAvailable', 'The requested PHP extension is not available');
		$this->a_getCode('PhpExtensionNotAvailable', 5);

		$this->a_getMessage($this->a_data, 'The requested PHP extension is not available');

		$this->a_nextError();

		$this->a_register('UnknownTestObject', 'Unknown test object', $this->a_next);

		$this->a_unknownErrorName('youdontknowme', 'Unknown error name: youdontknowme');

		$this->a_register('AnotherUnknownTestObject', 'Another unknown test object', $this->a_next+1);
    }

    /**
     * a_unknownErrorName
     *
     * Generate an unknown error from bad name
     * @param string $name = unknown error name
     * @param string $expected = expected response message
     */
    public function a_unknownErrorName($name, $expected)
    {
    	$this->labelBlock('Unknown Error Name', 40, '*');

    	$this->a_name = $name;


    	$assertion = '$this->a_data = \Library\Error::message($this->a_name);';
		if (! $this->assertTrue($assertion, sprintf("Lookup invalid name - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
    }

    /**
     * a_register
     *
     * Register new error code and message
     * @param string $name = name of the message
     * @param string $message = message to create
     * @param string $expected = expected message
     */
    public function a_register($name, $message, $expected)
    {
    	$this->labelBlock('Register', 40, '*');

    	$assertion = sprintf('$this->a_data = \Library\Error::register("%s", "%s", false);', $name, $message);
		if (! $this->assertTrue($assertion, sprintf("Registering new name - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
    }

    /**
     * a_nextError
     *
     * Get the next error and try to get a matching message
     */
    public function a_nextError()
    {
    	$this->labelBlock('Next Error', 40, '*');

    	$assertion = '$this->a_next = \Library\Error::nextError();';
		if (! $this->assertTrue($assertion, sprintf("Next error number - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->assertLogMessage(sprintf('Next error = %u', $this->a_next));

		$this->a_getMessage($this->a_next, sprintf('Unknown error name: %d', $this->a_next));
    }

    /**
     * a_getMessage
     *
     * Get message from code name/number
     * @param mixed $name = name of code or code value
     * @param string $expected = error message
     */
    public function a_getMessage($name, $expected)
    {
    	$this->labelBlock('Get Message', 40, '*');

    	$this->a_name = $name;

    	$assertion = '$this->a_data = \Library\Error::message($this->a_name);';
		if (! $this->assertTrue($assertion, sprintf("Get message - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
    }

    /**
     * a_getCode
     *
     * Get code from name
     * @param string $name = name of the error code
     * @param integer $expected = expected error code value
     * @param boolean $type = true = true comparison, false = false comparison
     */
    public function a_getCode($name, $expected, $type=true)
    {
    	$this->labelBlock('Get Code', 40, '*');

    	$this->a_name = $name;

    	$assertion = sprintf('(($this->a_data = \Library\Error::code("%s")) !== null);', $this->a_name);
		if (! $this->assertTrue($assertion, sprintf("getCode: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType($type, $expected);
    }

    /**
     * a_loadClass
     *
     * Load requested class
     * @param string $className = name of the class to load
     */
    public function a_loadClass($className)
    {
    	$this->labelBlock('Load Class', 40, '*');

   		$assertion = sprintf("\Library\Autoload::loadClass('%s')", $className);
		if (! $this->assertTrue($assertion, sprintf("Load class %s, Asserting: %s", $className, $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

}

