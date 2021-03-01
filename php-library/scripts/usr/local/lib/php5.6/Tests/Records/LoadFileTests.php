<?php
namespace Tests\Records;
use Library\CliParameters;

/*
 *		Records\LoadFileTests is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, 
 *            or from http://opensource.org/licenses/academic.php
*/
/**
 * LoadFileTests
 *
 * Records\LoadFileTests Process class test.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Records
 */
class LoadFileTests extends \Application\Launcher\Testing\UtilityMethods
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
     * @param string $logger = (optional) name of the logger to use, null for none
     * @param string $format = (optional) log output format 
     */
    public function assertionTests($logger=null, $format=null)
    {
    	parent::assertionTests($logger, $format);
    	
    	$fileName = CliParameters::parameterValue('file', '/Tests/standardTests');
		$fileName = $this->a_absoluteFileName($fileName);

		$this->a_recordsStack = null;
		$this->a_includedStack = null;

    	$this->a_newLoadFile($fileName);
    	$this->a_isPrepared(true);

    	$this->a_getRecordsStack();

    	$this->a_recordsCount();
    	$this->a_countRecords = $this->a_data;
    	
    	$this->a_getIncludedStack();

    	$this->a_includedCount(1);
    	$this->a_countIncluded = $this->a_data;

    	$this->a_forEach();

    	$this->a_includeRecords('all');
		$this->a_countIncluded = $this->a_data;

    	$this->a_includedStack = null;

    	$this->a_getIncludedStack();

    	$this->a_isPrepared(false);

    	$this->a_includedCount($this->a_countIncluded);
    	$this->a_countIncluded = $this->a_data;

    	$this->a_excludeRecords('5,9,10-15');
    	$this->a_compareExpected(($this->a_countIncluded - 8), true);
		$this->a_countIncluded -= 8;

    	$this->a_getIncludedStack();

		$this->a_isPrepared(true);

    	$this->a_forEach();

    	$this->a_getIncludedRecord(32);

    	$this->a_getRecord(21);

    	$this->a_deleteLoadFile();
    }

    /**
     * a_getRecord
     *
     * Get the requested record
     * @param integer $recordNumber
     */
    public function a_getRecord($recordNumber)
    {
    	$this->labelBlock('GetRecord', 60);

    	$this->a_getIncludedRecord($recordNumber);

    	$this->a_recordNumber = $this->a_includedRecordNumber;
    	$this->a_showData($this->a_recordNumber, 'RecordNumber');
    	 
    	$assertion = '(($this->a_data = $this->a_recordsStack[$this->a_recordNumber]) !== null);';
    	if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}
    	 
    	$this->a_exceptionCaughtFalse();
		$this->a_showData($this->a_data, 'Record');    
    }

    /**
     * a_getIncludedRecord
     * 
     * Get the requested record
     * @param integer $recordNumber
     */
    public function a_getIncludedRecord($recordNumber)
    {
    	$this->labelBlock('GetIncludedRecord', 40);
    	
    	$this->a_includedRecordNumber = $recordNumber;
    	$this->a_showData($this->a_includedRecordNumber, 'RecordNumber');
    	
    	$assertion = '(($this->a_data = $this->a_loadFile[$this->a_includedRecordNumber]) !== null);';
    	if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}
    	
    	$this->a_exceptionCaughtFalse();

    	$this->a_compareExpected($this->a_includedRecordNumber, true);
    }

    /**
     * a_recordsCount
     * 
	 * Get the count of records in the records stack
     * @param string $expected = (optional) expected count of records
     */
    public function a_recordsCount($expected=null)
    {
    	$this->labelBlock('recordsCount', 40);

    	$this->a_expected = $expected;
    	$this->a_showData($this->a_expected, 'Expected');

    	$assertion = '(($this->a_data = count($this->a_loadFile->records())) > 0);';
    	if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}

    	$this->a_exceptionCaughtFalse();
    	
    	if ($this->a_expected === null)
    	{
    		$this->a_showData($this->a_data, 'Count');
    	}
    	else
    	{
    		$this->a_compareExpectedType(true, $this->a_expected);
    	}
    }

    /**
     * a_includedCount
     * 
	 * Get the count of records in the includedRecords stack
     * @param string $expected = expected count of included records
     */
    public function a_includedCount($expected)
    {
    	$this->labelBlock('includedCount', 40);

    	$this->a_expected = $expected;
    	$this->a_showData($this->a_expected, 'Expected');

    	$assertion = '(($this->a_data = count($this->a_loadFile->includedRecords())) > 0);';
    	if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}

    	$this->a_exceptionCaughtFalse();
    	
    	$this->a_compareExpectedType(true, $this->a_expected);
    }

    /**
     * a_excludeRecords
     * 
	 * Set the range of records to exclude from the original records
     * @param string $list = list of records to be excluded
     */
    public function a_excludeRecords($list)
    {
    	$this->labelBlock('excludeRecords', 40);

		$this->a_list = $list;
    	$this->a_showData($this->a_list, 'Exclude Records');

    	$assertion = '(($this->a_data = $this->a_loadFile->excludeRecords($this->a_list)) !== null);';
    	if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}

    	$this->a_exceptionCaughtFalse();
    	
    	$this->a_showData($this->a_data, 'Count');
    }

    /**
     * a_includeRecords
     * 
	 * Set the range of records to include from the original records
     * @param string $list = list of records to be included
     */
    public function a_includeRecords($list)
    {
    	$this->labelBlock('includeRecords', 40);

		$this->a_list = $list;
    	$this->a_showData($this->a_list, 'Include Records');

    	$assertion = '(($this->a_data = $this->a_loadFile->includeRecords($this->a_list)) !== null);';
    	if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}

    	$this->a_exceptionCaughtFalse();
    	
    	$this->a_showData($this->a_data, 'Count');
    }

    /**
     * a_isPrepared
     * 
	 * Test the prepared flag
     * @param boolean $expected = (optional) expected setting (default = true)
     */
    public function a_isPrepared($expected=true)
    {
    	$this->labelBlock('isPrepared', 40);

		$this->a_prepared = $expected;
    	$this->a_showData($this->a_prepared, 'Expected');

    	$assertion = '(($this->a_data = $this->a_loadFile->isPrepared()) === $this->a_prepared);';
    	if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}

    	$this->a_compareExpected($this->a_prepared, true);
    }

    /**
	 * a_forEach
	 *
	 * Iterate through the directory
	 */
	public function a_forEach()
	{
		$this->labelBlock('ForEach.', 40, '*');

		foreach($this->a_loadFile as $key => $value)
		{
			$this->assertLogMessage(sprintf('%s = %s', $key + 1, $value));
		}
	}

    /**
     * a_getRecordsStack
     *
     * Get the records stack object
     */
    public function a_getRecordsStack()
    {
    	$this->labelBlock('Get RecordsStack', 60);

    	$assertion = '(($this->a_data = $this->a_loadFile->records()) !== null);';
    	if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}
    	 
    	$this->a_exceptionCaughtFalse();

    	$this->a_recordsStack = $this->a_data;
    	$this->a_showData($this->a_recordsStack, 'RecordsStack');
    }

    /**
     * a_getIncludedStack
     *
     * Get the includedRecords stack object
     */
    public function a_getIncludedStack()
    {
    	$this->labelBlock('Get IncludedStack', 60);

    	$assertion = '(($this->a_data = $this->a_loadFile->includedRecords()) !== null);';
    	if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}
    	 
    	$this->a_exceptionCaughtFalse();

    	$this->a_includedStack = $this->a_data;
    	$this->a_showData($this->a_includedStack, 'IncludedRecordsStack');
    }

	/**
	 * a_deleteLoadFile
	 * 
	 * Delete the LoadFile object
	 */
	public function a_deleteLoadFile()
	{
		$this->labelBlock('Delete Records\LoadFile', 40);
		
		$assertion = '(($this->a_loadFile = null) === null);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->assertLogMessage('Expected true, result is false');
			exit(1);
		}
		
		$this->a_exceptionCaughtFalse();
	}

	/**
     * a_newLoadFile
     * 
     * Create a new instance of Records\LoadFile
     * @param string $fileName = (optional) name of the file to load records from
     * @param string $includes = (optional) list of records to include, default = 1
     * @param string $excludes = (optional) list of records to exclude, default = null
     */
    public function a_newLoadFile($fileName=null, $includes=1, $excludes=null)
    {
    	$this->labelBlock('New Records\LoadFile', 40);

    	$this->a_fileName = $fileName;
    	$this->a_includes = $includes;
    	$this->a_excludes = $excludes;

    	$this->a_showData($this->a_fileName, 'FileName');
    	$this->a_showData($this->a_includes, 'includeList');
    	$this->a_showData($this->a_excludes, 'excludeList');

    	$assertion = '(($this->a_loadFile = new \Library\Records\LoadFile($this->a_fileName, $this->a_includes, $this->a_excludes)) !== null);';
    	if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
    	{
    		$this->assertLogMessage('Expected true, result is false');
    		exit(1);
    	}

    	$this->a_exceptionCaughtFalse();
    	 
    	$this->a_compareExpectedType(true, get_class($this->a_loadFile), 'Library\Records\LoadFile');
    }

}
