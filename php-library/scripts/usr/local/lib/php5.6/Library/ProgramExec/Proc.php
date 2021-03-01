<?php
namespace Library\ProgramExec;
use Library\Error;
use Library\Exception\Descriptor;
use Library\Properties;

/*
 * 		Proc is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
 */

/**
 * Proc.
 *
 * Process execution class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage ProgramExec
 */

class Proc
{
	const	STATE_CONSTRUCT = 1;
	const	STATE_OPEN 		= 2;
	const	STATE_CLOSE 	= 3;
	const	STATE_DESTRUCT 	= 4;
	const	STATE_TERMINATE	= 5;

	const	PIPE_TO_STDIN	= 0;	// The number of the STDIN  pipe element
	const	PIPE_TO_STDOUT	= 1;	// The number of the STDOUT pipe element
	const	PIPE_TO_STDERR	= 2;	// The number of the STDERR pipe element

	/**
	 * name
	 * 
	 * The (internal) name of the Proc
	 * @var string $name
	 */
	public $name;

	/**
	 * descriptorSpec
	 * 
	 * An indexed array where the key represents the descriptor number 
	 *    and the value represents how PHP will pass that descriptor to the child process
	 * @var array $descriptorSpec
	 */
	protected $descriptorSpec;

	/**
	 * pipes
	 * 
	 * Set to file descriptors by PHP
	 * @var array $pipes
	 */
	public $pipes;

	/**
	 * cwd
	 * 
	 * Default working directory at process start
	 * @var string $cwd
	 */
	protected $cwd;

	/**
	 * env
	 * 
	 * Environment variables to apply to process environment
	 * @var unknown
	 */
	protected $env;

	/**
	 * otherThings
	 * 
	 * Array of other option/argument fields
	 * @var array $otherThings
	 */
	protected $otherThings;

	/**
	 * process
	 * 
	 * a resource representing the process
	 * @var resource $process
	 */
	protected $process;

	/**
	 * status
	 * 
	 * a Properties object containing (running) process status (only if $process <> null)
	 * @var object $status
	 */
	protected $status;

	/**
	 * terminateStatus
	 * 
	 * The result from the terminate/close operation
	 * @var integer $terminateStatus
	 */
	protected $terminateStatus;

	/**
	 * writeableProperties
	 * 
	 * @var array $writeableProperties;
	 */
	private $writeableProperties;

	/**
	 * readOnly
	 * 
	 * Read-only state for writeableProperties
	 * @var boolean $readOnly
	 */
	private $readOnly;

	/**
	 * exceptionDescriptor
	 * 
	 * A copy of the last Exception processed
	 * @var Exception\Descriptor $exceptionDescriptor
	 */
	private $exceptionDescriptor;

	/**
	 * command
	 * 
	 * The current command to/being excute(d)
	 * @var string $command
	 */
	public $command;

	/**
	 * useExec
	 * 
	 * if true, preceed $command with 'exec '
	 * @var boolean $useExec
	 */
	private $useExec;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $name = the internal name of the process
	 * @param string $command = (optional) command to execute
	 */
	public function __construct($name, $command=null)
	{
		$this->name = $name;
		$this->process = null;
		$this->exceptionDescriptor = null;
		$this->status = null;
		$this->readOnly = false;
		$this->terminateStatus = 1;
		$this->writeableProperties = array('cwd', 'env', 'otherThings');
		$this->command = $command;
		$this->useExec = false;
		
		$this->cwd = null;
		$this->env = null;
		$this->otherThings = null;
		
		$this->descriptorSpec = array(self::PIPE_TO_STDIN  => array('pipe', 'r'),
				                      self::PIPE_TO_STDOUT => array('pipe', 'w'),
									  self::PIPE_TO_STDERR => array('file', '/tmp/phpTest-Proc.txt', 'a'));

		$this->pipes = array();

		if ($this->command !== null)
		{
			$this->open($this->command);
		}
		
	}

	/**
	 * __destruct
	 * 
	 * Class destructor
	 * 
	 */
	public function __destruct()
	{
		$this->readOnly = false;
		if ($this->isOpen())
		{
			$this->terminate();
			$this->close();
		}
	}
	
	/**
	 * __get
	 * 
	 * Magic function to get a property, if it exists, null if not
	 * @param string $property = name of the property to get
	 * @return mixed $value = value of the property, null if it does not exist
	 */
	public function __get($property)
	{
		if (property_exists($this, $property))
		{
			return $this->{$property};
		}

		return null;
	}

