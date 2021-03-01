<?php
namespace Library\XML;

use Library\Error;
use Library\Utilities\PHPModule;

/*
 *		Library\XML\LoadXML is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 		or from http://opensource.org/licenses/academic.php
*/
/**
 * Library\XML\LoadXML
 *
 * Loads an XML document into a SimpleXMLElement and parses it into a single level array
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage XML
 */
class LoadXML implements  \ArrayAccess, \Iterator, \Countable
{
	/**
	 * $xml
	 *
	 * SimpleXMLElement
	 * @var object $xml = SimpleXMLElement
	 */
	protected $xml;

	/**
	 * callback
	 *
	 * The (optional) callback function description
	 * @var array $callback
	 */
	protected $callback;

	/**
	 * xmlUrl
	 *
	 * URL of the xml file
	 * @var string $xmlUrl
	 */
	protected $xmlUrl;

	/**
	 * records
	 *
	 * An array containing the transformed xml records
	 * @var array $records
	 */
	public $records;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $xmlUrl = url to load xml file from
	 * @throws Library\XML\Exception
	 */
	public function __construct($xmlUrl, $callback=null)
	{
		if (! PHPModule::loaded(array('SimpleXML')))
		{
			throw new Exception(Error::code('PhpExtensionNotAvailable'));
		}

		$this->callback = null;
		$this->records = array();
		$this->xmlUrl = $xmlUrl;

		$isData = ! stream_is_local($this->xmlUrl);
		$this->xml = new \SimpleXMLElement($this->xmlUrl, 0, $isData, "", false);
		if ($callback !== null)
		{
			$this->xmlArray($callback);
		}
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 */
	public function __destruct()
	{
		unset($this->xml);
	}

	/**
	 * __toString
	 *
	 * Return the contents of the class as a printable string
	 * @return string $buffer = printable string
	 */
	public function __toString()
	{
		$buffer = '';

		foreach($this->records as $element => $child)
		{
			$buffer .= sprintf("[%s] => %s\n", $element, get_class($child));
		}

		return $buffer;
	}

	/**
	 * xmlArray
	 *
	 * Convert the SimpleXMLElement object to a single-level array
	 * @param array $callback = (optional) Element processing callback function/class::method
	 * @return array $xmlArray = xml object in an array
     * @throws Library\XML\Exception
	 */
	public function xmlArray($callback=null)
	{
		if ($callback != null)
		{
			$this->xmlSetCallback($callback);
		}

		$this->records = array();
		foreach($this->xml->children() as $child)
		{
			if ($this->callback === null)
			{
				array_push($this->records, $child);
			}
			else
			{
				if (($value = call_user_func_array($this->callback, array($child))) === false)
				{
					throw new Exception(Error::code('CallbackFunctionError'));
				}

				array_push($this->records, $value);
			}
		}

		return $this->records;
	}

	/**
	 * xmlString
	 *
	 * Convert the SimpleXMLElement object to a 'well-formed XML string'
	 * @param string $xmlFile = (optional) if provided, write to this file and return boolean result
	 * @return string $xmlString = xml string if $xmlFile is null or true if $xmlFile provided was successfully written
	 * @throws Library\XML\Exception
	 */
	public function xmlString($xmlFile=null)
	{
		if ($xmlFile === null)
		{
			if ($xmlString = $this->xml->asXML() !== false)
			{
				return $this->xml->asXML();
			}

			throw new Exception(Error::code('XmlToString'));
		}

		if (! $this->xml->asXML($xmlFile))
		{
			throw new Exception(Error::code('FileWriteError'));
		}

		return true;
	}

	/**
	 * xml
	 *
	 * Returns the current xml property
	 * @return object $xml
	 */
	public function xml()
	{
		return $this->xml;
	}

	/**
	 * xmlName
	 *
	 * Returns the name of the top xml element
	 * @return string $name = name (tag) of the top SimpleXMLElement
	 */
	public function xmlName()
	{
		return $this->xml->getName();
	}


	/**
     * xmlCallback
     *
     * Set callback function
     * @param array|string $callback = name of the callback function or array containing class and method
     * @return array $callback = current callback property
     * @throws Library\XML\Exception
     */
    public function xmlSetCallback($callback)
    {
   		if (! is_array($callback))
   		{
   			if (! method_exists($this, $callback))
   			{
   				throw new Exception(Error::code('CallbackIsNotCallable'));
   			}

   			$callback = array($this, $callback);
   		}

		if (! is_callable($callback, true))
   		{
   			throw new Exception(Error::code('CallbackIsNotCallable'));
   		}

   		$this->callback = $callback;
    	return $this->callback;
    }

    /**
     * xmlGetCallback
     *
     * Returns a copy of the current callback function property
     * @return array $callback
     */
    public function xmlGetCallback()
    {
    	return $this->callback;
    }

	/**
	 * records
	 *
	 * Get a copy of the records array
	 * @param array $records = (optional) records array, null to query only
	 * @return array $records = current records array
	 */
	public function records($records=null)
	{
		if ($records !== null)
		{
			$this->records = $records;
		}

		return $this->records;
	}

	/**
	 * ******************************************
	 *
	 * Iterator Implementation
	 *
	 * ******************************************
	 */

	/**
	 * rewind.
	 *
	 * Moves the current record array pointer to the first item in the array.
	 */
	public function rewind()
	{
		reset($this->records);
	}

	/**
	 * current.
	 *
	 * Returns the current array element data.
	 */
	public function current()
	{
		return current($this->records);
	}

	/**
	 * key.
	 *
	 * Returns the current array key value.
	 * @return $key = current array key value.
	 */
	public function key()
	{
		return key($this->records);
	}

	/**
	 * next.
	 *
	 * Moves current to the next array element.
	 * @return null.
	 */
	public function next()
	{
		return next($this->records);
	}

	/**
	 * valid.
	 *
	 * Returns the validity of the current array pointer
	 * @return boolean true = the current node pointer is valid.
	 */
	public function valid()
	{
		if (($key = $this->key()) !== null)
		{
			return array_key_exists($this->key(), $this->records);
		}

		return false;
	}

	/**
	 * ******************************************
	 *
	 * Countable Implementation
	 *
	 * ******************************************
	 */

	/**
	 * count
	 *
	 * Returns the number of unique nodes in the tree
	 * @return integer $count
	 */
	public function count()
	{
		return count($this->records);
	}

	/**
	 * ******************************************
	 *
	 * Array Access Implementation
	 *
	 * ******************************************
	 */

	/**
	 * offsetSet
	 *
	 * Set the value at the indicated offset
	 * @param mixed $offset = offset to the entry
	 * @param mixed $value = value of entry at $offset
	 */
	public function offsetSet($offset, $value)
	{
		$this->records[$offset] = $value;
	}

	/**
	 * offsetGet
	 *
	 * Returns the value at the indicated offset
	 * @param mixed $offset = offset to the entry
	 * @returns mixed $value = value of entry at $offset, null if not found
	 */
	public function offsetGet($offset)
	{
		if (array_key_exists($offset, $this->records))
		{
			return $this->records[$offset];
		}

		return null;
	}

	/**
	 * offsetExists
	 *
	 * Returns true if the offset exists, false if not
	 * @param mixed $offset = offset (property) to test for existence
	 */
	public function offsetExists($offset)
	{
		return array_key_exists($offset, $this->records);
	}

	/**
	 * offsetUnset
	 *
	 * Unset the provided offset
	 * @param mixed $offset = offset to unset
	 */
	public function offsetUnset($offset)
	{
		unset($this->records[$offset]);
	}

}
