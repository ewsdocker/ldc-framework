<?php
namespace Tests\Compress;
use Library\Compress;

/*
 * 		Compress\CompressTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * Compress\CompressTest.
 *
 * Compress class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage Compress.
 */

class CompressTest extends \Application\Launcher\Testing\UtilityMethods
{
	/**
	 * __construct
	 *
	 * Class constructor - pass parameters on to parent class
	 */
	public function __construct()
	{
		parent::__construct();
		$this->assertSetup(1, 0, 0, array('Application\Launcher\Testing\Base', 'assertCallback'));
	}

	/**
	 * __destruct
	 *
	 * Class destructor.
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
     * assertionTests
     *
     * run the current assertion test steps
     * @parm string $logger = (optional) name of the logger to use, null for none 
     */
    public function assertionTests($logger=null, $format=null)
    {
    	parent::assertionTests($logger, $format);

    	$adapterName = \Library\CliParameters::parameterValue('adapter', 'zlib');
    	$this->assertLogMessage(sprintf('Adapter: %s', $adapterName));

    	$this->a_fileName = null;
    	$this->a_mode = 'rb';

    	$this->a_adapterFactory($adapterName);
    	$this->a_fileName('Tests\Compress\testfile.gz');

    	$fileName1 = 'Tests\Compress\Testfiles\Test.xml';
    	$fileName2 = 'Tests\Compress\Testfiles\Test.txt';

    	$fileName1Size = strlen($fileName1);
    	$fileName2Size = strlen($fileName2);

    	$this->a_openRegularFile($fileName1, 'rt');
		$this->a_flags = 0;
		$this->a_updateFlags();

		$this->a_readRegularFile();
		$this->a_closeRegularFile();

		$this->assertLogMessage(sprintf("Regular File Buffer:\n%s", $this->a_buffer));

		$this->a_openCompressedFile('Tests\Compress\Testfiles\testfile.gz', 'wb6');
		$this->a_writeCompressedFile($this->a_buffer);
		$this->a_closeCompressedFile();

		$this->a_openCompressedFile('Tests\Compress\Testfiles\testfile.gz', 'rb');
		$this->a_readCompressedFile($this->a_buffer);
		$this->a_closeCompressedFile();

		$this->a_openCompressedFile('Tests\Compress\Testfiles\testfile.gz', 'wb6');

		$buffer1 = $this->a_buffer;
		$buffer2 = $this->a_buffer;
		
		$this->a_writeCompressedLibrary($fileName1, $buffer1);
		$this->a_writeCompressedLibrary($fileName2, $buffer2);

		$this->a_closeCompressedFile();
		$this->a_openCompressedFile('Tests\Compress\Testfiles\testfile.gz', 'rb');

		$this->a_readCompressedLibrary($fileName1, $buffer1);
		$this->a_readCompressedLibrary($fileName2, $buffer2);

		$this->a_closeCompressedFile();

		$this->a_compressionTests($this->a_buffer);
    }

