<?php
namespace Library;

/*
 *		Stream.php is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * Stream.
 *
 * The Stream class provides an interface to the Stream drivers to perform stream i/o Functions.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Stream
 */
class Stream implements \RecursiveIterator, \SeekableIterator
{
	/**
	 * stream
	 *
	 * driver class object
	 * @var object $stream
	 */
	protected	$stream;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $adapter = stream adapter name
	 * @param string $url = url of the file or resource
	 * @param string $mode = (optional) 'r' (or empty) for read, 'w' for write
	 * @param resource $context = (optional) stream context resource, null for none
	 * @throws Library\Stream\Exception
	 */
	public function __construct($adapter, $url, 
										  $mode='r', 
										  $useIncludePath=false, 
										  $context=null)
	{
		$this->stream = Stream\Factory::instantiateClass($adapter, $url, 
																   $mode, 
																   $useIncludePath, 
																   $context);
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 * @return null
	 */
	public function __destruct()
	{
		unset($this->stream);
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
		return $this->stream->getLine($maxLength, $ending);
	}

	/**
	 * getStream
	 *
	 * get stream contents
	 * @param integer $maxLength = (optional) length to read from the stream, 
	 * 										  -1 to read to end-of-file (default)
	 * @param integer $offset = (optional) bytes to skip before reading to buffer, 
	 * 									   -1 to start at the next byte (default)
	 * @return string $buffer = stream content
	 * @throws \Library\Stream\Exception
	 */
	public function getStream($maxLength=-1, $offset=-1)
	{
		return $this->stream->getStream($maxLength, $offset);
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
		return $this->stream->getContents($maxLength, $offset);
	}

	/**
	 * streamInfo
	 *
	 * read the streamInfo array (meta_data)
	 * @throws \Library\Stream\Exception
	 */
	public function streamInfo()
	{
		return $this->stream->streamInfo();
	}

	/**
	 * streamSelect
	 * 
	 * Returns the first selected stream in the array, and sets vkey to the key
	 * @param array $array = array containing the stream(s) to wait for
	 * @param integer $vkey = on return, is set to the array element which was selected
	 * @param integer $timeout = time, in msec, to wait (0 = return immediately, null = wait forever)
	 * @return mixed $element = array element selected, false on error
	 */
	public function streamSelect($array, &$vkey, $timeout=1)
	{
		return $this->stream->streamSelect($array, $vkey, $timeout);
	}

	/**
	 * extractHeader
	 *
	 * Extract the header from the buffer and return the (shortened) buffer
	 * @return string $buffer
	 */
	public function extractHeader()
	{
		return $this->stream->extractHeader();
	}

	/**
	 * isTimeout
	 *
	 * check if there was a timeout
	 * @returns boolean $timeout = true if a timeout occurred, false if not
	 */
	public function isTimeout()
	{
		return $this->stream->isTimeout();
	}

	/**
	 * isLocal
	 *
	 * check if this is a local file stream
	 * @return boolean $local = true if on local host, false if remote
	 * @throws \Library\Stream\Exception
	 */
	public function isLocal()
	{
		return $this->stream->isLocal();
	}

	/**
	 * isLockable
	 *
	 * Returns true if stream can be locked using flock(), false if not
	 * @return boolean $lockable = true if can be locked, false if not
	 */
	public function isLockable()
	{
		return $this->stream->isLockable();
	}

	/**
	 * setTimeout
	 *
	 * set the timeout for this stream in seconds and microseconds
	 * @param int $seconds = number of seconds until timeout
	 * @param int $microseconds = (optional) number of microseconds, defaults to 0
	 * @return boolean $set = true if successful, false if not
	 * @throws Library\Stream\Exception
	 */
	public function setTimeout($seconds, $microseconds=0)
	{
		return $this->stream->setTimeout($seconds, $microseconds);
	}

	/**
	 * createContext
	 *
	 * Get a stream context for the supplied options
	 * @param array $options = an associative array of associative arrays
	 * @param array $parameters = (optional) associative array of parameter names and their values.
	 * @return resource $context
	 */
	public function createContext($options, $parameters=null)
	{
		return $this->stream->createContext($options, $parameters);
	}

	/**
	 * buffer
	 *
	 * Get a copy of the input buffer
	 * @return string $buffer = content buffer, or empty
	 */
	public function buffer($buffer)
	{
		return $this->stream->buffer($buffer);
	}

	/**
	 * header
	 *
	 * Get a copy of the header buffer
	 * @return string $header = header, or empty if no header
	 */
	public function header()
	{
		return $this->stream->header();
	}

	/** *****************************************************************
	 *
	 * 		Library\FileIO\FileObject inherited classes
	 *
	 * ****************************************************************** */

	/**
	 * fgetc
	 *
	 * get the next character from the file
	 * @return string $character
	 * @throws \Library\FileIO\Exception
	 */
	public function fgetc()
	{
		return $this->stream->fgetc();
	}

	/**
	 * fgets
	 *
	 * get the next line from the file
	 * @return string $buffer
	 */
	public function fgets()
	{
		return $this->stream->fgets();
	}

	/**
	 * fgetss
	 *
	 * get the next line from the file
	 * @param string $allowable = (optional) list of allowable tags (won't be stripped)
	 * @return string $buffer
	 * @throws \Library\FileIO\Exception
	 */
	public function fgetss($allowable=null)
	{
		return $this->stream->fgetss($allowable);
	}

	/**
	 * fgetcsv
	 *
	 * get the next line from the csv file as an indexed array of fields (index) and values
	 * @return array $buffer
	 * @throws \Library\FileIO\Exception
	 */
	public function fgetcsv()
	{
		return $this->stream->fgetcsv();
	}

	/**
	 * fscanf
	 *
	 * Returns the next input line from the file stream parsed according to a format.
	 * @param string $format = The specified format
	 * @param mixed $variables = (optional) list of variables passed by reference to receive the parsed values
	 * @return array|integer $parsed = array of parsed fields if $variables is not provided, else the number of variables assigned
	 * @throws \Library\FileIO\Exception
	 */
	public function fscanf($format)
	{
		if (func_num_args() == 1)
		{
			return $this->stream->fscanf($format);
		}

		$arguments = func_get_args();
		array_shift($arguments);

		$variables = array();
		foreach($arguments as $argument)
		{
			$variables[] = &$argument;
		}

		array_unshift($variables, $format);

		$method = new \ReflectionMethod($this->stream, 'fscanf');

		return $method->invokeArgs($this->stream, $arguments);
	}

	/**
	 * fputcsv
	 *
	 * Format line as CSV and write to file pointer
	 * @param array $fields = array of field name/value pairs to store
	 * @param string $delimiter = (optional) csv delimiter, default = null
	 * @param string $enclosure = (optional) csv enclosure, default = null
	 * @return integer|boolean $result = the length of the written string or FALSE on failure.
	 */
	public function fputcsv($fields, $delimiter=null, $enclosure=null)
	{
		return $this->stream->fputcsv($fields, $delimiter, $enclosure);
	}

	/**
	 * fwrite
	 *
	 * Write the buffer to the file stream
	 * @param string $buffer = buffer to write
	 * @param integer $length = (optional) length to write, null to write the complete buffer
	 * @return integer $bytes|null = bytes written, null on error
	 */
	public function fwrite($buffer, $length=null)
	{
		return $this->stream->fwrite($buffer, $length);
	}

	/**
	 * fseek
	 *
	 * Seek to the requested record
	 * @param integer $position = final position relative to $whence
	 * @param integer $whence = SEEK_SET, SEEK_CUR or SEEK_END
	 * @return integer $result = 0 if successful, -1 if failed
	 */
	public function fseek($position, $whence='SEEK_SET')
	{
		return $this->stream->fseek($position, $whence);
	}

	/**
	 * fflush
	 *
	 * Flush all buffered output to the output buffer
	 * @return boolean $result = true successful, otherwise false
	 */
	public function fflush()
	{
		return $this->stream->fflush();
	}

	/**
	 * ftruncate
	 *
	 * Returns the position of the file pointer
	 * @return integer $position
	 */
	public function ftruncate($size)
	{
		return $this->stream->ftruncate($size);
	}

	/**
	 * flock
	 *
	 * Attempt to lock the file
	 * @param integer $operation = lock operation (LOCK_SH, LOCK_EX, LOCK_UN, or LOCK_NB)
	 * @param integer $wouldBlock = (optional) true if  the lock would block (EWOULDBLOCK errno condition), default = false
	 * @return boolean $result = true successful, otherwise false
	 */
	public function flock($operation, $wouldBlock=false)
	{
		return $this->stream->flock($operation, $wouldBlock);
	}

	/**
	 * fstat
	 *
	 * Gets information about the file
	 * @return array $stat = array with the statistics of the file
	 */
	public function fstat()
	{
		return $this->stream->fstat();
	}

	/**
	 * ftell
	 *
	 * Returns the position of the file pointer
	 * @return integer $position
	 */
	public function ftell()
	{
		return $this->stream->ftell();
	}

	/**
	 * fpassthru
	 *
	 * Reads to EOF on the given file pointer from the current position and writes the results to the output buffer
	 * @return integer $bytes = number of bytes passed thru to the output buffer, false on error
	 */
	public function fpassthru()
	{
		return $this->stream->fpassthru();
	}

	/**
	 * eof
	 *
	 * Return EOF status for the file
	 * @return boolean $eof = true if the file is at (or beyond) eof or an error occurred, otherwise false
	 */
	public function eof()
	{
		return $this->stream->eof();
	}

	/** *******************************************************************
	 *
	 *		Control Functions
	 *
	 ********************************************************************** */

	/**
	 * getFlags
	 *
	 * Get current flag settings
	 * @return integer $flags
	 */
	public function getFlags()
	{
		return $this->stream->getFlags();
	}

	/**
	 * setFlags
	 *
	 * Set current flag settings
	 * @param integer $flags
	 */
	public function setFlags($flags)
	{
		return $this->stream->setFlags($flags);
	}

	/**
	 * getCsvControl
	 *
	 * get the csv control flags
	 * @return array $csvControl, index 0 = delimiter, index 1 = enclosure
	 */
	public function getCsvControl()
	{
		return $this->stream->getCsvControl();
	}

	/**
	 * setCsvControl
	 *
	 * Set the csv control flags
	 * @param string $delimiter = (optional) delimiter, default = ','
	 * @param string $enclosure = (optional) enclosure, default = '"'
	 * @param string $escape    = (optional) escape, default = "\\"
	 */
	public function setCsvControl($delimiter=',', $enclosure='"', $escape="\\")
	{
		return $this->stream->setCsvControl($delimiter, $enclosure, $escape);
	}

	/**
	 * getCurrentLine
	 *
	 * get the next line from the file
	 * @return string $buffer
	 */
	public function getCurrentLine()
	{
		return $this->stream->getCurrentLine();
	}

	/**
	 * getMaxLineLen
	 *
	 * Get maximum line length
	 * @return integer $length
	 */
	public function getMaxLineLen()
	{
		return $this->stream->getMaxLineLen();
	}

	/**
	 * setMaxLineLen
	 *
	 * Set the maximum line length, 0 to be unlimited
	 * @param integer $length
	 */
	public function setMaxLineLen($length)
	{
		return $this->stream->setMaxLineLen($length);
	}

	/** *******************************************************************
	 *
	 * 		Iterator
	 *
	 ********************************************************************** */

	/**
	 * current
	 *
	 *  Retrieve current line of file
	 *  @return string|array $buffer
	 */
	public function current()
	{
		return $this->stream->current();
	}

	/**
	 * key
	 *
	 * retrieve the current file line number
	 * @return integer $lineNumber
	 */
	public function key()
	{
		return $this->stream->key();
	}

	/**
	 * next
	 *
	 * retrieve the next line
	 */
	public function next()
	{
		return $this->stream->next();
	}

	/**
	 * rewind
	 *
	 * rewind to the first line of the file
	 */
	public function rewind()
	{
		return $this->stream->rewind();
	}

	/**
	 * valid
	 *
	 * returns true if file is not at eof, otherwise returns false
	 * @return boolean $valid = false if file is at eof, otherwise true
	 */
	public function valid()
	{
		return $this->stream->valid();
	}

	/** *******************************************************************
	 *
	 * 		SeekableIterator
	 *
	 ********************************************************************** */

	/**
	 * seek
	 *
	 * Seek to the requested line
	 * @param integer $linePos = line to seek to
	 * @throws \Library\FileIO\Exception
	 */
	public function seek($linePos)
	{
		return $this->stream->seek($linePos);
	}

	/** *******************************************************************
	 *
	 * 		RecursiveIterator
	 *
	 ********************************************************************** */

	/**
	 * hasChildren
	 *
	 * Returns true if has children, false if not
	 * @return boolean $has = true if has children, false if not
	 */
	public function hasChildren()
	{
		return $this->stream->hasChildren();
	}

	/**
	 * getChildren
	 *
	 * Returns null, because has no children
	 * @return void
	 */
	public function getChildren()
	{
		return $this->stream->getChildren();
	}

	/** *******************************************************************
	 *
	 * 		Traversable
	 *
	 ********************************************************************** */

	/** *******************************************************************
	 *
	 *		Magic Methods
	 *
	 ********************************************************************** */

	/**
	 * __get
	 * 
	 * Intercept get request and, if $name is handle, return the file handle
	 * @param string $name
	 * @return mixed $value
	 */
	public function __get($name)
	{
		if ($name == 'handle')
		{
			return $this->stream->handle;
		}
		
		return null;
	}

	/**
	 * __toString
	 *
	 * Return the current record as a string (alias of current())
	 * @return string $buffer
	 */
	public function __toString()
	{
		return $this->stream->__toString();
	}

}
