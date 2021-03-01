<?php
namespace ApplicationTests\TVDB;

use Library\CliParameters;
use Application\Utility\Support;

/*
 *		TVDB\UserLanguageTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source, 
 *	  		or from http://opensource.org/licenses/academic.php
*/
/**
 *
 * Application\TVDB\UserLanguageTest tests
 *
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage TVDB
 */
class UserLanguageTest extends \Application\Launcher\Testing\UtilityMethods
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
		
		$account = CliParameters::parameterValue('account', '9FC72AE60462A27C');
		$this->a_newUserLanguage('http://thetvdb.com/api/', $account);

		$this->a_records();
		$this->a_xmlString();

		$this->a_language();
		$this->a_abbreviation();
	}

	/**
	 * a_abbreviation
	 *
	 * Get the default language abbreviation
	 */
	public function a_abbreviation($expected='en')
	{
		$this->labelBlock('Get Language Abbreviation.', 50, '*');
	
		$this->a_showData($expected, 'Expected');
	
		$assertion = '$this->a_data = $this->a_language->abbreviation;';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
	
		$this->a_exceptionCaughtFalse();
	
		$this->a_compareExpected($expected);
	}

	/**
	 * a_language
	 *
	 * Get the default language name
	 */
	public function a_language($expected='English')
	{
		$this->labelBlock('Get Language Name.', 50, '*');
	
		$this->a_showData($expected, 'Expected');
	
		$assertion = '$this->a_data = $this->a_language->name;';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
	
		$this->a_exceptionCaughtFalse();
	
		$this->a_compareExpected($expected);
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
		$this->labelBlock('xmlString Test.', 50, '*');

		$this->a_xmlFile = $xmlFile;
		$this->a_showData($this->a_xmlFile, 'xmlFile');

		$assertion = '$this->a_data = $this->a_language->xmlString($this->a_xmlFile);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_data, 'a_data');
	}

	/**
	 * a_records
	 *
	 * Get the xml records array
	 */
	public function a_records()
	{
		$this->labelBlock('records Test.', 50, '*');

		$assertion = '$this->a_recordsArray = $this->a_language->records();';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_recordsArray, 'records');
	}

	/**
	 * a_newLanguages
	 *
	 * Create a new Languages class object
	 * @param string $account = tvdb account number
	 * @param string $url = url of the tvdb api
	 */
	public function a_newUserLanguage($url, $account)
	{
		$this->labelBlock('UserLanguage Class Test.', 60, '*');

		$this->a_url = $url;
		$this->a_account = $account;

		$this->a_showData($this->a_url, 'URL');
		$this->a_showData($this->a_account, 'account');

		$assertion = '$this->a_language = new \Application\TVDB\UserLanguage($this->a_account, $this->a_url);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, 'Application\TVDB\UserLanguage', get_class($this->a_language));

		$this->a_showData($this->a_language, 'UserLanguage');
		$this->a_showData((string)$this->a_language);
	}

}
