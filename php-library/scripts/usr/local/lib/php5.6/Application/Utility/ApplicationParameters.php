<?php
namespace Application\Utility;

use Library\CliParameters;
use Library\Properties;

/*
 *		Application\Utility\ApplicationParameters is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\Utility\ApplicationParameters
 *
 * Run-time Parameters (CliParametrs) mapped to class properties.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Application
 * @subpackage Utility
 */
class ApplicationParameters extends Properties
{
	/**
	 * __construct
	 *
	 * Class constructor
	 * @param array $defaultProperties = default property names/values
	 * @param array $cliMap = Cli parameter names to property name map array to map potential Cli input into properties
	 * @throws Application\Utility\Exception
	 */
	public function __construct($defaultProperties, $cliMap)
	{
		parent::__construct($defaultProperties);
		$this->parametersToProperties($cliMap);
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
	 * parametersToProperties
	 *
	 * Using the cliMap array, map cli input into property values
	 * @param array $cliMap = cli parameter name to property name mapping array
	 */
	private function parametersToProperties(array $cliMap)
	{
		foreach($cliMap as $parameter => $property)
		{
			$this->{$property} = CliParameters::parameterValue($parameter, $this->{$property});
		}
	}

}
