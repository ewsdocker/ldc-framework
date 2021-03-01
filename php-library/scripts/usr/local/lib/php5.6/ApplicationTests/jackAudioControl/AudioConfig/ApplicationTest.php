<?php
namespace ApplicationTests\jackAudioControl\AudioConfig;

use ApplicationTests\jackAudioControl\AudioConfig\NodeTest;
use Application\jackAudioConfig\AudioConfig\Application;

/*
 * 		ApplicationTests\jackAudioControl\AudioConfig\ApplicationTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * AudioConfig\ApplicationTest.
 *
 * AudioConfig\Application class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package jackAudioControl
 * @subpackage AudioConfig
 */

class ApplicationTest extends NodeTest
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

		$this->labelBlock('BEGIN ApplicationTest.', 60, '=');

    	$this->a_rewindNodes();

    	while ($this->a_node !== null)
    	{
    		$this->a_newApplication($this->a_node->key(), '\Application\jackAudioControl\AudioConfig\Application');

			$this->a_applicationName();

			$this->a_processStartSequence();
			$this->a_processStartCommand();

			$this->a_processStopSequence();
			$this->a_processStopCommand();

			$this->a_openProcess();

			$this->a_wait(4, 0);

			$this->a_terminateProcess();

			$this->a_wait(1, 500000000);

			$this->a_closeProcess();

			$this->a_deleteApplication();

			$this->a_nextNodes();

    	}

	}

	/**
	 * a_wait - now a part of the Application\Launcher\Testing\UtilityMethods class
	 *
	 * Wait for requested number of seconds
	 * @param integer $pauseSeconds = seconds, default = 10
	 * @param integer $pauseNano = nano-seconds, default = 0
	 *
	public function a_wait($pauseSeconds=10, $pauseNano=0)
	{
		$this->labelBlock('Wait.', 40, '*');

		$this->a_waitSeconds = $pauseSeconds;
		$this->a_waitNano = $pauseNano;

		$this->a_showData($this->a_waitSeconds, 'Wait Seconds');
		$this->a_showData($this->a_waitNano, 'Wait Nano');

		$assertion = '(($this->a_data = time_nanosleep($this->a_waitSeconds, $this->a_waitNano)) === true);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Wait - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse(false);

		$this->a_showData($this->a_data, 'Wait Result');
	}

	/**
	 * a_terminateProcess
	 *
	 * Terminate the process
	 */
	public function a_terminateProcess()
	{
		$this->labelBlock('Terminate Process.', 40, '*');

		$assertion = '(($this->a_data = $this->a_application->terminate()) === true);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Terminate Process - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse(false);

		$this->a_showData($this->a_data, 'Terminate Process');
	}

	/**
	 * a_closeProcess
	 *
	 * Stop the process
	 *
	 */
	public function a_closeProcess()
	{
		$this->labelBlock('Close Process.', 40, '*');

		$assertion = '(($this->a_data = $this->a_application->close()) != -1);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Close Process - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse(false);

		$this->a_showData($this->a_data, 'Close Process');
	}

	/**
	 * a_openProcess
	 *
	 * Start the process
	 *
	 */
	public function a_openProcess()
	{
		$this->labelBlock('Open Process.', 40, '*');

		$assertion = '(($this->a_data = $this->a_application->open()) === true);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Open Process - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_data, 'Open Process');
	}

	/**
     * a_processStartSequence
     *
     * get the process start sequence number
     */
    public function a_processStartSequence()
    {
    	$this->labelBlock('Process StartSequence.', 40, '*');

    	$assertion = '$this->a_data = $this->a_application->startSequence();';
		if (! $this->assertTrue($assertion, sprintf("Start Sequence - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_data, 'Start Sequence');
    }

    /**
     * a_processStartCommand
     *
     * get the process start command string
     */
    public function a_processStartCommand()
    {
    	$this->labelBlock('Process StartCommand.', 40, '*');

    	$assertion = '$this->a_data = $this->a_application->startCommand();';
		if (! $this->assertTrue($assertion, sprintf("Process StartCommand - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_data, 'Start Command');
    }

    /**
     * a_processStopSequence
     *
     * get the process stop sequence number
     */
    public function a_processStopSequence()
    {
    	$this->labelBlock('Process StopSequence.', 40, '*');

    	$assertion = '$this->a_data = $this->a_application->stopSequence();';
		if (! $this->assertTrue($assertion, sprintf("Process StopSequence - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_data, 'Stop Sequence');
    }

    /**
     * a_processStopCommand
     *
     * get the process stop command string
     */
    public function a_processStopCommand()
    {
    	$this->labelBlock('Process StopCommand.', 40, '*');

    	$assertion = '$this->a_data = $this->a_application->stopCommand();';
		if (! $this->assertTrue($assertion, sprintf("Process StopCommand - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_data, 'Stop Command');
    }

	/**
     * a_processName
     *
     * get the name of the process
     */
    public function a_applicationName()
    {
    	$this->labelBlock('Process Element Name.', 40, '*');

    	$assertion = '$this->a_data = $this->a_application->processName();';
		if (! $this->assertTrue($assertion, sprintf("Process Name - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_data, 'Process Name');
    }

	/**
     * a_deleteApplication
     *
     * Delete the current Node class instance
     */
    public function a_deleteApplication()
    {
    	$this->labelBlock('Delete Application.', 40, '*');

    	$assertion = '(($this->a_application = null) === null);';
		if (! $this->assertTrue($assertion, sprintf("Delete Application - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

	/**
	 * a_newApplication
	 *
	 * create a new Application object
	 * @param string $processName = name of the process
	 * @param string $expected = expected class name
	 */
	public function a_newApplication($processName, $expected='\Application\jackAudioControl\AudioConfig\Application')
	{
		$this->labelBlock('New Application.', 40, '*');

		$this->a_expected = $expected;
		$this->a_processName = $processName;

		$this->a_showData($this->a_processName, 'processName');
		$this->a_showData($this->a_expected, 'Expected');

		$assertion = '$this->a_application = new \Application\jackAudioControl\AudioConfig\Application($this->a_processName, $this->a_nodes->{$this->a_processName});';
		if (! $this->assertTrue($assertion, sprintf("New Application - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, $expected, get_class($this->a_application));

	}

}
