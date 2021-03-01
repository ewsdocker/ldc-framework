<?php
namespace Tests\TestStats;

use Application\Launcher\Testing\ProcessDescriptor;

use Library\Error;

/*
 * 		Tests\TestsStats\TestStatsTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * Tests\TestsStats\TestStatsTest.
 *
 * ProcessDescriptor class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Tests
 * @subpackage TestStats
 */

class TestStatsTest extends \Application\Launcher\Testing\UtilityMethods
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
    	
    	$properties = array('Started' 	=> microtime(true),
    						'Ended'   	=> microtime(true),
    						'Name'		=> get_class($this),
    						);
    	$this->a_newTestStats($properties, 'Application\Launcher\ProcessDescriptor');
    	
    	$this->a_displayTestStats();
    	
    	$this->a_delaySeconds(10);
    	
    	$this->a_setTestEnd();
    	
    	$this->a_displayTestStats();
    	
    	$this->a_getElapsed();
    }

    /**
     * a_getElapsed
     * 
     * Compute the elapsed time of the test steps.
     */
    public function a_getElapsed()
    {
    	$this->labelBlock('Get Elapsed.', 40, '*');

    	$assertion = '$this->a_elapsed = $this->a_stats->Ended - $this->a_stats->Started;';
    	if (! $this->assertTrue($assertion, sprintf("Get Elapsed - Asserting: %s", $assertion)))
    	{
    		$this->a_outputAndDie();
    	}
    	
    	$this->assertLogMessage(sprintf('Elapsed = %f seconds.', $this->a_elapsed));
    }

    /**
     * a_setTestEnd
     * 
     * Set the test end time (microtime)
     */
    public function a_setTestEnd()
    {
    	$this->labelBlock('Set Test End.', 40, '*');
    	
    	$this->a_ended = microtime(true);
    	$this->a_showData($this->a_ended, 'Ended');
    	 
    	$assertion = '(($this->a_stats->Ended = $this->a_ended) != 0);';
    	if (! $this->assertTrue($assertion, sprintf("Set Test End - Asserting: %s", $assertion)))
    	{
    		$this->a_outputAndDie();
    	}
    }

    /**
     * a_delaySeconds
     * 
     * Delay the number of seconds specified
     * @param integer $seconds = number of seconds to delay
     */
    public function a_delaySeconds($seconds)
    {
    	$this->labelBlock('Delay Seconds.', 40, '*');

    	$this->a_delay = 1000 * $seconds;
    	$this->a_showData($seconds, 'Seconds');
    	$this->a_showData($this->a_delay, 'Delay');

    	$assertion = sprintf('usleep($this->a_delay);');
    	$this->assertTrue($assertion, sprintf("Delay Seconds - Asserting: %s", $assertion));
    }

    /**
     * a_displayTestStats
     * 
     * Display the ProcessDescriptor object structure
     */
    public function a_displayTestStats()
    {
    	$this->labelBlock('Display TestStats.', 40, '*');

    	$this->assertLogMessage('ProcessDescriptor: ' . (string)$this->a_stats);
    }

    /**
     * a_newTestStats
     * 
     * Create a new ProcessDescriptor class
     * @param object $properties = properties object to base ProcessDescriptor on
     * @param string $expected = expected class name of the created object
     */
    public function a_newTestStats($properties, $expected)
    {
    	$this->labelBlock('New ProcessDescriptor.', 40, '*');
    	
    	$this->a_properties = $properties;

    	$assertion = '$this->a_stats = new Application\Launcher\ProcessDescriptor($this->a_properties);';
    	if (! $this->assertTrue($assertion, sprintf("New ProcessDescriptor - Asserting: %s", $assertion)))
    	{
    		$this->a_outputAndDie();
    	}
    	
    	$this->a_compareExpectedType(true, $expected, get_class($this->a_stats));
    }

}
