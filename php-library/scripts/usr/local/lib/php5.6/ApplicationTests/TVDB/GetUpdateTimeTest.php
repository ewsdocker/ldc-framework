<?php
namespace ApplicationTests\TVDB;

use Library\CliParameters;
use Application\Utility\Support;

/*
 *		TVDB\GetUpdateTimeTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source, 
 *	  		or from http://opensource.org/licenses/academic.php
*/
/**
 *
 * Application\TVDB\GetUpdateTimeTest tests
 *
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage TVDB
 */
class GetUpdateTimeTest extends \Application\Launcher\Testing\UtilityMethods
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
		
		$updatesUrl = CliParameters::parameterValue('apiurl', 'http://thetvdb.com/api/Updates.php');
		$type = CliParameters::parameterValue('type', 'none');

		$url = sprintf('%s?type=%s', $updatesUrl, $type);
		$this->a_newGetTime($url);

		$this->a_getTimestamp();
	}

	/**
	 * a_getTimestamp
	 *
	 * Get a copy of the timestamp
	 * 
	 */
	public function a_getTimestamp()
	{
		$this->labelBlock('getTimestamp Test.', 40, '*');

		$assertion = '$this->a_timestamp = $this->a_updateTime->timestamp();';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_timestamp, 'a_timestamp');
	}

	/**
	 * a_newGetTime
	 *
	 * Create a new GetUpdateTime class object
	 * @param string $url = url of the function
	 */
	public function a_newGetTime($url)
	{
		$this->labelBlock('GetUpdateTime Class Test.', 60, '*');

		$this->a_url = $url;
		$this->a_showData($this->a_url, 'URL');

//$this->a_updateTime = new \Application\TVDB\GetUpdateTime($this->a_url);
		$assertion = '$this->a_updateTime = new \Application\TVDB\GetUpdateTime($this->a_url);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, 'Application\TVDB\GetUpdateTime', get_class($this->a_updateTime));

		$this->a_showData($this->a_updateTime, 'GetUpdateTime');
		$this->a_showData((string)$this->a_updateTime);
	}

}
