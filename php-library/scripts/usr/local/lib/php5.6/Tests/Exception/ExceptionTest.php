<?php
namespace Tests\Exception;

/*
 *		ExceptionTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * ExceptionTest
 *
 * Exception tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Exception
 */
class ExceptionTest extends \Application\Launcher\Testing\UtilityMethods
{
	/**
	 * __construct
	 *
	 * Class constructor
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

		$assertion = "new \\Library\\Factory('null');";
		if (! $this->assertExceptionFalse($assertion, sprintf("Throw exception, Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$assertion = sprintf('"%s"', $this->exception);
		if (! $this->assertTrue($assertion, sprintf("Decode exception, Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

    }

}
