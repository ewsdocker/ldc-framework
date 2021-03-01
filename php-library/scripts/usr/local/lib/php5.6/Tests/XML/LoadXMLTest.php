<?php
namespace Tests\XML;

use Library\XML\LoadXML;
use Library\CliParameters;

use Application\TVDB\Records\Language;
use Application\Utility\Support;

/*
 *		XML\LoadXMLTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source, 
 *	  		or from http://opensource.org/licenses/academic.php
*/
/**
 *
 * Library\XML\LoadXML tests
 *
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage XML
 */
class LoadXMLTest extends \Application\Launcher\Testing\UtilityMethods
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
	 * @param string $format = (optional) format for logging
	 */
	public function assertionTests($logger=null, $format=null)
	{
		parent::assertionTests($logger, $format);
		
		$apiKey = CliParameters::parameterValue('apikey', 'D29BEE8036451BD0');
		$apiFile = 'languages.xml';

		$this->a_xmlUrl = sprintf("http://thetvdb.com/api/%s/%s", $apiKey, $apiFile);
		$this->a_newXML($this->a_xmlUrl);

		$this->a_xmlName('Languages');

		$this->a_xmlArray();
		$this->a_xmlString();
		
		$this->a_xmlString(Support::absoluteFileName(sprintf('Tests/XML/TestFiles/%s', $apiFile)));

		$this->a_xmlArray(array($this, 'a_xmlCallbackFunction'));
	}

	/**
	 * a_xmlCallbackFunction
	 * 
	 * @param array $args = callback function arguement in an array
	 * @return \Tests\XML\Language
	 */
	public function a_xmlCallbackFunction($args)
	{
		$leaves = array();

		foreach($args[0] as $key => $value)
		{
			if ($value->count() == 0)
			{
				$leaves[$key] = (string)$value;
			}
		}

		return new Language($leaves);
	}

	/**
	 * a_xmlCallback
	 *
	 * Set the xml Callback function
	 * @param array $callback = callable array
	 */
	public function a_xmlCallback($callback)
	{
		$this->labelBlock('xmlCallback Test.', 40, '*');

		$this->a_callback = $callback;
		$this->a_showData($this->a_callback, 'callback');

		$assertion = '$this->a_xmlCallback = $this->a_xml->xmlCallback($this->a_callback);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_xmlCallback, 'xmlCallback');
	}

	/**
	 * a_xmlName
	 *
	 * Get the xml element tag
	 * @param string $expected = expected name
	 * 
	 */
	public function a_xmlName($expected)
	{
		$this->labelBlock('xmlName Test.', 40, '*');

		$this->a_expected = $expected;
		$this->a_showData($this->a_expected, 'expected');

		$assertion = '$this->a_name = $this->a_xml->xmlName();';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, $this->a_expected, $this->a_name);
	}

	/**
	 * a_xmlArray
	 *
	 * Get a copy of the xml object as an XML 1.0 string, optionally save to $xmlFile
	 * @param string $callback = (optional) callback function
	 * 
	 */
	public function a_xmlArray($callback=null)
	{
		$this->labelBlock('xmlArray Test.', 40, '*');

		$this->a_callback = $callback;
//		$this->a_showData($this->a_callback, 'callback');

		$assertion = '$this->a_localArray = $this->a_xml->xmlArray($this->a_callback);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_localArray, 'a_localArray');
	}

	/**
	 * a_xmlString
	 *
	 * Get a copy of the xml object as an XML 1.0 string, optionally save to $xmlFile
	 * @param string $xmlFile = (optional) file to save xml string to
	 * 
	 */
	public function a_xmlString($xmlFile=null)
	{
		$this->labelBlock('xmlString Test.', 40, '*');

		$this->a_xmlFile = $xmlFile;

		$this->a_showData($this->a_xmlFile, 'xmlFile');

		$assertion = '$this->a_data = $this->a_xml->xmlString($this->a_xmlFile);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_data, 'a_data');
	}

	/**
	 * a_newXML
	 *
	 * Create a new LoadXML class object
	 * @param string $url = url of the xml object to open
	 */
	public function a_newXML($url)
	{
		$this->labelBlock('NEW LoadXML Class Test.', 60, '*');

		$this->a_url = $url;

		$this->a_showData($this->a_url, 'URL');
		
		$assertion = '$this->a_xml = new \Library\XML\LoadXML($this->a_url);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, 'Library\XML\LoadXML', get_class($this->a_xml));

		$this->a_showData($this->a_xml, 'XML');
		$this->a_showData((string)$this->a_xml);
	}

}
