<?php
namespace Library\Compress;

/*
 * 		iAdapter is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * iAdapter.
 *
 * Compress Adapter interface.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Compress
 */
interface iAdapter
{
	/**
	 * open
	 *
	 * open a compressed file
	 * @param string $file = name of the file to open
	 * @param string $mode = file open mode - 3 bytes: 1st = access type {r, w, a}, 2nd = text-mode, either text or binary {t, b}, 3rd = compression level
	 * @param boolean $use_includes = (optional) true = search include directories also
	 * @throws \Library\Compress\Exception
	 */
	public function open($fileName=null, $mode=null, $useIncludePath=false);

	/**
	 * fgetc
	 *
	 * get the next character from the current file
	 * @return string $char
	 * @throws \Library\Compress\Exception
	 */
	public function fgetc();

	/**
	 * fgets
	 *
	 * get the next length of uncompressed characters from the current file
	 * @param integer $length = length of uncompressed characters
	 * @return string $buffer
	 * @throws \Library\Compress\Exception
	 */
	public function fgets($length);

	/**
	 * fgetss
	 *
	 * get the next length of uncompressed characters from the current file and strip HTML/PHP tags
	 * @param integer $length = length of uncompressed characters
	 * @param string $allowableTags = (optional) list of allowable tags
	 * @return string $buffer
	 * @throws \Library\Compress\Exception
	 */
	public function fgetss($length, $allowableTags=null);

	/**
	 * fread
	 *
	 * Reads up to length uncompressed bytes from a file
	 * @param integer $length = length of bytes to read from the file
	 * @return string $buffer
	 * @throws \Library\Compress\Exception
	 */
	public function fread($length);

	/**
	 * fwrite
	 *
	 * Write length bytes (or all) of the buffer to the current file
	 * @param string $buffer = (optional) buffer to write, null = use current buffer
	 * @param integer $length = (optional) buffer bytes to write, buffer length bytes if null
	 * @return integer $bytes = bytes written
	 * @throws \Library\Compress\Exception
	 */
	public function fwrite($buffer=null, $length=null);

	/**
	 * fpassthru
	 *
	 * Send remainder of uncompressed file to the output device
	 * @return integer $bytes = number of uncompressed bytes processed
	 * @throws \Library\Compress\Exception
	 */
	public function fpassthru();

	/**
	 * rewind
	 *
	 * reset the file pointer to the start of the file
	 * @throws \Library\Compress\Exception
	 */
	public function rewind();

	/**
	 * fseek
	 *
	 * Seek to the given offset, or, if whence=SEEK_CUR, relative to current position
	 * @param integer $offset = distance to seek
	 * @param integer $whence = (optional) seek type, SEEK_SET (absolute) or SEEK_CUR (relative)
	 * @throws \Library\Compress\Exception
	 */
	public function fseek($offset, $whence=SEEK_SET);

	/**
	 * ftell
	 *
	 * Return the current pointer into the UNCOMPRESSED file
	 * @return integer $offset
	 * @throws \Library\Compress\Exception
	 */
	public function ftell();

	/**
	 * eof
	 *
	 * Test the handle for end of file
	 * @return boolean $eof = end of file status
	 * @throws \Library\Compress\Exception
	 */
	public function eof();

	/**
	 * fclose
	 *
	 * close the file
	 * @throws \Library\Compress\Exception
	 */
	public function fclose();

 	/**
	 * validHandle
	 *
	 * returns if the handle is valid, throws exception if not valid
	 * @throws \Library\Compress\Exception
	 */
	public function validHandle();

	/**
	 * handle
	 *
	 * get the current file handle
	 * @return resource $handle
	 */
	public function handle();

	/**
	 * readToArray
	 *
	 * Open and read a complete gz-file into an array of lines.
	 * @param string $fileName = (optional) file name to open
	 * @param boolean $useIncludePath = (optional) include path flag
	 * @return array $lines
	 * @throws \Library\Compress\Exception
	 */
	public function readToArray($fileName=null, $useIncludePath=false);

