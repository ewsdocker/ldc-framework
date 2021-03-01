<?php
namespace Application\jackAudioControl\AudioConfig;

use Library\Config\SectionTree as AudioNode;
use Library\ProgramExec\Proc;
use Library\Error;
use Library\Exception\Descriptor as ExceptionDescriptor;

/*
 *		jackAudioControl\AudioConfig\ProcessElement is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * jackAudioControl\AudioConfig\Application
 *
 * Load the Node and provide processing of the start and stop commands
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package jackAudioControl
 * @subpackage AudioConfig
 */
class Application extends Proc
{
	/**
	 * node
	 * 
	 * the audio element node
	 * @var AudioNode $node
	 */
	private $node;

	/**
	 * pipeConnection
	 * 
	 * An array to map pipe array to connection (pipe) names
	 * @var array $pipeConnection
	 */
	protected $pipeConnection;

	/**
	 * processStarted
	 * 
	 * true = process has been started, false = not started (process has not started / has exited)
	 * @var boolean $processStarted
	 */
	public $processStarted;

	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param string $processName = the name of the process
	 * @param AudioNode $node = the audio element node
	 */
	public function __construct($processName, AudioNode $node)
	{
		parent::__construct($processName);
		$this->node = new Node($processName, $node);
		$this->pipeConnection = array();
		$this->useExec(true);
	}

	/**
	 * __destruct
	 * 
	 * Class destructor
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * __call
	 * 
	 * pass unknown call to the proper class and method, if found, otherwise throw exception
	 * @param string $name = method name
	 * @param array $arguments = arguments to pass to method
	 * @throws Exception
	 */
	public function __call($name, array $arguments)
	{
		if (method_exists($this, $name))
		{
			return $this->$name(implode(',', $arguments));
		}

		if (method_exists($this->node, $name))
		{
			return $this->node->$name(implode(',', $arguments));
		}
		
		throw new Exception(Error::code('UnknownClassMethod'));
	}

	/**
	 * open
	 * 
	 * Start the process
	 * @param string $command = (0ptional) command to execute
	 * @return boolean $result = result of the Proc::open operation
	 * @throws AudioConfig/Exception, \Library\ProgramExec\Exception
	 */
	public function open($command=null)
	{
		if ($this->isOpen())
		{
			throw new Exception(Error::code('ProcessAlreadyOpen'));
		}

		if (! $command = $this->command($command))
		{
			$command = $this->node->startCommand();
		}

		return $this->processStarted = parent::open($command);
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
		parent::close();
		$this->processStarted = false;
	}

	/**
	 * processStarted
	 * 
	 * Return the processStarted status flag
	 * @return boolean $processStarted
	 */
	public function processStarted()
	{
		return $this->processStarted;
	}

	/**
	 * startSequence
	 * 
	 * Returns the start sequence number
	 * @return integer $startSequence = start sequence number
	 */
	public function startSequence()
	{
		return $this->node->startSequence();
	}

	/**
	 * stopSequence
	 * 
	 * Returns the stop sequence number
	 * @return integer $stopSequence = stop sequence number
	 */
	public function stopSequence()
	{
		return $this->node->stopSequence();
	}

	/**
	 * node
	 * 
	 * Get the node for this process element
	 * @return AudioNode $node
	 */
	public function node()
	{
		return $this->node;
	}

	public function readProcess($descriptor)
	{
		$descriptor->buffer = parent::read();
		$descriptor->index = strlen($descriptor->buffer);
	}
	
	public function writeProcess($descriptor)
	{
		parent::write($descriptor->buffer);
	}

}