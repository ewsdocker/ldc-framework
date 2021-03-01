<?php
namespace Tests\ProgramExec;

/*
 *		ProgramExec\ProcStatusTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, 
 *            or from http://opensource.org/licenses/academic.php
*/
/**
 * ProcStatusTest
 *
 * ProgramExec\ProcStatusTest Process class test.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage ProgramExec
 */
class ProcStatusTest extends \Application\Launcher\Testing\UtilityMethods
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

    	$this->a_newProc('php');
    	 
    	$this->a_getPipes();
    	$this->a_pipeArray = array();
    	
    	$this->a_pipeArray['STDIN']  = \Library\ProgramExec\Proc::PIPE_TO_STDIN;
    	$this->a_pipeArray['STDOUT'] = \Library\ProgramExec\Proc::PIPE_TO_STDOUT;
    	$this->a_pipeArray['STDERR'] = \Library\ProgramExec\Proc::PIPE_TO_STDERR;

    	$this->a_procStatus();
		$this->a_pid();
		$this->a_running();

		$this->a_propertyValue('command');
		$this->a_propertyValue('stop');

    	$this->a_write('<?php echo "PHP Echo\n" ?>');
   		$this->a_closeProcPipe($this->a_pipeArray['STDIN']);
    		 
    	$this->a_procStatus();

   		$this->a_read();
   		$this->a_closeProcPipe($this->a_pipeArray['STDOUT']);
   		
   		$this->a_procStatus();

   		$this->a_terminate();

   		$this->a_procStatus();

   		$this->a_closeProc();
    	
    	$this->a_propertyValue('exitcode');

	   	$this->a_destructProc();
   	}

    /**
     * a_propertyValue
     * 
     * Get the value of the status variable
     * @param string $name = name of the variable to get
     */
    public function a_propertyValue($name)
    {
    	$this->labelBlock('Property Value for "$name"', 40);
    	
    	$this->a_name = $name;

    	$assertion = 'sprintf("($this->a_data = $this->a_procStatus->%s)", $this->a_name) !== null;';
    	if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}
    	
    	$this->a_showData($this->a_data, $this->a_name);
    }

    /**
     * a_running
     * 
     * Get the running status of the process
     */
    public function a_running()
    {
    	$this->labelBlock('Running', 40);
    	 
    	$assertion = '($this->a_data = $this->a_procStatus->running) !== null;';
    	if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}
    	
    	$this->a_showData($this->a_data, 'running');
    }

    /**
     * a_pid
     * 
     * Get the PID of the process
     */
    public function a_pid()
    {
    	$this->labelBlock('PID', 40);
    	 
    	$assertion = '($this->a_data = $this->a_procStatus->pid) !== null;';
    	if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}
    	
    	$this->a_showData($this->a_data, 'PID');
    }

    /**
     * a_procStatus
     * 
     * Get the process status string
     */
    public function a_procStatus()
    {
    	$this->labelBlock('Proc Status', 40);

$this->a_procStatus = $this->a_proc->getStatus();
    	$assertion = '($this->a_procStatus = $this->a_proc->getStatus()) !== false;';
    	if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}
    	 
		$this->assertLogMessage(sprintf("\nProcess status\n==============\n %s", $this->a_procStatus));
    }

    /**
     * a_getPipes
     * 
     * Get the pipes array from Proc
     */
    public function a_getPipes()
    {
    	$this->labelBlock('Get Pipes', 40);

    	$assertion = '(($this->a_pipes = $this->a_proc->pipes) !== false);';
    	if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}
    	
    	$this->assertLogMessage('Test result is TRUE');
    }

    /**
     * a_closeProcPipe
     * 
     * Close the requested pipe
     * @param integer $pipe =  the pipe number to close
     */
    public function a_closeProcPipe($pipe)
    {
    	$this->labelBlock('Close Proc Pipe', 40);
    	
    	$this->a_pipe = $pipe;

    	$assertion = '($this->a_proc->closePipe($this->a_pipe) !== false);';
    	if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}
    	 
    	$this->assertLogMessage('Test result is TRUE');
    }

    /**
     * a_read
     * 
     * Read the buffer from the input pipe
     */
    public function a_read()
    {
    	$this->labelBlock('Read', 40);
    	
    	$assertion = '($this->a_data = $this->a_proc->read()) !== false;';
    	if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}
    	 
		$this->assertLogMessage($this->a_data);
    }

    /**
     * a_readErr
     * 
     * Read the buffer from the error pipe
     */
    public function a_readErr()
    {
    	$this->labelBlock('ReadErr', 40);
    	
    	$assertion = '($this->a_data = $this->a_proc->readErr()) !== false;';
    	if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		return;
    	}
    	 
		$this->assertLogMessage($this->a_data);
    }

    /**
     * a_write
     * 
     * Write the buffer to the output pipe
     * @param string $buffer = Buffer to write
     */
    public function a_write($buffer)
    {
    	$this->labelBlock('Write', 40);

    	$this->a_buffer = $buffer;
    	$this->a_showData($this->a_buffer, 'Buffer');

    	$assertion = '(($this->a_data = $this->a_proc->write($this->a_buffer)) !== false);';
    	if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}

		$this->a_exceptionCaughtFalse();
    	
		$this->a_showData($this->a_data, 'Write Result');
    }

    /**
     * a_openProc
     * 
     * Open the Proc connection
     * @param string $command
     */
    public function a_openProc($command)
    {
    	$this->labelBlock('Open Proc', 40);

    	$this->command = $command;

$this->a_data = $this->a_proc->open($this->command);
    	$assertion = '$this->a_data = $this->a_proc->open($this->command);';
    	if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}

    	$this->assertLogMessage('Test result is TRUE');
    }

    /**
     * a_terminate
     * 
     * Terminate the running process
     */
    public function a_terminate()
    {
    	$this->labelBlock('Terminate Proc', 40);

    	$assertion = '(($this->a_data = $this->a_proc->terminate()) !== null);';
    	if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}

    	$this->assertLogMessage(sprintf("Terminate status: %d", $this->a_data));

    	$this->a_compareExpected('-1', false);
    }

    /**
     * a_closeProc
     * 
     * Close the Proc connection
     */
    public function a_closeProc()
    {
    	$this->labelBlock('Close Proc', 40);

    	$assertion = '(($this->a_data = $this->a_proc->close()) !== null);';
    	if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}

    	$this->assertLogMessage(sprintf("Terminate status: %d", $this->a_data));

    	$this->a_compareExpected('-1', false);
    }

    /**
     * a_destructProc
     * 
     * Call class destructor to close all streams and the process.
     */
    public function a_destructProc()
    {
    	$this->labelBlock('Class Destructor', 40);

    	$this->a_proc = null;

    	$this->assertLogMessage('Test result is TRUE');
    }

    /**
     * a_newProc
     * 
     * Run a proc command
     * @param string $command = (optional) command to execute
     */
    public function a_newProc($command=null)
    {
    	$this->labelBlock('New Proc', 40);

    	if ($command != null)
    	{
			$this->a_command = $command;
    		$this->assertLogMessage($this->a_command);
    		$assertion = '$this->a_proc = new \Library\ProgramExec\Proc($this->a_command);';
    	}
    	else
    	{
    		$assertion = '$this->a_proc = new \Library\ProgramExec\Proc();';
    	}

    	if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}

    	$this->assertLogMessage('Test result is TRUE');
    }

}
