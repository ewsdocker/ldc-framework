<?php
namespace Library;

use Library\Select\Poll;
use Library\Select\Process;
use Library\Select\Register;
use Library\Select\Storage;
use Library\Utilities\FormatVar;

/*
 *		Library\Select is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  Refer to the file named License.txt provided with the source,
 *		 or from http://opensource.org/licenses/academic.php
*/
/**
 * Library\Select
 *
 * Wrapper for the stream_select function
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Select
 */
class Select
{
	/**
	 * storage
	 * 
	 * Select storage container
	 * @var object $storage
	 */
	public $storage;

	/**
	 * register
	 * 
	 * Select Register class instance
	 * @var object $register
	 */
	public $register;

	/**
	 * poll
	 * 
	 * Select Poll class instance
	 * @var object Poll
	 */
	public $poll;

	/**
	 * process
	 * 
	 * Select Process class instance
	 * @var object Process
	 */
	public $process;

	/**
	 * __construct
	 * 
	 * Class constructor
	 */
	public function __construct()
  	{
  		$this->storage = new Storage();
  		$this->register = new Register($this->storage);
  		$this->poll = new Poll($this->storage);
  		$this->process = new Process($this->storage);
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
	 * Returns a printable string of all properties
	 * @return string $buffer
	 */
	public function __toString()
	{
		FormatVar::sort(false);
		FormatVar::sortValues(false);
		FormatVar::recurse(true);

		return FormatVar::format(get_object_vars($this), get_class($this));
	}

	/**
	 * processDescriptors
	 * 
	 * Process the descriptor stack
	 * @throws Select\Exception
	 */
	public function processDescriptors()
	{
		if ($this->pollSelect() > 0)
		{
			return $this->readyDescriptors();
		}
		
		return false;
	}

	/**
	 * readyDescriptors
	 * 
	 * Process all ready descriptors
	 * @return boolean $result = true
	 * @throws Select\Exception
	 */
	public function readyDescriptors()
	{
		return $this->process->readyDescriptors();
	}

	/**
	 * descriptorsCompleted
	 * 
	 * Returns the number of descriptors completed on the last call to readyDescriptors
	 * @return integer $descriptorsCompleted
	 */
	public function descriptorsCompleted()
	{
		return $this->process->descriptorsCompleted();
	}

	/**
	 * pollSelect
	 * 
	 * poll (select) the selected arrays for a completed event, or until timeout expires
	 * @return mixed $result = count of all ready events, 0 if timeout
	 * @throws Select\Exception if selected arrays are empty
	 */
	public function pollSelect()
	{
		return $this->poll->poll();
	}

	/**
	 * timeout
	 * 
	 * The wait timeout in seconds (real) to nanosec resolution
	 * @param real $timeout = (optional) wait timeout, null to query
	 * @return real $timeout = current wait timeout
	 */
	public function timeout($timeout=null)
	{
		return $this->poll->timeout($timeout);
	}

	/**
	 * selectReady
	 * 
	 * Returns the number of items selected in the last call to poll()
	 * @return integer $selectReady
	 */
	public function selectReady()
	{
		return $this->poll->selectReady();
	}

	/**
	 * selected
	 * 
	 * Returns the number of items scheduled to poll
	 * @return integer $selected
	 */
	public function selected()
	{
		return $this->poll->selected();
	}

	/**
	 * register
	 *
	 * Register the stream
	 * @param string $name = name of the stream owner
	 * @param string $type = i/o type of the stream ('read', 'write', 'except')
	 * @param resource $resource = stream to register
	 * @param boolean $enabled = (optional) stream enable, default = true
	 * @throws Select\Exception if unknown $streamType or $callback is not null or an array
	 */
	public function register($name, $type, $resource, $callback=null, $buffer='')
	{
		$this->register->register($name, $type, $resource, $callback, $buffer);
	}

	/**
	 * unregister
	 *
	 * Unregister the stream
	 * @param string $name = name of the stream owner
	 * @param string $type = i/o type of the stream ('read', 'write', 'except')
	 * @throws Select\Exception if unknown $type
	 */
	public function unregister($name, $type)
	{
		$this->register->unregister($name, $type);
	}

	/**
	 * unregisterAll
	 * 
	 * Unregister all streams
	 */
	public function unregisterAll()
	{
		$this->register->unregisterAll();
	}

	/**
	 * buffer
	 * 
	 * Get/set the i/o buffer for the named stream and type
	 * @param string $name = registered stream name
	 * @param string $type = stream type ('read', 'write', 'except')
	 * @param string $buffer = (optional) buffer to set, null to query only
	 * @return string $buffer = current buffer
	 * @throws Select\Exception if unregistered stream name / type
	 */
	public function buffer($name, $type, $buffer=null)
	{
		return $this->register->buffer($name, $type, $buffer);
	}

	/**
	 * bufferAddress
	 * 
	 * Get/set the i/o buffer address for the named stream and type
	 * @param string $name = registered stream name
	 * @param string $type = stream type ('read', 'write', 'except')
	 * @param string $bufferAddress = buffer address to set (pass by value), null to query only
	 * @return string $bufferAddress = current buffer
	 * @throws Exception if unregistered stream name / type
	 */
	public function bufferAddress($name, $type, &$bufferAddress)
	{
		return $this->register->bufferAddress($name, $type, $bufferAddress);
	}

	/**
	 * bufferIndex
	 * 
	 * Get/set the i/o buffer index for the named stream and type
	 * @param string $name = registered stream name
	 * @param string $type = stream type ('read', 'write', 'except')
	 * @param integer $bufferIndex = buffer index to set, null to query only
	 * @return integer $bufferIndex = current buffer index
	 * @throws Exception if unregistered stream name / type
	 */
	public function bufferIndex($name, $type, $index=null)
	{
		return $this->register->bufferIndex($name, $type, $index);
	}

	/**
	 * isEnabled
	 * 
	 * Get the enabled flag for the named stream and type
	 * @param string $name = registered stream name
	 * @param string $type = stream type ('read', 'write', 'except')
	 * @return boolean $enabled = true if enabled, false if not
	 * @throws Select\Exception if unregistered stream name / type
	 */
	public function isEnabled($name, $type)
	{
		return $this->register->isEnabled($name, $type);
	}

	/**
	 * enable
	 * 
	 * Set the enabled flag for the named stream and type
	 * @param string $name = registered stream name
	 * @param string $type = stream type ('read', 'write', 'except')
	 * @return boolean = current setting (true)
	 * @throws Select\Exception if unregistered stream name / type
	 */
	public function enable($name, $type)
	{
		return $this->register->enable($name, $type);
	}

	/**
	 * disable
	 * 
	 * Reset the enabled flag for the named stream and type
	 * @param string $name = registered stream name
	 * @param string $type = stream type ('read', 'write', 'except')
	 * @return boolean = current setting (false)
	 * @throws Select\Exception if unregistered stream name / type
	 */
	public function disable($name, $type)
	{
		return $this->register->disable($name, $type);
	}

	/**
	 * callback
	 * 
	 * Set/get the descriptor's callback function
	 * @param string $name = descriptor name
	 * @param string $type = select type
	 * @param mixed $callback = (optional) callback function, null to query
	 * @return array $callback = current callback function array
	 * @throws Select\Exception if unregistered stream name / type
	 */
	public function callback($name, $type, $callback=null)
	{
		return $this->register->callback($name, $type, $callback);
	}

	/**
	 * createCallback
	 * 
	 * Create default callback array
	 * @param string $type = select type ('read', 'write', 'except')
	 * @return array $callback = callback array
	 */
	public function createCallback($type)
	{
		return $this->register->createCallback($type);
	}

	/**
	 * getDescriptor
	 * 
	 * Get requested descriptor
	 * @param string $name = registered stream name
	 * @param string $type = stream type ('read', 'write', 'except')
	 * @return Select\Descriptor $descriptor = selected descriptor
	 * @throws Select\Exception if unregistered stream name / type
	 */
	public function getDescriptor($name, $type)
	{
		return $this->register->getDescriptor($name, $type);
	}

	/**
	 * descriptorKey
	 * 
	 * Check if the 'type' parameter is valid
	 * @param string $type = parameter to be checked (s.b. 'read', 'write', 'except')
	 * @return string $key = descriptor key
	 * @throws Select\Exception if not valid
	 */
	public function descriptorKey($name, $type)
	{
		return $this->register->descriptorKey($name, $type);
	}

	/**
	 * selectDescriptors
	 * 
	 * selectDescriptors array
	 * @return array $selectDescriptors = Select\Storage::$selectDescriptors
	 */
	public function selectDescriptors()
	{
		return $this->storage->selectDescriptors;
	}

	/**
	 * storage
	 * 
	 * Storage instance
	 * @return object $storage = Select\Storage instance
	 */
	public function storage()
	{
		return $this->storage;
	}

}
