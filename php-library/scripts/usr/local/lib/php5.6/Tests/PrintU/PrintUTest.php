<?php
namespace Tests\PrintU;
use Library\PrintU;

/*
 *		PrintUTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * PrintUTest
 *
 * PrintU tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage PrintU
 */
class PrintUTest extends \Application\Launcher\Testing\UtilityMethods
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

    	$this->assertFailures(false);

    	$this->interfaceType = PrintU::INTERFACE_CONSOLE;
		$this->a_selectInterface();

		$this->outputDevice = $this->a_absoluteFileName('Tests/PrintU/TestOutput');
		$this->a_resetOutputFile();

		$testArray = array('line',
  	                       'function',
  	                       'class',
  						   'object',
  						   'Log_Method',
  						   'type');

		$this->a_printArray($testArray, "testArray unsorted", false, false);
		$this->a_printArray($testArray, "testArray sorted by value", true, true);
		$this->a_printArray($testArray, "testArray sorted by key", true, false);

		$testArray = $this->properties->properties();

		$this->a_printArray($testArray, "Properties unsorted");
		$this->a_printArray($testArray, "Properties sorted by value", true, true);
		$this->a_printArray($testArray, "Properties sorted by key", true, false);

		$this->a_getOutputDevice();

		$this->outputDevice = 'php://output';
		$this->a_resetOutputFile();

		$this->a_getOutputDevice();
	}

    /**
     * a_getOutputDevice
     *
     * Get the current output device
     */
    public function a_getOutputDevice()
    {
    	$this->labelBlock('getOutputDevice', 40, '*');

    	$assertion = '$this->outputDevice = \Library\PrintU::getOutputDevice();';
   		if (! $this->assertTrue($assertion, sprintf("Get output device. - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->assertLogMessage(sprintf('Output device: "%s"', $this->outputDevice));
    }

    /**
     * a_selectInterface
     *
     * select a supported interface
     */
    public function a_selectInterface()
    {
    	$this->labelBlock('Select interface.', 40, '*');

    	$assertion = '\Library\PrintU::selectInterface($this->interfaceType);';
   		if (! $this->assertTrue($assertion, sprintf("Selecting interface - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_resetOutputFile
     *
     * set output device to a file
     */
    public function a_resetOutputFile()
    {
    	$this->labelBlock('Reset output device.', 40, '*');

    	$assertion = '\Library\PrintU::resetOutputDevice($this->outputDevice);';
   		if (! $this->assertExceptionFalse($assertion, sprintf("Set output device to file - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->currentDevice = null;
		$assertion = '$this->currentDevice = \Library\PrintU::getOutputDeviceName();';
   		if (! $this->assertTrue($assertion, sprintf("Getting output device - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$assertion = sprintf('("%s" == $this->outputDevice);', $this->currentDevice);
   		if (! $this->assertTrue($assertion, sprintf("Checking output device - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

    }

}
