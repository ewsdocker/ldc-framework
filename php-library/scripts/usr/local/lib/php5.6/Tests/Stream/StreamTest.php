<?php
namespace Tests\Stream;

use Library\PrintU;
use Library\Stream as Streamer;
use Library\Utilities\FormatVar;

/*
 *		StreamTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * StreamTest
 *
 * Stream\FileObject class tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Stream
 */
class StreamTest extends FactoryTest
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

    	$this->a_mode = 'r';
		$this->a_includePath = false;
		$this->a_context = null;

    	$this->a_onLine = \Library\CliParameters::parameterValue('online', 1);

    	$this->a_streamSelectTests('Tests/FileIO/Files/TestXML.txt');
#    	$this->a_streamSelectTests(STDIN);
    }

    /**
     * a_streamSelectTests
     * 
     * Perform select tests
     * @param mixed $file = file name or handle
     */
	public function a_streamSelectTests($file)
	{
		$this->labelBlock('streamSelectTests', 60, '*');

		$this->a_showData($file, 'File name');

		if (is_string($file))
		{
			$this->a_absoluteFileName($file);
		}
    	else
		{
    		$this->a_fileName = $file;
		}

		$this->a_newFileObject();

		$this->a_selectedCount = false;
		while (($this->a_selectedCount === 0) || ($this->a_selectedCount === false))
		{
			$this->a_streamSelect(1);
		}

		$this->a_getContents(-1, -1);

		$this->a_header();

    	$this->a_destroyFileObject();
    }

    /**
     * a_streamSelect
     *
     * Wait for i/o available on the current stream
     * @param integer $timeout = (optional) number of seconds to wait before timeout, null to wait forever
     */
    public function a_streamSelect($timeout=1)
    {
    	$this->labelBlock('Stream Select', 50, '*');
    	
		$this->a_selectArray = array('file' => $this->a_file->handle);
		$this->a_timeout = $timeout;
		$this->a_keys = null;

		$this->a_showData($this->a_timeout, 'Timeout');
		$this->a_showData($this->a_selectArray, 'Select Array');

		$assertion = '(($this->a_selectedCount = $this->a_file->streamSelect($this->a_selectArray, $this->a_keys, $this->a_timeout)) !== false);';
    	if (! $this->assertTrue($assertion, sprintf("Stream Select - Asserting: %s", $assertion)))
    	{
    		$this->a_outputAndDie();
    	}

		$this->a_showData($this->a_selectedCount, 'Count');
		$this->a_showData($this->a_keys, 'Keys');
    }

    /**
     * a_getStream
     *
     * Get stream contents
     */
    public function a_getStream($maxLines=-1, $offset=-1)
    {
    	$this->labelBlock('GetStream', 40, '*');

    	$this->a_maxLines = $maxLines;
    	$this->a_offset = $offset;

    	$assertion = '$this->a_data = $this->a_file->getStream($this->a_maxLines, $this->a_offset);';
		if (! $this->assertExceptionTrue($assertion, sprintf("GetStream - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->assertLogMessage($this->a_data);

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_streamInfo
     *
     * Get the stream info array (meta-data)
     */
    public function a_streamInfo()
    {
    	$this->labelBlock('StreamInfo', 40, '*');

    	$assertion = '(($this->a_info = $this->a_file->streamInfo()) !== null);';
		if (! $this->assertTrue($assertion, sprintf("StreamInfo - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		if (is_array($this->a_info))
		{
			$this->assertLogMessage(FormatVar::format($this->a_info, 'Stream Info'));
		}
    }

    /**
     * a_createContext
     *
     * Create a stream context from supplied options
     * @param array $options
     */
    public function a_createContext($options)
    {
    	$this->labelBlock('CreateContext', 40, '*');

    	$this->a_options = $options;

		$assertion = '(($this->a_context = $this->a_file->createContext($this->a_options)) !== null);';
		if (! $this->assertTrue($assertion, sprintf("CreateContext - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_getContents
     *
     * Get stream contents
     */
    public function a_getContents($maxLines=-1, $offset=-1)
    {
    	$this->labelBlock('GetContents', 40, '*');

    	$this->a_maxLines = $maxLines;
    	$this->a_offset = $offset;

    	$assertion = '$this->a_data = $this->a_file->getContents($this->a_maxLines, $this->a_offset);';
		if (! $this->assertExceptionTrue($assertion, sprintf("GetContents - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->assertLogMessage($this->a_data);

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_isTimeout
     *
     * Check for Timeout
     * @param boolean $expected = true for local stream, false for remote or url
     * @param boolean $exit = true to exit on error, false to ignore error
     */
    public function a_isTimeout($expected=false)
    {
    	$this->labelBlock('isTimeout', 40, '*');

    	$this->a_timeout = $expected;
    	$assertion = '(($this->a_data = $this->a_file->isTimeout()) === null);';
		if (! $this->assertExceptionFalse($assertion, sprintf("isTimeout - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpected($expected);
    }

    /**
     * a_header
     *
     * get stream header
     */
    public function a_header()
    {
    	$this->labelBlock('Get Header', 40, '*');

    	$assertion = '(($this->a_data = $this->a_file->header()) !== 0);';
		if (! $this->assertTrue($assertion, sprintf("get Header - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		if ($this->a_data !== null)
		{
			$this->assertLogMessage($this->a_data);
    	}
    }

    /**
     * a_localStream
     *
     * Check for local stream
     * @param boolean $expected = true for local stream, false for remote or url
     */
    public function a_localStream($expected=true)
    {
    	$this->labelBlock('LocalStream Tests', 40, '*');

    	$assertion = '$this->a_data = $this->a_file->isLocal();';
		if (! $this->assertTrue($assertion, sprintf("isLocal - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
    }

    /**
     * a_getLine
     *
     * Get next line from the stream
     */
    public function a_getLine($expected=null)
    {
    	$this->labelBlock('GetLine Tests', 40, '*');

    	$assertion = '(($this->a_data = $this->a_file->getLine(1024, "\n")) !== false);';
		if (! $this->assertExceptionTrue($assertion, sprintf("GetLine - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->assertLogMessage($this->a_data);

		$this->a_exceptionCaughtFalse();

		if ($expected !== null)
		{
			$this->a_compareExpected(rtrim($expected));
		}
    }

    /**
     * a_destroyFileObject
     *
     * Destroy the current file object and stream context
     */
    public function a_destroyFileObject()
    {
    	$this->a_context = null;
    	$this->a_file = null;
    }

	/**
     * a_newFactoryObject
     *
     * Create a new FileIO Object object
     */
    public function a_newFileObject()
    {
    	$this->labelBlock('Creating NEW FileObject Object.', 40, '*');

		$this->a_showData($this->a_objectName,  'Object name');
		$this->a_showData($this->a_fileName,    'File name');
		$this->a_showData($this->a_mode,        'Mode');
		$this->a_showData($this->a_includePath, 'Include Path');
		$this->a_showData($this->a_context,     'Context');

		$assertion = '(($this->a_file = new \Library\Stream($this->a_objectName, $this->a_fileName, $this->a_mode, $this->a_includePath, $this->a_context)) !== null);';

		if (! $this->assertExceptionTrue($assertion, sprintf("NEW Stream Object - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType(true, 'Library\Stream', get_class($this->a_file));

		$this->a_lineNumber = 0;
    }

}
