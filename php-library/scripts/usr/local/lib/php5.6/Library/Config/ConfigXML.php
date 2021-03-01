<?php
namespace Library\Config;
use Library\Error;

/*
 * 		Config\ConfigXML is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * Config\ConfigXML
 *
 * Configuration XML loader.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Config.
 */
class ConfigXML extends ConfigAbstract
{
	/**
	 * __construct
	 *
	 * Class constructor
	 * @param array $parameters = (optional) parameters array
	 */
	public function __construct($parameters=null)
	{
		$this->parameters = $parameters;
		$this->configArray = null;
		$this->xmlString = null;
	}

	/**
	 * __destruct
	 *
	 * Class destructor
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
	 * @throws \Library\Config\Exception
	 */
	public function load($iniString=null)
	{
		$this->configArray = \Library\XML\ConvertArray::toArray($iniString);
	    if (array_key_exists('#document', $this->configArray))
	    {
	    	$this->configArray = $this->configArray['#document'];
	    }

	    $this->xmlString = $iniString;
	    return $this->xmlString;
	}

}
