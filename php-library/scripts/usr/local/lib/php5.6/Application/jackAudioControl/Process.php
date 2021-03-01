<?php
namespace Application\jackAudioControl;

use Application\jackAudioControl\AudioConfig\Application;
use Application\jackAudioControl\Console\Command;

use Library\Error;
use Library\ProgramExec\Proc;
use Library\Select;
use Library\Select\Drivers as SelectDrivers;
use Library\Select\Exception as SelectException;
use Library\Utilities\FormatVar;

/*
 *		jackAudioControl\Process is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
 */

/**
 * jackAudioControl\Process
 *
 * Start/stop the required external programS in the selected Audio configuration
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package jackAudioControl
 */
class Process
{
	/**
	 * tree
	 * 
	 * The audio node configuration tree
	 * @var SectionTree $tree
	 */
	private $tree;

	/**
	 * processes
	 * 
	 * the process element node array
	 * @var array $processes = array of AudioNode's containing the AudioElement's to process
	 */
	private $processes;

	/**
	 * processesRunning
	 * 
	 * Number of processes running on the last scan
	 * @var integer $processRunning
	 */
	protected $processesRunning;

	/**
	 * startSequence
	 * 
	 * Processing start sequence array
	 * @var array $startSequence
	 */
	private $startSequence;

	/**
	 * stopSequence
	 * 
	 * Processing stop sequence array
	 * @var array $stopSequence
	 */
	private $stopSequence;

	/**
	 * properties
	 * 
	 * Properties object containing current application settings
	 * @var object $properties
	 */
	private $properties;

	/**
	 * name
	 * 
	 * The name of the process (audio group)
	 * @var string $name
	 */
	private $name;

	/**
	 * select
	 * 
	 * Select class instance
	 * @var object $select
	 */
	private $select;

	/**
	 * console
	 * 
	 * jackAudioControl\Console object instance
	 * @var object $console
	 */
	protected $console;

	/**
	 * command
	 * 
	 * Command object
	 * @var object $command
	 */
	protected $command;

	/**
	 * executing
	 * 
	 * Execution flag: true = executing, false = ending
	 * @var unknown
	 */
	public $executing;

	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param SectionTree $properties = property object containing initialization 
	 * 										and jackAudioControl options
	 */
	public function __construct($properties)
	{
		$this->properties = $properties;

		$this->name = $properties->Jack_AudioSystem;
		$this->tree = $properties->Jack_ConfigTree->{$properties->Jack_ConfigGroup}->{$properties->Jack_AudioSystem};

		$this->processes = array();
		$this->startSequence = array();
		$this->stopSequence = array();

		$this->executing = true;
		$this->setWaitTimers();
		$this->processesRunning = 0;

		$this->select = new Select();
		$this->command = new Command();

		$this->console = new Console($this->select, $this->executing, $this->command);
	}
	
	/**
	 * __destruct
	 * 
	 * Class destructor
	 */
	public function __destruct()
	{
	}

	/**
	 * __toString
	 * 
	 * Return a printable string containing the process queues
	 * @return string $buffer
	 */
	public function __toString()
	{
		FormatVar::sort(false);
		FormatVar::sortValues(false);
		FormatVar::recurse(true);

		$buffer = FormatVar::format($this->processes, 'processes');
		$buffer .= "\n";
		$buffer .= FormatVar::format($this->startSequence, 'startSequence');
		$buffer .= "\n";
		$buffer .= FormatVar::format($this->stopSequence, 'stopSequence');
		
		return $buffer;
	}

	/**
	 * execute
	 * 
	 * Execute the console command
	 * @return integer 0 = successful, non-zero = result code
	 * @throws Exception
	 */
	public function execute()
	{
		$this->select->timeout(1.0);

		$this->executing = true;
		while($this->executing)
		{
			$this->select->processDescriptors();
			if ($this->command->changed())
			{
				if (! $this->executeCommand())
				{
					break;
				}
			}

			$this->checkProcessesRunning();
		}
		
		$this->unregisterProcesses();
	}

