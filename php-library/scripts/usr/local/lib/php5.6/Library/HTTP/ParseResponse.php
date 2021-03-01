<?php
namespace Library\HTTP;
use Library\Error;

/*
 * 		HTTP\ParseResponse is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * ParseResponse.
 *
 * Object class to parse an HTTP response into headers (if present) and body
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage HTTP
 */
class ParseResponse
{
	/**
	 * header
	 *
	 * A string containing the header lines of the response
	 * @var string $header
	 */
	private		$header;

	/**
	 * body
	 *
	 * A string containing the body lines of the response
	 * @var string $body;
	 */
	private		$body;

	/**
	 * headerArray
	 *
	 * Associative array containing key/value pairs for header lines
	 * @var array $headerArray
	 */
	private		$headerArray;

	/**
	 * bodyArray
	 *
	 * Associative array containing key/value pairs for body lines
	 * @var array $bodyArray
	 */
	private		$bodyArray;

	/**
	 * htmlBuffer
	 *
	 * html buffer being parsed
	 * @var string $htmlBuffer
	 */
	private		$htmlBuffer;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $htmlBuffer = (optional) buffer to parse
	 * @throws EWSLibrary_HTTP_Exception
	 */
	public function __construct($htmlBuffer=null)
	{
		$this->header = null;
		$this->body   = null;

		$this->headerArray = null;
		$this->bodyArray   = null;

		$this->htmlBuffer = null;
		$this->htmlBuffer($htmlBuffer);
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 * @return null
	 */
	public function __destruct()
	{
	}

	/**
	 * htmlBuffer
	 *
	 * set (and parse) the htmlBuffer
	 * @param string $buffer = (optional) buffer to set (and parse), null to query
	 * @return string $buffer
	 * @throws \Library\HTTP\Exception
	 */
	public function htmlBuffer($buffer=null)
	{
		if ($buffer !== null)
		{
			$this->htmlBuffer = $buffer;
			$this->parseBuffer();
		}

		return $this->htmlBuffer;
	}

	/**
	 * parseBuffer
	 *
	 * parse the html buffer into a header (if present) and body
	 * @throws \Library\HTTP\Exception
	 */
	protected function parseBuffer()
	{
		$this->header = null;
		$this->body   = null;

		$this->headerArray = null;
		$this->bodyArray   = null;

		$buffer = str_replace("\r\n", "\n", $this->htmlBuffer);
		$segments = explode("\n\n", $buffer, 2);
		if (count($segments) == 1)
		{
			$this->body = trim($buffer);
		}
		else
		{
			$this->header = rtrim($segments[0]);
			$this->body   = trim($segments[1]);
		}

		$this->parseHeader();
		$this->parseBody();
	}

    /**
     * parseHeader
     *
     * parse the header from the buffer and return as an array
     * @param string $header      = buffer to parse for a header
     * @return array $headerArray = header array
     * @throws \Library\HTTP\Exception
     */
	protected function parseHeader($header=null)
    {
    	if ($header)
    	{
    		$this->htmlBuffer($header);
    		$this->header = $this->body;
    		$this->body = null;
    	}

		$lines = explode("\n", $this->header);
		foreach($lines as $index => $line)
		{
			if ($index == 0)
			{
				$fields = explode(' ', $line, 3);
				if (count($fields) < 3)
				{
					throw new Exception(Error::code('HttpHeaderError'));
				}

				$this->headerArray['HTTP'] = substr($fields[0], 5);
				$this->headerArray['HTTP-Status'] = $fields[1];
				$this->headerArray['HTTP-Message'] = $fields[2];
			}
			else
			{
				$fields = explode(':', $line);
				if (count($fields) < 2)
				{
					throw new Exception(Error::code('HttpHeaderError'));
				}

				$this->headerArray[$fields[0]] = $fields[1];
			}
		}

		return $this->headerArray;
	}

	/**
     * parseBody
     *
     * parse the body from the buffer and return as an array
     * @param string $body = body to parse
     * @return array $bodyArray = body array
     * @throws \Library\HTTP\Exception
     */
	protected function parseBody($body=null)
    {
    	if ($body)
    	{
    		$header = $this->header;
    		$this->htmlBuffer($body);
    		$this->header = $header;
    	}

    	$this->bodyArray = explode("\n", $this->body);
	}

	/**
	 * __get
	 *
	 * override default __get to allow $object->$name
	 * @param string $name = name of field to get
	 * @return mixed $name value
	 */
	public function __get($name)
	{
		if (! property_exists($this, $name))
		{
			return null;
		}

		return $this->$name;
	}

	/**
	 * __set
	 *
	 * override default __set to allow $object->$name = $value
	 * @param string $name = name of the field to set
	 * @param mixed $value = value to set field to
	 */
	public function __set($name, $value)
	{
		if (property_exists($this, $name))
		{
			$this->$name = $value;
		}
	}

	/**
	 * __toString
	 *
	 * Return a printable buffer containing the variables and their values
	 * @return string $buffer
	 */
	public function __toString()
	{
		$buffer = '';
		foreach($this as $name => $value)
		{
			if (is_array($value))
			{
				$buffer .= sprintf("%s\n", $name);
				foreach($value as $key => $data)
				{
					$buffer .= sprintf("\t%s = '%s'\n", $key, $data);
				}
			}
			else
			{
				$buffer .= sprintf("%s = '%s'\n", $name, $value);
			}
		}

		return $buffer;
	}

}
