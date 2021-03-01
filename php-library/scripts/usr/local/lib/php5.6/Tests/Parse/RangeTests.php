<?php
namespace Tests\Parse;

/*
 *		Parse\RangeTests is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, 
 *            or from http://opensource.org/licenses/academic.php
*/
/**
 * RangeTests
 *
 * Parse\RangeTests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Parse
 */
class RangeTests extends \Application\Launcher\Testing\UtilityMethods
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
     * @param string $logger = (optional) name of the logger to use, null for none
     * @param string $format = (optional) log output format 
     */
    public function assertionTests($logger=null, $format=null)
    {
    	parent::assertionTests($logger, $format);
    	
    	$this->a_limits();
    	$this->a_limits('12-120', 1, 120);
		$this->a_limits('1-', 1, 120);
		$this->a_limits('-89', 10, 120);
    }
    
    /**
     * a_limits
     * 
     * Pass $range, $first and $last to Range::limits and retrieve a_first and a_last as results
     * @param string $range = string containing the range to parse
     * @param number $first = the minimum value in the specified range (defaults to 1)
     * @param string $last = the maximum value in the specified range (defaults to $first)
     */
	public function a_limits($range='all', $first=1, $last=null)
	{
		$this->labelBlock('Limits', 40);

		$this->a_range = $range;
		$this->a_first = $first;
		$this->a_last = $last;
		
		$this->a_showData($this->a_range, 'Range');
		$this->a_showData($this->a_first, 'First');

		if ($this->a_last === null)
		{
			$this->a_last = $this->a_first;
		}

		$this->a_showData($this->a_last, 'Last');

		$assertion = '(($this->a_data = \Library\Parse\Range::limits($this->a_range, $this->a_first, $this->a_last)) !== false);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->assertLogMessage('Expected true, result is false');
			exit(1);
		}

		$this->a_exceptionCaughtFalse();
    	
		$this->a_compareExpected($this->a_first, true);

		$this->a_showData($this->a_first, 'Limits (minimum)');
		$this->a_showData($this->a_last, 'Limits (maximum)');
	}

}