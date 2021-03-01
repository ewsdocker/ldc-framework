<?php
namespace Tests\Select;

use \Application\Launcher\Testing\UtilityMethods;

use Library\Select\Process;

/*
 *		Select\ProcessTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * Select\ProcessTest
 *
 * Select\Process class tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Select
 */
class ProcessTest extends PollTest
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

		$this->a_newProcess();
		$this->a_readyDescriptors();
		
		$this->a_descriptorBuffer('stdout', 'write', 'Enter password: ');

		$this->a_enableStream('stdout', 'write');
		$this->a_enableStream('stdin', 'read');

		$this->a_setTimeout(10.0);
		$this->a_pollLoop(10);
		
		$this->a_selectReady();
		$this->a_selected();
		
		$this->a_readyDescriptors();

		$this->a_isEnabled('stdin', 'read', true);

		$this->a_pollLoop(10);
		
		$this->a_selectReady();
		$this->a_selected();
		
		$this->a_readyDescriptors();
		$this->a_descriptorBuffer('stdin', 'read');
	}

	/**
	 * a_readyDescriptors
	 * 
	 * Process ready descriptors
	 */
	public function a_readyDescriptors()
	{
		$this->labelBlock('New Process', 40);

		$assertion = '$this->a_process->readyDescriptors();';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_showData((string)$this->a_storage, "Storage");
	}

	/**
	 * a_newProcess
	 * 
	 * Create a new Select\Poll object
	 */
	public function a_newProcess()
	{
		$this->labelBlock('New Process', 40);

		$assertion = '$this->a_process = new \Library\Select\Process($this->a_storage);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, 'Library\Select\Process', get_class($this->a_process));

		$this->a_showData((string)$this->a_process, "Process");
	}

}
