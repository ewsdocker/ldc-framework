<?php
namespace Application\Launcher\Utility;
use Application\Launcher\ProcessDescriptor;
use Application\Utility\Support;

use Library\Exception\Descriptor as ExceptionDescriptor;
use Library\Error;
use Library\CliParameters;
use Library\PrintU;
use Library\Serialize as Serializer;
use Library\Serialize\Exception as SerializerException;
use Library\Utilities\FormatVar;

/*
 *		Launcher\Utility\Statistics is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	    Refer to the file named License.txt provided with the source, 
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * Launcher\Utility\Statistics
 *
 * Statistics class
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Launcher
 * @subpackage Utility
 */
class Statistics implements \Countable, \ArrayAccess, \Iterator
{
	/**
	 * processes
	 * 
	 * Array of ProcessDescriptor objects
	 * @var array $processes
	 */
	public 		$processes;

	/**
	 * processRun
	 * 
	 * The number of processes that have already been run
	 * @var integer $processesRun
	 */
	public		$processesRun;

	/**
	 * started
	 * 
	 * Process runner start time (microtime)
	 * @var double $started
	 */
	public		$started;
	
	/**
	 * ended
	 * 
	 * Process runner end time (microtime)
	 * @var double $ended
	 */
	public		$ended;

	/**
	 * timestamp
	 * 
	 * Current date and time of process runner start
	 * @var object $timestamp
	 */
	public		$timestamp;

	/**
	 * properties
	 * 
	 * Reference to a Library\Properties object instance
	 * @var object $properties
	 */
	protected	$properties;

	/**
	 * serialize
	 * 
	 * Serialization flag: true = serialize processDescriptor, false = don't
	 * @var boolean $serialize
	 */
	public		$serialize;

	/**
	 * logger
	 * 
	 * A logger object
	 * @var object $logger
	 */
	public		$logger;
	
	/**
	 * errorQueue
	 * 
	 * StackQueue object
	 * @var Stack\Queue $errorQueue
	 */
	public		$errorQueue;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param object $properties = reference to a Properties object instance
	 */
	public function __construct(&$properties)
	{
		$this->properties =& $properties;

		$this->processes = array();
		$this->processesRun = 0;

		$this->started = microtime(true);
		$this->ended = $this->started;

		$this->timestamp = date('Y-m-d H:i:s') . substr((string)microtime(), 1, 6);

		$this->active = false;
		$this->serialize = true;
		$this->logger = null;
		
		$this->errorQueue = null;
	}

	/**
	 * __destruct
	 *
	 * Destroy the current class instance
	 */
	public function __destruct()
	{
		$this->serializeStats();
	}

	/**
	 * __toString
	 *
	 * Print list of properties and settings
	 * @return string $buffer
	 */
	public function __toString()
	{
		return FormatVar::format(get_object_vars($this), get_class($this));
	}

	/**
	 * newProcess
	 * 
	 * Record the process record and create a new ProcessDescriptor class object for it
	 * @param string $processRecord = Process request record
	 * @return object $process = ProcessDescriptor object instance
	 */
	public function newProcess($processRecord)
	{
		$this->active = true;
		$processNumber = array_push($this->processes, new ProcessDescriptor(array('processRecord' => $processRecord))) - 1;
		$process = $this->processes[$processNumber];
		$process->serialized = false;

		return $process;
	}

	/**
	 * started
	 * 
	 * The time (microtime) that the processes began
	 * @return double $started
	 */
	public function started()
	{
		return $this->started;
	}

	/**
	 * ended
	 * 
	 * The time (microtime) that the processes ended
	 * @return double $ended
	 */
	public function ended()
	{
		$this->ended = microtime(true);
		return $this->ended;
	}

	/**
	 * elapsed
	 * 
	 * Returns the elapsed time
	 * @return double $elapsed
	 */
	public function elapsed()
	{
		return $this->ended - $this->started;
	}

