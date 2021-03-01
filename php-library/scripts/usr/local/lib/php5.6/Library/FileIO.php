<?php
namespace Library;

/*
 *		FileIO is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 				or from http://opensource.org/licenses/academic.php
*/
/**
 * FileIO.
 *
 * The FileIO class provides a factory-based interface to the FileIO classes.
 * @author Jay Wheeler
 * @version 1.1
 * @copyright © 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage FileIO
 */
class FileIO extends FileInfo implements \RecursiveIterator, \SeekableIterator
{
    /**
     * __construct
     *
     * Class constructor
     * @param string   $adapterName    = adapter class name
	 * @param string   $fileName       = name of the file associated with this class instance
	 * @param string   $mode           = (optional) file open mode (default = 'r')
	 * @param boolean  $useIncludePath = (optional) true = search include path for file, false = don't
	 * @param resource $context        = (optional) stream context
     * @throws FileIO\Exception
     */
    public function __construct($adapterName, $fileName, 
    										  $mode='r', 
    										  $useIncludePath=false, 
    										  $context=null)
	{
		if ($context !== null)
		{
			$this->fileIO = FileIO\Factory::instantiateClass($adapterName, $fileName, 
																		   $mode, 
																		   $useIncludePath, 
																		   $context);
		}
		else
		{
			$this->fileIO = FileIO\Factory::instantiateClass($adapterName, $fileName,
																		   $mode,
																		   $useIncludePath);
		}
	}

    /**
     * __destruct
     *
     * class destructor
     * @return null
     */
    public function __destruct()
    {
    	if ($this->fileIO)
    	{
    		unset($this->fileIO);
    	}
    }

	/**
	 * fgetc
	 *
	 * get the next character from the file
	 * @return string $character
	 * @throws \Library\FileIO\Exception
	 */
	public function fgetc()
	{
		return $this->fileIO->fgetc();
	}

	/**
	 * fgets
	 *
	 * get the next line from the file
	 * @return string $buffer
	 */
	public function fgets()
	{
		return $this->fileIO->fgets();
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
		return $this->fileIO->fgetss($allowable);
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
		return $this->fileIO->fgetcsv();
	}

	/**
	 * fscanf
	 *
	 * get the next line from the file
	 * @param string $format = The specified format
	 * @param mixed $variables = (optional) list of variables passed by reference to receive the parsed values
	 * @return array|integer $parsed = array of parsed fields if $variables is not provided, else the number of variables assigned
	 * @throws \Library\FileIO\Exception
	 */
	public function fscanf($format)
	{
		if (func_num_args() == 1)
		{
			return $this->fileIO->fscanf($format);
		}

		$arguments = func_get_args();
		array_shift($arguments);

		$variables = array();
		$index = 0;
		while($index < count($arguments))
		{
			$variables[$index] =& $arguments[$index];
			$index++;
		}

		array_unshift($variables, $format);

		$method = new \ReflectionMethod($this->fileIO, 'fscanf');

		return $method->invokeArgs($this->fileIO, $arguments);
	}

	/**
	 * fputcsv
	 *
	 * Format line as CSV and write to file pointer
	 * @param array $fields = array of field name/value pairs to store
	 * @param string $delimiter = (optional) csv delimiter, default = ','
	 * @param string $enclosure = (optional) csv enclosure, default = '"'
	 * @return integer|boolean $result = the length of the written string or FALSE on failure.
	 */
	public function fputcsv($fields, $delimiter=',', $enclosure='"')
	{
		return $this->fileIO->fputcsv($fields, $delimiter, $enclosure);
	}

	/**
	 * fwrite
	 *
	 * get the next line from the file
	 * @param string $buffer = buffer to write
	 * @param integer $length = (optional) length to write, null to write the complete buffer
	 * @return integer $bytes|null = bytes written, null on error
	 */
	public function fwrite($buffer, $length=null)
	{
		if ($length === null)
		{
			return $this->fileIO->fwrite($buffer);
		}

		return $this->fileIO->fwrite($buffer, $length);
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
		return $this->fileIO->fseek($position, $whence);
	}

	/**
	 * fflush
	 *
	 * Flush all buffered output to the output buffer
	 * @return boolean $result = true successful, otherwise false
	 */
	public function fflush()
	{
		return $this->fileIO->fflush();
	}

	/**
	 * ftruncate
	 *
	 * Truncate the file to the indicated size
	 * @return boolean $result = true if successful, false if not
	 */
	public function ftruncate($size)
	{
		return $this->fileIO->ftruncate($size);
	}

