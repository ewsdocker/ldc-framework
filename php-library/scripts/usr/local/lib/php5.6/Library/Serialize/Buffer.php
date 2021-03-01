<?php
namespace Library\Serialize;
use Library\Error;

/*
 * 		Serialize\Buffer is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 * Serialize\Buffer.
 *
 * Serialize adapter class for in-memory buffer.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Serialize.
 */
class Buffer implements iSerialize
{
	/**
	 * source
	 *
	 * path to the buffer source
	 * @var string $source
	 */
	protected		$source;

	/**
	 * properties
	 *
	 * Library\Properties object
	 * @var object $properties
	 */
	protected		$properties;

	/**
	 * buffer
	 *
	 * Contents of the buffer
	 * @var mixed $buffer
	 */
	protected		$buffer;

	/**
	 * adapter
	 *
	 * adapter object
	 * @var object $adapter
	 */
	protected		$adapter;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param object $properties = Library\Properties object
	 */
	public function __construct($properties)
	{
		$this->properties = null;

		if (! $properties)
		{
			throw new Exception(Error::code('MissingRequiredProperties'));
		}

		$this->adapter = null;
		$this->buffer  = '';
		$this->source  = '';

		if ($this->properties($properties)->exists('Serialize_Source'))
		{
			$this->source = $this->properties['Serialize_Source'];
		}
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
  	 * connect.
  	 *
  	 * Connect to the adapter.
  	 * @param object $properties = (optional) Library\Properties object
  	 */
	public function connect($properties=null)
	{
		$this->properties($properties);
	}

  	/**
  	 * disconnect.
  	 *
  	 * Disconnect the caller.
  	 * @return boolean true = successful, false = unable to disconnect.
  	 */
	public function disconnect()
	{
		return true;
	}

	/**
	 * read
	 *
	 * Read the object from the specified source without unserializing.
	 * @param string $source = (optional) source name
	 * @return mixed $buffer = response buffer
	 * @throws Exception
	 */
	public function read($source=null)
	{
		$this->source($source);
		return $this->buffer;
	}

	/**
	 * write
	 *
	 * Write the object to the designated source.
	 * @param string $buffer = buffer to write
	 * @param string $source = (optional) source path
	 * @throws Exception
	 */
	public function write(&$buffer, $source=null)
	{
		if (! $buffer)
		{
			throw new Exception(Error::code('EmptyBuffer'));
		}

		$this->source($source);
		$this->buffer = $buffer;
	}

	/**
	 * buffer
	 *
	 * Returns the buffer.
	 * @return mixed $buffer
	 */
	public function buffer()
	{
		return $this->buffer;
	}

	/**
	 * adapter
	 *
	 * Returns the adapter
	 * @return object $adapter
	 */
	public function adapter()
	{
		return null;
	}

	/**
	 * source
	 *
	 * (Sets and) returns the source name.
	 * @param string $source = (optional) source name, null to query
	 * @return string $source
	 */
	public function source($source=null)
	{
		if ($source !== null)
		{
			$this->source = $source;
		}

		return $this->source;
	}

  	/**
	 * properties
	 *
	 * (Sets and) returns the connection properties.
	 * @param object $properties = (optional) Library\Properties
	 * @return object $properties
	 */
	public function properties($properties=null)
	{
		if ($properties !== null)
		{
			$this->properties = $properties;
		}

		return $this->properties;
	}

}