	/**
	 * processes
	 * 
	 * Get a copy of the $processes array
	 * @return array $processes
	 */
	public function processes()
	{
		return $this->processes;
	}

	/**
	 * errorQueue
	 * 
	 * Get a copy of the errorQueue object
	 */
	public function errorQueue()
	{
		return $this->errorQueue;
	}

	/**
	 * serializeStats
	 * 
	 * Serialize and summarize the statistics objects
	 */
	public function serializeStats()
	{
		if ($this->active && $this->serialize)
		{
			foreach($this->processes as $index => $process)
			{
				if (! $process->serialized)
				{
					Support::serializeProcess($process);
				}
			}
		}
	}

	/**
	 * setSerialize
	 * 
	 * Get/set the serialize flag (true = serialize processDescriptor, false = don't serialize, null = query)
	 * @param boolean $serialize
	 */
	public function setSerialize($serialize=null)
	{
		if ($serialize !== null)
		{
			$this->properties->Serialize = $serialize;
		}
		
		return $this->properties->Serialize;
	}

	/**
	 * rewind.
	 * 
	 * Moves the current node pointer to the first item in the tree.
	 */
	public function rewind()
	{
		reset($this->processes);
#		$this->process = $this->key();
	}

	/**
	 * current.
	 * 
	 * Returns the current array element.
	 */
	public function current()
	{
		return current($this->processes);
	}

	/**
	 * key.
	 * 
	 * Returns the current array pointer.
	 * @return $key = current array pointer.
	 */
	public function key()
	{
		return key($this->processes);
	}

	/**
	 * next.
	 * 
	 * Moves current to the next array element.
	 */
	public function next()
	{
		next($this->processes);
	}

	/**
	 * prev.
	 * 
	 * Moves current to the previous array element.
	 */
	public function prev()
	{
		prev($this->processes);
	}

	/**
	 * valid.
	 * 
	 * Returns the validity of the current node pointer
	 * @return boolean true = the current node pointer is valid.
	 */
	public function valid()
	{
		return array_key_exists($this->key(), $this->processes);
	}

	/**
	 * offsetSet
	 *
	 * Set the value at the indicated offset
	 * @param mixed $offset = offset to the entry (property name)
	 * @param mixed $value = value of property
	 */
	public function offsetSet($offset, $value)
	{
		if (! array_key_exists($offset, $this->processes))
		{
			$this->processes[$offset] = new ProcessDescriptor($this->properties);
		}

		$this->processes[$offset]->Record = $value;
	}

	/**
	 * offsetGet
	 *
	 * Get the value at the indicated offset
	 * @param mixed $offset = offset to the entry (property name)
	 * @returns mixed $value = value of property, null if not found
	 */
	public function offsetGet($offset)
	{
		return $this->processes[$offset];
	}

	/**
	 * offsetExists
	 *
	 * Returns true if the offset exists, false if not
	 * @param mixed $offset = offset (property) to process for existence
	 */
	public function offsetExists($offset)
	{
		return $this->exists($this->processes[$offset]);
	}

	/**
	 * offsetUnset
	 *
	 * Unset the provided offset in the processes and records arrays
	 * @param mixed $offset = offset to unset
	 */
	public function offsetUnset($offset)
	{
		unset($this->processes[$offset]);
	}

	/**
	 * count.
	 *
	 * returns the number of elements in the processes array
	 * @return integer $count = number of elements in the stack
	 */
	public function count()
	{
		return count($this->processes);
	}
	
	/**
	 * logger
	 * 
	 * Get/set the logger object
	 * @param object $logger
	 * @return object $logger
	 */
	public function logger($logger=null)
	{
		if ($logger != null)
		{
			$this->logger = $logger;
		}
		
		return $this->logger;
	}

	/**
	 * active
	 * 
	 * Get/set the active flag
	 * @param bool $active = active flag setting, null to query
	 * @return bool $active
	 */
	public function active($active=null)
	{
		if ($active !== null)
		{
			$this->active = $active;
		}

		return $this->active;
	}

}
