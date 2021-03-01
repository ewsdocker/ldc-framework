<?php
namespace Library\Config;
use Library\Error;

/*
 * 		Config\ConfigINI is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * Config\ConfigINI
 *
 * Configuration INI loader.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Config.
 */
class ConfigINI extends ConfigAbstract
{
	/**
	 * __construct
	 *
	 * Class constructor
	 * @param array $properties = (optional) properties array
	 */
	public function __construct($properties=null)
	{
		$this->properties = $properties;
		$this->configArray = null;
		$this->xmlString = null;
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 * @return null
	 */
	public function __destruct()
	{
	}

	/**
	 * load
	 *
	 * load the configuration file to an xml string
	 * @param string $config = (optional) configuration file name
	 * @return string $xmlString
	 * @throws EWSLibrary_Config_Exception
	 */
	public function load($iniString=null)
	{
		$this->configArray = \Library\INI\ConvertArray::toArray($iniString);
		$this->xmlString = \Library\INI\ConvertArray::toXML($this->configArray);
		return $this->xmlString;
	}

}
