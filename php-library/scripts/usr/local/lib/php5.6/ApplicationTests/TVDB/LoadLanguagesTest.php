<?php
namespace ApplicationTests\TVDB;

use Application\Utility\Support;

/*
 *		TVDB\LoadLanguagesTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *	  		or from http://opensource.org/licenses/academic.php
*/
/**
 *
 * Application\TVDB\LoadLanguagesTest tests
 *
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage TVDB
 */
class LoadLanguagesTest extends TVDBTestLib
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
		$this->a_table = 'Languages';
		$this->a_xmlFile = 'languages.xml';

		parent::assertionTests($logger, $format);

		$this->a_languagesUrl = sprintf('%s%s/%s', $this->a_apiUrl, $this->a_apiKey, $this->a_xmlFile);

		$this->a_newDataClass($this->a_table, $this->a_languagesUrl);

		$this->a_records();
		$this->a_xmlString();

		$this->a_xmlString(Support::absoluteFileName(sprintf('Tests/XML/TestFiles/%s', $this->a_xmlFile)));
	}

	/**
	 * a_xmlString
	 *
	 * Get a copy of the xml object as an XML 1.0 string, optionally save to $xmlFile
	 * @param string $xmlOutputFile = (optional) file to save xml string to
	 *
	 */
	public function a_xmlString($xmlOutputFile=null)
	{
		$this->labelBlock('xmlString Test.', 40, '*');

		$this->a_xmlOutputFile = $xmlOutputFile;
		$this->a_showData($this->a_xmlOutputFile, 'xmlOutputFile');

		$assertion = '$this->a_data = $this->a_dataLink->xmlString($this->a_xmlOutputFile);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_data, 'a_data');
	}

}
