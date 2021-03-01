<?php
namespace Tests\Config;
use Library\Error;

/*
 * 		Config\ConfigTest is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * Config\ConfigTest.
 *
 * Config class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage Config.
 */

class ConfigTest extends \Application\Launcher\Testing\UtilityMethods
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
    	$this->a_configProperties(\Library\CliParameters::parameterValue('type', 'xml'),
    							  \Library\CliParameters::parameterValue('adapter', 'file'),
    							  'testing');

    	$this->incrementSubTest();
    	$this->assertLogMessage(sprintf("Properties:\n%s", $this->a_defaultProperties));
    	
    	$this->a_newConfig($this->a_defaultProperties, 'Library\Config');
    	
    	$this->a_loadConfig();

    	$this->incrementSubTest();
    	$this->assertLogMessage(sprintf('Form FormURL = %s', $this->a_treeConfig->form->formurl));
    	$this->assertLogMessage(sprintf('Timezone = %s', $this->a_treeConfig->timezone));
    	$this->assertLogMessage(sprintf('Data Path = %s', $this->a_treeConfig->data->path));
    	$this->assertLogMessage(sprintf('Log Driver = %s', $this->a_treeConfig->log->driver));
    	
    	$this->assertLogMessage(sprintf("Tree:\n%s", $this->a_config));
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
		if (! $this->assertExceptionTrue($assertion, sprintf("Load Config - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_configProperties
     *
     * Create a configuration properties instance
     * @param string $configAdapter = (optional) config adapter name, default = 'ini'
     * @param string $section = (optional) section name, default = 'testing'
     * @param string $ioAdapter = (optional) io adapter name, default = 'file'
     * @param string $ioAdapterType = (optional) io adapter type, default = 'fileobject'
     */
    public function a_configProperties($configAdapter='ini', $ioAdapter='file', $section='testing', $ioAdapterType='fileobject')
    {
    	$this->labelBlock('Config Properties.', 40, '*');

    	$this->a_localArray = array('Config_Adapter'		=>	$configAdapter,
									'Config_FileName'		=>	sprintf('Tests/Config/Testfiles/Config.%s', $configAdapter),
									'Config_Section'		=>	$section,
									'Config_IoAdapter'		=>	$ioAdapter,
    								'Config_IoAdapterType'	=>	$ioAdapterType,
    								'Config_Mode'			=>	'r');

    	$assertion = '$this->a_defaultProperties = new \Library\Properties($this->a_localArray);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Config Properties - Asserting: %s", $assertion)))
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

    	$assertion = sprintf('$this->a_config = new \Library\Config("%s", "%s", $this->a_properties);',
    						 $this->a_properties->Config_FileName, $this->a_properties->Config_Section);
		if (! $this->assertTrue($assertion, sprintf("New Config - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_compareExpectedType(true, $expected, get_class($this->a_config));
    }
}
