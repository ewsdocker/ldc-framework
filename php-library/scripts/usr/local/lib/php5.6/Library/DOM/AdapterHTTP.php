<?php
namespace Library\DOM;
use Library\Error;

/*
 * 		DOM\AdapterHTTP is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * DOM\AdapterHTTP
 *
 * A generic XML DOM - HTTP Loader implementation.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage DOM.
 */
class AdapterHTTP implements iDOMAdapter
{
	/**
	 * adapter
	 *
	 * EWSLibrary_HTTP driver
	 * @var object $adapter
	 */
	protected	$adapter;

	/**
	 * defaultProperties
	 *
	 * Default properties in an array
	 * @var array $defaultProperties
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
	 * response
	 * 
	 * Response buffer
	 * @var string $response
	 */
	protected	$response;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param array $properties = (optional) class construction properties
	 */
	public function __construct($properties=null)
	{
		$this->defaultProperties = new \Library\Properties(array('DOM_Adapter' => 'http',
										 						 'HTTP_Method'  => 'get',
										 						 'HTTP_Driver'  => 'http'));

		$this->properties = $this->defaultProperties;
		$this->properties($properties);

		$this->buffer = '';
		$this->response = '';
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 * @return null
	 */
	public function __destruct()
	{
		if ($this->adapter)
		{
			unset($this->adapter);
		}
	}

  	/**
  	 * connect.
  	 *
  	 * connect the caller. This is the factory entry point
  	 * @param array $properties = (optional) Library\Properties instance or associative parameter array
	 * @throws Library\DOM\Exception, Library\HTTP\Extensions
  	 */
	public function connect($properties=null)
	{
		$this->properties($properties);

		if (! $this->properties->exists('HTTP_Driver'))
		{
			throw new Exception(Error::code('MissingAdapter'));
		}

		$this->adapter = new \Library\HTTP\Client($this->properties['HTTP_Driver']);

		if ($this->properties->exists('HTTP_Uri'))
		{
			$this->adapter->setUri($this->properties['HTTP_Uri']);
		}

		if ($this->properties->exists('HTTP_Config'))
		{
			$this->adapter->setConfig($this->properties['HTTP_Config']);
		}

		if ($this->properties->exists('HTTP_Method'))
		{
			$this->adapter->setMethod($this->properties['HTTP_Method']);
		}
	}

  	/**
  	 * disconnect.
  	 *
  	 * disconnect from the user
  	 * @throws Library\HTTP\Exception
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
	 * @params array $properties = (optional) properties array
	 * @return string $buffer = response buffer
	 * @throws \Library\DOM\Exception, \Library\HTTP\Exception
	 */
	public function load($properties=null)
	{
		if ($this->adapter)
		{
			$this->disconnect();
		}

		$this->connect($properties);

		$this->adapter->setMethod('get');
		$this->adapter->setContentType('text/xml');
		$this->response = $this->adapter->send();

		return $this->response['body'];
	}

	/**
	 * save
	 *
	 * Save from source entry point
	 * @param string $buffer = buffer to write
	 * @params array $properties = (optional) properties array
	 * @throws \Library\DOM\Exception, \Library\HTTP\Exception
	 */
	public function save($buffer, $properties=null)
	{
		if ($this->adapter)
		{
			$this->disconnect();
		}

		$this->connect($properties);

		$this->adapter->setMethod('post');
		$this->adapter->setContentType('text/xml');
		$this->adapter->setContent($buffer);

		$this->response = $this->adapter->send();
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
		return $this->properties->HTTP_Driver;
	}

	/**
	 * response
	 *
	 * Return a copy of the response buffer
	 * @return string $response
	 */
	public function response()
	{
		return $this->response;
	}

}
