<?php
namespace Tests\Stream;

use Library\PrintU;
use Library\Utilities\FormatVar;

/*
 *		FileObjectTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * FileObjectTest
 *
 * FileObject class tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Stream
 */
class FileObjectTest extends \Tests\FileIO\ObjectFactoryTest
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

    	$this->a_onLine = \Library\CliParameters::parameterValue('online', 1);

    	$this->a_streamSelectTests($this->a_fileName_XML);

    	$this->a_streamLineTests($this->a_fileName_XML);
    	$this->a_getStreamTests($this->a_fileName_XML);
    	$this->a_streamSelectionTests($this->a_fileName_XML);

    	if ($this->a_onLine)
    	{
#	    	$this->a_getStreamTests('http://fedora13.ewdesigns.lan/~jay/HauntedHollow/index.html');

	    	$this->a_streamLineTests('http://sourceforge.net/apps/wordpress/igsgateway/feed/');
    		$this->a_getStreamTests('http://sourceforge.net/apps/wordpress/igsgateway/feed/');
    	}
    }

    /**
     * a_getStreamTests
     *
     * Execute a series of stream_get_line tests
     */
    public function a_getStreamTests($file)
    {
    	$this->labelBlock('getStream Tests', 60, '*');

    	$this->a_absoluteFileName($file);

		$this->a_newFileObject();

		$this->a_getContents(-1, -1);

		$this->a_header();

		$this->a_isTimeout(false, false);

    	$this->a_destroyFileObject();

    	$this->a_absoluteFileName($file);

    	if (! stream_is_local($this->a_fileName))
    	{
			\Library\Url::url($this->a_fileName);

    		if ((\Library\Url::scheme() == 'http') || (\Library\Url::scheme() == 'https'))
    		{
	    		$opts = array('http' => array('Log_Method' 		=> 'GET',
    	    								  'max_redirects'	=> '0',
        									  'ignore_errors' 	=> '1',
											  ),
							 );

				$this->a_createContext($opts);
    		}
    	}

		$this->a_newFileObject();

		$this->a_getStream(-1, -1);

		if ($this->a_context)
		{
			$this->a_streamInfo();
		}

		$this->a_isTimeout(false, false);

    	$this->a_destroyFileObject();
    }

    /**
     * a_streamSelectTests
     *
     */
    public function a_streamSelectTests($file)
    {
    	$this->labelBlock('Stream Select Tests', 60, '*');

    	$this->a_absoluteFileName($file);
		$this->a_newFileObject();

		$this->a_data = false;
		while (! $this->a_data)
		{
			$this->a_streamSelect(null);
		}

		$this->a_getContents(-1, -1);

		$this->a_header();

		$this->a_isTimeout(false, false);

    	$this->a_destroyFileObject();
    }

    /**
     * a_streamLineTests
     *
     * Execute a series of stream_get_line tests
     */
    public function a_streamLineTests($file)
    {
    	$this->labelBlock('Stream getLine Tests', 60, '*');

    	$this->a_absoluteFileName($file);

		$this->a_newFileObject();

		$lines = array();

		if (stream_is_local($this->a_fileName))
		{
	    	$this->a_foreach();

	    	$lines = $this->a_fileRecords;
    	}

    	$this->a_destroyFileObject();

    	$this->a_newFileObject();

    	$line = 0;
    	while(! $this->a_file->eof())
    	{
    		$this->a_getLine(($lines) ? $lines[$line++] : null);
    	}

    	$this->a_destroyFileObject();
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
     * a_streamSelect
     *
     * Wait for i/o available on the current stream
     * @param integer $timeout = number of seconds to wait before timeout
     */
    public function a_streamSelect($timeout=0)
    {
    	$this->labelBlock('Stream Select', 50, '*');
    	
		$this->a_selectArray = array('stdin' => $this->a_file->handle);
		$this->a_timeout = $timeout;
		$this->a_key = null;

    	$assertion = '$this->a_resource = $this->a_file->streamSelect($this->a_selectArray, $this->a_key, $this->a_timeout);';
    	if (! $this->assertTrue($assertion, sprintf("Stream Select - Asserting: %s", $assertion)))
    	{
    		$this->a_outputAndDie();
    	}
    	
		if ($this->a_data !== false)
		{
			$this->a_showData($this->a_key, 'Key');
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
    	parent::a_destroyFileObject();
    }

	/**
     * a_newFactoryObject
     *
     * Create a new FileIO Object object
     */
    public function a_newFileObject()
    {
    	$this->labelBlock('Creating NEW FileObject Object.', 40, '*');

    	$assertion = sprintf('(($this->a_file = new \Library\Stream("%s", "%s", $this->a_mode, $this->a_includePath, $this->a_context)) !== null);',
    						 $this->a_objectName,
    						 $this->a_fileName);

		if (! $this->assertExceptionTrue($assertion, sprintf("NEW Stream Object - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType(true, 'Library\Stream', get_class($this->a_file));

		$this->a_lineNumber = 0;
    }

}