	/**
	 * flock
	 *
	 * Attempt to lock the file
	 * @param integer $operation = lock operation (LOCK_SH, LOCK_EX, LOCK_UN, or LOCK_NB)
	 * @param integer $wouldBlock = (optional) true if  the lock would block 
	 * 												(EWOULDBLOCK errno condition), default = false
	 * @return boolean $result = true successful, otherwise false
	 */
	public function flock($operation, $wouldBlock=false)
	{
		return $this->fileIO->flock($operation, $wouldBlock);
	}

	/**
	 * fstat
	 *
	 * Gets information about the file
	 * @return array $stat = array with the statistics of the file
	 */
	public function fstat()
	{
		return $this->fileIO->fstat();
	}

	/**
	 * ftell
	 *
	 * Returns the position of the file pointer
	 * @return integer $position
	 */
	public function ftell()
	{
		return $this->fileIO->ftell();
	}

	/**
	 * fpassthru
	 *
	 * Reads to EOF on the given file pointer from the current position and writes the results to the output buffer
	 * @return integer $bytes = number of bytes passed thru to the output buffer, false on error
	 */
	public function fpassthru()
	{
		return $this->fileIO->fpassthru();
	}

	/**
	 * eof
	 *
	 * Return EOF status for the file
	 * @return boolean $eof = true if the file is at (or beyond) eof 
	 * 							or an error occurred, otherwise false
	 */
	public function eof()
	{
		return $this->fileIO->eof();
	}

	/**
	 * getFlags
	 *
	 * Get current flag settings
	 * @return integer $flags
	 */
	public function getFlags()
	{
		return $this->fileIO->getFlags();
	}

	/**
	 * setFlags
	 *
	 * Set current flag settings
	 * @param integer $flags
	 */
	public function setFlags($flags)
	{
		$this->fileIO->setFlags($flags);
	}

	/**
	 * getCsvControl
	 *
	 * get the csv control flags
	 * @return array $csvControl, index 0 = delimiter, index 1 = enclosure
	 */
	public function getCsvControl()
	{
		return $this->fileIO->getCsvControl();
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
		return $this->fileIO->setCsvControl($delimiter, $enclosure, $escape);
	}

	/**
	 * getCurrentLine
	 *
	 * get the next line from the file
	 * @return string $buffer
	 */
	public function getCurrentLine()
	{
		return $this->fileIO->getCurrentLine();
	}

	/**
	 * getMaxLineLen
	 *
	 * Get maximum line length
	 * @return integer $length
	 */
	public function getMaxLineLen()
	{
		return $this->fileIO->getMaxLineLen();
	}

	/**
	 * setMaxLineLen
	 *
	 * Set the maximum line length, 0 to be unlimited
	 * @param integer $length
	 */
	public function setMaxLineLen($length)
	{
		return $this->fileIO->setMaxLineLen($length);
	}

	/**
	 * current
	 *
	 *  Retrieve current line of file
	 *  @return string|array $buffer
	 */
	public function current()
	{
		return $this->fileIO->current();
	}

	/**
	 * key
	 *
	 * retrieve the current file line number
	 * @return integer $lineNumber
	 */
	public function key()
	{
		return $this->fileIO->key();
	}

	/**
	 * next
	 *
	 * retrieve the next line
	 */
	public function next()
	{
		return $this->fileIO->next();
	}

	/**
	 * rewind
	 *
	 * rewind to the first line of the file
	 */
	public function rewind()
	{
		return $this->fileIO->rewind();
	}

	/**
	 * valid
	 *
	 * returns true if file is not at eof, otherwise returns false
	 * @return boolean $valid = false if file is at eof, otherwise true
	 */
	public function valid()
	{
		return $this->fileIO->valid();
	}

	/**
	 * seek
	 *
	 * Seek to the requested line
	 * @param integer $linePos = line to seek to
	 * @throws \Library\FileIO\Exception
	 */
	public function seek($linePos)
	{
		return $this->fileIO->seek($linePos);
	}

	/**
	 * hasChildren
	 *
	 * Returns true if has children, false if not
	 * @return boolean $has = true if has children, false if not
	 */
	public function hasChildren()
	{
		return $this->fileIO->hasChildren();
	}

	/**
	 * getChildren
	 *
	 * Returns null, because has no children
	 * @return void
	 */
	public function getChildren()
	{
		return $this->fileIO->getChildren();
	}

	/**
	 * __toString
	 *
	 * Return the current record as a string (alias of current())
	 * @return string $buffer
	 */
	public function __toString()
	{
		return $this->fileIO->__toString();
	}

}
