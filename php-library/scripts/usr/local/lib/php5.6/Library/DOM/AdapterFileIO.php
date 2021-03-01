<?php
namespace Library\DOM;
use Library\Error;

/*
 * 		DOM\AdapterFileIO is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * DOM\AdapterFileIO
 *
 * A XML DOM adapter for FileIO classes.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage DOM.
 */
class AdapterFileIO implements iDOMAdapter
{
	/**
	 * adapter
	 *
	 * Library\FileIO object
	 * @var object $adapter
	 */
	protected	$adapter;

	/**
	 * defaultProperties
	 *
	 * Default properties in a LIbrary\Properties object
	 * @var object $defaultProperties
	 */
	protected	$defaultProperties;

	/**
	 * properties
	 *
	 * Properties in a LIbrary\Properties object
	 * @var object $properties
	 */
	protected	$properties;
	
	/**
	 * buffer
	 * 
	 * cache buffer
	 * @var string $buffer
	 */
	protected	$buffer;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param array $properties = (optional) class construction properties
	 */
	public function __construct($properties=null)
	{
		$this->defaultProperties = new \Library\Properties(array('DOM_Adapter'			=> 'file',

										 						 'FileIO_Mode'			=> 'r',
		                                 						 'FileIO_Adapter'		=> 'fileobject'));

		$this->properties = $this->defaultProperties;
		$this->properties($properties);

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
  	 * @param mixed $properties = (optional) \Library\Properties instance or associative properties array
  	 * @throws Library\DOM\Exception, Library\FileIO\Exception
  	 */
	public function connect($properties=null)
	{
		$this->properties($properties);
		$this->adapter = \Library\FileIO\Factory::instantiateClass($this->properties['FileIO_Adapter'],
																   $this->properties['FileIO_Source'],
																   $this->properties['FileIO_Mode']);
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

		$this->properties($properties);
		$this->properties['FileIO_Mode'] = 'r';

		$this->connect();

		$this->buffer = '';
		foreach($this->adapter as $lineNumber => $lineText)
		{
//			$this->buffer .= rtrim($lineText);
//			$this->buffer .= PHP_EOL;
			$this->buffer .= $lineText;
		}

		return $this->buffer;
	}

	/**
	 * save
	 *
	 * Save from source entry point
	 * @param string $buffer = (optional) buffer to write
	 * @param mixed $properties = (optional) properties
	 * @throws Library\DOM\Exception
	 */
	public function save($buffer=null, $properties=null)
	{
		if ($this->adapter)
		{
			$this->disconnect();
		}

		$this->properties($properties);
		$this->properties['FileIO_Mode'] = 'w';

		$this->connect();

		if (! $buffer = $this->buffer($buffer))
		{
			throw new Exception(Error::code('EmptyBuffer'));
		}

		if (! $this->adapter->fwrite($buffer, strlen($buffer)))
		{
			throw new Exception(Error::code('FileWriteError'));
		}
	}

  	/**
	 * properties
	 *
	 * get/set the connection properties
	 * @param mixed $properties = (optional) connection properties, an associative array or Library\Properties instance
	 * @return object $properties
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
				throw new Exception(Error::code('MissingRequiedProperties'));
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
	 * get FILEIO adapter
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
		return $this->properties->DOM_Adapter;
	}

}
