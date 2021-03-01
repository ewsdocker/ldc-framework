<?php
namespace Tests\Select;

use \Application\Launcher\Testing\UtilityMethods;

use Library\Select\Poll;

/*
 *		Select\PollTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * Select\PollTest
 *
 * Select\Poll class tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Select
 */
class PollTest extends RegisterTest
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

		$this->a_newPoll();

		$this->a_registerStream('stdin', 'read', $this->a_inputHandle, null);
		$this->a_enableStream('stdin', 'read');

		$this->a_outputHandle = STDOUT;

		$this->a_registerStream('stdout', 'write', $this->a_outputHandle, null);
		$this->a_descriptorBuffer('stdout', 'write', 'A buffered message.');
		$this->a_enableStream('stdout', 'write');

		$this->a_setupPollArrays();
		$this->a_setTimeout(0.5);

		$this->a_pollLoop(10);

		$this->a_selectReady();
		$this->a_selected();
		
		
	}

	/**
	 * a_pollLoop
	 * 
	 * Poll for a successful outcome up to $loops times (or until failure signaled)
	 * @param number $loops
	 */
	public function a_pollLoop($loops=10)
	{
		$this->labelBlock('Poll Loop', 60);

		$this->a_readyEvents = false;

		for($index = 0; $index < $loops; $index++)
		{
			$this->assertLogMessage(sprintf('poll #% 3u', $index+1));

			if (! $this->a_pollSelected())
			{
				$this->assertLogMessage('Poll failed - nothing to do!');
				return;
			}

			if ($this->a_data)
			{
				break;
			}
		}

		$this->a_readyEvents = true;
		$this->a_showData((string)$this->a_poll, 'Poll Results');
	}

	/**
	 * a_pollSelected
	 * 
	 * Poll the selected arrays
	 * @return boolean true = requests pending, false = nothing to do
	 */
	public function a_pollSelected()
	{
		$this->labelBlock('Poll Selected', 40);

		$assertion = '(($this->a_data = $this->a_poll->poll()) > 0);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->assertLogMessage('No events ready.');
			return true;
		}

		$this->a_exceptionCaughtFalse(false);

		if ($this->exceptionCaught())
		{
			return false;
		}

		$this->a_showData((string)$this->a_storage, 'Storage');
		$this->a_showData((string)$this->a_poll, 'PollArrays');
		
		return true;
	}

	/**
	 * a_selected
	 * 
	 * Get the count of selected streams
	 */
	public function a_selected()
	{
		$this->labelBlock('Selected', 40);

		$assertion = '($this->a_data = $this->a_poll->selected());';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->assertLogMessage('No events ready.');
		}

		$this->a_showData($this->a_data, 'Selected');
	}

	/**
	 * a_selectReady
	 * 
	 * Get the count of selected streams in a ready state
	 */
	public function a_selectReady()
	{
		$this->labelBlock('SelectReady', 40);

		$assertion = '(($this->a_data = $this->a_poll->selectReady()) !== true);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->assertLogMessage('No events ready.');
		}

		$this->a_showData($this->a_data, 'SelectReady');
	}

	/**
	 * a_setTimeout
	 * 
	 * Set select timeout
	 * @param real $timeout = the timeout value (sec.msec) to set
	 */
	public function a_setTimeout($timeout)
	{
		$this->labelBlock('Set Timeout', 40);

		$this->a_timeout = $timeout;
		$this->a_showData($this->a_timeout, 'Timeout');

		$assertion = '$this->a_data = $this->a_poll->timeout($this->a_timeout);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData((string)$this->a_storage, 'Storage');
		$this->a_compareExpected($this->a_timeout, true);
	}

	/**
	 * a_setupPollArrays
	 * 
	 * Setup selected arrays
	 */
	public function a_setupPollArrays()
	{
		$this->labelBlock('Setup Poll Arrays', 40);

		$assertion = '($this->a_poll->setupPollArrays() !== false);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse(false);
		
		$this->a_showData((string)$this->a_storage, 'Storage');
		$this->a_showData((string)$this->a_poll, 'PollArrays');
	}

	/**
	 * a_newPoll
	 * 
	 * Create a new Select\Poll object
	 */
	public function a_newPoll()
	{
		$this->labelBlock('New Poll', 40);

		$assertion = '$this->a_poll = new \Library\Select\Poll($this->a_storage);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, 'Library\Select\Poll', get_class($this->a_poll));

		$this->a_showData((string)$this->a_poll, "Poll");
	}

}
