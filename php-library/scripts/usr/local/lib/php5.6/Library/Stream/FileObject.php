<?php
namespace Library\Stream;

/*
 *		FileObject is copyright � 2012, 2015. EarthWalk Software.
 *		Licensed under the Academic Free License version 3.0.
 *		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 * FileObject.
 *
 * Open a stream with the FileIO\FileObject class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Stream
 */
class FileObject extends \Library\FileIO\FileObject
{
	/**
	 * buffer
	 *
	 * The i/o buffer contents
	 * @var string $buffer
	 */
	protected	$buffer;

	/**
	 * header
	 *
	 * The i/o header contents, if there was one
	 * @var string $header
	 */
	protected	$header;

	/**
	 * streamInfo
	 *
	 * Stream info from last connect
	 * @var array $streamInfo
	 */
	protected	$streamInfo;

	/**
	 * context
	 * 
	 * Stream context.
	 * @var context $context
	 */
	protected	$context;

	/**
	 * streamReadArray
	 * 
	 * Array containing the input streams which need to be watched (stream_select)
	 * @var array $streamArray
	 */
	protected	$streamReadArray;

	/**
	 * streamReadArray
	 * 
	 * Array containing the output streams which need to be watched (stream_select)
	 * @var array $streamWroteArray
	 */
	protected	$streamWriteArray;

	/**
	 * streamExceptArray
	 * 
	 * Array containing the out-of-band input streams which need to be watched (stream_select)
	 * @var array $streamExceptArray
	 */
	protected	$streamExceptArray;

