<?php
namespace Library\DOM;
use Library\Error;

/*
 * 		DOM\AdapterStreamIO is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * DOM\AdapterStreamIO.
 *
 * A DOM StreamIO Adapter implementation.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage DOM.
 */
class AdapterStreamIO implements iDOMAdapter
{
	/**
	 * adapter
	 *
	 * Library\DOM\AdapterStreamIO adapter object
	 * @var object $adapter
	 */
	protected	$adapter;

	/**
	 * defaultProperties
	 *
	 * Default properties
	 * @var object $defaultProperties
	 */
	protected	$defaultProperties;

	/**
	 * properties
	 *
	 * Library\Properties instance
	 * @var object $properties
	 */
	protected	$properties;

	/**
	 * buffer
	 * 
	 * I/O buffer
	 * @var string $buffer
	 */
	protected	$buffer;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param mixed $properties = (optional) class construction properties
	 */
	public function __construct($properties=null)
	{
		$this->defaultProperties = new \Library\Properties(array('DOM_Adapter'			=> 'stream',

										 						 'StreamIO_Mode'		=> 'r',
		                                 						 'StreamIO_Type'		=> 't',
		                                 						 'StreamIO_Suppress'	=> true,
		                                 						 'StreamIO_Adapter'		=> 'stream'));

		$this->properties = $this->defaultProperties;
		$this->properties($properties);

		$this->adapter = null;
		$this->buffer = '';
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 * @return null
	 */
	public function __destruct()
	{
		$this->disconnect();
	}

  	/**
  	 * connect.
  	 *
  	 * connect the caller. This is the factory entry point
  	 * @param mixed $properties = (optional) properties
  	 * @return boolean true = successful, false = failed.
  	 */
	public function connect($properties=null)
	{
		$properties = $this->properties($properties);
		$this->adapter = \Library\Stream\Factory::instantiateClass($properties['StreamIO_Adapter'],
																   $properties['StreamIO_Timeout'],
																   $properties['StreamIO_Source'],
																   $properties['StreamIO_Mode'],
																   $properties['StreamIO_Type']
																   );

	}

  	/**
  	 * disconnect.
  	 *
  	 * disconnect from the user - prepare for termination.
  	 */
	public function disconnect()
	{
		if ($this->adapter)
		{
			unset($this->adapter);
			$this->adapter = null;
		}
	}

	/**
	 * load
	 *
	 * Load from source entry point
	 * @return string $buffer = response buffer
	 * @throws Library\DOM\Exception
	 */
	public function load($properties=null)
	{
		if ($this->adapter)
		{
			$this->disconnect();
		}

		$this->connect($properties);

		if (! $this->adapter->getStream())
		{
			throw new Exception(Error::code('StreamReadFailed'));
		}

		$this->buffer = $this->adapter->buffer();
		return $this->buffer;
	}

	/**
	 * save
	 *
	 * Save from source entry point
	 * @param string $buffer = buffer to write
	 * @throws Library\DOM\Exception
	 */
	public function save($buffer, $properties=null)
	{
		if (! $buffer = $this->buffer($buffer))
		{
			throw new Exception(Error::code('EmptyBuffer'));
		}

		if ($this->adapter)
		{
			$this->disconnect();
		}

		$this->connect($properties);

		if (! $this->adapter->fwrite($buffer))
		{
			throw new Exception(Error::code('StreamWriteFailed'));
		}
	}

  	/**
	 * properties
	 *
	 * get/set the connection properties
	 * @param array $properties = (optional) connection properties
	 * @return array $properties
	 */
	public function properties($properties=null)
	{
		if ($properties !== null)
		{
			if (is_array($properties))
			{
				$properties = new \Library\Properties($properties);
			}
			elseif ((! is_object($properties)) || (! ($properties instanceOf \Library\Properties)))
			{
				throw new Exception(Error::code('InvalidClassObject'));
			}

			$this->properties = $this->defaultProperties;
			$this->properties->setProperties($properties->properties());
		}

		return $this->properties;
	}

	/**
	 * buffer
	 *
	 * get cache buffer
	 * @param string $buffer = (optional) buffer, null to query
	 * @return string $buffer
	 */
	public function buffer($buffer=null)
	{
		if ($buffer !== null)
		{
			$this->buffer = $buffer;
		}

		return $this->buffer;
	}

	/**
	 * adapter
	 *
	 * get STREAM adapter
	 * @return adapter $adapter
	 */
	public function adapter()
	{
		return $this->adapter;
	}

	/**
	 * adapterName
	 *
	 * Returns the name of the DOM adapter.
	 * @return string $adapterName
	 */
	public function adapterName()
	{
		return $this->properties->StreamIO_Adapter;
	}

}
