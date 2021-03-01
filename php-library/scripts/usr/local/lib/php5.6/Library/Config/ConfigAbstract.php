<?php
namespace Library\Config;
use Library\Error;

/*
 * 		Config\ConfigAbstract is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * Config\ConfigAbstract
 *
 * Abstract configuration loader.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Config.
 */
abstract class ConfigAbstract
{
	/**
	 * properties
	 *
	 * Array containing the config adapter properties
	 * @var array $properties
	 */
	protected		$properties;

	/**
	 * configArray
	 * 
	 * A copy of the input string in an array format
	 * @var array $configArray
	 */
	protected		$configArray;

	/**
	 * xmlString
	 * 
	 * The configArray converted to an XML-encoded string
	 * @var string $xmlString
	 */
	protected		$xmlString;

	/**
	 * load
	 *
	 * Load the configuration file
	 * @param string $iniString = (optional) string to be converted
	 * @return string $xmlString
	 * @throws \Library\Config\Exception
	 */
	abstract public function load($iniString=null);

	/**
	 * xmlString
	 *
	 * set/get the xmlString value
	 * @param string $xmlString = (optional) xmlString, null to query
	 * @return string $xmlString
	 */
	public function xmlString($xmlString=null)
	{
		if ($xmlString !== null)
		{
			$this->xmlString = $xmlString;
		}

		return $this->xmlString;
	}

	/**
	 * configArray
	 *
	 * configuration file in an array
	 * @param string $configArray = (optional) configuration array, null to query
	 * @return string $configArray
	 */
	public function configArray($configArray=null)
	{
		if ($configArray !== null)
		{
			$this->configArray = $configArray;
		}

		return $this->configArray;
	}

	/**
	 * properties
	 *
	 * get/set properties array
	 * @param object|array $properties = (optional) properties array or object, null to query
	 * @return object $properties
	 * @throws \Library\Config\Exception
	 */
	public function properties($properties=null)
	{
		if ($properties !== null)
		{
			if (is_array($properties))
			{
				$properties = new \Library\Properties($properties);
			}
			
			if (! ($properties instanceOf \Library\Properties))
			{
				throw new Exception(Error::code('InvalidClassObject'));
			}

			$this->properties = $properties;
		}

		return $this->properties;
	}

	/**
	 * __get
	 *
	 * override parent __get to allow $section->name to return the value
	 * @param string $name = name of the value to fetch
	 * @return mixed $value = fetched value
	 */
	public function __get($name)
	{
		if (($name !== 'load') && method_exists($this, $name))
		{
			return $this->$name();
		}

		return null;
	}

	/**
	 * __set
	 *
	 * override parent __set to allow [ $section->name = $value ]to set the value
	 * @param string $name = name of the value to set
	 * @param mixed $value = value to set
	 */
	public function __set($name, $value)
	{
		if (($name !== 'load') && method_exists($this, $name))
		{
			$this->$name($value);
		}
	}

}
