<?php
namespace Library;
use Library\Error;
use Library\Properties;
use Library\Serialize\Exception as SerializeException;
use Library\Serialize\Factory as SerializeFactory;

/*
 * 		Library\Serialize is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */

/**
 * Library\Serialize
 *
 * Serialize class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Serialize.
 */
class Serialize
{
	/**
	 * properties
	 *
	 * Adapter properties in a Library\Properties object
	 * @var object $properties
	 */
	protected $properties;

	/**
	 * adapter
	 *
	 * The Library\Serialize adapter to perform the serialization storage/retrieval
	 * @var object $adapter
	 */
	protected $adapter;

	/**
	 * source
	 *
	 * name of the source (or destination) for the serialized object
	 * @var string $source
	 */
	protected $source;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $adapter = adapter name, defaults to 'file'
	 * @param array $properties = properties object or array
	 * @throws Library\Serialize\Exception
	 */
	public function __construct($adapter, $properties)
	{
		$this->properties = new Properties($properties);
		$this->adapter = SerializeFactory::instantiateClass($adapter, $this->properties);
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
	 * load
	 *
	 * load the object from the supplied source
	 * @param object $object = object to load
	 * @param string $source = (optional) source name
	 * @throws \Library\Serialize\Exception
	 */
	public function load(&$object, $source=null)
	{
		$this->read($object, $source);
		if (($object = unserialize($object)) === false)
		{
			throw new SerializeException(Error::code('UnableToUnserialize'));
		}
	}

	/**
	 * read
	 *
	 * read the object from the supplied source without unserializing
	 * @param object $object = object to load
	 * @param string $source = (optional) source name
	 * @throws \Library\Serialize\Exception
	 */
	public function read(&$object, $source=null)
	{
		if (! $source = $this->source($source))
		{
			throw new SerializeException(Error::code('MissingSourceFileName'));
		}

		$object = $this->adapter->read($source);
	}

	/**
	 * save
	 *
	 * serialize and save the requested object to the specified source
	 * @param object $object = object to serialize
	 * @param string $source = (optional) destination name
	 * @throws \Library\Serialize\Exception
	 */
	public function save($object, $source=null)
	{
		$this->write(serialize($object), $source);
	}

	/**
	 * write
	 *
	 * store the pre-serialized data
	 * @param object $serialized = serialized data to store
	 * @param string $source = (optional) source name
	 * @throws \Library\Serialize\Exception
	 */
	public function write($serialized, $source=null)
	{
		if (! $source = $this->source($source))
		{
			throw new SerializeException(Error::code('MissingSourceFileName'));
		}

		$this->adapter->write($serialized, $source);
	}

	/**
	 * source
	 *
	 * get/set the source name
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
	 * adapter
	 *
	 * get the adapter object
	 * @return object $adapter = adapter object handling the current serialization
	 */
	public function adapter()
	{
		return $this->adapter;
	}

	/**
	 * properties
	 *
	 * set/get the properties array
	 * @param object $properties = (optional) Library\Properties to set, null to query only
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
