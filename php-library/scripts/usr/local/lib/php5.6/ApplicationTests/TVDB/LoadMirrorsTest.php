<?php
namespace ApplicationTests\TVDB;

use Library\CliParameters;
use Application\TVDB\Records\Mirror;
use Library\XML\LoadXML;

use Application\Utility\Support;

/*
 *		TVDB\LoadMirrorsTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source, 
 *	  		or from http://opensource.org/licenses/academic.php
*/
/**
 *
 * Application\TVDB\LoadMirrorsTest tests
 *
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage TVDB
 */
class LoadMirrorsTest extends \Application\Launcher\Testing\UtilityMethods
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
		$apiFile = 'mirrors.xml';

		$url = sprintf("http://thetvdb.com/api/%s/%s", $apiKey, $apiFile);
		$this->a_newMirrors($url);

		$this->a_records();
		$this->a_parseMirrors();
		$this->a_xmlString();
		
		$this->a_xmlString(Support::absoluteFileName(sprintf('Tests/XML/TestFiles/%s', $apiFile)));
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

		$assertion = '$this->a_data = $this->a_mirrors->xmlString($this->a_xmlFile);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_data, 'a_data');
	}

	/**
	 * a_parseMirrors
	 *
	 * Get a copy of the xml object as an XML 1.0 string, optionally save to $xmlFile
	 */
	public function a_parseMirrors()
	{
		$this->labelBlock('parseMirrors Test.', 40, '*');

		$assertion = '$this->a_urls = $this->a_mirrors->parseMirrors();';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_showData($this->a_urls, 'a_urls');
	}

	/**
	 * a_records
	 *
	 * Get the xml records array
	 */
	public function a_records()
	{
		$this->labelBlock('records Test.', 40, '*');

		$assertion = '$this->a_recordsArray = $this->a_mirrors->records();';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_recordsArray, 'recordsArray');
	}

	/**
	 * a_callbackFunction
	 * 
	 * @param array $args = callback function arguement in an array
	 * @return \Tests\XML\Language
	 */
	public function a_callbackFunction($args)
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
	 * a_setCallback
	 *
	 * Set the xml Callback function
	 * @param array $callback = callable array
	 */
	public function a_setCallback($callback)
	{
		$this->labelBlock('xmlCallback Test.', 40, '*');

		$this->a_callback = $callback;
		$this->a_showData($this->a_callback, 'callback');

		$assertion = '$this->a_callback = $this->a_languages->xmlSetCallback($this->a_callback);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_callback, 'callback');
	}

	/**
	 * a_newMirrors
	 *
	 * Create a new Mirrors class object
	 * @param string $url = url of the xml object to open
	 */
	public function a_newMirrors($url, $callback=null)
	{
		$this->labelBlock('Mirrors Class Test.', 60, '*');

		$this->a_url = $url;
		$this->a_callback = $callback;

		$this->a_showData($this->a_url, 'URL');
		$this->a_showData($this->a_callback, 'callback');

//$this->a_mirrors = new \Application\TVDB\Mirrors($this->a_url, $this->a_callback);
		$assertion = '$this->a_mirrors = new \Application\TVDB\Mirrors($this->a_url, $this->a_callback);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, 'Application\TVDB\Mirrors', get_class($this->a_mirrors));

		$this->a_showData($this->a_mirrors, 'Mirrors');
		$this->a_showData((string)$this->a_mirrors);
	}

}
