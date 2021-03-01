<?php
namespace ApplicationTests\Utility;

use \Application\Launcher\Testing\UtilityMethods;

use Library\Error;
use Library\Properties;
use Library\Config;
use Library\CliParameters;

/*
 * 		ApplicationTests\Utility\ProcessConfigurationTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * Utility\ProcessConfigurationTest.
 *
 * Utility\ProcessConfiguration class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package ApplicationTest
 * @subpackage Utility
 */
class ProcessConfigurationTest extends UtilityMethods
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
		
	}

	/**
	 * a_setupDefaults
	 * 
	 * Setup default property settings
	 */
	public function a_setupDefaults()
	{
		$this->labelBlock('Setup Defaults.', 40, '*');

		$assertion = "$this->a_defaultProperties = array(
	
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
	
		);";

		if (! $this->assertTrue($assertion, sprintf("Setup Defaults - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
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
		
								   );
		
		$this->a_showData($this->a_optionMap, 'OptionMap');

		$assertion = '$this->a_jackProperties = new JackProperties($this->properties, $this->a_defaultProperties, $this->a_optionMap);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Map Options - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		
		$this->a_compareExpected(true, 'Application\jackAudioControl\JackProperties', $this->a_jackProperties);
	}

	/**
	 * a_newProcessConfiguration
	 * 
	 * create a new ProcessConfiguration object
	 * @param string $processName = name of the process
	 * @param string $expected = expected class name
	 */
	public function a_newProcessConfiguration($processName, $expected='\Application\jackAudioControl\AudioConfig\Process')
	{
		$this->labelBlock('New Process.', 40, '*');
		
		$this->a_expected = $expected;
		$this->a_processName = $processName;

		$this->a_showData($this->a_processName, 'processName');
		$this->a_showData($this->a_expected, 'Expected');

		$assertion = '$this->a_process = new \Application\jackAudioControl\AudioConfig\Process($this->a_processName, $this->a_audioElements->{$this->a_processName});';
		if (! $this->assertTrue($assertion, sprintf("New Process - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_compareExpectedType(true, $expected, get_class($this->a_process));

	}

}
