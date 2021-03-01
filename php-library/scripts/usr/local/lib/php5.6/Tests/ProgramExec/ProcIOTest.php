<?php
namespace Tests\ProgramExec;

/*
 *		ProgramExec\ProcIOTests is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, 
 *            or from http://opensource.org/licenses/academic.php
*/
/**
 * ProcIOTests
 *
 * ProgramExec\ProcIOTests Process I/O class test.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage ProgramExec
 */
class ProcIOTests extends \Application\Launcher\Testing\UtilityMethods
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
    	
		$this->a_runProcTests('<?php echo "PHP Echo\n" ?>');
		$this->a_destructProc();
		
		$this->a_newProc();
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

    	$this->a_writePipe($this->a_pipeArray['STDIN'], $buffer);
    	$this->a_closePipe($this->a_pipeArray['STDIN']);
    	
    	$this->a_readPipe($this->a_pipeArray['STDOUT']);
    	$this->a_closePipe($this->a_pipeArray['STDOUT']);
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
     * a_closePipe
     * 
     * Close the requested pipe
     * @param integer $pipe =  the pipe number to close
     */
    public function a_closePipe($pipe)
    {
    	$this->labelBlock('Close Pipe', 40);
    	
    	$this->a_pipe = $this->a_pipes[$pipe];
    	$assertion = '$this->a_data = fclose($this->a_pipe);';
    	if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}
    	 
    	$this->assertLogMessage('Test result is TRUE');
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
    	
    	$assertion = '($this->a_data = stream_get_contents($this->a_pipe)) !== false;';
    	if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}
    	 
		$this->assertLogMessage($this->a_data);
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

    	$assertion = '(($this->a_data = fwrite($this->a_pipe, $this->a_buffer)) !== false);';
    	if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}

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

    	$assertion = '$this->a_data = $this->a_proc->open($this->command);';
    	if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}

    	$this->assertLogMessage('Test result is TRUE');
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
     * a_newProcIO
     * 
     * Run a proc command
     * @param string $command = (optional) command to execute
     * @param object $properties = (optional) properties object or array
     */
    public function a_newProcIO($command=null, $properties=null)
    {
    	$this->labelBlock('New Proc', 40);

    	if ($command != null)
    	{
			$this->a_command = $command;
    		$this->assertLogMessage($this->a_command);
    		$this->a_properties = $properties;
    		$assertion = '$this->a_proc = new \Library\ProgramExec\ProcIO($this->a_command, $this->a_properties);';
    	}
    	else
    	{
    		$assertion = '$this->a_proc = new \Library\ProgramExec\ProcIO();';
    	}

    	if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}

    	$this->assertLogMessage('Test result is TRUE');
    }

    /**
     * a_setProperties
     * 
     * Set default properties in the properties array for use in ProcIOTest methods
     * @param string $adapterName = name of the I/O adapter to use ('file', 'stream')
     */
    public function a_setProperties($adapterName)
    {
    	$this->a_newProperties(array('StreamIO_Mode'		=> 'r',
    								 'StreamIO_Type'		=> 't',
    								 'StreamIO_Suppress'	=> true,
    								 'StreamIO_Adapter'		=> 'stream',

    								 'FileIO_Mode'			=> 'r',
    								 'FileIO_Filename'		=> '',
    								 'FileIO_Adapter'		=> 'fileobject'));
    	
    	$this->a_updateProperty('ProcIO_Adapter', $adapterName);
    }

	/**
	 * a_updateProperties
	 *
	 * Update/Create multiple properties from an associative array
	 * @param array $array
	 */
	public function a_updateProperties($array)
	{
    	$this->labelBlock('Update Properties Tests.', 60, '*');

    	$this->a_array = $array;
    	$assertion = '$this->a_properties->setProperties($this->a_array);';
		if (! $this->assertExceptionFalse($assertion, sprintf('Update Properties - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

    /**
	 * a_propertyValue
	 *
	 * Get a property's value
	 * @param string $property = name of the property
	 * @param mixed $expected = expected value
	 */
	public function a_propertyValue($property, $expected)
	{
    	$this->labelBlock('Property Value Tests.', 60, '*');

    	$this->a_property = $property;
    	$assertion = sprintf('$this->a_data = $this->a_properties->%s;', $property);
		if (! $this->assertTrue($assertion, sprintf('Property Value - Asserting: %s', $assertion)))
		{
			if ($this->a_data !== $expected)
			{
				$this->a_outputAndDie();
			}
		}

		$this->a_compareExpected($expected);
	}

    /**
	 * a_updateProperty
	 *
	 * Update/Create a property's value
	 * @param string $property = name of the property to update
	 * @param mixed $value = value of the property to update
	 */
	public function a_updateProperty($property, $value)
	{
    	$this->labelBlock('Update Property Tests.', 60, '*');

    	$this->a_property = $property;
    	$this->a_data = $value;

    	$assertion = sprintf('$this->a_properties->%s = $this->a_data;', $property);
		if (! $this->assertTrue($assertion, sprintf('Update Property - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

	}

    /**
     * a_newProperties
     *
     * Create a new properties class object
     */
    public function a_newProperties($properties=null)
    {
    	$this->labelBlock('NEW Properties Class Tests.', 60, '*');
    
    	$this->a_fromProperties = $properties;
    
    	$assertion = '$this->a_properties = new \Library\Properties($this->a_fromProperties);';
    	if (! $this->assertTrue($assertion, sprintf('NEW Properties Class - Asserting: %s', $assertion)))
    	{
    		$this->a_outputAndDie();
    	}
    
    	$this->a_compareExpectedType(true, 'Library\Properties', get_class($this->a_properties));
    }

}
