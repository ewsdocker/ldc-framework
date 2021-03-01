<?php
namespace Application\jackAudioControl;

use Application\jackAudioControl\Exception;

use Application\Utility\Support;

use Library\CliParameters;
use Library\Exception\Descriptor as ExceptionDescriptor;
use Library\Properties;

/*
 *		Application\jackAudioControl\JackProperties is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\jackAudioControl\JackProperties
 *
 * Configuration Properties class for the jackAudioControl application.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Application
 * @subpackage jackAudioControl
 */
class JackProperties extends Properties
{
	/**
	 * __construct
	 *
	 * Class constructor
	 * @param array $properties = default properties
	 * @throws Exception
	 */
	public function __construct($properties, $defaultProperties, $cliProperties)
	{
		parent::__construct($properties);

		$this->setProperties($defaultProperties, true);
		$this->mapOptionsToProperties($cliProperties);

		$this->load();
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
	 * load
	 *
	 * Load the jackAudioControl AudioControl.xml configuration file
	 * @throws Exception
	 */
	public function load()
	{
		$this->Jack_ConfigSource = '';
		if (strpos($this->Jack_Folder, '/', 0) !== 0)
		{
			$this->Jack_ConfigSource = $this->Root_Path;
		}

		$this->Jack_ConfigSource = sprintf('%s/%s/%s.%s',
										   $this->Jack_ConfigSource,
										   $this->Jack_Folder,
										   $this->Jack_ConfigName,
										   $this->Jack_Adapter);

		try
		{
			$config = new \Library\Config($this->Jack_ConfigSource, $this->Config_Section, $this);
			$this->Jack_ConfigInstance = $config;
			$this->Jack_ConfigTree = $config->load();
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
	 * mapOptionsToProperties
	 *
	 * Map cli input into property values using cliOptions to convert cli parameter name to property
	 * @param array $cliOptions = option name to property name mapping array
	 */
	protected function mapOptionsToProperties(array $cliOptions)
	{
		foreach($cliOptions as $option => $property)
		{
			$this->{$property} = CliParameters::parameterValue($option, $this->{$property});
		}
	}

}