    /**
     * a_closeCompressedFile
     *
     * Close the compressed file
     */
    public function a_closeCompressedFile()
    {
    	$this->labelBlock('Close Compressed File.', 40, '*');

    	$assertion = '$this->a_adapter->fclose();';
		if (! $this->assertExceptionFalse($assertion, sprintf("Close Compressed File - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_readCompressedFile
     *
     * Read a compressed file
     */
    public function a_readCompressedFile($expected, $length=8192)
    {
    	$this->labelBlock('Read Compressed File.', 40, '*');

    	$assertion = sprintf('$this->a_uncompressedBuffer = $this->a_adapter->fread(%u);', $length);
		if (! $this->assertExceptionTrue($assertion, sprintf("Read Compressed File - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();
		
		$this->a_compareExpectedType(true, $this->a_uncompressedBuffer, $expected);
    }

    /**
     * a_writeCompressedFile
     *
     * Write a compressed file
     * @param string $buffer = data to compress
     */
    public function a_writeCompressedFile($buffer)
    {
    	$this->labelBlock('Write Compressed File.', 40, '*');

    	$this->a_regularBuffer = $buffer;
    	$assertion = sprintf('$this->a_data = $this->a_adapter->fwrite($this->a_regularBuffer, %u);', strlen($buffer));
		if (! $this->assertExceptionTrue($assertion, sprintf("Write Compressed File - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();
		
		$this->a_compareExpected(strlen($buffer));
    }

    /**
     * a_writeCompressedLibrary
     *
     * Write a compressed library file
     * @param string $fileName = library file name
     * @param string $buffer = data to compress
     */
    public function a_writeCompressedLibrary($fileName, $buffer)
    {
    	$this->labelBlock('Write Compressed Library.', 60, '*');

    	$this->a_libraryBuffer = sprintf('%03u%s', strlen($fileName), $fileName);
		$this->a_writeCompressedFile($this->a_libraryBuffer);

		$this->a_libraryBuffer = sprintf('%05u%s', strlen($buffer), $buffer);
		$this->a_writeCompressedFile($this->a_libraryBuffer);
    }

    /**
     * a_readCompressedLibrary
     *
     * Read a compressed library file
     * @param string $fileName = library file name
     * @param string $buffer = data to compress
     */
    public function a_readCompressedLibrary($fileName, $buffer)
    {
    	$this->labelBlock('Read Compressed Library.', 60, '*');

    	$this->a_readCompressedFile(sprintf('%03u', strlen($fileName)), 3);
		$length = $this->a_uncompressedBuffer;
		$this->a_readCompressedFile($fileName, $length);

    	$this->a_readCompressedFile(sprintf('%05u', strlen($buffer)), 5);
		$length = $this->a_uncompressedBuffer;
		$this->a_readCompressedFile($buffer, $length);
    }

    /**
     * a_openCompressedFile
     *
     * Open a compressed file using the Compress class factory
     * @param string $fileName = (optional) name of the file to open
     * @param string $mode = (optional) open mode
     */
    public function a_openCompressedFile($fileName=null, $mode=null)
    {
    	$this->labelBlock('Open Compressed File.', 40, '*');

    	if ($fileName !== null)
    	{
    		$this->a_absoluteFileName($fileName);
    	}

    	$this->a_setMode($mode);
    	$assertion = '$this->a_adapter->open($this->a_fileName, $this->a_mode);';
		if (! $this->assertExceptionFalse($assertion, sprintf("Open Compressed File - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_setMode
     *
     * Set the file mode
     * @param string $mode = the file open mode
     */
    public function a_setMode($mode)
    {
    	$this->labelBlock('Set Mode.', 40, '*');

    	$this->a_mode = $mode;

		$assertion = '$this->a_data = $this->a_adapter->mode($this->a_mode);';
		if (! $this->assertTrue($assertion, sprintf("Set Mode - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($mode);
    }

    /**
     * a_fileName
     *
     * Set the file name
     * @param string $fileName = name of the file
     */
    public function a_fileName($fileName)
    {
    	$this->labelBlock('File Name.', 40, '*');

    	$this->a_absoluteFileName($fileName);
		$assertion = '$this->a_data = $this->a_adapter->fileName($this->a_fileName);';
		if (! $this->assertTrue($assertion, sprintf("File Name - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($this->a_fileName);
    }

    /**
     * a_adapterFactory
     *
     * Create a new adapter instance using the class factory
     * @param string $adapter = name of the adapter to instantiate
     */
    public function a_adapterFactory($adapter)
    {
    	$this->labelBlock('Adapter Factory.', 40, '*');

    	$this->a_adapterName = $adapter;
		$assertion = '$this->a_adapter = \Library\Compress\Factory::instantiateClass($this->a_adapterName, $this->a_fileName, $this->a_mode);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Adapter Factory - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_readRegularFile
     *
     * Read a regular (uncompressed) file using the FileIO adapter
     */
    public function a_readRegularFile()
    {
    	$this->labelBlock('Read Regular File.', 40, '*');

    	$assertion = '$this->a_buffer = $this->a_foreach();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Open Regular File - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_updateFlags
     *
     * Update the object flags
     */
	public function a_updateFlags()
	{
    	$this->labelBlock('Update Flags.', 40, '*');

		$this->a_setFlags($this->a_flags);
		$this->a_getFlags($this->a_flags);
	}

    /**
     * a_getFlags
     *
     * Get flags
     * @param integer $expected = expected flags setting
     */
    public function a_getFlags($expected)
    {
    	$this->labelBlock('getFlags.', 40, '*');

    	$assertion = '(($this->a_data = $this->a_regularFile->getFlags()) !== false)';
		if (! $this->assertTrue($assertion, sprintf('getFlags - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
    }

    /**
     * a_setFlags
     *
     * Set flags
     * @param integer $flags = flags setting
     */
    public function a_setFlags($flags)
    {
    	$this->labelBlock('setFlags.', 40, '*');

    	$this->a_data = $flags;
    	$assertion = '$this->a_regularFile->setFlags($this->a_data);';
		if (! $this->assertFalse($assertion, sprintf('setFlags %o - Asserting: %s', $flags, $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

	/**
     * a_openRegularFile
     *
     * Open a regular (uncompressed) file using the FileIO class factory
     * @param string $fileName = name of the file to open
     * @param string $mode = open mode
     */
    public function a_openRegularFile($fileName, $mode='rb')
    {
    	$this->labelBlock('Open Regular File.', 40, '*');

    	$this->a_regularFileName = $this->a_absoluteFileName($fileName);
    	$this->a_mode = $mode;
    	$assertion = '$this->a_regularFile = \Library\FileIO\Factory::instantiateClass("fileobject", $this->a_regularFileName, $this->a_mode);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Open Regular File - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();
		
		$this->a_compareExpectedType(true, get_class($this->a_regularFile), \Library\FileIO\Factory::getInstance()->className('fileobject'));
    }

    /**
     * a_closeRegularFile
     *
     * Close the regular file
     */
    public function a_closeRegularFile()
    {
    	$this->labelBlock('Close Regular File.', 40, '*');

    	unset($this->a_regularFile);
    	$this->a_regularFile = null;
    }

    /**
     * a_foreach
     *
     * iterate over the regular file
     */
    public function a_foreach()
    {
    	$this->labelBlock('ForEach.', 40, '*');

    	$this->a_fileRecords = array();
    	foreach($this->a_regularFile as $key => $line)
    	{
    		$this->a_fileRecords[$key] = rtrim($line);
   			$this->assertLogMessage(sprintf('%03u : %s', $key, $this->a_fileRecords[$key]));
    	}
    	
    	return implode("\n", $this->a_fileRecords);
    }

    /**
     * a_compressionTests
     *
     * Perform compression tests
     * @param string $buffer = buffer to test
     */
    public function a_compressionTests($buffer)
    {
    	$this->labelBlock('Compression Tests.', 40, '*');

    	$this->a_compressionTest($buffer, 'ZLIB');
		$this->a_compressionTest($buffer, 'DEFLATE');
//		$this->a_compressionTest($buffer, 'GZIP');
    }

    /**
     * a_compressionTest
     *
     * Perform the requested compression test
     * @param string  $buffer     = reference to the i/o buffer
     * @param string  $compressor = compression test
     */
    public function a_compressionTest($buffer, $compressor)
    {
    	$this->a_buffer = $buffer;
    	$bufferLength = strlen($buffer);

    	$this->assertLogMessage(sprintf('Compression test with compressor: %s', $compressor));

		switch($compressor)
		{
			case 'ZLIB':
				$this->a_compress($buffer);
				$this->a_uncompress($this->a_buffer);
				break;

			case 'DEFLATE':
				$this->a_deflate($buffer);
				$this->a_inflate($this->a_buffer);
				break;

			case 'GZIP':
				$this->a_encode($buffer);
				$this->a_decode($this->a_buffer);
				break;

			default:
				$this->a_outputAndDie(sprintf('Bad request = %s', $compressor));
		}

		$uncompressedLength = strlen($this->a_unCompressed);

		$this->assertLogMessage(sprintf('original     size = %d', $bufferLength));
		$this->assertLogMessage(sprintf('compressed   size = %d', strlen($this->a_compressed)));
		$this->assertLogMessage(sprintf('uncompressed size = %d', $uncompressedLength));

		$this->a_compareExpectedType(true, $bufferLength, $uncompressedLength);

		$this->a_compareExpectedType(true, $this->a_buffer, $this->a_unCompressed);
    }

	/**
     * a_encode
     *
     * encode method call
     * @param string $buffer = buffer to encode
     */
    public function a_encode($buffer, $level=9)
    {
    	$this->labelBlock('Encode.', 40, '*');

    	$this->a_buffer = $buffer;
    	$this->a_level = $level;
    	
    	$assertion = '$this->a_compressed = $this->a_adapter->encode($this->a_buffer, $this->a_level);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Encode - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();
    }

	/**
     * a_decode
     *
     * decode method call
     * @param string $expected = expected results
     */
    public function a_decode($expected)
    {
    	$this->labelBlock('DeCode.', 40, '*');

    	$assertion = '$this->a_unCompressed = $this->a_adapter->decode($this->a_compressed, 8192);';
		if (! $this->assertExceptionTrue($assertion, sprintf("DeCode - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();
		
		$this->a_compareExpectedType(true, $this->a_unCompressed, $expected);
    }

    /**
     * a_deflate
     *
     * defalte method call
     * @param string $buffer = buffer to deflate
     */
    public function a_deflate($buffer)
    {
    	$this->labelBlock('Deflate.', 40, '*');

    	$this->a_buffer = $buffer;
    	$assertion = '$this->a_compressed = $this->a_adapter->deflate($this->a_buffer);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Deflate - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();
    }

	/**
     * a_inflate
     *
     * inflate method call
     * @param string $expected = expected results
     */
    public function a_inflate($expected)
    {
    	$this->labelBlock('Inflage.', 40, '*');

    	$assertion = '$this->a_unCompressed = $this->a_adapter->inflate($this->a_compressed);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Inflate - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();
		
		$this->a_compareExpectedType(true, $this->a_unCompressed, $expected);
    }

    /**
     * a_compress
     *
     * compress method call
     * @param string $buffer = buffer to compress
     */
    public function a_compress($buffer)
    {
    	$this->labelBlock('Compress.', 40, '*');

    	$this->a_buffer = $buffer;
    	$assertion = '$this->a_compressed = $this->a_adapter->compress($this->a_buffer);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Compress - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();
    }

	/**
     * a_uncompress
     *
     * uncompress method call
     * @param string $expected = expected results
     */
    public function a_uncompress($expected)
    {
    	$this->labelBlock('UnCompress.', 40, '*');

    	$assertion = '$this->a_unCompressed = $this->a_adapter->uncompress($this->a_compressed);';
		if (! $this->assertExceptionTrue($assertion, sprintf("UnCompress - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();
		
		$this->a_compareExpectedType(true, $this->a_unCompressed, $expected);
    }

}
