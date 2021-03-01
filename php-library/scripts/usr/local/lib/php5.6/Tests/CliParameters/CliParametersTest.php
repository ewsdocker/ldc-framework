<?php
namespace Tests\CliParameters;

/*
 *		CliParametersTest is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * CliParametersTest.
 *
 * The CliParameters tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage CliParameters
 */
class CliParametersTest extends \Application\Launcher\Testing\UtilityMethods
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

    	$this->a_loadClass('\Library\CliParameters');

    	$this->a_applicationPath = \Library\CliParameters::parameterValue('bin', '/usr/bin/php');
    	$this->a_applicationName = basename($this->a_applicationPath);

    	$this->a_initialize(120, false);
		$this->a_initialize();

		$this->a_parameterValue('count', 2);

		$this->a_setArgs(array($this->a_applicationPath, 'a=12','b=34','c','d','e='));

		$this->a_applicationName($this->a_applicationName);
		$this->a_applicationPath(dirname($this->a_applicationPath));

		$this->a_parameterValue('a', '12');

		$this->a_parameterExists('b', true);
		$this->a_parameterExists('tests', false);

    }

    /**
     * a_applicationPath
     *
     * Get application path
     * @param string $expected = expected results
     */
    public function a_applicationPath($expected)
    {
    	$this->labelBlock('Application Path', 40, '*');

#$this->a_data = \Library\CliParameters::applicationPath();
    	$assertion = '$this->a_data = \Library\CliParameters::applicationPath();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_compareExpected($expected);
    }

    /**
     * a_applicationName
     *
     * Get application name
     * @param string $expected = expected results
     */
    public function a_applicationName($expected)
    {
    	$this->labelBlock('Application Name', 40, '*');

    	$assertion = '$this->a_data = \Library\CliParameters::applicationName();';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_compareExpected($expected);
    }

    /**
     * a_parameterExists
     *
     * Check if parameter exists
     * @param string $name = name of parameter to check for
     * @param boolean $expected = (optional) test type, default = true
     */
    public function a_parameterExists($name, $expected=true)
    {
    	$this->labelBlock('Parameter Exists', 40, '*');

    	$this->a_name = $name;

		$assertion = '(($this->a_data = \Library\CliParameters::parameterExists($this->a_name)) !== null);';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_compareExpected($expected);
    }

    /**
     * a_setArgs
     *
     * Set arguements to the CliParameters
     * @param array $args = array of arguements
     */
    public function a_setArgs($args)
    {
    	$this->labelBlock('Set Args', 40, '*');

    	$this->a_localArray = $args;
    	$this->a_argCount = count($args);

		$assertion = '\Library\CliParameters::setArgs($this->a_argCount, $this->a_localArray);';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_parameterValue
     *
     * Get parameter value
     * @param string $name = name of the value to get
     * @param mixed $expected = expected value
     */
    public function a_parameterValue($name, $expected)
    {
    	$this->labelBlock('Parameter Value', 40, '*');

    	$this->a_name = $name;
		$this->a_default = $expected;

		$assertion = '$this->a_data = \Library\CliParameters::parameterValue($this->a_name, $this->a_default);';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
    }

    /**
     * a_initialize
     *
     * Initialize CliParameters
     * @param mixed $args = (optional) arguements to initialize, null for none
     * @param boolean $test = (optional) test type: true or false, default = true
     */
    public function a_initialize($args=null, $test=true)
    {
    	$this->labelBlock('Initialize', 40, '*');

    	$this->args = $args;

		$assertion = '\Library\CliParameters::initialize($this->args);';

		if ($test)
		{
			if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
			{
				$this->a_outputAndDie();
			}

			$this->a_exceptionCaughtFalse();
		}
		else
		{
			if (! $this->assertExceptionFalse($assertion, sprintf("Asserting falsely: %s", $assertion)))
			{
				$this->a_outputAndDie();
			}

			$this->a_exceptionCaughtTrue();
		}
    }

    /**
     * a_loadClass
     *
     * Load the specified class
     * @param string $className = name of class to load
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
