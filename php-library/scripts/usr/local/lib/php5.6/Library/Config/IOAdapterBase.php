<?php
namespace Library\Config;
use Library\Error;

/*
 * 		IOAdapterBase is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * IOAdapterBase
 *
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Config.
 */
class IOAdapterBase
{
	/**
	 * properties
	 *
	 * Parameters array
	 * @var array $properties
	 */
	protected		$properties;

	/**
	 * adapterName
	 *
	 * Name of the adapter to use
	 * @var string $adapterName
	 */
	protected		$adapterName;

	/**
	 * adapter
	 *
	 * The adapter instance
	 * @var object $adapter
	 */
	protected		$adapter;

	/**
	 * config
	 *
	 * Configuration file name
	 * @var string $config
	 */
	protected		$config;

	/**
	 * configArray
	 *
	 * INI array contents
	 * @var array $configArray
	 */
	protected		$configArray;

	/**
	 * xmlString
	 *
	 * XML string buffer
	 * @var string $xmlString
	 */
	protected		$xmlString;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param mixed $properties = (optional) properties array or object
	 */
	public function __construct($properties=null)
	{
		$this->adapterName = null;
		$this->properties($properties);
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 */
	public function __destruct()
	{
	}

	/**
	 * connect
	 *
	 * connect to the adapter
	 * @param string $factory = adapter factory class name
	 * @param string $config = (optional) configuration file name
	 * @return string $xmlString
	 * @throws \Library\Config\Exception
	 */
	public function connect($factory, $config=null)
	{
		if ($this->adapter)
		{
			unset($this->adapter);
			$this->adapter = null;
		}

		$properties = $this->properties();

		if (! $adapterName = $this->adapterName())
		{
			if (! $properties->exists('Config_IoAdapterType'))
			{
				throw new Exception(Error::code('AdapterError'));
			}

			$this->adapterName = $properties->Config_IoAdapterType;
		}

		if (! $this->config($config))
		{
			if (! $properties->exists('Config_Source'))
			{
				throw new Exception(Error::code('MissingFileName'));
			}

			$this->config($properties->Config_Source);
		}

		$this->adapter = $factory::instantiateClass($this->adapterName, 
													$this->config, 
													$this->properties->Config_Mode);

	}

	/**
	 * config
	 *
	 * get/set the configuration file name
	 * @param string $config = (optional) configuration file name, null to query
	 * @return string $config
	 */
	public function config($config=null)
	{
		if ($config !== null)
		{
			$this->config = $config;
		}

		return $this->config;
	}

	/**
	 * configArray
	 *
	 * get/set the ini array
	 * @param array $configArray = (optional) ini array, null to query
	 * @return array $configArray
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
	 * xmlString
	 *
	 * get/set the xml string
	 * @param string $xmlString = (optional) xml string, null to query
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
	 * adapterName
	 *
	 * get/set the adapterName name
	 * @param string $adapterName = (optional) adapterName name, null to query
	 * @return string $adapterName
	 */
	public function adapterName($adapterName=null)
	{
		if ($adapterName !== null)
		{
			$this->adapterName = $adapterName;
		}

		return $this->adapterName;
	}

	/**
	 * properties
	 *
	 * get/set the properties array
	 * @param array $properties = (optional) properties array, null to query
	 * @return array $properties
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
				throw new \Library\Config\Exception(Error::code('MissingParametersArray'));
			}

			$this->properties = $properties;
		}

		return $this->properties;
	}

}