	/**
	 * __set
	 * 
	 * Magic function to dynamically set a property to a given value
	 * @param string $property = name of the property to set
	 * @param mixed $value = value to set property to
	 */
	public function __set($property, $value)
	{
		if ($this->readOnly)
		{
			$this->checkNotOpen();

			if (array_search($property, $this->writeableProperties) == false)
			{
				throw new Exception(Error::code('ProcessPropertyReadOnly'));
			}
		}

		$this->{$property} = $value;
	}

	/**
	 * open
	 * 
	 * Execute a command and open file pointers for input/output
	 * @param string $command = command to execute
	 * @throws Library\ProgramExec\Exception on failure
	 * @return boolean $result = status from isRunning()
	 */
	public function open($command)
	{
		if (! $command = $this->command($command))
		{
			throw new Exception(Error::code('ProcessCommandMissing'));
		}

		if ($this->useExec())
		{
			$command = $this->command('exec ' . $command);
		}

		if ($this->cwd !== null)
		{
			if ($this->env !== null)
			{
				if ($this->other_things !== null)
				{
					$this->process = proc_open($command, $this->descriptorSpec, $this->pipes, $this->cwd, $this->env, $this->other_things);
				}
				else 
				{
					$this->process = proc_open($command, $this->descriptorSpec, $this->pipes, $this->cwd, $this->env);
				}
			}
			else 
			{
				$this->process = proc_open($command, $this->descriptorSpec, $this->pipes, $this->cwd);
			}
		}
		else
		{
			$this->process = proc_open($command, $this->descriptorSpec, $this->pipes);
		}

		if (! is_resource($this->process))
		{
			throw new Exception(Error::code('ProcessOpenFailed'));
		}

		$this->readOnly = true;
		return $this->isRunning();
	}

	/**
	 * close
	 * 
	 * Close a process opened by proc_open
	 * @return number = terminate status
	 * @throws Library\ProgramExec\Exception if not open
	 */
	public function close()
	{
		$this->checkValid();

		foreach($this->pipes as $number => $resource)
		{
			if (is_resource($resource))
			{
				try
				{
					fclose($resource);
				}
				catch(Exception $exception)
				{
					$this->descriptor = new Descriptor($exception);
				}
			}
		}

		$this->terminateStatus = proc_close($this->process);

		return $this->terminateStatus;
	}

	/**
	 * getStatus
	 * 
	 * Get information about a process opened by proc_open
	 * @return object $status = a Property object containing the returned status
	 */
	public function getStatus()
	{
		if ($this->process !== null)
		{
			if ($status = proc_get_status($this->process))
			{
				if ($this->status)
				{
					$this->status = null;
				}

				$this->status = new ProcStatus($this->name, $status);

				return $this->status;
			}
		}

		throw new Exception(Error::code('ProcessStatusError'));
	}

	/**
	 * nice
	 * 
	 * Change the priority of the current process
	 * @param int $increment
	 * @return boolean = true if successful, false if not
	 * @throws Library\ProgramExec\Exception if not open
	 */
	public function nice($increment)
	{
		$this->checkValid();
		return proc_nice($this->process, $increment);
	}
	
	/**
	 * terminate
	 * 
	 * Kills a process opened by proc_open
	 * @param number $signal = (optional) signal to send to the process using the kill(2) system call
	 * @return integer $terminateStatus = terminate status returned by proc_terminate
	 * @throws Library\ProgramExec\Exception if not open
	 */
	public function terminate($signal=15)
	{
		$this->checkValid();

		if ($this->isOpen())
		{
			$this->terminateStatus = proc_terminate($this->process, $signal);
		}
		else
		{
			$this->terminateStatus = 1;
		}
		
		return $this->terminateStatus;
	}

	/**
	 * setDescriptor
	 * 
	 * Set the indexed descriptor value
	 * @param integer $element = descriptor element number
	 * @param array $descriptor = descriptor value (null to unset)
	 * @throws Library\ProgramExec\Exception if process is open
	 */
	public function setDescriptor($element, $descriptor)
	{
		$this->checkValid();
		$this->descriptorSpec[$element] = $descriptor;
	}

	/**
	 * toggleReadOnly
	 * 
	 * Toggle the Read-Only flag
	 */
	protected function toggleReadOnly()
	{
		$this->readOnly = true;
	}

