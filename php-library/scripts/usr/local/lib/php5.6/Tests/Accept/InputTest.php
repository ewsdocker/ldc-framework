<?php
namespace Tests\Accept;

use \Application\Launcher\Testing\UtilityMethods;

use Library\Accept\Input;
use Library\PrintU;
use Library\Stream as Streamer;

/*
 *		Accept\InputTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * Accept\InputTest
 *
 * Accept\Input class tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Accept
 */
class InputTest extends UtilityMethods
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

		$this->a_newAccept('both', 'both', ' < ', 1);

		$word = 'ready';
		$this->waitForInput($word, null);
		$this->a_accept($word);

		$this->a_destructAccept();
	}

	/**
	 * a_waitForInput
	 * 
	 * Wait for input message available, wait for $timeout seconds
	 * @param string $word = word to wait for
	 * @param integer $timeout = (optional) number of seconds to wait (null = forever, 0 = return immediate)
	 */
	public function waitForInput($word, $timeout=1)
	{
		$this->labelBlock('Wait For Input', 40, '*');
		
		$this->a_prompt("Enter the word: $word");

		$this->a_resource = false;
		while($this->a_resource === false)
		{
			$this->a_waitAccept($timeout);
		}
		
		$this->a_showData($this->a_resource, 'Resource');
	}

	/**
	 * a_waitAccept
	 * 
	 * Check for an accept message available, wait for $timeout seconds
	 * @param integer $timeout = (optional) number of seconds to wait (null = forever, 0 = return immediate)
	 */
	public function a_waitAccept($timeout=null)
	{
		$this->labelBlock('Wait Accept', 40, '*');

		$this->a_timeout = $timeout;

		$assertion = '$this->a_resource = $this->a_object->acceptAvailable($this->a_timeout);';
		if (! $this->assertTrue($assertion, sprintf("waitAccept - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_resource, "Resource");
	}

	/**
	 * a_accept
	 * 
	 * Get the data from the input stream
	 * @param string $expected = expected input
	 * @param string $icase = (optional) input case shift
	 * @param string $trim = (optional) trim input stream
	 */
	public function a_accept($expected, $icase=null, $trim=null)
	{
		$this->labelBlock('Accept', 40, '*');

		$this->a_icase = $icase;
		$this->a_trim = $trim;

		$this->a_showData($expected, 'Expected');
		$this->a_showData($icase, 'Case');
		$this->a_showData($trim, 'Trim');

		$assertion = '(($this->a_data = $this->a_object->accept($this->a_icase, $this->a_trim)) !== false);';

		if (! $this->assertTrue($assertion, sprintf("Accept - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
	}

	/**
	 * a_prompt
	 * 
	 * Send a prompt to the output stream
	 * @param string $message = (optional) prompt message
	 */
	public function a_prompt($message=null)
	{
		$this->labelBlock('Prompt', 40, '*');

		$this->a_message = $message;
		$assertion = '($this->a_object->prompt($this->a_message) !== false);';

		if (! $this->assertTrue($assertion, sprintf("Prompt - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		 
	}

	/**
     * a_streamSelect
     *
     * Wait for i/o available on the current stream
     * @param integer $timeout = (optional) number of seconds to wait before timeout
     */
    public function a_streamSelect($timeout=1)
    {
    	$this->labelBlock('Stream Select', 40, '*');
    	
		$this->a_selectArray = array('file' => $this->a_file->handle);
		$this->a_timeout = $timeout;
		$this->a_key = null;
		$this->a_resource = null;

		$assertion = '$this->a_resource = $this->a_object->streamSelect($this->a_selectArray, $this->a_key, $this->a_timeout);';
    	if (! $this->assertTrue($assertion, sprintf("Stream Select - Asserting: %s", $assertion)))
    	{
    		$this->a_outputAndDie();
    	}
    	
		$this->a_showData($this->a_key, 'Key');
		$this->a_showData($this->a_resource, 'Resource');
    }

    /**
     * a_destructAccept
     * 
     * Destroy the object
     */
    public function a_destructAccept()
    {
    	$this->labelBlock('Destruct Accept.', 40, '*');
		$this->a_object = null;
    }

    /**
     * a_newAccept
     *
     * Create a new Accept class object
	 * @param string  $icase   = (optional) input string case mapping (upper, lower, both)
	 * @param string  $trim    = (optional) input string trim setting (left, right, both, none)
	 * @param string  $prompt  = (optional) prompt string to terminate prompt inputs
	 * @param integer $console = (optional) 1 to direct to console, 0 to direct to html
     */
    public function a_newAccept($icase='both', $trim='both', $prompt=' < ', $console=1)
    {
    	$this->labelBlock('Creating NEW Accept class object.', 40, '*');

    	$this->a_icase   = $icase;
    	$this->a_trim    = $trim;
    	$this->a_prompt  = $prompt;
    	$this->a_console = $console;

    	$assertion = '(($this->a_object = new \Library\Accept\Input($this->a_icase, $this->a_trim, $this->a_prompt, $this->a_console)) !== null);';

		if (! $this->assertExceptionTrue($assertion, sprintf("NEW Accept Object - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType(true, 'Library\Accept\Input', get_class($this->a_object));
    }

}