	/**
	 * unregisterProcesses
	 * 
	 * unregister all registered processes
	 */
	public function unregisterProcesses()
	{
		foreach($this->startSequence as $processName)
		{
			$process = $this->processes[$processName];
			$pipes = $process->getPipes();
			foreach($pipes as $number => $resource)
			{
				switch($number)
				{
				default:
				case Proc::PIPE_TO_STDOUT:
					$method = 'read';
					break;
		
				case Proc::PIPE_TO_STDIN:
					$method = 'write';
					break;
		
				case Proc::PIPE_TO_STDERR:
					$method = 'except';
					break;
				}

				$this->select->unregister($processName, $method);
			}
		}
	}

	/**
	 * executeCommand
	 * 
	 * Execute the console command
	 * @return boolean $result = true if processing should continue, false if not
	 */
	public function executeCommand()
	{
		switch ($this->command->getCommand())
		{
			case 'start':
				$this->startProcesses();
				break;
		
			case 'restart':
				$this->restartProcesses();
				break;
		
			case 'quit':
				$this->stopProcesses();
				$this->executing = false;
				return false;
		
			case 'stop':
				$this->stopProcesses();
				break;
		
			case 'status':
				$this->statusProcesses();
				break;
		
			default:
			case 'help':
			case '?':
				$this->showHelp();
				break;

		}

		$this->select->enable('jackAudio', 'read');

		return true;
	}

	/**
	 * checkProcessesRunning
	 * 
	 * Check for process which are no longer running and delete them
	 */
	public function checkProcessesRunning()
	{
		$this->processesRunning = 0;
		foreach($this->processes as $processName => $process)
		{
			if ($process->processStarted())
			{
				try
				{
					if ($process->isRunning())
					{
						$this->processesRunning++;
					}
					else
					{
						$this->stopProcess($processName);
					}
				}
				catch(SelectException $exception)
				{
					;
				}
			}
		}
	}

	/**
	 * startProcesses
	 * 
	 * Start all requested processes
	 * @param object $callbackClass = (optional) callback class, null to use jackAudio\Application class
	 * @throws Exception
	 */
	public function startProcesses($callbackClass=null)
	{
		foreach($this->startSequence as $processName)
		{
			$this->startProcess($processName);
		}

	}

	/**
	 * restartProcesses
	 * 
	 * Restart all not running processes
	 */
	public function restartProcesses()
	{
		foreach($this->startSequence as $processName)
		{
		    $process = $this->processes[$processName];
		    if (! $process->processStarted())
		    {
		    	$process->command = null;
				$this->startProcess($processName);
			}
		}
	}

	/**
	 * startProcess
	 * 
	 * Start the requested process
	 * @param unknown $processName
	 */
	public function startProcess($processName)
	{
		$this->checkProcess();
		$callback = array();
		
		$process = $this->processes[$processName];
		if (! $process->open())
		{
			throw new Exception(Error::code('ProcessOpenFailed'));
		}
		
		$pipes = $process->getPipes();
		foreach($pipes as $number => $resource)
		{
			switch($number)
			{
				default:
				case Proc::PIPE_TO_STDOUT:
					$method = 'read';
					break;
		
				case Proc::PIPE_TO_STDIN:
					$method = 'write';
					break;
		
				case Proc::PIPE_TO_STDERR:
					$method = 'except';
					break;
			}
		
			$callback = array('class'  => new SelectDrivers($this->select->storage()),
							  'method' => $method,
							  );
		
			$this->select->register($processName, $method, $resource, $callback);
		}
		
		$this->select->enable($processName, 'read');
		$this->wait($this->properties->Jack_StartWait);
	}

	/**
	 * stopProcesses
	 * 
	 * Stop all requested processes
	 * @throws Exception
	 */
	public function stopProcesses()
	{
		foreach($this->stopSequence as $processName)
		{
			$this->stopProcess($processName);
		}
	}

	/**
	 * stopProcess
	 * 
	 * Stop the requested process
	 * @param string $processName
	 * @throws Select\Exception
	 */
	public function stopProcess($processName)
	{
		$this->checkProcess();
		$process = $this->processes[$processName];

		if ($process->processStarted())
		{
			$pipes = $process->getPipes();
			foreach($pipes as $number => $resource)
			{
				switch($number)
				{
					default:
					case Proc::PIPE_TO_STDOUT:
						$method = 'read';
						break;
		
					case Proc::PIPE_TO_STDIN:
						$method = 'write';
						break;

					case Proc::PIPE_TO_STDERR:
						$method = 'except';
						break;
				}
		
				try
				{
					$this->select->unregister($processName, $method);
				}
				catch(SelectException $exception)
				{
					;
				}
			}
		
			$process->terminate();
			$process->close();

			$this->wait($this->properties->Jack_StopWait);
		}
	}

