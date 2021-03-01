<?php
namespace Tests\Stream;

use Library\PrintU;
use Library\Utilities\FormatVar;
use Library\CliParameters;

/*
 *		FileObjectStreamTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * FileObjectStreamTest
 *
 * FileObject class tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Stream
 */
class FileObjectStreamTest extends \Application\Launcher\Testing\UtilityMethods
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

		$object = CliParameters::parameterValue('object', 'fileobject');

		$zipdir = CliParameters::parameterValue('dir', 'Testfiles/zip');
		$xmldir = CliParameters::parameterValue('xml', 'Testfiles/xml');

		$adapter = 'stream';
		$mode = 'rb';

		$apiKey = CliParameters::parameterValue('apikey', 'D29BEE8036451BD0');
		$seriesid = CliParameters::parameterValue('seriesid', 73028);
		$apiFile = 'en.zip';

		$url = sprintf("http://thetvdb.com/api/%s/series/%s/all/%s", $apiKey, $seriesid, $apiFile);

		if (stream_is_local($url))
		{
			$this->a_outputAndDie(sprintf("%s is a local file", $url));
		}

		$this->a_parseUrl($url);
		$this->a_urlScheme();

		if (($this->a_data != 'http') && ($this->a_data != 'https'))
		{
			$this->a_outputAndDie(sprintf("'%s' scheme is not supported", $this->a_data));
		}

		$context = null;
		$this->a_newFileObject($object, $url, $mode, false, $context);

		$this->a_getStream(-1, -1);
		$buffer = $this->a_data;

		if ($this->a_context)
		{
			$this->a_streamInfo();
		}

		$this->a_isTimeout(false, false);

		$this->a_destroyFileObject();

		$file = sprintf("%s%s.zip", $zipdir, $seriesid);

		$mode = 'wb';
		$adapter = 'stream';
		$this->a_newFileObject($object, $file, $mode, false, $context);
		
		$this->a_fwrite($buffer, null, null);

		$this->a_destroyFileObject();
		
		$this->a_newArchiveZip();
		$this->a_openArchiveZip($file, 'r');
		
		$this->a_getArchiveZip('banners.xml');
		$this->a_loadBanners($this->a_data);

		$this->a_getArchiveZip('actors.xml');
		$this->a_loadActors($this->a_data);

		$this->a_getArchiveZip('en.xml');

		$this->a_closeArchiveZip();
		$this->a_zip = null;
	}

	/**
	 * a_loadBanners
	 *
	 * Create a new Banners class object
	 * @param string $url = url of the banners api
	 */
	public function a_loadBanners($url, $callback=null)
	{
		$this->labelBlock('Load Banners Test.', 60, '*');

		$this->a_url = $url;
		$this->a_callback = $callback;

		$this->a_showData($this->a_url, 'URL');
		$this->a_showData($this->a_callback, 'callback');

		$assertion = '$this->a_xml = new \Library\TVDB\Banners($this->a_url, $this->a_callback);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, 'Library\TVDB\Banners', get_class($this->a_xml));

		$assertion = '$this->a_array = $this->a_xml->xmlArray();';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_array, 'Banners');
		
		$this->a_xml = null;
	}

	/**
	 * a_loadActors
	 *
	 * Create a new Actors class object
	 * @param string $url = url of the actors data
	 */
	public function a_loadActors($url, $callback=null)
	{
		$this->labelBlock('Load Actors Test.', 60, '*');

		$this->a_url = $url;
		$this->a_callback = $callback;

		$this->a_showData($this->a_url, 'URL');
		$this->a_showData($this->a_callback, 'callback');

		$assertion = '$this->a_xml = new \Library\TVDB\Actors($this->a_url, $this->a_callback);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, 'Library\TVDB\Actors', get_class($this->a_xml));

		$assertion = '$this->a_array = $this->a_xml->xmlArray();';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_array, 'Actors');
		
		$this->a_xml = null;
	}

	/**
	 * a_openArchiveZip
	 *
	 * Open a zip archive
	 * @param string $file = file to open
	 * @param string $mode = (optional) open mode: 'w' to write, 'r' to read (default)
	 */
	public function a_openArchiveZip($file, $mode='r')
	{
		$this->labelBlock('Open Archive Zip', 40, '*');

		$this->a_file = $file;
		
		if ($mode == 'w')
		{
			$mode = ZipArchive::CREATE | ZipArchive::OVERWRITE;
		}

		$this->a_mode = $mode;

		$this->a_showData($this->a_file, 'File');
		$this->a_showData($this->a_mode, 'Mode');

		if ($this->a_mode == 'r')
		{
			$assertion = '$this->a_data = $this->a_zip->open($this->a_file);';
		}
		else
		{
			$assertion = '$this->a_data = $this->a_zip->open($this->a_file, $this->a_mode);';
		}

		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		
		$this->a_showData($this->a_data, 'Result');
	}
	
	/**
	 * a_closeArchiveZip
	 *
	 * Close the ZipArchive
	 */
	public function a_closeArchiveZip()
	{
		$this->labelBlock('Close Archive Zip', 40, '*');
		
		$assertion = '$this->a_data = $this->a_zip->close();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();
		
		$this->a_showData($this->a_data, 'Close Status');
	}

	/**
	 * a_getArchiveZip
	 *
	 * Get a file from the ZipArchive
	 * @param string $zipName = name of the zip file to extract
	 */
	public function a_getArchiveZip($zipName)
	{
		$this->labelBlock('Get Archive Zip', 40, '*');

		$this->a_zipName = $zipName;
		$this->a_showData($this->a_zipName, 'Zip Name');

		$assertion = '$this->a_data = $this->a_zip->getFromName($this->a_zipName);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_showData($this->a_data, '$this->a_zipName');		
	}

	/**
	 * a_newArchiveZip
	 *
	 * Create a ZipArchive class instance
	 */
	public function a_newArchiveZip()
	{
		$this->labelBlock('New Archive Zip', 40, '*');

		$assertion = '$this->a_zip = new \ZipArchive();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		
		$this->a_compareExpectedType(true, 'ZipArchive', get_class($this->a_zip));
	}

	/**
	 * a_fwrite
	 *
	 * Write a string to the open file
	 * @param string $buffer = data to write
	 * @param integer $expected = expected write result (bytes written)
	 * @param integer $length = (optional) length to write - if null, the complete buffer is written
	 */
	public function a_fwrite($buffer, $expected=null, $length=null)
	{
		$this->labelBlock('fwrite.', 40, '*');

		$this->a_buffer = $buffer;
		$this->a_expected = $expected;
		$this->a_length = $length;

   		$this->a_showData($this->a_expected, 'expected');
   		$this->a_showData($this->a_length, 'length');
   		$this->a_showData($this->a_buffer, 'a_buffer');

		if ($length === null)
		{
			$assertion = '($this->a_data = $this->a_file->fwrite($this->a_buffer)) !== null;';
		}
		else
		{
			$assertion = sprintf('($this->a_data = $this->a_file->fwrite($this->a_buffer, %u)) !== null;', $length);
		}

		if (! $this->assertTrue($assertion, sprintf('fwrite - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_data, 'a_data');

		if ($this->a_expected != null)
		{
			$this->a_compareExpected($expected);
		}
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
		$this->a_showData($this->a_options, 'Optiona');

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
	 * a_destroyFileObject
	 *
	 * Destroy the current file object
	 */
	public function a_destroyFileObject()
	{
		$this->labelBlock('DESTROYING object.', 40, '*');

		$assertion = '$this->a_file = null;';
		if (! $this->assertFalse($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_newFactoryObject
	 *
	 * Create a new FileIO Object object
	 * @param string $objectName = name of the stream adapter
	 * @param string $fileName = name of the file to be streamed
	 * @param string $mode = (optional) read/write mode (default = 'rb')
	 * @param boolean $includePath = (optional) true to search include path for file (default = false)
	 * @param object $context = (optional) context object (default = null)
	 */
	public function a_newFileObject($objectName, $fileName, $mode='rb', $includePath=false, $context=null)
	{
		$this->labelBlock('Creating NEW FileObject Stream.', 40, '*');

		$this->a_objectName = $objectName;
		$this->a_fileName = $fileName;
		$this->a_mode = $mode;
		$this->a_includePath = $includePath;
		$this->a_context = $context;

		$this->a_showData($this->a_objectName, 'Object Name');
		$this->a_showData($this->a_fileName, 'File Name');
		$this->a_showData($this->a_mode, 'Mode');
		$this->a_showData($this->a_includePath, 'Include Path');
		$this->a_showData($this->a_context, 'Context');

//$this->a_file = new \Library\Stream($this->a_objectName, $this->a_fileName, $this->a_mode, $this->a_includePath, $this->a_context);

		if ($this->a_context)
		{
			$assertion = '(($this->a_file = new \Library\Stream($this->a_objectName, $this->a_fileName, $this->a_mode, $this->a_includePath, $this->a_context)) !== null);';
		}
		else
		{
			$assertion = '(($this->a_file = new \Library\Stream($this->a_objectName, $this->a_fileName, $this->a_mode, $this->a_includePath)) !== null);';
		}

		if (! $this->assertExceptionTrue($assertion, sprintf("NEW Stream Object - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType(true, 'Library\Stream', get_class($this->a_file));

		$this->a_lineNumber = 0;
	}

	/**
	 * a_urlFragment
	 *
	 * Get the fragment string, if it exists
	 * @param integer $expected = fragment expected
	 * @param boolean $type = type of test (true or false), default=true
	 */
	public function a_urlFragment($expected, $type=true)
	{
		$this->labelBlock('urlFragment.', 40, '*');

		$assertion = '(($this->a_data = \Library\Url::fragment()) !== null);';
		if (! $this->assertTrue($assertion, sprintf("Fragment: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType($type, $expected);
	}

	/**
	 * a_urlQuery
	 *
	 * Get the query, if it exists
	 * @param integer $expected = query string expected
	 * @param boolean $type = type of test (true or false), default=true
	 */
	public function a_urlQuery($expected, $type=true)
	{
		$this->labelBlock('urlQuery.', 40, '*');

		$assertion = '(($this->a_data = \Library\Url::query()) !== null);';
		if (! $this->assertTrue($assertion, sprintf("Query: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType($type, $expected);
	}

	/**
	 * a_urlScheme
	 *
	 * Get the protocol scheme
	 * @param integer $expected = (optional) protocol scheme expected
	 * @param boolean $type = (optional) type of test (true or false), default=true
	 */
	public function a_urlScheme($expected=null, $type=true)
	{
		$this->labelBlock('urlScheme.', 40, '*');

		$this->a_expected = $expected;
		$this->a_type = $type;
		
		$this->a_showData($this->a_expected, 'Expected');
		$this->a_showData($this->a_type, 'Comparison Type');

		$assertion = '(($this->a_data = \Library\Url::scheme()) !== null);';
		if (! $this->assertTrue($assertion, sprintf("Scheme: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		if ($this->a_expected != null)
		{
			$this->a_compareExpectedType($this->a_type, $this->a_expected);
		}
	}

	/**
	 * a_urlPort
	 *
	 * Get the port number from the parser
	 * @param integer $expected = port number expected
	 * @param boolean $type = type of test (true or false), default=true
	 */
	public function a_urlPort($expected, $type=true)
	{
		$this->labelBlock('urlPort.', 40, '*');

		$assertion = '(($this->a_data = \Library\Url::port()) !== null);';
		if (! $this->assertTrue($assertion, sprintf("Port: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType($type, $expected);
	}

	/**
	 * a_urlPath
	 *
	 * Get the url path
	 * @param string $expected = expected path
	 */
	public function a_urlPath($expected)
	{
		$this->labelBlock('urlPath.', 40, '*');

		$assertion = '$this->a_data = \Library\Url::path();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Path: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
	}

	/**
	 * a_urlHost
	 *
	 * Get the host name
	 * @param string $expected = expected host name
	 */
	public function a_urlHost($expected)
	{
		$this->labelBlock('urlHost.', 40, '*');

		$assertion = '$this->a_data = \Library\Url::host();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Host: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
	}

	/**
	 * a_parseUrl
	 *
	 * Parse the url using Library\Url\Parse
	 * @param string $url = url to parse
	 */
	public function a_parseUrl($url)
	{
		$this->labelBlock('Parse Url.', 60, '*');

		$assertion = sprintf("\\Library\\Url::url('%s');", $url);
		if (! $this->assertExceptionTrue($assertion, sprintf("Parse Url: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}
}
