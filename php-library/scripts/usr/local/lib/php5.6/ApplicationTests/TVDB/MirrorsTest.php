<?php
namespace ApplicationTests\TVDB;

use Application\TVDB\irrors;
use Library\CliParameters;

/*
 *		TVDB\MirrorsTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source, 
 *	  		or from http://opensource.org/licenses/academic.php
*/
/**
 *
 * Application\TVDB\Mirrors tests
 *
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage Mirrors
 */
class MirrorsTest extends \Application\Launcher\Testing\UtilityMethods
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
		$this->a_newMirrors($apiKey);
	}

	/**
	 * a_newMirrors
	 *
	 * Create a new TVDB\Mirrors class object
	 */
	public function a_newMirrors($apiKey, $apiUrl='http://thetvdb.com/api/')
	{
		$this->labelBlock('NEW TVDB\Mirrors Class Tests.', 60, '*');

		$this->a_apiKey = $apiKey;
		$this->a_apiUrl = $apiUrl;
		$this->a_mirrorsUrl = sprintf("%s%s/%s", $this->a_apiUrl, $this->a_apiKey, 'mirrors.xml');

		$this->a_showData($this->a_apiKey, 'API Key');
		$this->a_showData($this->a_apiUrl, 'API URL');
		$this->a_showData($this->a_mirrorsUrl, 'Mirrors URL');
		
//$this->a_mirrors = new \Application\TVDB\Mirrors($this->a_mirrorsUrl);
		$assertion = '$this->a_mirrors = new \Application\TVDB\Mirrors($this->a_mirrorsUrl);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, 'Application\TVDB\Mirrors', get_class($this->a_mirrors));

		$this->a_showData($this->a_mirrors, 'Mirrors');
		$this->a_showData((string)$this->a_mirrors);
	}

}
