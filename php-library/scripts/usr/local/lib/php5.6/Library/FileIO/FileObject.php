<?php
namespace Library\FileIO;
use Library\Error;

/*
 *		FileIO\FileObject is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * FileIO\FileObject.
 *
 * The FileIO\FileObject class implements the methods used in SPLFileObject, but exposes the file handle so it can be used in extended clases.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage FileIO
 */
class FileObject extends FileInfo implements \RecursiveIterator, \SeekableIterator
{
	/*
	 * The following constants are from ext/spl_directory.c in PHP 5.4.0 source
	 */

	/**
	 * DROP_NEW_LINE
	 *
	 * Drop new lines on input
	 */
	const			DROP_NEW_LINE	=  \SplFileObject::DROP_NEW_LINE;

	/**
	 * READ_AHEAD
	 *
	 * read the next line automatically following a rewind/next/seek
	 */
	const			READ_AHEAD		=  2;

	/**
	 * SKIP_EMPTY
	 *
	 * Skip empty lines on input
	 */
	const			SKIP_EMPTY		=  4;

	/**
	 * READ_CSV
	 *
	 * Read the file as a CSV file
	 */
	const			READ_CSV		=  8;

	/**
	 * MASK
	 *
	 * Mask options to a single byte
	 */
	const			MASK			= 15;

	/**
	 * handle
	 *
	 * The FileObject file handle (opened in __construct)
	 * @var resource $handle
	 */
	protected		$handle;

	/**
	 * flags
	 *
	 * An integer containing the FileObject flags (currently same as SplFileObject flags)
	 * @var integer $flags
	 */
	private			$flags;

	/**
	 * csvControl
	 *
	 * Array containing the csv control field names and associated character ('delimiter', 'enclosure', 'escape')
	 * @var array csvControl
	 */
	private			$csvControl;

	/**
	 * mode
	 *
	 * The file open mode
	 * @var string $mode
	 */
	private			$mode;

	/**
	 * context
	 *
	 * The stream context, or null
	 * @var resource $context
	 */
	private			$context;

	/**
	 * maxLineLen
	 *
	 * The optional maximum line length for fgets and fwrite, 0 = no limit
	 * @var $maxLineLen
	 */
	private			$maxLineLen;

	/**
	 * currentLineNum
	 *
	 * The file input (read) line number
	 * @var string $currentLineNum
	 */
	private			$currentLineNum;

	/**
	 * currentLine
	 *
	 * The file input (read) buffer
	 * @var string $currentLine
	 */
	private			$currentLine;

	/**
	 * currentLineLen
	 *
	 * The file input (read) line length
	 * @var string $currentLineLen
	 */
	private			$currentLineLen;

