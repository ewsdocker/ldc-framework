<?php
namespace Library\Serialize;
use Library\Error;

/*
 * 		Serialize\FileIO is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */

/**
 * Serialize\FileIO.
 *
 * Serialize adapter class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Serialize.
 */
class FileIO implements iSerialize
{
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
	 * fileio
	 *
	 * Library\FileIO factory generated object
	 * @var object $fileio
	 */
	protected		$fileio;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param array $properties = (optional) Library\Properties intance
	 */
	public function __construct($properties=null)
	{
		if ($this->properties($properties))
		{
			$this->properties->setProperties(array('FileIO_Mode'	=> 'r',
		    	                      			   'FileIO_Adapter'	=> 'fileobject'),
											 false);
		}

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
	}

  	/**
  	 * connect.
  	 *
  	 * Connect to the adapter.
  	 * @param array $properties = (optional) associative parameter array
  	 * @throws Library\Factory\Exception, Library\FileIO\Exception.
  	 */
	public function connect($properties=null)
	{
		$this->properties($properties);

		if ($this->fileio)
		{
			$this->disconnect();
		}

		$this->fileio = \Library\FileIO\Factory::instantiateClass($this->properties->FileIO_Adapter, 
																  $this->properties->Serialize_Source,
		                      									  $this->properties->FileIO_Mode);
	}

  	/**
  	 * disconnect.
  	 *
  	 * Disconnect the caller.
  	 * @return boolean true = successful, false = unable to disconnect.
  	 */
	public function disconnect()
	{
		unset($this->fileio);
		return true;
	}

	/**
	 * read
	 *
	 * Read the object from the specified source without unserializing.
	 * @return string $buffer = response buffer
	 * @throws Library\Serialize\Exception
	 */
	public function read($source=null)
	{
		if (! $this->properties)
		{
			throw new Exception (Error::code('MissingRequiredProperties'));
		}

		$this->properties->FileIO_Mode = 'r';

		if (! $this->source($source))
		{
			throw new Exception(Error::code('MissingFilename'));
		}

		$this->connect();

		$this->buffer = '';
		foreach($this->fileio as $lineNumber => $line)
		{
			$this->buffer .= $line;
		}

		return $this->buffer;
	}

	/**
	 * write
	 *
	 * Write the object to the designated source.
	 * @param string $buffer = buffer to write
	 * @param string $source = (optional) path to the file, null to use current
	 * @throws Library\Serialize\Exception
	 */
	public function write(&$buffer, $source=null)
	{
		if (! $buffer)
		{
			throw new Exception(Error::code('EmptyBuffer'));
		}

		if (! $this->source($source))
		{
			throw new Exception(Error::code('MissingFilename'));
		}

		if (! $this->properties)
		{
			throw new Exception (Error::code('MissingRequiredProperties'));
		}

		$this->properties->FileIO_Mode = 'w';

		$this->connect();

		if ($this->fileio->fwrite($buffer, strlen($buffer)) === null)
		{
			throw new Exception(Error::code('FileWriteError'));
		}
	}

	/**
	 * buffer
	 *
	 * Returns the contents of the buffer class property.
	 * @return mixed $buffer
	 */
	public function buffer()
	{
		return $this->buffer;
	}

	/**
	 * adapter
	 *
	 * Returns the adapter class property.
	 * @return object $fileio
	 */
	public function adapter()
	{
		return $this->fileio;
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
			$this->properties->Serialize_Source = $source;
		}

		return $this->properties->Serialize_Source;
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
			$this->properties = $properties;
		}

		return $this->properties;
	}

}
