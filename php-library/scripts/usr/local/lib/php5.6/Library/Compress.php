<?php
namespace Library;

/*
 * 		Library\Compress is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * Library\Compress
 *
 * Static interface wrapper for the Library\Compress\<adapter> class
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Compress
 */
class Compress
{
	/**
	 * me
	 *
	 * static variable containing a pointer to this class
	 * @var object $me
	 */
	protected		static	$me = null;

	/**
	 * adapter
	 *
	 * Compress adapter class
	 * @var object $adapter
	 */
	protected		$adapter;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string   $adapterName    = compression adapter name
	 * @param string   $fileName       = name of the file associated with this class instance
	 * @param string   $mode           = (optional) file open mode (default = 'rb')
	 * @param boolean  $useIncludePath = (optional) true = search include path for file, false = don't
	 */
	private function __construct($adapter, $fileName, $mode='rb', $useIncludePath=false)
	{
		$this->adapter = Factory::instantiateClass($adapter, $fileName, $mode, $useIncludePath);
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 */
	public function __destruct()
	{
		
	}

	/**
	 * instance
	 *
	 * get the class instance, create one if it doesn't exist
	 * @return object $instance
	 */
	public static function instance($adapter='zlib')
	{
		if (! self::$me)
		{
			self::$me = new Compress($adapter);
		}

		return self::$me;
	}

	/**
	 * adapter
	 *
	 * STATIC
	 * 
	 * return the compress adapter
	 * @return object $adapter
	 */
	public static function adapter()
	{
		if (! self::$me)
		{
			self::instance();
		}

		return self::$me->adapter;
	}

	/**
	 * open
	 *
	 * STATIC
	 * 
	 * open a compress file
	 * @param string $file = name of the file to open
	 * @param string $mode = file open mode - 3 bytes: 1st = access type {r, w, a}, 2nd = text-mode, either text or binary {t, b}, 3rd = compression level
	 * @param boolean $use_includes = (optional) true = search include directories also
	 * @throws \Library\Compress\Exception
	 */
	public static function open($fileName=null, $mode=null, $useIncludePath=false)
	{
		self::adapter()->open($fileName, $mode, $useIncludePath);
	}

	/**
	 * fgetc
	 *
	 * STATIC
	 * 
	 * get the next character from the current file
	 * @return string $char
	 * @throws \Library\Compress\Exception
	 */
	public static function fgetc()
	{
		return self::adapter()->fgetc();
	}

	/**
	 * fgets
	 *
	 * STATIC
	 * 
	 * get the next length of uncompressed characters from the current file
	 * @param integer $length = length of uncompressed characters
	 * @return string $buffer
	 * @throws \Library\Compress\Exception
	 */
	public static function fgets($length)
	{
		return self::adapter()->fgets($length);
	}

	/**
	 * fgetss
	 *
	 * STATIC
	 * 
	 * get the next length of uncompressed characters from the current file and strip HTML/PHP tags
	 * @param integer $length = length of uncompressed characters
	 * @param string $allowableTags = (optional) list of allowable tags
	 * @return string $buffer
	 * @throws \Library\Compress\Exception
	 */
	public static function fgetss($length, $allowableTags=null)
	{
		return self::adapter()->fgetss($length, $allowableTags);
	}

	/**
	 * fread
	 *
	 * STATIC
	 * 
	 * Reads up to length uncompressed bytes from a file
	 * @param integer $length = length of bytes to read from the file
	 * @return string $buffer
	 * @throws \Library\Compress\Exception
	 */
	public static function fread($length)
	{
		return self::adapter()->fread($length);
	}

	/**
	 * fwrite
	 *
	 * STATIC
	 * 
	 * Write length bytes (or all) of the buffer to the current file
	 * @param string $buffer = (optional) buffer to write, null = use current buffer
	 * @param integer $length = (optional) buffer bytes to write, buffer length bytes if null
	 * @return integer $bytes = bytes written
	 * @throws \Library\Compress\Exception
	 */
	public static function fwrite($buffer=null, $length=null)
	{
		return self::adapter()->fwrite($buffer, $length);
	}

	/**
	 * fpassthru
	 *
	 * STATIC
	 * 
	 * Send remainder of uncompressed file to the output device
	 * @return integer $bytes = number of uncompressed bytes processed
	 * @throws \Library\Compress\Exception
	 */
	public static function fpassthru()
	{
		return self::adapter()->fpassthru();
	}

	/**
	 * rewind
	 *
	 * STATIC
	 * 
	 * reset the file pointer to the start of the file
	 * @throws \Library\Compress\Exception
	 */
	public static function rewind()
	{
		return self::adapter()->rewind();
	}

	/**
	 * fseek
	 *
	 * STATIC
	 * 
	 * Seek to the given offset, or, if whence=SEEK_CUR, relative to current position
	 * @param integer $offset = distance to seek
	 * @param integer $whence = (optional) seek type, SEEK_SET (absolute) or SEEK_CUR (relative)
	 * @throws \Library\Compress\Exception
	 */
	public static function fseek($offset, $whence=SEEK_SET)
	{
		return self::adapter()->fseek($offset, $whence);
	}

	/**
	 * ftell
	 *
	 * STATIC
	 * 
	 * Return the current pointer into the UNCOMPRESSED file
	 * @return integer $offset
	 * @throws \Library\Compress\Exception
	 */
	public static function ftell()
	{
		return self::adapter()->ftell();
	}

	/**
	 * eof
	 *
	 * STATIC
	 * 
	 * Test the handle for end of file
	 * @return boolean $eof = end of file status
	 * @throws \Library\Compress\Exception
	 */
	public static function eof()
	{
		return self::adapter()->eof();
	}

	/**
	 * fclose
	 *
	 * STATIC
	 * 
	 * close the compress file
	 * @throws \Library\Compress\Exception
	 */
	public static function fclose()
	{
		return self::adapter()->fclose();
	}

	/**
	 * readToArray
	 *
	 * STATIC
	 * 
	 * Open and read a complete gz-file into an array of lines.
	 * @param string $fileName = (optional) file name to open
	 * @param boolean $useIncludePath = (optional) include path flag
	 * @return array $lines
	 * @throws \Library\Compress\Exception
	 */
	public static function readToArray($fileName=null, $useIncludePath=false)
	{
		return self::adapter()->readToArray($fileName, $useIncludePath);
	}

	/**
	 * readFileAndOutput
	 *
	 * STATIC
	 * 
	 * Reads a file, decompresses it and writes it to standard output.
	 * @param string $fileName = (optional) file name to open
	 * @param boolean $useIncludePath = (optional) include path flag
	 * @return integer $bytes = number of bytes read.
	 * @throws \Library\Compress\Exception
	 */
	public static function readFileAndOutput($fileName=null, $useIncludePath=false)
	{
		return self::adapter()->readFileAndOutput($fileName, $useIncludePath);
	}

	/**
	 * compress
	 *
	 * STATIC
	 * 
	 * Uses adapter compression to convert the buffer into a compressed buffer
	 * @param string $buffer = (optional) buffer to compress, use current buffer if null
	 * @param integer level = (optional) compression factor (0 to 9), -1 = use default
	 * @return string $compressed
	 * @throws \Library\Compress\Exception
	 */
	public static function compress($buffer=null, $level=null)
	{
		return self::adapter()->compress($buffer, $level);
	}

	/**
	 * uncompress
	 *
	 * STATIC
	 * 
	 * Uses adapter compression to convert the compressed buffer into the uncompressed buffer
	 * @param string $compressed = (optional) buffer to uncompress, use current compressed buffer if null
	 * @param integer level = (optional) compression factor (0 to 9), -1 = use default
	 * @throws \Library\Compress\Exception
	 */
	public function uncompress($compressed=null, $length=0)
	{
		return self::adapter()->uncompress($compressed, $length);
	}

	/**
	 * encode
	 *
	 * STATIC
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
		return self::adapter()->encode($buffer, $level, $mode);
	}

	/**
	 * decode
	 *
	 * STATIC
	 * 
	 * Use GZIP compression to decode the supplied buffer
	 * @param string $compressed = (optional) buffer to compress, use current compressed buffer if null
	 * @param integer length = (optional) length to decode
	 * @return string $buffer = decoded buffer
	 * @throws \Library\Compress\Exception
	 */
	public function decode($compressed=null, $length=null)
	{
		return self::adapter()->decode($compressed, $length);
	}

	/**
	 * defalte
	 *
	 * STATIC
	 * 
	 * Use DEFLATE compression to deflate the supplied buffer
	 * @param string $buffer = (optional) buffer to deflate, use current buffer if null
	 * @param integer level = (optional) compression factor (0 to 9), -1 = use default
	 * @return string $compressed = DEFLATE encoded buffer
	 * @throws \Library\Compress\Exception
	 */
	public function deflate($buffer=null, $level=null)
	{
		return self::adapter()->deflate($buffer, $level);
	}

	/**
	 * inflate
	 *
	 * STATIC
	 * 
	 * Use GZIP compression to decode the supplied buffer
	 * @param string $compressed = (optional) buffer to compress, use current compressed buffer if null
	 * @param integer length = (optional) length to decode
	 * @return string $buffer = decoded buffer
	 * @throws \Library\Compress\Exception
	 */
	public function inflate($compressed=null, $length=null)
	{
		return self::adapter()->inflate($compressed, $length);
	}

	/**
	 * compressionLevel
	 *
	 * STATIC
	 * 
	 * Compression factor
	 * @param integer $level = (optional) compression level, null to query
	 * @return integer $level
	 */
	public function compressionLevel($level=null)
	{
		return self::adapter()->compressionLevel($level);
	}

	/**
	 * buffer
	 *
	 * STATIC
	 * 
	 * get/set the global i/o buffer
	 * @param string $buffer = (optional) buffer contents to set, null to query
	 * @return string $buffer
	 */
	public function buffer($buffer=null)
	{
		return self::adapter()->buffer($buffer);
	}

	/**
	 * compressed
	 *
	 * STATIC
	 * 
	 * get/set the global compressed i/o buffer
	 * @param string $compressed = (optional) compressed contents to set, null to query
	 * @return string $compressed
	 */
	public function compressed($compressed=null)
	{
		return self::adapter()->compressed($compressed);
	}

	/**
	 * lines
	 *
	 * STATIC
	 * 
	 * get an array conaining the complete file, one line per element
	 * @return array $lines
	 */
	public function lines()
	{
		return self::adapter()->lines();
	}

	/**
	 * fileName
	 *
	 * STATIC
	 * 
	 * set/get the file name
	 * @param string $fileName = (optional) fileName, null to query
	 * @return string $fileName
	 */
	public function fileName($fileName=null)
	{
		return self::adapter()->fileName($fileName);
	}

	/**
	 * mode
	 *
	 * STATIC
	 * 
	 * set/get the mode setting
	 * @param string $mode = (optional) mode setting, null to query
	 * @return string $mode
	 */
	public function mode($mode=null)
	{
		return self::adapter()->mode($mode);
	}

	/**
	 * useIncludePath
	 *
	 * STATIC
	 * 
	 * set/get the use include path flag used when searching for a file
	 * @param boolean $useIncludePath = (optional) use include path flag, null to query
	 * @return boolean $useIncludePath
	 */
	public function useIncludePath($useIncludePath=null)
	{
		return self::adapter()->useIncludePath($useIncludePath);
	}

}
