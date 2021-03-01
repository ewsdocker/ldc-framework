<?php
namespace ApplicationTests\jackAudioControl;

use ApplicationTests\jackAudioControl\JackProperties;
use ApplicationTests\jackAudioControl\Process;
use ApplicationTests\jackAudioControl\Exception;

use \Application\Launcher\Testing\UtilityMethods;

use Library\Error;
use Library\Properties;
use Library\Config;
use Library\CliParameters;

/*
 * 		ApplicationTests\jackAudioControl\ProcessTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * ProcessTest.
 *
 * jackAudioControl\Process class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package ApplicationTests
 * @subpackage jackAudioControl
 */

class ProcessTest extends UtilityMethods
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

		$this->a_setupDefaults();
		$this->a_mapOptions();

		$this->a_newProcess($this->a_jackProperties, 'Application\jackAudioControl\Process');

		$this->a_loadProcesses();
		$this->a_startSequence();
		$this->a_stopSequence();

		$this->a_executeProcesses();
		$this->assertLogMessage('Done', 'debug');
	}

	/**
	 * a_executeProcesses
	 *
	 */
	public function a_executeProcesses()
	{
		$this->labelBlock('Execute Processes', 40, '*');

//$this->a_process->execute();
		$assertion = '($this->a_process->execute() !== true);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse(false);
	}

	/**
	 * a_wait
	 *
	 * Wait for requested number of seconds
	 * @param integer $pauseSeconds = seconds, default = 10
	 * @param integer $pauseNano = nano-seconds, default = 0
	 */
	public function a_wait($pauseSeconds=10, $pauseNano=0)
	{
		$this->labelBlock('Wait.', 40, '*');

		$this->a_waitSeconds = $pauseSeconds;
		$this->a_waitNano = $pauseNano;

		$this->a_showData($this->a_waitSeconds, 'Wait Seconds');
		$this->a_showData($this->a_waitNano, 'Wait Nano');

		$assertion = '($this->a_process->wait($this->a_waitSeconds, $this->a_waitNano) !== true);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Wait - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse(false);

		$this->a_showData($this->a_data, 'Wait Result');
	}

	/**
	 * a_startProcesses
	 *
	 * Start requested processes
	 */
	public function a_startProcesses()
	{
		$this->labelBlock('Start Processes.', 40, '*');

		$assertion = '($this->a_process->startProcesses() !== true);';
		if (! $this->assertTrue($assertion, sprintf("Start Processes - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_stopProcesses
	 *
	 * Load stop sequence array
	 */
	public function a_stopProcesses()
	{
		$this->labelBlock('Stop Processes.', 40, '*');

		$assertion = '($this->a_process->stopProcesses() !== true);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Stop Processes - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_stopSequence
	 *
	 * Load stop sequence array
	 */
	public function a_stopSequence()
	{
		$this->labelBlock('Stop Sequence.', 40, '*');

		$assertion = '$this->a_data = $this->a_process->stopSequence();';
		if (! $this->assertTrue($assertion, sprintf("Stop Sequence - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_data, 'Stop Sequence');
	}

	/**
	 * a_startSequence
	 *
	 * Load start sequence array
	 */
	public function a_startSequence()
	{
		$this->labelBlock('Start Sequence.', 40, '*');

		$assertion = '$this->a_data = $this->a_process->startSequence();';
		if (! $this->assertTrue($assertion, sprintf("Start Sequence - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_data, 'Start Sequence');
	}

	/**
	 * a_loadProcesses
	 *
	 * Load process elements
	 */
	public function a_loadProcesses()
	{
		$this->labelBlock('Load Processes.', 40, '*');

		$assertion = '($this->a_process->loadProcesses() !== false);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Load Processes - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData((string)$this->a_process, 'Processes');
	}

	/**
	 * a_setupDefaults
	 *
	 * Setup default property settings
	 */
	public function a_setupDefaults()
	{
		$this->labelBlock('Setup Defaults.', 40, '*');

		$this->a_defaultProperties = array(

			/**
			 * Jack_Adapter
			 *
		 	 * The configuration file type adapter ('ini', 'xml', 'db')
			 * @var string Config_Adapter
			 */
			'Jack_Adapter' => 'xml',

			/**
			 * Jack_Folder
			 *
			 * The folder in the root path that contains the configuration file,
			 *   or the absolute path (must begin with '/') to the configuration folder
			 * @var string Config_Folder
			 */
			'Jack_Folder' => 'Application/jackAudioControl',

			/**
			 * Jack_ConfigName
			 *
			 * The configuration file name (only - no file type)
			 * @var string Jack_FileName = the configuration file name.
			 */
			'Jack_ConfigName' => 'AudioControl',

			/**
			 * Jack_IoAdapter
			 *
			 * The configuration I/O driver name ('file', 'stream')
			 * @var string Jack_IoAdapter
			 */
			'Jack_IoAdapter' => 'file',

			/**
			 * Jack_IoAdapterType
			 *
			 * The type of I/O adapter
			 * @var string Jack_IoAdapterType
			 */
			'Jack_IoAdapterType' => 'fileobject',

			/**
			 * Jack_ConfigGroup
			 *
			 * Configuration group name (default = Audio)
			 * @var string Jack_ConfigGroup
			 */
			'Jack_ConfigGroup' => 'Audio',

			/**
			 * Jack_AudioSystem
			 *
			 * Audio configuration system (default = Radio)
			 */
			'Jack_AudioSystem' => 'Radio',

			/**
			 * Jack_StartWait
			 *
			 * Audio configuration system (default = Radio)
			 */
			'Jack_StartWait' => '4',

			/**
			 * Jack_StopWait
			 *
			 * Audio configuration system (default = Radio)
			 */
			'Jack_StopWait' => '2',
		);
	}

	/**
	 * a_mapOptions
	 *
	 * Map cli parameters to default properties
	 */
	public function a_mapOptions()
	{
		$this->labelBlock('Map Options.', 40, '*');

		$this->a_optionMap = array('jackadapter'	=> 'Jack_Adapter',
								   'jackfolder'		=> 'Jack_Folder',
								   'jackio'			=> 'Jack_IoAdapter',
								   'jackiotype'		=> 'Jack_IoAdapterType',
								   'jackconfig'		=> 'Jack_ConfigName',

								   'jackgroup'		=> 'Jack_ConfigGroup',
								   'jacksystem'		=> 'Jack_AudioSystem',
								   'jackstartwait'	=> 'Jack_StartWait',
								   'jackstopwait'	=> 'Jack_StopWait,'

								   );

		$this->a_showData($this->a_optionMap, 'OptionMap');

		$assertion = '$this->a_jackProperties = new \Application\jackAudioControl\JackProperties($this->properties, $this->a_defaultProperties, $this->a_optionMap);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Map Options - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType(true, 'Application\jackAudioControl\JackProperties', get_class($this->a_jackProperties));
	}

	/**
	 * a_newProcess
	 *
	 * create a new Process object
	 * @param object $properties = Properties object
	 * @param string $expected = expected class name
	 */
	public function a_newProcess($properties, $expected)
	{
		$this->labelBlock('New Process.', 40, '*');

		$this->a_properties = $properties;
		$this->a_expected = $expected;

		$this->a_showData($this->a_properties, 'Properties');
		$this->a_showData($this->a_expected, 'Expected');

		$assertion = '$this->a_process = new \Application\jackAudioControl\Process($this->a_properties);';
		if (! $this->assertExceptionTrue($assertion, sprintf("New Process - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType(true, $this->a_expected, get_class($this->a_process));
	}

}
