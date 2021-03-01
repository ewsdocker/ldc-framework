<?php
namespace Library\Compress;
use Library\Error;

/*
 * 		Compress\AdapterZlibBase is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * AdapterZlibBase.
 *
 * Compress AdapterZlibBase class to provide object oriented interface to the Zlib compression extensions
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Compress
 */
class AdapterZlibBase implements iAdapter
{
	/**
	 * handle
	 *
	 * Zlib handle for functions which require one
	 * @var resource $handle
	 */
	protected		$handle;

	/**
	 * fileName
	 *
	 * Name of the file to access
	 * @var string $fileName
	 */
	protected		$fileName;

	/**
	 * mode
	 *
	 * File access mode
	 * @var string $mode
	 */
	protected		$mode;

	/**
	 * useIncludePath
	 *
	 * Flag set to search include path when looking for a file
	 * @var boolean $useIncludePath
	 */
	protected		$useIncludePath;

	/**
	 * lines
	 *
	 * Array of lines from gzfile operation
	 * @var array[string] $lines
	 */
	protected		$lines;

	/**
	 * bytes
	 *
	 * Number of bytes read from functions that support it
	 * @var integer $bytes
	 */
	protected		$bytes;

	/**
	 * buffer
	 *
	 * The uncompressed i/o buffer
	 * @var string $buffer
	 */
	protected		$buffer;

	/**
	 * compressed
	 *
	 * The compressed i/o buffer
	 * @var string $compressed
	 */
	protected		$compressed;

