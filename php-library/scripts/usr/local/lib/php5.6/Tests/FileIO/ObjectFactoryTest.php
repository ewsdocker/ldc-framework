<?php
namespace Tests\FileIO;
use Library\PrintU;
use Library\Utilities\FormatVar;

/*
 *		ObjectFactoryTest is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * ObjectFactoryTest
 *
 * FileObject class tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage FileIO
 */
class ObjectFactoryTest extends \Application\Launcher\Testing\UtilityMethods
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

    	$this->labelBlock('ObjectFactoryTest Assertion Tests.', 40, '*');

    	if (property_exists($this, 'a_doNotRunTests'))
    	{
	    	$this->a_showData($this->a_doNotRunTests, 'DoNotRunTests');
    		return;
    	}

    	$this->labelBlock('ObjectFactoryTest Assertion Tests.', 40, '*');

    	if (! $this->a_objectName = \Library\CliParameters::parameterValue('object', false))
		{
			$this->a_outputAndDie("No object specified for class factory");
		}

		$this->a_adapterName = \Library\CliParameters::parameterValue('adapter', null);
		$this->a_binary = \Library\CliParameters::parameterValue('binary', false);
		if ($this->a_bunary == '1')
		{
			$this->a_binary = true;
		}

   		$this->a_testAll();
    }

    /**
     * a_testAll
     *
     * Run all FileIO tests
     */
    public function a_testAll()
    {
    	$this->labelBlockFlag(true);

    	$this->a_fileName_CSV  = 'Tests/FileIO/Files/TestCSV.txt';
    	$this->a_fileName_TXT  = 'Tests/FileIO/Files/TestText.txt';
    	$this->a_fileName_XML  = 'Tests/FileIO/Files/TestXML.txt';
		$this->a_fileName_HTML = 'Tests/FileIO/Files/TestHTML.txt';
		$this->a_fileName_LOCK = 'Tests/FileIO/Files/TestLock.txt';

    	$this->a_absoluteFileName($this->a_fileName_XML);
    	$this->a_mode = 'r';
    	$this->a_includePath = false;

		$this->a_newFactoryObject($this->a_fileName, $this->a_adapter, $this->a_object);

    	$this->a_objectClass = get_class($this->a_file);

    	$this->assertLogMessage(sprintf('Properties for class: %s', $this->a_objectClass));

		$reflection = new \ReflectionClass($this->a_file);
		$classProperties = $reflection->getProperties();

		foreach($classProperties as $index => $classProperty)
		{
			$name = $classProperty->getName();
			$this->assertLogMessage(sprintf("\t% 3u. %s is %s", $index, $classProperty->getName(), $classProperty->isProtected() ? 'Protected' : ($classProperty->isPublic() ? 'Public' : 'Private')));
		}

    	$this->a_includePath = false;
		$this->a_context = null;

		$this->a_flags = 0;

		$this->a_readTest();

		$this->labelBlockFlag(true);

		$this->a_readTest(12);

		$this->a_testCsv();

	   	if (method_exists($this->a_objectClass, 'fputcsv'))
    	{
    		$this->a_testPutCsv();
    	}

		$this->a_writeTest();

    	$this->a_absoluteFileName($this->a_fileName_HTML);
    	$this->a_mode = 'r';
    	$this->a_flags = 0;

    	$this->a_newFactoryObject($this->a_fileName, $this->a_adapter, $this->a_object);

    	$this->a_updateFlags();

    	$this->a_fstat();

    	$this->a_foreach();

		$this->a_rewind();

		$buffer = implode("", $this->a_fileRecords);

		$this->a_fseek(10);
		$this->a_ftell(10);

		$this->a_fgets(substr($buffer, 10, strpos($buffer, "\n", 10) - 9), true, true);

    	$this->a_destroyFileObject();

    	$this->a_lockTest();
    }

    /**
     * a_writeTest
     *
     * Write file tests
     * @param integer $maxLineLen = (optional) maximum read line length
     */
    public function a_writeTest($maxLineLen=null)
    {
    	$this->labelBlock('Write File Tests.', 60, '*');

    	if ($this->verbose > 1)
    	{
    		$this->a_showData($maxLineLen, 'maxLineLen');
    	}

    	$this->a_absoluteFileName($this->a_fileName_HTML);
    	$this->a_mode = 'w+';
    	$this->a_flags = 0;

    	$this->a_newFactoryObject($this->a_fileName, $this->a_adapter, $this->a_object);

    	$this->a_updateFlags();

    	$this->a_ftruncate(0);
		$this->a_ftell(0);

		$this->a_buffer  = "<html>\n" .
							"  <body>\n" .
							"    <p>Welcome! Today is now!</p>\n" .
							"  </body>\n" .
							"</html>\n" .
							"Text outside of the HTML block.\n";

        $this->a_fileLength = strlen($this->a_buffer);
		$this->a_localArray = explode("\n", $this->a_buffer);

        $this->a_fwrite($this->a_fileLength);

        $this->a_rewind();

		$this->a_foreach();

		$this->a_rewind();
		$this->a_fgets($this->a_fileRecords[$this->a_lineNumber]);
		$this->a_key(1, false);

		$this->a_getCurrentLine($this->a_fileRecords[$this->a_lineNumber]);

		$this->a_fgetss(strip_tags($this->a_fileRecords[$this->a_lineNumber]));

		$this->a_rewind();
		$this->a_fgets($this->a_fileRecords[$this->a_lineNumber]);
		$this->a_next();
		$this->a_key(1);

		$this->a_getCurrentLine($this->a_fileRecords[$this->a_lineNumber], false);
		$this->a_next();

		$this->a_fgetss_false(strip_tags($this->a_fileRecords[$this->a_lineNumber]));

		$this->a_destroyFileObject();

		// *****************************************************

    	$this->a_absoluteFileName($this->a_fileName_HTML);
    	$this->a_mode = 'r';
    	$this->a_flags = 0;

    	$this->a_newFactoryObject($this->a_fileName, $this->a_adapter, $this->a_object);

    	$this->a_updateFlags();

        if ($maxLineLen !== null)
        {
        	$this->a_setMaxLineLen(12);
        	$this->a_getMaxLineLen(12);
        }

    	$this->a_foreach();

		$this->a_rewind();

		$this->a_fgets($this->a_fileRecords[$this->a_lineNumber]);
		$this->a_getCurrentLine($this->a_fileRecords[$this->a_lineNumber]);

		$this->a_fgetss(strip_tags($this->a_fileRecords[$this->a_lineNumber]));

		$this->a_rewind();

		$this->a_fsize();
		$this->a_fpassthru($this->a_data, true);

		$this->a_hasChildren();
		$this->a_getChildren();

    	$this->a_destroyFileObject();
    }

    /**
     * a_testPutCsv
     *
     * perform a set of csv put tests
     */
    public function a_testPutCsv()
    {
    	$this->labelBlock('PutCsv Tests.', 60, '*');

    	$this->a_absoluteFileName($this->a_fileName_CSV);
    	$this->a_mode = 'w+';

    	$this->a_flags = 0;

    	$this->a_newFactoryObject($this->a_fileName, $this->a_adapter, $this->a_object);

    	$this->a_updateFlags();

    	$this->a_csvStringArray = array("jay,wheeler,1950-10-31\n",
		                            	"fran,wheeler,1949-07-30\n",
										"edward,wheeler,1980-11-01\n",
		                            	"michael,wheeler,1980-11-01\n",
										"murphy,thedog,1992-03-01\n",
										"oscar,thedog,2005-07-05");

    	$this->a_fileRecords = array();
    	foreach($this->a_csvStringArray as $key => $line)
    	{
   			$csvArray = explode(',', trim($line));
   			array_push($this->a_fileRecords, $csvArray);
    	}

		foreach($this->a_fileRecords as $index => $csvArray)
		{
			$this->a_fputcsv($csvArray, strlen(implode(',', $csvArray)) + strlen(PHP_EOL));
		}

        $this->a_destroyFileObject();

    	// *****************************************************

    	$this->a_absoluteFileName($this->a_fileName_CSV);
    	$this->a_mode = 'r';
    	$this->a_flags = 0;

    	$this->a_newFactoryObject($this->a_fileName, $this->a_adapter, $this->a_object);

    	$this->a_setFlagBit(\Library\FileIO\FileObject::READ_CSV);
    	$this->a_setFlagBit(\Library\FileIO\FileObject::SKIP_EMPTY);
    	$this->a_setFlagBit(\Library\FileIO\FileObject::DROP_NEW_LINE);

    	$this->a_updateFlags();

    	$this->a_iteration(false);

    	$this->a_destroyFileObject();
    }

    /**
     * a_arrayToCsv
     *
     * Convert the array contents to a csv string
     * @param array $array = array to convert
     * @param string $delimiter = (optional) delimiter character
     * @param string $enclosure = (optional) enclosure character
     * @param string $escape    = (optional) escape    character
     * @return string $csvBuffer
     */
	public function a_arrayToCsv($array, $delimiter=',', $enclosure='"', $escape='\\')
	{
    	$this->labelBlock('ArrayToCsv.', 40, '*');

		if ($this->verbose > 1)
    	{
    		$this->a_showData($array, 'array');
    		$this->a_showData($delimiter, 'delimiter');
    		$this->a_showData($enclosure, 'enclosure');
    		$this->a_showData($escape, 'escape');
    	}

		foreach($array as $field => $value)
		{
			$array[$field] = str_replace($enclosure, $escape . $enclosure, $value);
		}

		$string = $enclosure . implode($enclosure . $delimiter . $enclosure, $array) . $enclosure;
		return $string;
	}

    /**
     * a_testCsv
     *
     * perform a set of csv tests
     */
    public function a_testCsv()
    {
    	$this->labelBlock('Csv Tests.', 60, '*');

    	$this->a_absoluteFileName($this->a_fileName_CSV);
    	$this->a_mode = 'w+';

    	$this->a_flags = 0;

    	$this->a_newFactoryObject($this->a_fileName, $this->a_adapter, $this->a_object);

    	$this->a_updateFlags();

    	$csvControl = array('?', ':');
    	$this->a_setCsvControl($csvControl);
    	$this->a_getCsvControl($csvControl);

    	$csvControl = array(',', '"');
    	$this->a_setCsvControl($csvControl);
    	$this->a_getCsvControl($csvControl);

    	$this->a_csvStringArray = array("jay,wheeler,1950-10-31\n",
		                            	"fran,wheeler,1949-07-30\n",
										"edward,wheeler,1980-11-01\n",
		                            	"michael,wheeler,1980-11-01\n",
										"murphy,thedog,1992-03-01\n",
										"oscar,thedog,2005-07-05");

		$this->a_buffer = implode("", $this->a_csvStringArray);
        $this->a_length = strlen($this->a_buffer);

        $this->a_fwrite(strlen($this->a_buffer));

        $this->a_destroyFileObject();

    	// *****************************************************

    	$this->a_absoluteFileName($this->a_fileName_CSV);
    	$this->a_mode = 'r';
    	$this->a_flags = 0;

    	$this->a_newFactoryObject($this->a_fileName, $this->a_adapter, $this->a_object);

    	$this->a_setFlagBit(\Library\FileIO\FileObject::READ_CSV);
    	$this->a_setFlagBit(\Library\FileIO\FileObject::SKIP_EMPTY);
    	$this->a_updateFlags();

    	$this->a_iteration();

		$this->a_rewind();

		$this->a_fgets(sprintf("%s\n", implode(',', $this->a_fileRecords[$this->a_lineNumber])));
		$this->a_fgetcsv($this->a_fileRecords[$this->a_lineNumber]);

		$this->a_fscanf("%[^,],%[^,],%[^,\n]", $this->a_fileRecords[$this->a_lineNumber], null);

		$this->a_rewind();
		$this->a_localArray = array('first', 'last', 'birthday');

		$this->a_fscanfFields("%[^,],%[^,],%[^,\n]", count($this->a_localArray), $this->a_localArray[0], $this->a_localArray[1], $this->a_localArray[2], false);

		$this->a_printArray($this->a_localArray);

		$this->a_destroyFileObject();
    }

    /**
     * a_lockTest
     *
     * flock test
     */
    public function a_lockTest()
    {
    	$this->labelBlock('File Lock Tests.', 60, '*');

    	$this->a_flags = 0;
    	$this->a_absoluteFileName($this->a_fileName_LOCK);

    	$this->a_mode = 'w+';
    	$this->a_newFactoryObject($this->a_fileName, $this->a_adapter, $this->a_object);
    	$this->a_updateFlags();

    	$file1 = $this->a_file;

    	$this->a_mode = 'r+';
    	$this->a_newFactoryObject($this->a_fileName, $this->a_adapter, $this->a_object);
    	$this->a_updateFlags();

		$file2 = $this->a_file;

    	$this->a_buffer = "File2 New line\nFile2 Another line\n";
        $this->a_length = strlen($this->a_buffer);

        $this->a_fwrite($this->a_length);

		$this->a_file = $file1;
		$this->a_flock(LOCK_EX | LOCK_NB, false);

    	$this->a_localArray = array("File1 Line 1\n", "File1 Line 2\n");

		$this->a_buffer = implode("", $this->a_localArray);
        $this->a_length = strlen($this->a_buffer);

        $this->a_fwrite($this->a_length);

		$this->a_foreach();
		$this->a_compareArray($this->a_fileRecords, false);

		$this->a_file = $file1;
		$this->a_destroyFileObject();

		$this->a_file = $file2;
        $this->a_destroyFileObject();

        // **************************************************

    	$this->a_flags = 0;

    	$this->a_mode = 'w+';
    	$this->a_newFactoryObject($this->a_fileName, $this->a_adapter, $this->a_object);
    	$this->a_updateFlags();

    	$file1 = $this->a_file;

    	$this->a_mode = 'r+';
    	$this->a_newFactoryObject($this->a_fileName, $this->a_adapter, $this->a_object);
    	$this->a_updateFlags();

		$file2 = $this->a_file;

		$this->a_flock(LOCK_EX | LOCK_NB, false);

		$this->a_buffer = "File2 New line\nFile2 Another line\n";
        $this->a_length = strlen($this->a_buffer);

        $this->a_fwrite($this->a_length);

		$this->a_file = $file1;
		$this->a_flock(LOCK_EX | LOCK_NB, false, false, false);

		$this->assertLogMessage("Unable to obtain lock.");

		$this->a_file = $file2;
		$this->assertLogMessage("Releasing lock.");

		$this->a_flock(LOCK_UN, false);

		$this->a_file = $file1;
		$this->a_flock(LOCK_EX | LOCK_NB, false);

        $this->a_localArray = array("File1 Line 1\n", "File1 Line 2\n");

		$this->a_buffer = implode("", $this->a_localArray);
        $this->a_length = strlen($this->a_buffer);

        $this->a_fwrite($this->a_length);

		$this->a_foreach();
		$this->a_compareArray($this->a_fileRecords, false);

		$this->a_file = $file1;
		$this->a_destroyFileObject();

		$this->a_file = $file2;
		$this->a_destroyFileObject();
    }

    /**
     * a_readTests
     *
     * test all types of file reading
     * @param integer $maxLineLen = (optional) maximum read line length
     */
    public function a_readTest($maxLineLen=null)
    {
    	$this->labelBlock('Read Tests.', 70, '*');

        if ($this->verbose > 1)
    	{
    		$this->a_showData($maxLineLen, 'maxLineLen');
    	}

    	$this->a_absoluteFileName($this->a_fileName_XML);
    	$this->a_mode = 'r';
    	$this->a_newFactoryObject($this->a_fileName, $this->a_adapter, $this->a_object);

    	if ($maxLineLen !== null)
    	{
    		$this->a_setMaxLineLen($maxLineLen);
    		$this->a_getMaxLineLen($maxLineLen);
    	}

    	$this->a_iteration();
		$this->a_rewind();

		$this->a_fgetc('<');
		$this->a_fgetc('P');

		$this->a_fgets(substr($this->a_fileRecords[$this->a_lineNumber], 2));

    	$this->a_destroyFileObject();
    }

    /**
     * a_iteration
     *
     * check iterator behavior
     * @param boolean $readAhead = (optional) read ahead flag, default = true
     */
    public function a_iteration($readAhead=true)
    {
    	$this->labelBlock('Iteration Tests.', 60, '*');

        if ($this->verbose > 1)
    	{
    		$this->a_showData($readAhead, 'readAhead');
    	}

    	if ($readAhead)
    	{
   			$this->a_setFlagBit(\Library\FileIO\FileObject::READ_AHEAD);
    	}
    	else
    	{
			$this->a_resetFlagBit(\Library\FileIO\FileObject::READ_AHEAD);
    	}

		$this->a_updateFlags();

    	$this->a_foreach();

    	$this->a_valid(false);

		$this->a_readAhead($readAhead);

		$this->a_reRead();

		$this->a_seek($this->a_lineNumber + 2);

		$this->a_lineNumber += 2;
		$this->a_key($this->a_lineNumber);

    	$this->a_current($this->a_fileRecords[$this->a_lineNumber]);
		$this->a_key($this->a_lineNumber);

		$this->a_seek(4);

		$this->a_lineNumber = 4;

		$this->a_key($this->a_lineNumber);

		$this->a_valid(true);
		$this->a_eof(false);

    	$this->a_current($this->a_fileRecords[$this->a_lineNumber]);
		$this->a_key($this->a_lineNumber);

		$this->a_seek(count($this->a_fileRecords));

		$this->a_lineNumber = count($this->a_fileRecords);
		$this->a_key($this->a_lineNumber, (get_class($this->a_file) != 'SplFileObject') && ($this->a_adapterName != 'splobject'));
    }

    /**
     * a_readAhead
     *
     * Read and readAhead to the next line
     * @param boolean $set = true to set read ahead, false to reset it.
     */
    public function a_readAhead($set=true)
    {
    	$this->labelBlock('ReadAhead Tests.', 50, '*');

        if ($this->verbose > 1)
    	{
    		$this->a_showData($set, 'set');
    	}

    	if ($set)
    	{
    		$this->a_setFlagBit(\Library\FileIO\FileObject::READ_AHEAD);
    	}
		else
		{
			$this->a_resetFlagBit(\Library\FileIO\FileObject::READ_AHEAD);
		}

		$this->a_updateFlags();

		$this->a_rewind();
		$this->a_key($this->a_lineNumber);

    	$this->a_current($this->a_fileRecords[$this->a_lineNumber]);
    	$this->a_key($this->a_lineNumber);

    	$this->a_next();
		$this->a_next();
		$this->a_next();

		$this->a_toString($this->a_fileRecords[$this->a_lineNumber], $set);
    	$this->a_key($this->a_lineNumber);

    	if ($set)
    	{
   			$this->a_resetFlagBit(\Library\FileIO\FileObject::READ_AHEAD);
    		$this->a_updateFlags();
    	}

		$this->labelBlock('END ReadAhead.', 40, '*');
    }

    /**
     * a_reRead
     *
     * Read and re-read the same line
     */
    public function a_reRead()
    {
    	$this->labelBlock('Re-read Tests.', 60, '*');

    	$this->a_rewind();
		$this->a_key($this->a_lineNumber);

    	$this->a_current($this->a_fileRecords[$this->a_lineNumber]);
    	$this->a_key($this->a_lineNumber);

    	$this->a_current($this->a_fileRecords[$this->a_lineNumber]);
    	$this->a_key($this->a_lineNumber);

    	$this->labelBlock('END Re-read.', 40, '*');
    }

    /**
     * a_foreach
     *
     * iterate over the file
     */
    public function a_foreach()
    {
    	$this->labelBlock('FOREACH.', 40, '*');

		$flags = 0;

    	$this->a_fileRecords = array();
    	foreach($this->a_file as $key => $line)
    	{
    		$this->a_fileRecords[$key] = $line;
    		if ($this->a_flags & \Library\FileIO\FileObject::READ_CSV)
    		{
    			$this->assertLogMessage(rtrim(FormatVar::format($line, sprintf('Row %u', $key))));
    		}
    		else
    		{
    			$this->assertLogMessage(sprintf('%03u : %s', $key, rtrim($this->a_fileRecords[$key])));
    		}
    	}
    }

    /**
     * a_getChildren
     *
     * get children - s/b null
     */
    public function a_getChildren()
    {
    	$this->labelBlock('getChildren.', 40, '*');

    	$assertion = '(! $this->a_file->getChildren())';
		if (! $this->assertTrue($assertion, sprintf('getChildren - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_hasChildren
     *
     * get 'hasChildren' status - s/b false
     */
    public function a_hasChildren()
    {
    	$this->labelBlock('hasChildren.', 40, '*');

    	$assertion = '($this->a_file->hasChildren() === false)';
		if (! $this->assertTrue($assertion, sprintf('hasChildren - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_getCurrentLine
     *
     * get next line from the file
     * @param string $expected = expected result
     * @param boolean $result = (optional) true or false (default = true)
     */
    public function a_getCurrentLine($expected, $compare=true, $result=true)
    {
    	$this->labelBlock('getCurrentLine.', 40, '*');

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    		$this->a_showData($compare, 'compare');
    		$this->a_showData($result, 'result');
    	}

    	if ($result)
    	{
    		$assertion = '(($this->a_data = $this->a_file->getCurrentLine()) !== false)';
    	}
    	else
    	{
	    	$assertion = '(($this->a_data = $this->a_file->getCurrentLine()) === false)';
    	}

		if (! $this->assertExceptionTrue($assertion, sprintf('getCurrentLine - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType($compare, $expected);

		$this->a_lineNumber++;
    }

    /**
     * a_flock
     *
     * lock/unlock a file
     * @param integer $operation = lock operation (LOCK_SH, LOCK_EX, LOCK_UN, LOCK_NB)
     * @param boolean $wouldBlock = (optional) default = false
     */
    public function a_flock($operation, $wouldBlock=false, $test=true, $expected=true)
    {
    	$this->labelBlock('flock.', 40, '*');

        if ($this->verbose > 1)
    	{
    		$this->a_showData($operation, 'operation');
    		$this->a_showData($wouldBlock, 'wouldBlock');
    		$this->a_showData($test, 'test');
    		$this->a_showData($expected, 'expected');
    	}

    	$this->a_lockOperation = $operation;
    	$this->a_wouldBlock = $wouldBlock;

    	$assertion = '$this->a_data = $this->a_file->flock($this->a_lockOperation, $this->a_wouldBlock)';
    	if ($test)
    	{
			if (! $this->assertTrue($assertion, sprintf('flock - Asserting: %s', $assertion)))
			{
    			$this->a_showData($this->a_data, 'a_data');
				$this->a_outputAndDie();
			}
    	}
    	else
    	{
			if (! $this->assertFalse($assertion, sprintf('flock - Asserting: %s', $assertion)))
			{
    			$this->a_showData($this->a_data, 'a_data');
				$this->a_outputAndDie();
			}
    	}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

    	$this->a_compareExpected($expected);
    }

    /**
     * a_fflush
     *
     * force a write of any data remaining in the output buffer
     * @param boolean $expected = (optional) expected result, default = true
     */
    public function a_fflush($expected=true)
    {
    	$this->labelBlock('fflush.', 40, '*');

        if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '$this->a_data = $this->a_file->fflush()';
		if (! $this->assertTrue($assertion, sprintf('fflush - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_compareExpected($expected);
    }

    /**
     * a_fsize
     *
     * get size of the current file
     * @param integer $expected = (optional) expected result, none = null
     */
    public function a_fsize($expected=null)
    {
    	$this->labelBlock('fsize.', 40, '*');

        if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '$this->a_data = $this->a_file->getSize()';
		if (! $this->assertExceptionTrue($assertion, sprintf('fsize - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		if ($expected != null)
		{
			$this->a_compareExpected($expected);
		}
    }

    /**
     * a_fwrite
     *
     * Write a string to the open file
     * @param string $buffer = data to write
     * @param integer $expected = expected write result (bytes written)
     * @param integer $length = (optional) length to write - if null, the complete buffer is written
     */
	public function a_fwrite($expected=null, $length=null)
	{
    	$this->labelBlock('fwrite.', 40, '*');

   		$this->a_showData($expected, 'expected');
   		$this->a_showData($length, 'length');
   		if ($expected !== null)
   		{
   			$this->a_showData(substr($this->a_buffer, 0, $expected), 'a_buffer');
   		}

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
			$this->a_outputAndDie();
		}

		if (! $this->a_binary)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		if ($expected !== null)
		{
			$this->a_compareExpected($expected);
		}
	}

    /**
     * a_fscanfFields
     *
     * read next record according to a format template
     * @param string $format = format command
     * @param mixed $expected = expected result
     * @param array $fields = (optional) array of variables to assign associated field value to, default = null
     */
	public function a_fscanfFields($format, $expected, &$field0, &$field1, &$field2, $type=true)
	{
		$this->labelBlock('fscanf Fields.', 40, '*');

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($format, 'format');
    		$this->a_showData($expected, 'expected');
    		$this->a_showData($field0, 'field0');
    		$this->a_showData($field1, 'field1');
    		$this->a_showData($field2, 'field2');
    		$this->a_showData($type, 'type');
    	}

		$this->a_buffer = $format;

		$this->a_field0 =& $field0;
		$this->a_field1 =& $field1;
		$this->a_field2 =& $field2;

		$assertion = '(($this->a_data = $this->a_file->fscanf($this->a_buffer, $this->a_field0, $this->a_field1, $this->a_field2)) !== -1);';
		if (! $this->assertExceptionTrue($assertion, sprintf('fscanf Fields- Asserting: %s', $assertion)))
		{
			if (! $type)
			{
				return;
			}

			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($type)
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpected(3);
	}

    /**
     * a_newMethodTest
     *
     * Create a new class method object
     */
    public function a_newMethodTest($class, $instance, $method)
    {
    	$this->labelBlock('Creating NEW methodTest object.', 40, '*');

        if ($this->verbose > 1)
    	{
    		$this->a_showData($class, 'class');
    		$this->a_showData($instance, 'instance');
    		$this->a_showData($method, 'method');
    	}

    	$this->a_testInstance = $instance;
    	$assertion = sprintf('(get_class($this->a_methodTest = new %s($this->a_testInstance, "%s")) == "%s");', $class, $method, $class);

		if (! $this->assertExceptionTrue($assertion, sprintf("NEW MethodTest %s - Asserting: %s", $class, $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_fscanf
     *
     * read next record according to a format template
     * @param string $format = format command
     * @param mixed $expected = expected result
     * @param array $fields = (optional) array of variables to assign associated field value to, default = null
     */
    public function a_fscanf($format, $expected, $fields=null)
    {
    	$this->labelBlock('fscanf.', 40, '*');

		if ($this->verbose > 1)
    	{
    		$this->a_showData($format, 'format');
    		$this->a_showData($expected, 'expected');
    		$this->a_showData($fields, 'fields');
    	}

    	$this->a_buffer = $format;

    	if ($fields != null)
    	{
    		$this->a_fields = $fields;
    		$assertion = '$this->a_data = $this->a_file->fscanf($this->a_buffer, $this->a_fields)';
    	}
    	else
    	{
    		$assertion = '$this->a_data = $this->a_file->fscanf($this->a_buffer)';
    	}

		if (! $this->assertExceptionTrue($assertion, sprintf('fscanf - Asserting: %s', $assertion)))
		{
			if (($fields === null) || (($fields !== null) && ($this->a_data !== 0)))
			{
    			$this->a_showData($this->a_data, 'a_data');
				$this->a_outputAndDie();
			}
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_compareExpectedType(true, $expected);

		if (is_array($this->a_data) && ($this->verbose < 2))
		{
			$this->a_printArray($this->a_data);
		}
    }

	/**
     * fpassthru
     *
     * Output all remaining data on a file
     * @param integer $expected = expected result
     * @param boolean $type = (optional) comparison type, default = true
     */
    public function a_fpassthru($expected, $type=true)
    {
    	$this->labelBlock('fpassthru.', 40, '*');

    	if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    		$this->a_showData($type, 'type');
    	}

    	ob_start();

    	$assertion = '$this->a_data = $this->a_file->fpassthru();';
		if (! $this->assertTrue($assertion, sprintf('fpassthru - Asserting: %s', $assertion)))
		{
			if ($this->a_data === false)
			{
				$this->assertLogMessage(ob_get_clean());
    			$this->a_showData($this->a_data, 'a_data');
				$this->a_outputAndDie();
			}
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->assertLogMessage(sprintf("fpassthru buffer:\n%s", ob_get_clean()));

		$this->a_compareExpectedType($type, $expected);
    }

    /**
     * fgetc
     *
     * get next character from the file
     * @param string $expected = expected result
     */
    public function a_fgetc($expected)
    {
    	$this->labelBlock('fgetc.', 40, '*');

   	    if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '(($this->a_data = $this->a_file->fgetc()) !== false)';
		if (! $this->assertTrue($assertion, sprintf('fgetc - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_compareExpected($expected);
		if ($this->a_data === PHP_EOL)
		{
			$this->a_lineNumber++;
		}
    }

    /**
     * fgetss
     *
     * get next line from the file and strip html/php tags
     * @param string $expected = expected result
     */
    public function a_fgetss_false($expected)
    {
    	$this->labelBlock('fgetss_false.', 40, '*');

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '$this->a_data = $this->a_file->fgetss();';
		if (! $this->assertExceptionTrue($assertion, sprintf('fgetss - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_exceptionCaughtFalse();

		$assertion = '($this->a_data == $expected)';
		if (! $this->assertFalse($assertion, sprintf('Falsely Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_lineNumber++;
    }

    /**
     * fgetss
     *
     * get next line from the file and strip html/php tags
     * @param string $expected = expected result
     */
    public function a_fgetss($expected)
    {
    	$this->labelBlock('fgetss.', 40, '*');

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '$this->a_data = $this->a_file->fgetss();';
		if (! $this->assertExceptionTrue($assertion, sprintf('fgetss - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_exceptionCaughtFalse();

		$assertion = '($this->a_data == $expected)';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_lineNumber++;
    }

    /**
     * fgets
     *
     * get next line from the file
     * @param string $expected = expected result
     * @param string $compare = type of comparison to perform with $expected (equal = true, not equal = false)
     * @param boolean $result = fgets returned result: true or false (default = true)
     */
    public function a_fgets($expected, $compare=true, $result=true)
    {
    	$this->labelBlock('fgets.', 40, '*');

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    		$this->a_showData($compare, 'compare');
    		$this->a_showData($result, 'result');
    	}

    	if ($result)
    	{
    		$assertion = '(($this->a_data = $this->a_file->fgets()) !== false)';
    	}
    	else
    	{
	    	$assertion = '(($this->a_data = $this->a_file->fgets()) === false)';
    	}

		if (! $this->assertExceptionTrue($assertion, sprintf('fgets - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType($compare, $expected);

		$this->a_lineNumber++;
    }

    /**
     * fgetcsv
     *
     * get next csv array from the file
     * @param string $expected = expected result
     */
    public function a_fgetcsv($expected)
    {
    	$this->labelBlock('FGet CSV.', 40, '*');

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '(($this->a_data = $this->a_file->fgetcsv()) !== false)';
		if (! $this->assertExceptionTrue($assertion, sprintf('FGet CSV - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType(true, $expected);

		$this->a_lineNumber++;
    }

    /**
     * fputcsv
     *
     * put csv array to the file
     * @param array $csvArray = csv array to output
     * @param integer $expected = expected buffer length
     */
    public function a_fputcsv($csvArray, $expected)
    {
    	$this->labelBlock('fputcsv.', 40, '*');

    	$this->a_csvArray = $csvArray;

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($csvArray, 'csvArray');
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '(($this->a_data = $this->a_file->fputcsv($this->a_csvArray)) !== false)';
		if (! $this->assertExceptionTrue($assertion, sprintf('fputcsv - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpected($this->a_data);

		$this->a_lineNumber++;
    }

    /**
     * a_ftruncate
     *
     * truncate the file
     * @param integer $size = size in bytes to truncate the file to
     */
    public function a_ftruncate($size)
    {
    	$this->labelBlock('ftruncate.', 40, '*');

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($size, 'size');
    	}

    	$assertion = sprintf('$this->a_file->ftruncate(%d);', $size);
		if (! $this->assertExceptionTrue($assertion, sprintf('ftruncate - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_fstat
     *
     * Get the current file stat array
     */
    public function a_fstat()
    {
    	$this->labelBlock('fstat.', 40, '*');

    	$assertion = '$this->a_localArray = $this->a_file->fstat();';
		if (! $this->assertExceptionTrue($assertion, sprintf('fstat - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_localArray, 'a_localArray');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_localArray, 'a_localArray');
		}

		$this->a_printArray($this->a_localArray);
    }

    /**
     * a_ftell
     *
     * Get the current byte offset into the file
     * @param integer $expected = exected byte number
     */
    public function a_ftell($expected)
    {
    	$this->labelBlock('ftell.', 40, '*');

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '(($this->a_data = $this->a_file->ftell()) !== false);';
		if (! $this->assertExceptionTrue($assertion, sprintf('ftell - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_compareExpected($expected);
    }

    /**
     * a_fseek
     *
     * Seek to the requested byte
     * @param integer $byte = byte number to seek to
     * @param integer $whende = SEEK_SET, SEEK_CUR or SEEK_END
     */
    public function a_fseek($byte, $whence=SEEK_SET)
    {
    	$this->labelBlock('fseek.', 40, '*');

    	$this->a_data = $byte;
    	if ($this->verbose > 1)
    	{
    		$this->a_showData($byte, 'byte');
    		$this->a_showData($whence, 'whence');
    	}

    	$assertion = sprintf('($this->a_file->fseek(%d, %u) !== -1);', $this->a_data, $whence);
		if (! $this->assertExceptionTrue($assertion, sprintf('fseek %u - Asserting: %s', $this->a_data, $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_seek
     *
     * Seek to the requested record
     * @param integer $lineNumber = line number to seek to
     * @param boolean $except = false (default) to not expect exception, true to expect exception
     */
    public function a_seek($lineNumber, $except=false)
    {
    	$this->labelBlock('seek.', 40, '*');

    	$this->a_data = $lineNumber;
    	if ($this->verbose > 1)
    	{
    		$this->a_showData($lineNumber, 'lineNumber');
    		$this->a_showData($except, 'except');
    	}

    	$assertion = '$this->a_file->seek($this->a_data)';
    	if ($except)
    	{
			if (! $this->assertExceptionTrue($assertion, sprintf('seek %u - Asserting: %s', $this->a_data, $assertion)))
			{
				$this->a_outputAndDie();
			}

			$this->a_exceptionCaughtTrue();
    	}
    	else
    	{
			if (! $this->assertExceptionFalse($assertion, sprintf('seek %u - Asserting: %s', $this->a_data, $assertion)))
			{
				$this->a_outputAndDie();
			}

			$this->a_exceptionCaughtFalse();
    	}
    }

    /**
     * a_eof
     *
     * Check the file eof status
     */
    public function a_eof($expected=false)
	{
    	$this->labelBlock('EOF.', 40, '*');

	   	if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '$this->a_data = $this->a_file->eof();';
		if ($expected)
		{
	    	if (! $this->assertTrue($assertion, sprintf('EOF True - Asserting: %s', $assertion)))
			{
				$this->a_showData($this->a_data, 'a_data');
				$this->a_outputAndDie();
			}
		}
		else
		{
	    	if (! $this->assertFalse($assertion, sprintf('EOF False - Asserting: %s', $assertion)))
			{
				$this->a_showData($this->a_data, 'a_data');
				$this->a_outputAndDie();
			}
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_compareExpected($expected);
	}

    /**
     * a_valid
     *
     * Check the file validity (eof status)
     */
    public function a_valid($expected)
    {
    	$this->labelBlock('valid.', 40, '*');

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '$this->a_data = $this->a_file->valid();';
		if ($expected)
		{
	    	if (! $this->assertTrue($assertion, sprintf('Valid True - Asserting: %s', $assertion)))
			{
				$this->a_showData($this->a_data, 'a_data');
				$this->a_outputAndDie();
			}
		}
		else
		{
	    	if (! $this->assertFalse($assertion, sprintf('Valid False - Asserting: %s', $assertion)))
			{
				$this->a_showData($this->a_data, 'a_data');
				$this->a_outputAndDie();
			}
		}

    	if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_compareExpected($expected);
    }

    /**
     * a_rewind
     *
     * Rewind the file to the first line
     */
    public function a_rewind()
    {
    	$this->labelBlock('rewind.', 40, '*');

    	$assertion = '$this->a_file->rewind();';
		if (! $this->assertExceptionFalse($assertion, sprintf('Rewind - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_lineNumber = 0;
    }

    /**
     * a_key
     *
     * Get the current line number
     * @param integer $expected = expected line number
     */
    public function a_key($expected, $type=true)
    {
    	$this->labelBlock('key.', 40, '*');

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    		$this->a_showData($type, 'type');
    	}

    	$assertion = '(($this->a_data = $this->a_file->key()) !== false);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Key - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType($type, $expected);
    }

    /**
     * a_next
     *
     * Move to the next record
     */
    public function a_next()
    {
    	$this->labelBlock('next.', 40, '*');

    	$assertion = '$this->a_file->next();';
		if (! $this->assertFalse($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_lineNumber++;
    }

    /**
     * a_current
     *
     * Get the current record
     */
    public function a_current($expected, $type=true)
    {
    	$this->labelBlock('current.', 40, '*');

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    		$this->a_showData($type, 'type');
    	}

    	$assertion = '$this->a_data = $this->a_file->current();';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_compareExpectedType($type, $expected);
    }

    /**
     * a_toString
     *
     * Test the __toString magic function
     * @param string $expected = expected value
     * @param boolean $type = (optional) true or false
     */
    public function a_toString($expected, $type=true)
    {
    	$this->labelBlock('toString.', 40, '*');

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    		$this->a_showData($type, 'type');
    	}

    	$assertion = '$this->a_data = $this->a_file->__toString();';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_compareExpectedType($type, $expected);
    }

    /**
     * a_setFlagBit
     *
     * Set the bit in the flags variable
     * @param integer $bit = bit number(s) to set
     */
    public function a_setFlagBit($bit)
    {
    	$this->a_flags |= $bit;
    }

    /**
     * a_resetFlagBit
     *
     * Reset the bit in the flags variable
     * @param integer $bit = bit number(s) to reset
     */
    public function a_resetFlagBit($bit)
    {
		$this->a_flags &= ~ $bit;
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
     * a_setMaxLineLen
     *
     * Set maximum line length
     * @param integer $maxLineLen = maximum line length in bytes
     */
    public function a_setMaxLineLen($maxLineLen)
    {
    	$this->labelBlock('setMaxLineLen.', 40, '*');

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($maxLineLen, 'maxLineLen');
    	}

    	$this->a_data = $maxLineLen;
    	$assertion = '$this->a_file->setMaxLineLen($this->a_data);';
		if (! $this->assertFalse($assertion, sprintf('setMaxLineLen - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

    }

    /**
     * a_getMaxLineLen
     *
     * Get maximum line length value
     * @param integer $expected = expected csv flags array
     */
    public function a_getMaxLineLen($expected)
    {
    	$this->labelBlock('getMaxLineLen.', 40, '*');

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '$this->a_data = $this->a_file->getMaxLineLen();';
		if (! $this->assertTrue($assertion, sprintf('getMaxLineLen - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}

		$this->a_compareExpected($expected);
    }

    /**
     * a_setCsvControl
     *
     * Set csv control array
     * @param array $control = csv control array
     */
    public function a_setCsvControl($control)
    {
    	$this->labelBlock('setCsvControl.', 40, '*');

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($control, 'control');
    	}

    	$this->a_localArray = $control;
    	$assertion = '$this->a_file->setCsvControl($this->a_localArray[0], $this->a_localArray[1]);';
		if (! $this->assertFalse($assertion, sprintf('setCsvControl - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_getCsvControl
     *
     * Get csv control flags
     * @param array $expected = expected csv flags array
     */
    public function a_getCsvControl($expected)
    {
    	$this->labelBlock('getCsvControl.', 40, '*');

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '$this->a_localArray = $this->a_file->getCsvControl();';
		if (! $this->assertTrue($assertion, sprintf('getCsvControl - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_localArray, 'a_localArray');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_localArray, 'a_localArray');
		}

		$this->a_compareArray($expected, true);
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

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '(($this->a_data = $this->a_file->getFlags()) !== false)';
		if (! $this->assertTrue($assertion, sprintf('getFlags - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
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

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($flags, 'flags');
    	}

    	$this->a_data = $flags;
    	$assertion = '$this->a_file->setFlags($this->a_data);';
		if (! $this->assertFalse($assertion, sprintf('setFlags %o - Asserting: %s', $flags, $assertion)))
		{
			$this->a_outputAndDie();
		}
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
		if (! $this->assertFalse($assertion, sprintf("DESTROY %s - Asserting: %s", $this->a_objectClass, $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_newFactoryObject
     *
     * Create a new FileIO Object object
     */
    public function a_newFactoryObject($fileName, $adapterName=null, $objectName=null)
    {
    	$this->labelBlock('Creating NEW FileIO Factory Object.', 40, '*');

    	$this->a_file = null;

    	$this->a_adapterName = $adapterName;
		$this->a_objectName = $objectName;
		$this->a_fileName = $fileName;

        $this->a_showData($this->a_adapterName, 'a_adapterName');
    	$this->a_showData($this->a_objectName, 'a_objectName');
    	$this->a_showData($this->a_fileName, 'a_fileName');

    	if ($this->a_adapterName)
    	{
    		$assertion = sprintf('(($this->a_file = \Library\FileIO\Factory::instantiateClass("%s", "%s", "%s", $this->a_mode, $this->a_includePath)) != null);',
	    						 $this->a_objectName,
    							 $this->a_adapterName,
    							 $this->a_fileName);
    	}
    	else
    	{
    		$assertion = sprintf('(($this->a_file = \Library\FileIO\Factory::instantiateClass("%s", "%s", $this->a_mode, $this->a_includePath)) != null);',
	    						 $this->a_objectName,
    							 $this->a_fileName);
    	}

		if (! $this->assertExceptionTrue($assertion, sprintf("NEW FileIO Factory Object - Asserting: %s", $assertion)))
		{
			$this->a_showData($this->a_file, 'a_file');
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_file, 'a_file');

		$this->a_exceptionCaughtFalse();
		$this->a_compareExpectedType(true, \Library\FileIO\Factory::getInstance()->className($this->a_objectName), get_class($this->a_file));

		$this->a_lineNumber = 0;
    }

}