	/**
	 * currentArray
	 *
	 * The input buffer converted to an array (if the currentLine contains CSV data)
	 * @var $currentArray
	 */
	private			$currentArray;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string   $fileName       = name of the file associated with this class instance
	 * @param string   $mode           = (optional) file open mode (default = 'r')
	 * @param boolean  $useIncludePath = (optional) true = search include path for file, false = don't
	 * @param resource $context        = (optional) stream context
	 * @throws Library\FileIO\Exception, \Exception
	 */
	public function __construct($fileName, $mode='r', $useIncludePath=false, $context=null)
	{
		parent::__construct($fileName);

		$this->mode = $mode;
		$this->context = $context;

		$this->flags = 0;
		$this->maxLineLen = 0;

		$this->csvControl = array('delimiter'	=> ',',
								  'enclosure'	=> '"',
								  'escape'		=> '\\');

		if (is_resource($fileName))
		{
			$this->handle = $fileName;
			$fileName = "";
		}
		else 
		{
			if ($context)
			{
    			$this->handle = @fopen($fileName, $mode, $useIncludePath, $context);
			}
			else
			{
    			$this->handle = @fopen($fileName, $mode, $useIncludePath);
			}
		}

   		if (! $this->handle)
  		{
  			throw new Exception(Error::code('FileOpenError'));
  		}

	  	$this->currentLineNum = 0;

	  	$this->currentLine = null;
	  	$this->currentLineLen = 0;

	  	$this->currentArray = null;
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 */
	public function __destruct()
	{
		parent::__destruct();

		if ($this->handle)
		{
			@fclose($this->handle);
		}
	}

	/**
	 * __call
	 *
	 * Trap call to non-existent method and redirect, if valid
	 * @param string $method = name of the method being called
	 * @param array $args = argument array
	 * @return mixed $result = result from invoked method, if appropriate
	 * @throws \Library\FileIO\Exception, \ReflectionException, \Exception
	 */
	public function __call($method, $args)
	{
		if ($method != 'getCurrentLine')
		{
			throw new Exception(sprintf('Unknown Class Method: %s', $method), Error::code('UnknownClassMethod'));
		}

		$method = new \ReflectionMethod($this, 'fgets');
		if (! $args)
		{
			return $method->invoke($this);
		}
		else
		{
			return $method->invokeArgs($this, $args);
		}
	}

	/**
	 * __get
	 * 
	 * 
	 * @param unknown $name
	 * @return NULL
	 */
	public function __get($name)
	{
		if ($name == 'handle')
		{
			return $this->handle;
		}

		return null;
	}

	/** *******************************************************************
	 *
	 *		File and I/O Functions
	 *
	 ********************************************************************** */

	/**
	 * fgetc
	 *
	 * get the next character from the file
	 * @return string $character
	 * @throws \Library\FileIO\Exception
	 */
	public function fgetc()
	{
		$this->freeLine();

		if (($character = @fgetc($this->handle)) === false)
		{
			return false;
		}

		if ($character === PHP_EOL)
		{
			$this->currentLineNum++;
		}

		return $character;
	}

	/**
	 * fgets
	 *
	 * get the next line from the file
	 * @return string $buffer = next line from file, false if at End-Of-File when the method was called.
	 */
	public function fgets()
	{
		$lineLen = 0;
		$lineAdd = $this->currentLine ? 1 : 0;

		$this->freeLine();

		if ($this->eof())
		{
			return false;
		}

		if ($this->maxLineLen > 0)
		{
			$this->currentLine = @fgets($this->handle, $this->maxLineLen);
		}
		else
		{
			$this->currentLine = @fgets($this->handle);
		}

		if ($this->currentLine !== false)
		{
			if (! $this->currentLine)
			{
				$this->currentLine = '';
				$this->currentLineLen = 0;
			}
			else
			{
				if (($this->flags & self::DROP_NEW_LINE) &&
					(($pos = strpos($this->currentLine, PHP_EOL)) !== false))
				{
					$this->currentLine = substr($this->currentLine, 0, $pos);
				}

				$this->currentLineLen = strlen($this->currentLine);
			}

			$this->currentLineNum += $lineAdd;
		}
		
		return $this->currentLine;
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
		$this->freeLine();

		if ($this->eof())
		{
			return false;
		}

		if ($this->maxLineLen > 0)
		{
			$length = $this->maxLineLen;
		}
		else
		{
			$length = 1024;
		}

		if ($allowable == null)
		{
			$this->currentLine = @fgetss($this->handle, $length);
		}
		else
		{
			$this->currentLine = @fgetss($this->handle, $length, $allowable);
		}

		if ($this->currentLine === false)
		{
			$this->freeLine();
			return false;
		}

		$this->currentLineLen = strlen($this->currentLine);

		return $this->currentLine;
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
		do
		{
			if (($this->fgets() === false) || ($this->currentLine === null))
			{
				return false;
			}
		}
		while ((trim($this->currentLine) === '') && ($this->flags & self::SKIP_EMPTY));

		$this->currentArray = str_getcsv($this->currentLine, $this->csvControl['delimiter'], $this->csvControl['enclosure'], $this->csvControl['escape']);

		return $this->currentArray;
	}

	/**
	 * fscanf
	 *
	 * Parses input from a file according to a format.
	 * @param string $format = The specified format
	 * @param mixed $variables = (optional) list of variables passed by reference to receive the parsed values
	 * @return array|integer $parsed = array of parsed fields if $variables is not provided, 
	 *                                 else the number of variables assigned,
	 *                                 or -1 if the format asks for more fields than in the data.
	 * @throws \Reflection\Exception
	 */
	public function fscanf($format)
	{
		$this->freeLine();
		$this->currentLineNum++;
		$parameters = func_get_args();

		array_unshift($parameters, $this->handle);
		
		$function = new \ReflectionFunction('fscanf');
		return $function->invokeArgs($parameters);
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
		if ($delimiter === null)
		{
			$delimiter = $this->csvControl['delimiter'];
		}

		if ($enclosure === null)
		{
			$enclosure = $this->csvControl['enclosure'];
		}

		return @fputcsv($this->handle, $fields, $delimiter, $enclosure);
	}

	/**
	 * fwrite
	 *
	 * write the next line to the file
	 * @param string $buffer = buffer to write
	 * @param integer $length = (optional) length to write, null to write the complete buffer
	 * @return integer $bytes|null = bytes written, null on error
	 */
	public function fwrite($buffer, $length=null)
	{
		if ($length === null)
		{
			$length = strlen($buffer);
		}

		if ($length == 0)
		{
			return null;
		}

		$retries = 0;
		$written = 0;

		for ($writeLength = 0; $writeLength < $length; $writeLength += $written)
		{
        	$written = @fwrite($this->handle, substr($buffer, $writeLength));
        	if ($written === false)
        	{
				if (++$retries > 5)
				{
					break;
				}
			}
			else
			{
				$retries = 0;
			}
		}

		if ($writeLength == 0)
		{
			$writeLength = null;
		}

		return $writeLength;
	}

	/**
	 * fseek
	 *
	 * seek to the requested record
	 * @param integer $position = final position relative to $whence
	 * @param integer $whence = SEEK_SET, SEEK_CUR or SEEK_END
	 * @return integer $result = 0 if successful, -1 if failed
	 */
	public function fseek($position, $whence='SEEK_SET')
	{
		$this->freeLine();
		return @fseek($this->handle, $position, $whence);
	}

	/**
	 * fflush
	 *
	 * Flush all buffered output to the output buffer
	 * @return boolean $result = true successful, otherwise false
	 */
	public function fflush()
	{
		return @fflush($this->handle);
	}

	/**
	 * ftruncate
	 *
	 * Truncate the file to the indicated size
	 * @return boolean $result = true if successful, false if not
	 */
	public function ftruncate($size)
	{
		return @ftruncate($this->handle, $size);
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
		return @flock($this->handle, $operation, $wouldBlock);
	}

	/**
	 * fstat
	 *
	 * Gets information about the file
	 * @return array $stat = array with the statistics of the file (refer to php fstat() function for details)
	 */
	public function fstat()
	{
		return @fstat($this->handle);
	}

	/**
	 * ftell
	 *
	 * Returns the position of the file pointer
	 * @return integer $position
	 */
	public function ftell()
	{
		return @ftell($this->handle);
	}

	/**
	 * fpassthru
	 *
	 * Reads to EOF on the given file pointer from the current position and writes the results to the output buffer
	 * @return integer $bytes = number of bytes passed thru to the output buffer, false on error
	 */
	public function fpassthru()
	{
		return @fpassthru($this->handle);
	}

	/**
	 * eof
	 *
	 * Return EOF status for the file
	 * @return boolean $eof = true if the file is at (or beyond) eof or an error occurred, otherwise false
	 */
	public function eof()
	{
		return @feof($this->handle);
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
		return $this->flags;
	}

	/**
	 * setFlags
	 *
	 * Set current flag settings
	 * @param integer $flags
	 */
	public function setFlags($flags)
	{
		$this->flags = $flags;
	}

	/**
	 * getCsvControl
	 *
	 * get the csv control flags
	 * @return array $csvControl, index 0 = delimiter, index 1 = enclosure
	 */
	public function getCsvControl()
	{
		return array($this->csvControl['delimiter'], $this->csvControl['enclosure']);
	}

	/**
	 * setCsvControl
	 *
	 * set the csv control flags
	 * @param string $delimiter = (optional) delimiter, default = ','
	 * @param string $enclosure = (optional) enclosure, default = '"'
	 * @param string $escape    = (optional) escape, default = "\\"
	 */
	public function setCsvControl($delimiter=',', $enclosure='"', $escape="\\")
	{
		$this->csvControl['delimiter'] = $delimiter;
		$this->csvControl['enclosure'] = $enclosure;
		$this->csvControl['escape']    = $escape;
	}

	/**
	 * getMaxLineLen
	 *
	 * Get maximum line length
	 * @return integer $length
	 */
	public function getMaxLineLen()
	{
		return $this->maxLineLen;
	}

	/**
	 * setMaxLineLen
	 *
	 * Set the maximum line length, 0 to be unlimited
	 * @param integer $length
	 */
	public function setMaxLineLen($length)
	{
		if ($length < 0)
		{
			throw new Exception(Error::code('MaxLineLenLessThanZero'));
		}

		$this->maxLineLen = $length;
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
		if ((! $this->currentLine) && (! $this->currentArray))
		{
			$this->fgets();
		}

		if ($this->flags & self::READ_CSV)
		{
			if (! $this->currentArray)
			{
				$this->currentArray = $this->csvToArray();
			}

			return $this->currentArray;
		}

		return $this->currentLine;
	}

	/**
	 * key
	 *
	 * retrieve the current file line number
	 * @return integer $lineNumber
	 */
	public function key()
	{
		return $this->currentLineNum;
	}

	/**
	 * next
	 *
	 * move line number to the next line, get the line if READ_AHEAD flag is set
	 */
	public function next()
	{
		$this->freeLine();

		if ($this->flags & self::READ_AHEAD)
		{
			$this->fgets();
		}

		$this->currentLineNum++;
	}

	/**
	 * rewind
	 *
	 * rewind to the first line of the file, get the line if READ_AHEAD flag is set
	 */
	public function rewind()
	{
		if (! @rewind($this->handle))
		{
			throw new Exception(Error::code('FileRewindError'));
		}

		$this->freeLine();

		$this->currentLineNum = 0;
		if ($this->flags & self::READ_AHEAD)
		{
			$this->fgets();
		}
	}

	/**
	 * valid
	 *
	 * returns true if file is not at eof, otherwise returns false
	 * @return boolean $valid = false if file is at eof, otherwise true
	 */
	public function valid()
	{
		if ($this->flags & self::READ_AHEAD)
		{
			return ($this->currentLine || $this->currentArray) ? true : false;
		}

		return ! $this->eof();
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
		$this->rewind();		// in case someone else has it open output and the content has changed

		while($this->valid() && ($this->key() < $linePos))
		{
			$this->current();
			$this->next();
		}
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
		return false;
	}

	/**
	 * getChildren
	 *
	 * Returns null, because has no children
	 * @return void
	 */
	public function getChildren()
	{
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
	 * __toString
	 *
	 * Return the current record as a string (alias of current())
	 * @return string $buffer
	 */
	public function __toString()
	{
		return $this->current();
	}

	/** *******************************************************************
	 *
	 *		Utility Methods
	 *
	 ********************************************************************** */

	/**
	 * csvToArray
	 *
	 * Convert csv encoded buffer to an indexed array
	 * @return array $csv
	 */
	private function csvToArray()
	{
		return str_getcsv($this->currentLine, $this->csvControl['delimiter'], $this->csvControl['enclosure'], $this->csvControl['escape']);
	}

	/**
	 * freeLine
	 *
	 * Free the current line
	 */
	private function freeLine()
	{
		$this->currentLine = null;
		$this->currentLineLen = 0;
		$this->currentArray = null;
	}

}
