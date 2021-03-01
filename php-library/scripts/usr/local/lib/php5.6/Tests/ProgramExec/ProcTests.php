<?php
namespace Tests\ProgramExec;

/*
 *		ProgramExec\ProcTests is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  Refer to the file named License.txt provided with the source, 
 *			or from http://opensource.org/licenses/academic.php
*/
/**
 * ProcTests
 *
 * ProgramExec\ProcTests Process class test.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage ProgramExec
 */
class ProcTests extends \Application\Launcher\Testing\UtilityMethods
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

		$this->a_typeTests(0);
		$this->a_typeTests(1);
	}

	/**
	 * a_typeTests
	 * 
	 * Perform tests using the current type variable 
	 * 		(0 = read/write Pipe tests, 1 = read/write tests)
	 * @param integer $type = type of test
	 */
	public function a_typeTests($type)
	{
		$this->a_typeTest = $type;
		
		$this->labelBlock(sprintf("Type Tests %s", $this->a_typeTest), 60);

		$this->a_newProc('php', 'php');
		 
		$this->a_runProcTests('<?php echo "PHP Echo\n" ?>');
		$this->a_destructProc();
		
		$this->a_newProc('php');
		$this->a_openProc('php');
		
		$this->a_runProcTests('<?php echo "PHP Echo\n" ?>');
		
		$this->a_closeProc();
		$this->a_destructProc();
	}

	/**
	 * a_runProcTests
	 * 
	 * Run a sequence of tests.
	 * @param string $buffer = buffer to send in writePipe
	 */
	public function a_runProcTests($buffer)
	{
		$this->a_getPipes();
		$this->a_pipeArray = array();
		
		$this->a_pipeArray['STDIN']  = \Library\ProgramExec\Proc::PIPE_TO_STDIN;
		$this->a_pipeArray['STDOUT'] = \Library\ProgramExec\Proc::PIPE_TO_STDOUT;
		$this->a_pipeArray['STDERR'] = \Library\ProgramExec\Proc::PIPE_TO_STDERR;

		if ($this->a_typeTest == 0)
		{
			$this->a_writePipe($this->a_pipeArray['STDIN'], $buffer);
			$this->a_closePipe($this->a_pipeArray['STDIN']);
		
			$this->a_readPipe($this->a_pipeArray['STDOUT']);
			$this->a_closePipe($this->a_pipeArray['STDOUT']);
		}
		else
		{
			$this->a_write($buffer);
			$this->a_closeProcPipe($this->a_pipeArray['STDIN']);
			 
			$this->a_read();
			$this->a_closeProcPipe($this->a_pipeArray['STDOUT']);
		}
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
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_pipes, 'a_pipes');
	}

	/**
	 * a_closePipe
	 * 
	 * Close the requested pipe
	 * @param integer $pipe =  the pipe number to close
	 */
	public function a_closePipe($pipe)
	{
		$this->labelBlock('Close Pipe', 40);
		
		$this->a_pipe = $this->a_pipes[$pipe];
		$this->a_showData($this->a_pipe, 'Pipe');

		$assertion = '(($this->a_data = fclose($this->a_pipe)) !== 0);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_showData($this->a_data, 'a_data');
	}

	/**
	 * a_readPipe
	 * 
	 * Read the buffer from the input pipe
	 * @param integer $pipe = pipe to read
	 */
	public function a_readPipe($pipe)
	{
		$this->labelBlock('Read Pipe', 40);

		$this->a_pipe = $this->a_pipes[$pipe];
		$this->a_showData($this->a_pipe, 'Pipe');

		$assertion = '($this->a_data = stream_get_contents($this->a_pipe)) !== false;';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_showData($this->a_data, 'a_data');
	}

	/**
	 * a_writePipe
	 * 
	 * Write the buffer to the output pipe
	 * @param integer $pipe = the number of the pipe to write to
	 * @param string $buffer = Buffer to write
	 */
	public function a_writePipe($pipe, $buffer)
	{
		$this->labelBlock('Write Pipe', 40);

		$this->a_pipe = $this->a_pipes[$pipe];
		$this->a_buffer = $buffer;
		
		$this->a_showData($this->a_buffer, 'Buffer');
		$this->a_showData($this->a_pipe, 'Pipe');

		$assertion = '(($this->a_data = fwrite($this->a_pipe, $this->a_buffer)) !== false);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_showData($this->a_data, 'a_data');
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
		$this->a_showData($this->a_pipe, 'a_pipe');

		$assertion = '(($this->a_data = $this->a_proc->closePipe($this->a_pipe)) !== false);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_showData($this->a_data, 'a_data');
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
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_showData($this->a_data, 'a_data');
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
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_showData($this->a_data, 'a_data');
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
			$this->a_outputAndDie();
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

		$this->a_command = $command;
		$this->a_showData($this->a_command, 'command');

		$assertion = '$this->a_data = $this->a_proc->open($this->a_command);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_compareExpectedType(true, true);
	}

	/**
	 * a_closeProc
	 * 
	 * Close the Proc connection
	 */
	public function a_closeProc()
	{
		$this->labelBlock('Close Proc', 40);

		$assertion = '(($this->a_data = $this->a_proc->close()) != -1);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
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
	}

	/**
	 * a_newProc
	 * 
	 * Run a proc command
	 * @param string $name = internal name of the new process
	 * @param string $command = (optional) command to execute
	 */
	public function a_newProc($name, $command=null)
	{
		$this->labelBlock('New Proc', 40);

		$this->a_name = $name;
		$this->a_command = $command;

		$this->a_showData($this->a_name, 'name');
		$this->a_showData($this->a_command, 'command');

		$assertion = '$this->a_proc = new \Library\ProgramExec\Proc($this->a_name';

		if ($command != null)
		{
			$assertion .= ', $this->a_command';
		}

		$assertion .= ');';

		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_showData($this->a_proc, 'a_proc');
	}

}