	/**
	 * compressionLevel
	 *
	 * Compression level
	 * @var integer $compressionLevel
	 */
	protected		$compressionLevel;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string   $fileName       = (optional) name of the file associated with this class instance
	 * @param string   $mode           = (optional) file open mode (default = 'rb')
	 * @param boolean  $useIncludePath = (optional) true = search include path for file, false = don't
	 */
	public function __construct($fileName=null, $mode='rb', $useIncludePath=false)
	{
		$this->handle = null;

		$this->lines = null;
		$this->buffer = null;
		$this->compressed = null;

		$this->compressionLevel = -1;

		$this->fileName($fileName);
		$this->mode($mode);
		$this->useIncludePath($useIncludePath);

		if ($this->fileName)
		{
			$this->open();
		}
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 * @return null
	 */
	public function __destruct()
	{
		if ($this->handle)
		{
			try
			{
				$this->fclose();
			}
			catch(Exception $exception)
			{
			}

			$this->handle = null;
		}
	}

	/**
	 * open
	 *
	 * open a zlib file
	 * @param string $file = name of the file to open
	 * @param string $mode = file open mode - 3 bytes: 1st = access type {r, w, a}, 2nd = text-mode, either text or binary {t, b}, 3rd = compression level
	 * @param boolean $use_includes = (optional) true = search include directories also
	 * @throws \Library\Compress\Exception
	 */
	public function open($fileName=null, $mode=null, $useIncludePath=false)
	{
		if ($this->handle)
		{
			throw new Exception(Error::code('ResourceAlreadyAssigned'));
		}

		if ((!$fileName = $this->fileName($fileName)) || (! $mode = $this->mode($mode)))
		{
			throw new Exception(Error::code('MissingRequiredProperties'));
		}

		if (! $this->handle = @\gzopen($fileName, $mode, $this->useIncludePath($useIncludePath)))
		{
			throw new Exception(Error::code('FileOpenError'));
		}
	}

	/**
	 * fgetc
	 *
	 * get the next character from the current file
	 * @return string $char
	 * @throws \Library\Compress\Exception
	 */
	public function fgetc()
	{
		$this->validHandle();
		if (! $this->buffer = @\gzgetc($this->handle))
		{
			throw new Exception(Error::code('FileEOF'));
		}

		return $this->buffer;
	}

	/**
	 * fgets
	 *
	 * get the next length of uncompressed characters from the current file
	 * @param integer $length = length of uncompressed characters
	 * @return string $buffer
	 * @throws \Library\Compress\Exception
	 */
	public function fgets($length)
	{
		$this->validHandle();
		if (! $this->buffer = @\gzgets($this->handle, $length))
		{
			throw new Exception(Error::code('FileEOF'));
		}

		return $this->buffer;
	}

	/**
	 * fgetss
	 *
	 * get the next length of uncompressed characters from the current file and strip HTML/PHP tags
	 * @param integer $length = length of uncompressed characters
	 * @param string $allowableTags = (optional) list of allowable tags
	 * @return string $buffer
	 * @throws \Library\Compress\Exception
	 */
	public function fgetss($length, $allowableTags=null)
	{
		$this->validHandle();
		if (! $this->buffer = @\gzgetss($this->handle, $length, $allowableTags))
		{
			throw new Exception(Error::code('FileEOF'));
		}

		return $this->buffer;
	}

	/**
	 * fread
	 *
	 * Reads up to length uncompressed bytes from a file
	 * @param integer $length = length of bytes to read from the file
	 * @return string $buffer
	 * @throws \Library\Compress\Exception
	 */
	public function fread($length)
	{
		$this->validHandle();

		$this->buffer = @\gzread($this->handle, $length);
		return $this->buffer;
	}

	/**
	 * fwrite
	 *
	 * Write length bytes (or all) of the buffer to the current file
	 * @param string $buffer = (optional) buffer to write, null = use current buffer
	 * @param integer $length = (optional) buffer bytes to write, buffer length bytes if null
	 * @return integer $bytes = bytes written
	 * @throws \Library\Compress\Exception
	 */
	public function fwrite($buffer=null, $length=null)
	{
		$this->validHandle();
		if (! $buffer = $this->buffer($buffer))
		{
			throw new Exception(Error::code('EmptyBuffer'));
		}

		if ($length === null)
		{
			$length = strlen($buffer);
		}

		$this->bytes = @\gzwrite($this->handle, $buffer, $length);
		return $this->bytes;
	}

	/**
	 * fpassthru
	 *
	 * Send remainder of uncompressed file to the output device
	 * @return integer $bytes = number of uncompressed bytes processed
	 * @throws \Library\Compress\Exception
	 */
	public function fpassthru()
	{
		$this->validHandle();
		if (! $this->bytes = @\gzpassthru($this->handle))
		{
			throw new Exception(Error::code('FileFlushFailed'));
		}

		return $this->bytes;
	}

	/**
	 * rewind
	 *
	 * reset the file pointer to the start of the file
	 * @throws \Library\Compress\Exception
	 */
	public function rewind()
	{
		$this->validHandle();
		if (! \gzrewind($this->handle))
		{
			throw new Exception(Error::code('FileRewindError'));
		}
	}

	/**
	 * fseek
	 *
	 * Seek to the given offset, or, if whence=SEEK_CUR, relative to current position
	 * @param integer $offset = distance to seek
	 * @param integer $whence = (optional) seek type, SEEK_SET (absolute) or SEEK_CUR (relative)
	 * @throws \Library\Compress\Exception
	 */
	public function fseek($offset, $whence=SEEK_SET)
	{
		$this->validHandle();
		if (@\gzseek($this->handle, $offset, $whence) != 0)
		{
			throw new Exception(Error::code('FileSeekError'));
		}
	}

	/**
	 * ftell
	 *
	 * Return the current pointer into the UNCOMPRESSED file
	 * @return integer $offset
	 * @throws \Library\Compress\Exception
	 */
	public function ftell()
	{
		$this->validHandle();
		$position = @\gztell($this->handle);
		if ($position === false)
		{
			throw new Exception(Error::code('FileSeekError'));
		}

		return $position;
	}

	/**
	 * eof
	 *
	 * Test the handle for end of file
	 * @return boolean $eof = end of file status
	 * @throws \Library\Compress\Exception
	 */
	public function eof()
	{
		$this->validHandle();
		return \gzeof($this->handle);
	}

	/**
	 * fclose
	 *
	 * close the zlib file
	 * @throws \Library\Compress\Exception
	 */
	public function fclose()
	{
		$this->validHandle();
		if (! @\gzclose($this->handle))
		{
			$this->handle = null;
			throw new Exception(Error::code('FileCloseError'));
		}

		$this->handle = null;
	}

 	/**
	 * validHandle
	 *
	 * returns if the handle is valid, throws exception if not valid
	 * @throws \Library\Compress\Exception
	 */
	public function validHandle()
	{
		if (! $this->handle)
		{
			throw new Exception(Error::code('NotInitialized'));
		}
	}

	/**
	 * handle
	 *
	 * get the current file handle
	 * @return resource $handle
	 */
	public function handle()
	{
		return $this->handle;
	}

	/**
	 * readToArray
	 *
	 * Open and read a complete gz-file into an array of lines.
	 * @param string $fileName = (optional) file name to open
	 * @param boolean $useIncludePath = (optional) include path flag
	 * @return array $lines
	 * @throws \Library\Compress\Exception
	 */
	public function readToArray($fileName=null, $useIncludePath=false)
	{
		if (! $fileName = $this->fileName($fileName))
		{
			throw new Exception(Error::code('MissingRequiredProperties'));
		}

		$this->lines = @\gzfile($fileName, $this->useIncludePath($useIncludePath));

		return $this->lines;
	}

	/**
	 * readFileAndOutput
	 *
	 * Reads a file, decompresses it and writes it to standard output.
	 * @param string $fileName = (optional) file name to open
	 * @param boolean $useIncludePath = (optional) include path flag
	 * @return integer $bytes = number of bytes read.
	 * @throws \Library\Compress\Exception
	 */
	public function readFileAndOutput($fileName=null, $useIncludePath=false)
	{
		if (! $fileName = $this->fileName($fileName))
		{
			throw new Exception(Error::code('MissingRequiredProperties'));
		}

		$this->bytes = @\readgzfile($fileName, $this->useIncludePath($useIncludePath));

		return $this->bytes;
	}

	/**
	 * compress
	 *
	 * Uses ZLIB compression to compress the input
	 * @param string $buffer = (optional) buffer to compress, use current buffer if null
	 * @param integer level = (optional) compression factor (0 to 9), -1 = use default
	 * @return string $compressed = compressed $buffer in the ZLIB data format
	 * @throws \Library\Compress\Exception
	 */
	public function compress($buffer=null, $level=null)
	{
		if (! $buffer = $this->buffer($buffer))
		{
			throw new Exception(Error::code('EmptyBuffer'));
		}

		$level = $this->compressionLevel($level);

		if ($level === null)
		{
			$level = $this->compressionLevel(-1);
		}

		if (! $this->compressed = @\gzcompress($buffer, $level))
		{
			throw new Exception(Error::code('FileCompressError'));
		}

		return $this->compressed;
	}

	/**
	 * uncompress
	 *
	 * Uses ZLIB uncompress to extract the uncompressed buffer
	 * @param string $compressed = (optional) buffer to uncompress, use current compressed buffer if null
	 * @param integer length = (optional) length to decode
	 * @return string $buffer = uncompressed input
	 * @throws \Library\Compress\Exception
	 */
	public function uncompress($compressed=null, $length=0)
	{
		if (! $compressed = $this->compressed($compressed))
		{
			throw new Exception(Error::code('EmptyBuffer'));
		}

		if (! $this->buffer = @\gzuncompress($compressed, $length))
		{
			throw new Exception(Error::code('FileDecompressError'));
		}

		return $this->buffer;
	}

	/**
	 * encode
	 *
	 * Use GZIP compression to encode the supplied buffer
	 * @param string $buffer = (optional) buffer to compress, use current buffer if null
	 * @param integer level = (optional) compression factor (0 to 9), -1 = use default
	 * @param integer mode = encoding mode (FORCE_GZIP or FORCE_DEFLATE)
	 * @return string $compressed = GZIP encoded buffer
	 * @throws \Library\Compress\Exception
	 */
	public function encode($buffer=null, $level=null, $mode=FORCE_GZIP)
	{
		if (! $buffer = $this->buffer($buffer))
		{
			throw new Exception(Error::codes('EmptyBuffer'));
		}

		$level = $this->compressionLevel($level);

		if ($level === null)
		{
			$level = $this->compressionLevel(-1);
		}

		if (! $this->compressed = @\gzencode($buffer, $level, $mode))
		{
			throw new Exception(Error::code('EncodeError'));
		}

		return $this->compressed;
	}

	/**
	 * decode
	 *
	 * Use GZIP compression to decode the supplied buffer
	 * @param string $compressed = (optional) buffer to compress, use current compressed buffer if null
	 * @param integer length = (optional) length to decode
	 * @return string $buffer = decoded buffer
	 * @throws \Library\Compress\Exception
	 */
	public function decode($compressed=null, $length=null)
	{
		if (! $compressed = $this->compressed($compressed))
		{
			throw new Exception(Error::code('EmptyBuffer'));
		}

		if (! $this->buffer = \gzdecode($compressed, $length))
		{
			throw new Exception(Error::code('DecodeError'));
		}

		return $this->buffer;
	}

	/**
	 * deflate
	 *
	 * Use DEFLATE compression to deflate the supplied buffer
	 * @param string $buffer = (optional) buffer to deflate, use current buffer if null
	 * @param integer level = (optional) compression factor (0 to 9), -1 = use default
	 * @return string $compressed = DEFLATE encoded buffer
	 * @throws \Library\Compress\Exception
	 */
	public function deflate($buffer=null, $level=null)
	{
		if (! $buffer = $this->buffer($buffer))
		{
			throw new Exception(Error::code('EmptyBuffer'));
		}

		$level = $this->compressionLevel($level);

		if ($level === null)
		{
			$level = $this->compressionLevel(-1);
		}

		if (! $this->compressed = @\gzdeflate($buffer, $level))
		{
			throw new Exception(Error::code('DeflateError'));
		}

		return $this->compressed;
	}

	/**
	 * inflate
	 *
	 * Use INFLATE compression to decode the supplied buffer
	 * @param string $compressed = (optional) buffer to compress, use current compressed buffer if null
	 * @param integer length = (optional) length to decode
	 * @return string $buffer = decoded buffer
	 * @throws \Library\Compress\Exception
	 */
	public function inflate($compressed=null, $length=null)
	{
		if (! $compressed = $this->compressed($compressed))
		{
			throw new Exception(Error::code('EmptyBuffer'));
		}

		if (! $this->buffer = @\gzinflate($compressed, $length))
		{
			throw new Exception(Error::code('InflateError'));
		}

		return $this->buffer;
	}

	/**
	 * compressionLevel
	 *
	 * Compress factor
	 * @param integer $level = (optional) compression level, null to query
	 * @return integer $level
	 */
	public function compressionLevel($level=null)
	{
		if ($level !== null)
		{
			$this->compressionLevel = $level;
		}

		return $this->compressionLevel;
	}

	/**
	 * buffer
	 *
	 * get/set the global i/o buffer
	 * @param string $buffer = (optional) buffer contents to set, null to query
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
	 * compressed
	 *
	 * get/set the global compressed i/o buffer
	 * @param string $compressed = (optional) compressed contents to set, null to query
	 * @return string $compressed
	 */
	public function compressed($compressed=null)
	{
		if ($compressed !== null)
		{
			$this->compressed = $compressed;
		}

		return $this->compressed;
	}

	/**
	 * lines
	 *
	 * get an array containing the complete file, one line per element
	 * @return array $lines
	 */
	public function lines()
	{
		return $this->lines;
	}

	/**
	 * fileName
	 *
	 * set/get the file name
	 * @param string $fileName = (optional) fileName, null to query
	 * @return string $fileName
	 */
	public function fileName($fileName=null)
	{
		if ($fileName !== null)
		{
			$this->fileName = $fileName;
		}

		return $this->fileName;
	}

	/**
	 * mode
	 *
	 * set/get the mode setting
	 * @param string $mode = (optional) mode setting, null to query
	 * @return string $mode
	 */
	public function mode($mode=null)
	{
		if ($mode !== null)
		{
			$this->mode = $mode;
		}

		return $this->mode;
	}

	/**
	 * useIncludePath
	 *
	 * set/get the use include path flag used when searching for a file
	 * @param boolean $useIncludePath = (optional) use include path flag, null to query
	 * @return boolean $useIncludePath
	 */
	public function useIncludePath($useIncludePath=null)
	{
		if ($useIncludePath !== null)
		{
			$this->useIncludePath = $useIncludePath;
		}

		return $this->useIncludePath;
	}

}