	/**
	 * __construct
	 *
	 * class constructor
	 * @param string $url = connection url
	 * @param string $mode = (optional) file i/o mode (default = 'r')
	 * @param boolean $useIncludePath = (optional) use include path to locate $url if true (default = false)
	 * @param resource $context = (optional) stream context resource, null for none
	 * @throws Library\Stream\Exception
	 */
	public function __construct($url, $mode='r', $useIncludePath=false, $context=null)
	{
		parent::__construct($url, $mode, $useIncludePath, $context);

		$this->setTimeout(90);
		$this->streamInfo = null;

		$this->header = '';
		$this->buffer = '';

		$this->streamReadArray = array();
		$this->streamWriteArray = array();
		$this->streamExceptArray = array();
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * getLine
	 *
	 * read the line from the stream
	 * @param integer $maxLength = (optional) length to read from the stream, -1 to read to end-of-file (default)
	 * @param string $ending = (optional) line ending sequence, null to use system-relative standard (default)
	 * @return string $buffer = buffer read
	 * @throws Library\Stream\Exception
	 */
	public function getLine($maxLength=-1, $ending=null)
	{
		if ($ending)
		{
			$this->buffer = stream_get_line($this->handle, $maxLength, $ending);
		}
		else
		{
			$this->buffer = stream_get_line($this->handle, $maxLength);
		}

		if ($this->buffer === false)
		{
			if ($this->eof())
			{
				throw new Exception(Error::code('StreamEof'));
			}
			else
			{
				throw new Exception(Error::code('StreamReadFailed'));
			}
		}

		return $this->buffer;
	}

	/**
     * getStream
     *
     * get stream contents
     * @param integer $maxLength = (optional) maximum stream length, -1 for unlimited.
     * @param integer $offsets = (optional) number of bytes to skip before reading, -1 to not offset.
     * @return string $buffer = stream content
     * @throws \Library\Stream\Exception
     */
	public function getStream($maxLength=-1, $offset=-1)
	{
		$this->header = '';
		$this->buffer = '';

		$this->getContents($maxLength, $offset);
		$this->streamInfo();

		return $this->buffer;
	}

    /**
     * getContents
     *
     * read stream
     * @param integer $maxLength = (optional) length to read from the stream, -1 to read the complete stream
     * @param integer $offset = (optional) bytes to skip before reading to buffer, -1 to start at next byte
     * @return string $buffer = stream content
     * @throws Library\Stream\Exception
     */
	public function getContents($maxLength=-1, $offset=-1)
	{
		$this->buffer = '';

		if ($this->eof())
		{
			throw new Exception(Error::code('StreamEof'));
		}

		$length = -1;

		do
		{
			if ($maxLength > 0)
			{
				$length = $maxLength - strlen($this->buffer);
			}

			$buffer = stream_get_contents($this->handle, $length, $offset);

			if ($buffer !== false)
			{
				$this->buffer .= $buffer;
				if (($maxLength >= 0) && (strlen($this->buffer) >= $maxLength))
				{
					break;
				}
			}

			$offset = -1;
		}
		while($buffer && (! $this->eof()));

		if (($buffer === false) && (strlen($this->buffer) == 0))
		{
			throw new Exception(Error::code('StreamReadFailed'));
		}

		return $this->buffer;
	}

	/**
	 * streamSelect
	 * 
	 * Returns the first selected stream in the array, and sets vkey to the key
	 * @param array $array = array containing the stream(s) to wait for
	 * @param array $selected = on return contains keys of selected stream(s) in array
	 * @param integer $timeout = time, in sec, to wait (0 = return immediately, null = wait forever)
	 * @return mixed $element = number of items selected, false on error
	 */
	public function streamSelect($array, &$selected, $timeout=0)
	{
		$select = array_values($array);
		$selectKeys = array_keys($array);

		$selected = array();

		$null = NULL; // needs to be a var, not a constant

		if ($count = stream_select($select, $null, $null, $timeout))
		{
			foreach($select as $selected)
			{
				array_push($selected, array_search($selected, $array, false));
			}
		}
		
		return $count;
	}

	/**
	 * streamInfo
	 *
	 * read the streamInfo array (meta_data)
	 * @return array $streamInfo = array containing result
	 * @throws \Library\Stream\Exception
	 */
	public function streamInfo()
	{
		return $this->streamInfo = stream_get_meta_data($this->handle);
	}

	/**
	 * extractHeader
	 *
	 * Extract the header from the buffer and return the (shortened) buffer
	 * @return string $buffer
	 */
	public function extractHeader()
	{
		$crlf = "\r\n";

		$this->header = '';
		$pos = strpos($this->buffer, $crlf . $crlf);
		if ($pos !== false)
		{
			$this->header = substr($this->buffer, 0, $pos);
			$this->buffer = substr($this->buffer, $pos + 2 * strlen($crlf));
		}

		return $this->buffer;
	}

	/**
	 * isTimeout
	 *
	 * check if there was a timeout
	 * @return boolean $timeout = true if timeout, false if not
	 */
	public function isTimeout()
	{
		if (is_array($this->streamInfo) && 
			array_key_exists('timed_out', $this->streamInfo) && 
			$this->streamInfo['timed_out'])
		{
			return true;
		}

		return false;
	}

	/**
	 * isLocal
	 *
	 * check if this is a local file stream
	 * @return boolean $local = true if on local host, false if remote
	 */
	public function isLocal()
	{
		return stream_is_local($this->handle);
	}

	/**
	 * isLockable
	 *
	 * Returns true if stream can be locked using flock(), false if not
	 * @return boolean $lockable = true if can be locked, false if not
	 */
	public function isLockable()
	{
		return stream_supports_lock($this->handle);
	}

	/**
	 * setTimeout
	 *
	 * set the timeout for this stream in seconds and microseconds
	 * @param int $seconds = number of seconds until timeout
	 * @param int $microseconds = (optional) number of microseconds, defaults to 0
	 * @return boolean $set = true if successful, false if not
	 */
	public function setTimeout($seconds, $microseconds=0)
	{
		 return stream_set_timeout($this->handle, $seconds, $microseconds);
	}

	/**
	 * createContext
	 *
	 * Get a stream context for the supplied options
	 * @param array $options = an associative array of associative arrays
	 * @param array $parameters = an associative array of parameter names and their values.
	 * @return resource $context
	 */
	public function createContext($options, $parameters=null)
	{
		if ($parameters)
		{
			$this->context = stream_context_create($options, $parameters);
		}
		else
		{
			$this->context = stream_context_create($options);
		}
		
		return $this->context;
	}

	/**
	 * buffer
	 *
	 * Get/set a copy of the input buffer
	 * @param string $buffer = (optional) data to store in buffer, null to query
	 * @return string $buffer = content buffer, or empty
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
	 * header
	 *
	 * Get a copy of the header buffer
	 * @return string $header = header, or empty if no header
	 */
	public function header()
	{
		return $this->header;
	}

}