	/**
	 * readFileAndOutput
	 *
	 * Reads a file, decompresses it and writes it to standard output.
	 * @param string $fileName = (optional) file name to open
	 * @param boolean $useIncludePath = (optional) include path flag
	 * @return integer $bytes = number of bytes read.
	 * @throws \Library\Compress\Exception
	 *
	public function readFileAndOutput($fileName=null, $useIncludePath=false);

	/**
	 * compress
	 *
	 * Uses ZLIB compression to convert the buffer into a compressed buffer
	 * @param string $buffer = (optional) buffer to compress, use current buffer if null
	 * @param integer level = (optional) compression factor (0 to 9), -1 = use default
	 * @return string $compressed
	 * @throws \Library\Compress\Exception
	 */
	public function compress($buffer=null, $level=null);

	/**
	 * uncompress
	 *
	 * Uses ZLIB compression to convert the compressed buffer into the uncompressed buffer
	 * @param string $compressed = (optional) buffer to uncompress, use current compressed buffer if null
	 * @param integer level = (optional) compression factor (0 to 9), -1 = use default
	 * @throws \Library\Compress\Exception
	 */
	public function uncompress($compressed=null, $length=0);

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
	public function encode($buffer=null, $level=null, $mode=FORCE_GZIP);

	/**
	 * decode
	 *
	 * Use GZIP compression to decode the supplied buffer
	 * @param string $compressed = (optional) buffer to compress, use current compressed buffer if null
	 * @param integer length = (optional) length to decode
	 * @return string $buffer = decoded buffer
	 * @throws \Library\Compress\Exception
	 */
	public function decode($compressed=null, $length=null);

	/**
	 * deflate
	 *
	 * Use DEFLATE compression to deflate the supplied buffer
	 * @param string $buffer = (optional) buffer to deflate, use current buffer if null
	 * @param integer level = (optional) compression factor (0 to 9), -1 = use default
	 * @return string $compressed = DEFLATE encoded buffer
	 * @throws \Library\Compress\Exception
	 */
	public function deflate($buffer=null, $level=null);

	/**
	 * inflate
	 *
	 * Use GZIP compression to decode the supplied buffer
	 * @param string $compressed = (optional) buffer to compress, use current compressed buffer if null
	 * @param integer length = (optional) length to decode
	 * @return string $buffer = decoded buffer
	 * @throws \Library\Compress\Exception
	 */
	public function inflate($compressed=null, $length=null);

	/**
	 * compressionLevel
	 *
	 * Compress factor
	 * @param integer $level = (optional) compression level, null to query
	 * @return integer $level
	 */
	public function compressionLevel($level=null);

	/**
	 * buffer
	 *
	 * get/set the global i/o buffer
	 * @param string $buffer = (optional) buffer contents to set, null to query
	 * @return string $buffer
	 */
	public function buffer($buffer=null);

	/**
	 * compressed
	 *
	 * get/set the global compressed i/o buffer
	 * @param string $compressed = (optional) compressed contents to set, null to query
	 * @return string $compressed
	 */
	public function compressed($compressed=null);

	/**
	 * lines
	 *
	 * get an array conaining the complete file, one line per element
	 * @return array $lines
	 */
	public function lines();

	/**
	 * fileName
	 *
	 * set/get the file name
	 * @param string $fileName = (optional) fileName, null to query
	 * @return string $fileName
	 */
	public function fileName($fileName=null);

	/**
	 * mode
	 *
	 * set/get the mode setting
	 * @param string $mode = (optional) mode setting, null to query
	 * @return string $mode
	 */
	public function mode($mode=null);

	/**
	 * useIncludePath
	 *
	 * set/get the use include path flag used when searching for a file
	 * @param boolean $useIncludePath = (optional) use include path flag, null to query
	 * @return boolean $useIncludePath
	 */
	public function useIncludePath($useIncludePath=null);

}
