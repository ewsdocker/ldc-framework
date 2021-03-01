<?php
namespace Library\DOM;
use Library\Error;

/*
 * 		DOM\DocumentIO is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * DOM\DocumentIO.
 *
 * A generic XML DOM implementation.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage DOM.
 */
class DocumentIO extends Query
{
	/**
	 * adapter
	 *
	 * The DOMAdapter class name
	 * @var string $adapter
	 */
	protected	$adapter;

	/**
	 * DOMAdapter
	 *
	 * The DOMAdapter object
	 * @var DOMAdapter $DOMAdapter
	 */
	protected	$DOMAdapter;

	/**
	 * properties
	 *
	 * Connect properties for $dom
	 * @var array $properties
	 */
	protected	$properties;

	/**
	 * buffer
	 * 
	 * Original input/output buffer
	 * @var string $buffer
	 */
	protected	$buffer;

	/**
	 * __construct
	 *
	 * class constructor
	 * @param string/object $adapter = (optional) name of the DOM adapter or DOM adapter object
	 * @param string $properties = (optional) properties to pass to adapter->connect
	 * @throws \Library\DOM\Exception
	 */
    public function __construct($adapter=null, $properties=null)
	{
		parent::__construct();

		$this->buffer = '';
		$this->adapter = $adapter;
		$this->DOMAdapter = null;
		$this->properties($properties);
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 * @return null
	 */
	public function __destruct()
	{
		parent::__destruct();
		if ($this->DOMAdapter)
		{
			unset($this->DOMAdapter);
		}
	}

    /**
     * saveDom
     *
     * Save a DOM document to a file/database
	 * @param DOMDocument $domDocument = (optional) DOM document object
	 * @param mixed $properties = (optional) adapter properties, array or Library\Properties
     * @return string $buffer
     * @throws \Library\DOM\Exception
     */
	public function saveDom($domDocument=null, $properties=null)
	{
		$domDocument = $this->domDocument($domDocument);
		$this->properties($properties);

		$this->saveXML();

		if (! $this->adapter)
		{
			if (! $this->properties->exists('DOM_Adapter'))
			{
				throw new Exception(Error::code('DomUnknowAdapter'));
			}

			$this->adapter = $this->properties['DOM_Adapter'];
		}

		if (! $this->DOMAdapter)
		{
			if (! $this->DOMAdapter = Factory::instantiateClass($this->adapter, $this->properties))
			{
				throw new Exception(Error::code('DomUnknowAdapter'));
			}
		}

		$this->DOMAdapter->mode = 'w';
		return $this->DOMAdapter->save($this->domDocumentBuffer, $this->properties);
	}

	/**
     * loadDom
     *
     * Load a DOM document from a source file
	 * @param object $properties = (optional) adapter properties, array or Library\Properties
	 * @throws \Library\DOM\Exception
     */
	public function loadDom($properties=null)
	{
		$this->properties($properties);

		if (! $this->adapter)
		{
			if (! $this->properties->exists('DOM_Adapter'))
			{
				throw new Exception(Error::code('DomMissingAdapter'));
			}

			$this->adapter = $this->properties['DOM_Adapter'];
		}

		if (! $this->DOMAdapter = Factory::instantiateClass($this->adapter, $this->properties))
		{
			throw new Exception(Error::code('DomUknownAdapter'));
		}

		$this->buffer = $this->DOMAdapter->load();
		$this->loadXML($this->buffer);
	}

	/**
	 * properties
	 *
	 * get/set the connection properties
	 * @param mixed $properties = (optional) connection properties, array or Library\Properties instance
	 * @return object $properties = Library\Properties class instance
	 * @throws \Library\DOM\Exception
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
		
			$this->properties = $properties;
		}

		return $this->properties;
	}

	/**
	 * buffer
	 *
	 * (Set and) return the original file buffer
	 * @param string $buffer = (optional) buffer to store, null to query only
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

}
