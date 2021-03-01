<?php
namespace Application\MCP;

use Application\Utility\Support;

use Library\CliParameters;
use Library\Exception\Descriptor as ExceptionDescriptor;
use Library\Properties;

/*
 *		Application\MCP\MCPProperties is copyright � 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * Application\MCP\MCPProperties
 *
 * Configuration Properties class for the MCP application.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Application
 * @subpackage MCP
 */
class MCPProperties extends Properties
{
	/**
	 * __construct
	 *
	 * Class constructor
	 * @param object|array $properties = current properties settings
	 * @param object|array $defaultProperties = default property/value settings
	 * @param array $cliMap = Cli parameter names to property name map array
	 * @param array $cliDbMap = Cli db names to property name map array
	 * @throws Exception
	 */
	public function __construct($properties, $defaultProperties, $cliMap, $cliDbMap)
	{
		parent::__construct($properties);
		$this->setProperties($defaultProperties, true);
		$this->mapParametersToProperties($cliMap);

		$this->loadConfigProperties($cliDbMap);
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * __get
	 *
	 * Magic function to get a property, if it exists, null if not
	 * @param string $property = name of the property to get
	 * @return mixed $value = value of the property, null if it does not exist
	 */
	public function __get($name)
	{
		return parent::__get($name);
	}

	/**
	 * __set
	 *
	 * Magic function to dynamically set a property to a given value
	 * @param string $property = name of the property to set
	 * @param mixed $value = value to set property to
	 */
	public function __set($name, $value)
	{
		parent::__set($name, $value);
	}

	/**
	 * loadConfigProperties
	 *
	 * Load the MCP Control.xml configuration file
	 * @throws Exception
	 */
	private function loadConfigProperties($cliDbMap)
	{
		$this->Application_ConfigSource = '';
		if (strpos($this->Application_Folder, '/', 0) !== 0)
		{
			$this->Application_ConfigSource = $this->Root_Path;
		}

		$this->Application_ConfigSource = sprintf('%s/%s/%s.%s',
											      $this->Application_ConfigSource,
										   		  $this->Application_Folder,
										   		  $this->Application_ConfigName,
										   		  $this->Application_Adapter);

		$this->Config_Section = $this->Execute_Type;

		try
		{
			$config = new \Library\Config($this->Application_ConfigSource, $this->Config_Section, $this);
			$configTree = $config->load();

			foreach($configTree->MCP as $property => $value)
			{
				if (! $this->exists($property))
				{
					$this->{$property} = $value;
				}

				if (array_key_exists($property, $cliDbMap))
				{
					$this->{$property} = CliParameters::parameterValue($cliDbMap[$property], $value);
				}
			}

			$this->Application_ConfigInstance = $config;
			$this->Application_ConfigTree = $configTree;
		}
		catch(\Library\Config\Exception $exception)
		{
			Support::exceptionDescriptor(new ExceptionDescriptor($exception));
		}
		catch(\Library\Exception $exception)
		{
			Support::exceptionDescriptor(new ExceptionDescriptor($exception));
		}
		catch(\Exception $exception)
		{
			Support::exceptionDescriptor(new ExceptionDescriptor($exception));
		}
	}

	/**
	 * mapParametersToProperties
	 *
	 * Map cli input into property values using cliMap to convert cli parameter name to property
	 * @param array $cliMap = parameter name to property name mapping array
	 */
	protected function mapParametersToProperties(array $cliMap)
	{
		foreach($cliMap as $parameter => $property)
		{
			$this->{$property} = CliParameters::parameterValue($parameter, $this->{$property});
		}
	}

}