	/**
	 * isRunning
	 * 
	 * Check to see if there is a current process and if there is, if it is running
	 * @return boolean true = running, false = not
	 * @throws Library\ProgramExec\Exception if not open
	 */
	public function isRunning()
	{
		if ($this->isOpen())
		{
			if ($this->getStatus())
			{
				return $this->status->running;
			}
		}
		
		return false;
	}

	/**
	 * isOpen
	 * 
	 * Return open status
	 * @return boolean = true if process is open, else false
	 */
	public function isOpen()
	{
		return (is_resource($this->process) && (get_resource_type($this->process) == 'process'));
	}

	/**
	 * checkNotOpen
	 * 
	 * Check that the process is not already open
	 * @throws \Library\ProgramExec\Exception
	 */
	protected function checkNotOpen()
	{
		if ($this->isOpen())
		{
			throw new Exception(Error::code('ProcessAlreadyOpen'));
		}
	}

	/**
	 * checkValid
	 * 
	 * Check for valid request (process is a valid resource)
	 * @throws \Library\ProgramExec\Exception
	 */
	protected function checkValid()
	{
		if (! $this->isOpen())
		{
			throw new Exception(Error::code('ProcessNotOpen'));
		}
	}

	/**
	 * checkResource
	 * 
	 * Check that passed variable is a resource
	 * @param resource $resource
	 * @throws Exception if not a resource
	 */
	public function checkResource($resource)
	{
		if (! is_resource($resource))
		{
			throw new Exception(Error::code('ResourceNotResource'));
		}
	}

	/**
	 * command
	 * 
	 * Set and/or get the command string
	 * @param string $command = (optional) command string
	 * @returns string $command = command string
	 */
	protected function command($command=null)
	{
		if ($command !== null)
		{
			$this->command = $command;
		}

		return $this->command;
	}

	/**
	 * read
	 *
	 * Read from Process STDOUT pipe
	 * @return string $buffer = response buffer
	 * @throws Library\ProgramExec\ProcIO\Exception
	 */
	public function read()
	{
		if (! $buffer = stream_get_contents($this->checkPipe(self::PIPE_TO_STDOUT)))
		{
			throw new Exception(Error::code('StreamReadFailed'));
		}

		return $buffer;
	}

	/**
	 * write
	 *
	 * Write to Process STDIN
	 * @param string $buffer = buffer to write
	 * @return integer $bytes = number of bytes written
	 * @throws Library\ProgramExec\ProcIO\Exception
	 */
	public function write($buffer)
	{
		if (! $buffer)
		{
			throw new Exception(Error::code('EmptyBuffer'));
		}

		if (! $bytes = fwrite($this->checkPipe(self::PIPE_TO_STDIN), $buffer))
		{
			throw new Exception(Error::code('StreamWriteFailed'));
		}
		
		return $bytes;
	}

	/**
	 * readErr
	 *
	 * Read from Process STDERR pipe
	 * @return string $buffer = response buffer
	 * @throws Library\ProgramExec\ProcIO\Exception
	 */
	public function readErr()
	{
		return stream_get_contents($this->checkPipe(self::PIPE_TO_STDERR));
	}

	/**
	 * closePipe
	 * 
	 * @param integer $pipeToClose = pipe to close
	 */
	public function closePipe($pipeToClose)
	{
		try
		{
			fclose($this->checkPipe($pipeToClose));
		}
		catch (Exception $exception)
		{
			; // ignore the exception, but don't do the fclose!
		}
		
		$this->pipes[$pipeToClose] = null;
	}

	/**
	 * checkPipe
	 * 
	 * Check pipe of the specified pipe number
	 * @param integer $pipe = pipe number
	 * @throws Exception if pipe not connected or invalid name
	 * @return resource $pipeResource = pipe resource to use
	 */
	protected function checkPipe($pipe)
	{
		if (! array_key_exists($pipe, $this->pipes))
		{
			throw new Exception(Error::code('InvalidPipeNumber'));
		}

		if (! is_resource($this->pipes[$pipe]))
		{
			throw new Exception(Error::code('ResourceNotResource'));
		}

		return $this->pipes[$pipe];
	}

	/**
	 * getPipes
	 * 
	 * Returns the current $pipes array
	 * @return array $pipes
	 */
	public function getPipes()
	{
		return $this->pipes;
	}

	/**
	 * useExec
	 * 
	 * Get/Set the useExec flag
	 * @param boolean $useExec = (optional) setting, null to query only
	 * @return boolean $useExec
	 */
	public function useExec($useExec=null)
	{
		if ($useExec !== null)
		{
			$this->useExec = $useExec;
		}

		return $this->useExec;
	}

}
