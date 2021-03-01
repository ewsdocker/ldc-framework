<?php
namespace Library;
use Library\Error;
use Library\Config\Exception;
use Library\Config\IOAdapterFactory;
use Library\Config\ConfigFactory;
use Library\DOM\Query as DOMQuery;
use Library\Properties;
use Library\Config\SectionTree;

/*
 * 		Config is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * Config
 *
 * Configuration file process.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Config.
 */
class Config
{
	/**
	 * config
	 *
	 * Configuration file name
	 * @var string $config
	 */
	protected		$config;

	/**
	 * section
	 *
	 * Section name to load from the configuration file
	 * @var string $section
	 */
	protected		$section;

	/**
	 * sectionTree
	 *
	 * An LLRB representation of the configuration file (extended) section
	 * @var Library\LLRBTree $sectionTree
	 */
	protected		$sectionTree;

	/**
	 * xml
	 *
	 * Current XML object
	 * @var object $xml
	 */
	protected		$xml;

	/**
	 * xmlString
	 *
	 * The (possibly converted) xml string loaded from the configuration file
	 * @var string $xmlString
	 */
	protected		$xmlString;

	/**
	 * properties
	 *
	 * The configuration properties array or Library\Properties container
	 * @var array|object $properties
	 */
	protected		$properties;

	/**
	 * __construct
	 *
	 * class constructor
	 * @param string $config = (optional) configuration file name
	 * @param string $section = (optional) section name
	 * @param object|array $properties = (optional) class properties
	 */
	public function __construct($config=null, $section=null, $properties=null)
	{
		$this->config($config);
		$this->section($section);
		$this->properties($properties);
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
	 * __toString
	 * 
	 * Returns a printable string of the vars
	 * @return string $vars
	 */
	public function __toString()
	{
		$buffer = '';
		foreach($this->loadedSections as $sectionNumber => $section)
		{
			$buffer .= sprintf("Section %u = %s\n\t%s\n", $sectionNumber, $section, $this->sectionTree);
		}

		return $buffer;
	}

	/**
	 * load
	 *
	 * load the configuration file (extended) section and covert to an LLRBTree in a Library\Config\SectionTree object
	 * @param string $config = (optional) configuration file name
	 * @param string $section = (optional) configuration file section name
	 * @param string $query = (optional) configuration query string, defaults to "/configdata"
	 * @return object $SectionTree
	 * @throws \Library\Config\Exception, \Library\DOM\Exception
	 */
	public function load($config=null, $section=null, $query='/configdata')
	{
		if (! $properties = $this->properties())
		{
			throw new Exception(Error::code('MissingParametersArray'));
		}

		$config = $this->config($config);
		$section = $this->section($section);

		if (! $properties->exists('Config_IoAdapter'))
		{
			throw new Exception(Error::code('MissingRequiredProperties'));
		}

		$this->adapter = IOAdapterFactory::instantiateClass($properties->Config_IoAdapter, $properties);

		$this->configAdapter = ConfigFactory::instantiateClass($properties->Config_Adapter, $properties);

		$ioData = $this->adapter->load($config);
		$this->xmlString = $this->configAdapter->load($ioData);

		$this->xmlQuery = new DOMQuery($this->xmlString, 'load');

		$this->loadedSections = array();
		$this->processSection($section, $query);
		return $this->configuration();
	}

	/**
	 * processSection
	 *
	 * process the configdata nodelist
	 * @param string $section = section to load data from
	 * @param string $query = xml query string
	 * @throws \Library\Config\Exception
	 */
	private function processSection($section, $query)
	{
		$xpathQuery = sprintf('%s/%s', $query, $section);

		$sectionNode = $this->xmlQuery->query($xpathQuery);
		if ((! $sectionNode->hasChildNodes()) || ($sectionNode->childNodes->length == 0))
		{
			throw new Exception(Error::code('ConfigBadFormat'));
		}

		if (in_array($section, $this->loadedSections))
		{
			throw new Exception(Error::code('SectionExtendsFailed'));
		}

		if ($sectionNode->hasAttribute('extends'))
		{
			$extends = $sectionNode->getAttribute('extends');
			$this->processSection($extends, $query);
		}

		array_push($this->loadedSections, $section);

		if (! $this->sectionTree)
		{
			$this->sectionTree = new SectionTree($sectionNode, true);
		}
		else
		{
			$this->sectionTree->parse($sectionNode, true);
		}
	}

	/**
	 * configuration
	 *
	 * get the pointer to the configuration tree
	 * @return object $tree = configuration tree root
	 * @throws \Library\Config\Exception
	 */
	public function configuration()
	{
		if (! $this->sectionTree)
		{
			throw new Exception(Error::code('NotInitialized'));
		}

		return $this->sectionTree;
	}

	/**
	 * xmlString
	 *
	 * set/get the xml string
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
	 * xml
	 *
	 * set/get the xml object
	 * @param object $xml = (optional) xml object, null to query
	 * @return object $xml
	 */
	public function xml($xml=null)
	{
		if ($xml !== null)
		{
			$this->xmlQuery = $xml;
		}

		return $this->xmlQuery;
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
	 * section
	 *
	 * get/set the section name
	 * @param string $section = (optional) section name, null to query
	 * @return string $section
	 */
	public function section($section=null)
	{
		if ($section !== null)
		{
			$this->section = $section;
		}

		return $this->section;
	}

	/**
	 * loadedSections
	 *
	 * Returns an array containing the loaded section names
	 * @return array $loadedSections
	 */
	public function loadedSections()
	{
		return $this->loadedSections;
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
				$properties = new Properties($properties);
			}

			if (! ($properties instanceOf Properties))
			{
				throw new Exception(Error::code('MissingParametersArray'));
			}

			$this->properties = $properties;
		}

		return $this->properties;
	}

}