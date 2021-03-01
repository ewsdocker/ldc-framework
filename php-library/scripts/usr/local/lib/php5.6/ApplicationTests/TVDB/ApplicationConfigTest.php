<?php
namespace ApplicationTests\TVDB;

use Library\CliParameters;
use Library\Config;
use Library\Properties;

/*
 * 		TVDB\ApplicationConfigTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * TVDB\ApplicationConfigTest.
 *
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage ApplicationTests
 */

class ApplicationConfigTest extends TVDBTestLib
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
		$this->a_table = 'Series';
		$this->a_xmlFile = 'series.xml';

		parent::assertionTests($logger, $format);

		$this->labelBlock('BEGIN ApplicationConfigTest.', 60, '=');

		$this->a_configTests('Application/TVDB/TVDBControl');
	}

	/**
	 * a_configTests
	 *
	 * Load the xml configuration file and set the Config_Tree property.
	 */
	public function a_configTests($configFile)
	{
		$this->labelBlock('Load Configuration', 40, '*');

		$this->a_configFile = $configFile;

		$this->a_configProperties(CliParameters::parameterValue('config', $this->a_configFile),
								  CliParameters::parameterValue('adapter', 'file'), 'testing');
		$this->incrementSubTest();

		$this->a_showData($this->a_defaultProperties, 'Default Properties');
		$this->a_showData((string)$this->a_defaultProperties);

		$this->a_newConfig($this->a_defaultProperties, 'Library\Config');

		$this->a_loadConfig();
		$this->incrementSubTest();

		$this->a_displayConfig($this->a_treeConfig);
		$this->incrementSubTest();

		$this->a_displayConfig($this->a_treeConfig->TVDB);
		$this->incrementSubTest();

		$this->a_launcherProperties();
		$this->incrementSubTest();
	}

	/**
	 * a_loadConfig
	 *
	 * Load the configuration file to a file tree
	 */
	public function a_loadConfig()
	{
		$this->labelBlock('Load Config.', 40, '*');

		$assertion = '$this->a_treeConfig = $this->a_config->load();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_treeConfig, 'TreeConfig');
	}

	/**
	 * a_displayConfig
	 *
	 * Display the configuration file tree
	 * @param Node $configNode = configuration node to be displayed
	 */
	public function a_displayConfig($configNode)
	{
		$this->labelBlock('Display Config.', 40, '*');

		$this->a_configNode = $configNode;
		$this->a_showData($this->a_configNode, 'ConfigNode');

		$assertion = '$this->a_data = (string)$this->a_configNode;';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_data, 'Configuration', true);
	}

	/**
	 * a_configProperties
	 *
	 * Create a configuration properties instance
	 * @param string $file = Configuration file name (NO FILE TYPE)
	 * @param string $section = (optional) section name, default = 'testing'
	 * @param string $ioAdapter = (optional) io adapter name, default = 'file'
	 * @param string $ioAdapterType = (optional) io adapter type, default = 'fileobject'
	 */
	public function a_configProperties($configFile, $ioAdapter='file', $section='testing', $ioAdapterType='fileobject')
	{
		$this->labelBlock('Config Properties.', 40, '*');

		$this->a_localArray = array('Config_Adapter'		=>	'xml',
									'Config_FileName'		=>	$configFile . '.xml',
									'Config_Section'		=>	$section,
									'Config_IoAdapter'		=>	$ioAdapter,
									'Config_IoAdapterType'	=>	$ioAdapterType,
									'Config_Mode'			=>	'r');

		$this->a_showData($this->a_localArray, 'localArray', true);

		$assertion = '$this->a_defaultProperties = new \Library\Properties($this->a_localArray);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_newConfig
	 *
	 * Create a new config class instance
	 * @param string $expected = expected class name
	 */
	public function a_newConfig($properties, $expected)
	{
		$this->labelBlock('New Config.', 40, '*');

		$this->a_properties = $properties;
		$this->a_properties->Config_FileName = $this->a_absoluteFileName($properties->Config_FileName);

		$this->a_showData($this->a_properties->Config_FileName, 'Config_FileName');
		$this->a_showData($this->a_properties->Config_Section, 'Config_Section');

		$assertion = sprintf('$this->a_config = new \Library\Config("%s", "%s", $this->a_properties);',
							 $this->a_properties->Config_FileName, $this->a_properties->Config_Section);

		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, $expected, get_class($this->a_config));
	}

	/**
	 * a_launcherProperties
	 *
	 * Load the launcher properties to use in the test script
	 */
	public function a_launcherProperties()
	{
		$this->labelBlock('Launcher Properties.', 40, '*');

		$assertion = '$this->a_properties = $this->properties;';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

}
