<?php
namespace Tests\DBO;

use Library\CliParameters;

/*
 * 		DBO\ParseDsnTest is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * DBO\ParseDsnTest.
 *
 * DBO ParseDsn class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage DBO.
 */

class ParseDsnTest extends \Application\Launcher\Testing\UtilityMethods
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
     */
    public function assertionTests($logger=null, $format=null)
    {
    	parent::assertionTests($logger, $format);

    	$host = CliParameters::parameterValue('host', '10.10.10.4');
		$db = CliParameters::parameterValue('db', 'ProjectLibraryTest');

		$dsn = sprintf('mysql:host=%s;port=3306;charset=UTF8;dbname=%s', $host, $db);
		$this->a_newParseDsn($dsn, true);
		$this->a_parseDsn = null;

		$dsn = sprintf('mysql:port=3306;charset=UTF8;dbname=%s', $db);
		$this->a_newParseDsn($dsn, false);
    }

    /**
     * a_newParseDsn
     *
     * Parse the dsn into class properties
     * @param string $dsn = Data Source Name
     * @param boolean $testType = true to expect no exception, false to expect exception (default = true)
     */
    public function a_newParseDsn($dsn, $testType=true)
    {
    	$this->labelBlock('New ParseDsn.', 60, '*');

    	$this->a_dsn = $dsn;
   		$this->a_showData($this->a_dsn, 'a_dsn');

    	$assertion = '$this->a_parseDsn = new \Library\DBO\ParseDsn($this->a_dsn);';
    	switch($testType)
    	{
    		case false:
    			$result = $this->assertExceptionFalse($assertion, sprintf("Asserting: %s", $assertion));
    			break;
    			
    		default:
    			$result = $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion));
    			break;
    	}

    	$this->a_showData($this->a_parseDsn, 'a_parseDsn');

		if (! $result)
		{
			$this->a_outputAndDie();
		}

		switch($testType)
		{
			case false:
				break;
				$this->a_exceptionCaughtTrue();

			default:
				$this->a_exceptionCaughtFalse();
				break;
		}
    }

}
