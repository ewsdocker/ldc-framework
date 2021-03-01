<?php
namespace Library\Select;

use Library\Error;

/*
 *		Library\Select\Register is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * Library\Select\Register
 *
 * stream_select registration class for the Select class.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Select
 */
class Register
{
	/**
	 * storage
	 * 
	 * A Storage class instance
	 * @var object $storage
	 */
	public $storage;
	
	/**
	 * types
	 * 
	 * Array of valid type names ('read', 'write', 'except');
	 * @var array $types
	 */
	public $types;

	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param array $storage = default selectStore
	 */
	public function __construct($storage)
	{
		$this->storage = $storage;

		while (count($this->storage->selectDescriptors) > 0)
		{
			$this->storage->selectDescriptors->pop();
		}

		$this->storage->pollSetupRequired = true;
		$this->types = array('read', 'write', 'except');
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
	 * register
	 *
	 * Register the stream
	 * @param string $name = name of the stream owner
	 * @param string $type = i/o type of the stream ('read', 'write', 'except')
	 * @param resource $resource = stream to register
	 * @param boolean $enabled = (optional) stream enable, default = true
	 * @throws Exception if unknown $streamType or $callback is not null or an array
	 */
	public function register($name, $type, $resource, $callback=null, $buffer='')
	{
		$key = $this->descriptorKey($name, $type);

		if ($callback === null)
		{
			$callback = $this->createCallback($type);
		}
		else 
		{
			if (! is_array($callback))
			{
				throw new Exception(Error::code('ArrayVariableExpected'));
			}
			
			if ((! array_key_exists('class', $callback)) || (! array_key_exists('method', $callback)))
			{
				throw new Exception(Error::code('ArrayKeysMissing'));
			}
		}

		if (! is_resource($resource))
		{
			throw new Exception(Error::code('ResourceNotResource'));
		}

		$descriptor = new Descriptor(array('name' 		=> $name,
										   'type' 		=> $type,
										   'resource'	=> $resource,
										   'enabled'	=> false,
										   'ready'		=> false,
										   'callback'	=> $callback,
										   'buffer'		=> $buffer,
										   'index'		=> strlen($buffer),
										   ));

		$this->storage->selectDescriptors[$this->descriptorKey($name, $type)] = $descriptor;		
		$this->storage->pollSetupRequired = true;
	}

	/**
	 * unregister
	 *
	 * Unregister the stream
	 * @param string $name = name of the stream owner
	 * @param string $type = i/o type of the stream ('read', 'write', 'except')
	 * @throws Exception if unknown $type
	 */
	public function unregister($name, $type)
	{
		try
		{
			$descriptor = $this->getDescriptor($name, $type);
		}
		catch(Exception $exception)
		{
			if (($exception->getCode() == Error::code('StreamSelectUnknown')) || ($exception->getCode() == Error::code('StreamSelectRegistration')))
			{
				return;
			}

			throw new Exception($exception->getCode());
		}

		$this->storage->selectDescriptors->removeKey($this->descriptorKey($name, $type));
		$this->storage->pollSetupRequired = true;
	}

	/**
	 * unregisterAll
	 * 
	 * Unregister all streams
	 */
	public function unregisterAll()
	{
		while(count($this->storage->selectDescriptors) > 0)
		{
			$descriptor = $this->storage->selectDescriptors->pop();
			$descriptor = null;
		}
	}

	/**
	 * buffer
	 * 
	 * Get/set the i/o buffer for the named stream and type
	 * @param string $name = registered stream name
	 * @param string $type = stream type ('read', 'write', 'except')
	 * @param string $buffer = (optional) buffer to set, null to query only
	 * @return string $buffer = current buffer
	 * @throws Exception if unregistered stream name / type
	 */
	public function buffer($name, $type, $buffer=null)
	{
		$descriptor = $this->getDescriptor($name, $type);
		if ($buffer !== null)
		{
			$descriptor->buffer = $buffer;
			$descriptor->index = 0;
		}

		return $descriptor->buffer;
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
		$descriptor = $this->getDescriptor($name, $type);
		if ($bufferAddress !== null)
		{
			$descriptor->buffer =& $bufferAddress;
			$descriptor->index = 0;
		}

		return $descriptor->buffer;
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
		$descriptor = $this->getDescriptor($name, $type);
		if ($index !== null)
		{
			$descriptor->index = $index;
		}

		return $descriptor->index;
	}

	/**
	 * isEnabled
	 * 
	 * Get the enabled flag for the named stream and type
	 * @param string $name = registered stream name
	 * @param string $type = stream type ('read', 'write', 'except')
	 * @return boolean $enabled = true if enabled, false if not
	 * @throws Exception if unregistered stream name / type
	 */
	public function isEnabled($name, $type)
	{
		$descriptor = $this->getDescriptor($name, $type);
		return $descriptor->enabled;
	}

	/**
	 * enable
	 * 
	 * Set the enabled flag for the named stream and type
	 * @param string $name = registered stream name
	 * @param string $type = stream type ('read', 'write', 'except')
	 * @return boolean = current setting (true)
	 * @throws Exception if unregistered stream name / type
	 */
	public function enable($name, $type)
	{
		$descriptor = $this->getDescriptor($name, $type);
		$descriptor->enabled = true;
		return $this->isEnabled($name, $type);
	}

	/**
	 * disable
	 * 
	 * Reset the enabled flag for the named stream and type
	 * @param string $name = registered stream name
	 * @param string $type = stream type ('read', 'write', 'except')
	 * @return boolean = current setting (false)
	 * @throws Exception if unregistered stream name / type
	 */
	public function disable($name, $type)
	{
		$descriptor = $this->getDescriptor($name, $type);
		$descriptor->enabled = false;
		return $this->isEnabled($name, $type);
	}

	/**
	 * callback
	 * 
	 * Set/get the descriptor's callback function
	 * @param string $name = descriptor name
	 * @param string $type = select type
	 * @param mixed $callback = (optional) callback function, null to query
	 * @return array $callback = current callback function array
	 * @throws Exception if unregistered stream name / type
	 */
	public function callback($name, $type, $callback=null)
	{
		$descriptorKey = $this->descriptorKey($name, $type);

		if ($callback !== null)
		{
			if (! is_array($callback))
			{
				throw new Exception(Error::code('ArrayVariableExpected'));
			}

			if ((! array_key_exists('class', $callback)) || (! array_key_exists('method', $callback)))
			{
				throw new Exception(Error::code('ArrayKeysMissing'));
			}

			$this->storage->selectDescriptors[$descriptorKey]->callback = $callback;
		}

		return $this->storage->selectDescriptors[$descriptorKey]->callback;
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
		$class = new Drivers($this->storage);
		
		switch($type)
		{
			default:
			case 'read':
				return array('class' 	=> $class,
						     'method'	=> 'read');
		
			case 'write':
				return array('class' 	=> $class,
							 'method'	=> 'write');
		
			case 'except':
				return array('class' 	=> $class,
							 'method'	=> 'except');
			default:
				break;
		}

		return array('class' 	=> $class,
				     'method'	=> 'read');
	}

	/**
	 * getDescriptor
	 * 
	 * Get requested descriptor
	 * @param string $name = registered stream name
	 * @param string $type = stream type ('read', 'write', 'except')
	 * @return Select\Descriptor $descriptor = selected descriptor
	 * @throws Exception if unregistered stream name / type
	 */
	public function getDescriptor($name, $type)
	{
		$key = $this->descriptorKey($name, $type);

		if (! $this->storage->selectDescriptors->exists($key))
		{
			throw new Exception(Error::code('StreamSelectUnknown'));
		}

		return $this->storage->selectDescriptors[$key];
	}

	/**
	 * descriptorKey
	 * 
	 * Check if the 'type' parameter is valid
	 * @param string $type = parameter to be checked (s.b. 'read', 'write', 'except')
	 * @return string $key = descriptor key
	 * @throws Exception if not valid
	 */
	public function descriptorKey($name, $type)
	{
		if (array_search($type, $this->types) === false)
		{
			throw new Exception(Error::code('StreamSelectRegistration'));
		}

		return sprintf("%s.%s", $name, $type);
	}

}
