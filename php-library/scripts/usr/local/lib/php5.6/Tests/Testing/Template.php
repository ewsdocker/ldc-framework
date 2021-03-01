<?php
namespace Tests\Testing;

/*
 *		Testing\Template is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, 
 *            or from http://opensource.org/licenses/academic.php
*/
/**
 * Template
 *
 * Testing\Template sample test.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2013 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Testing
 */
class Template extends \Application\Launcher\Testing\Base
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
     * @param string $logName = (optional) name of the logger to use, null for none
     * @param string $format = (optional) log output format 
     */
    public function assertionTests($logName=null, $format=null)
    {
    	$this->eolSequence = $this->properties->eolSequence;

		if ($logName !== null)
		{
			$this->logger->startLogger($logName, $format);
		}

    	$this->assertFailures(false);
    	
    	$this->a_trueTest();
    	$this->a_falseTest();
    }

    /**
     * a_trueTest
     * 
     * A sample of calling the asertTrue method with a true assertion
     */
    public function a_trueTest()
    {
    	$this->labelBlock('True Test', 40);
    	
    	$assertion = '(1 + 1) == 2;';
    	if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('(1 + 1) != 2');
    		exit(1);
    	}

    	$this->assertLogMessage('Test result is TRUE');
    }

    /**
     * a_falseTest
     * 
     * A sample of calling the assertFalse method with a false assertion
     */
    public function a_falseTest()
    {
    	$this->labelBlock('False Test', 40);
    	
    	$assertion = '(1 + 1) == 3;';
    	if (! $this->assertFalse($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('(1 + 1) == 3');
    		exit(1);
    	}

    	$this->assertLogMessage('Test result is FALSE');
    }
}