	/**
	 * showHelp
	 * 
	 * Output the list of commands to the console display
	 */
	public function showHelp()
	{
		$buffer = "Commands:\n";
		foreach($this->command->commands() as $command => $detail)
		{
			$buffer .= sprintf("\t%s\t\t%s\n", $command, $detail);
		}

		$buffer .= "\n";
		$this->console->display($buffer);
	}

	/**
	 * statusProcesses
	 * 
	 * Output the status of each process to the console display
	 */
	public function statusProcesses()
	{
		$buffer = '';
		foreach($this->processes as $processName => $process)
		{
			if ($process->processStarted())
			{
				$buffer .= (string)$process->getStatus() . "\n";
			}
		}
		
		$this->console->display($buffer);
	}

	/**
	 * loadProcesses
	 * 
	 * Load the tree elements into the Elements arrays
	 */
	public function loadProcesses()
	{
		$node = $this->rewindProcess();
		while($node !== null)
		{
    		$process = new Application($node->key(), $this->tree->{$node->key()});

    		$this->processes[$processName = $process->processName()] = $process;

    		$this->startSequence[$process->startSequence()] = $processName;
    		$this->stopSequence[$process->stopSequence()] = $processName;

    		$node = $this->nextProcess();
		}
		
		ksort($this->startSequence);
		ksort($this->stopSequence);
	}

	/**
	 * nextProcess
	 * 
	 * Get the next tree node, null if none
	 * @return SectionTree $node = next node
	 */
	protected function nextProcess()
	{
		return $this->tree->nextNode();
	}

	/**
	 * rewindProcess
	 * 
	 * Rewind tree pointer to the first node
	 * @return SectionTree $node = first child node
	 */
	protected function rewindProcess()
	{
		return $this->tree->firstNode();
	}

	/**
	 * startSequence
	 * 
	 * Return the start sequence array
	 * @return array $startSequence
	 */
	public function startSequence()
	{
		return $this->startSequence;
	}

	/**
	 * stopSequence
	 * 
	 * Return the stop sequence array
	 * @return array $stopSequence
	 */
	public function stopSequence()
	{
		return $this->stopSequence;
	}

	/**
	 * processes
	 * 
	 * Return the process elements array
	 * @return array $processes
	 */
	public function processes()
	{
		return $this->processes;
	}

	/**
	 * name
	 * 
	 * Return the process name
	 * @return string $name
	 */
	public function name()
	{
		return $this->name;
	}

	/**
	 * checkProcess
	 * 
	 * Check that the process handler has been initialized
	 * @throws Exception
	 */
	protected function checkProcess()
	{
		if ((count($this->processes) == 0) || (count($this->startSequence) == 0) || (count($this->stopSequence) == 0))
		{
			throw new Exception(Error::code('NotInitialized'));
		}
	}

	/**
	 * wait
	 * 
	 * Wait for requested number of seconds
	 * @param integer $seconds = seconds, default = 10
	 * @param integer $nano = nano-seconds, default = 0
	 */
	public function wait($waitTime)
	{
		$timeoutSec = (integer)$waitTime;
		$timeoutNano = (integer)(1000000000 * ($waitTime - $timeoutSec));

		time_nanosleep($timeoutSec, $timeoutNano);
	}

	/**
	 * setWaitTimers
	 * 
	 * Setup the wait timers
	 */
	private function setWaitTimers()
	{
		if (! $this->properties->exists('Jack_StartWait'))
		{
			$this->properties->Jack_StartWait = 0.1;
		}
		
		if (! $this->properties->exists('Jack_StopWait'))
		{
			$this->properties->Jack_StopWait = 0.5;
		}
	}

	/**
	 * processesRunning
	 * 
	 * Returns the number of processes running at the last pass of the scanner
	 * @return integer $processRunning
	 */
	public function processesRunning()
	{
		return $this->processesRunning;
	}

}
