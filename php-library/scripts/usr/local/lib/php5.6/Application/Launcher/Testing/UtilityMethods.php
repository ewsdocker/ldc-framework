<?php
namespace Application\Launcher\Testing;
use Application\Utility\Support;

use Library\Error;
use Library\Utilities\FormatVar;

/*
 *		UtilityMethods is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *      	or from http://opensource.org/licenses/academic.php
*/
/**
 * UtilityMethods
 *
 * Some utility classes for use with Application\Launcher\Testing classes - intended to be extended by process class
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Application
 * @subpackage Launcher
 */
class UtilityMethods extends Base
{
	/**
	 * utility_os
	 *
	 * Contains the name of the current operating system, in lower case characters.
	 * @var string utility_os
	 */
	protected $utility_os;

	/**
	 * __construct
	 *
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->utility_os = strtolower(php_uname('s'));

		FormatVar::sort(false);
		FormatVar::sortValues(false);
		FormatVar::recurse(true);
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
     * @param string $format = (optional) output format name, null to not assign
     */
    public function assertionTests($logger=null, $format=null)
    {
    	$this->eolSequence = $this->properties->Eol_Sequence;
    	if ($logger != null)
    	{
			$this->logger->startLogger($logger, $format);
    	}

    	$this->assertFailures(false);
    }

    /**
     * a_compareArray
     *
     * Compare with local array
     * @param array $array = array to compare with local array
     * @param boolean $type = (optional) type of comparison (true or false), default = true
     * @param array $localArray = (optional) local array to compare $array to
     */
    public function a_compareArray($array, $type=true, $localArray=null)
    {
    	$this->labelBlock('Compare Array.', 40, '*');

    	$this->a_array = $array;

    	if ($localArray)
    	{
    		$this->a_localArray = $localArray;
    	}

	    $assertion = '$this->a_identicalArrays($this->a_localArray, $this->a_array);';

    	if ($this->a_type = $type)
    	{
			if (! $this->assertTrue($assertion, sprintf('Compare arrays TRUE - Asserting: %s', $assertion)))
			{
				$this->a_printArray($this->a_localArray, 'localArray');
				$this->a_printArray($this->a_array, 'array');
				$this->a_outputAndDie();
			}
    	}
    	else
    	{
			if (! $this->assertFalse($assertion, sprintf('Compare arrays FALSE - Asserting: %s', $assertion)))
			{
				$this->a_printArray($this->a_localArray, 'localArray');
				$this->a_printArray($this->a_array, 'array');
				$this->a_outputAndDie();
			}
    	}
    }

    /**
     * a_identicalArrays
     *
     * Compare fields in $compareArray with fields in $array - if the two arrays are identical, return true
     * If a class object is in the array, the names of the classes will be compared
     * NOTE: This may be slow, but it is more accurate and less prone to errors
     * @param array $array = array to compare to
     * @param array $compareArray = array to compare from
     * @return boolean $result = true if identical, false if not
     */
    public function a_identicalArrays($array, $compareArray)
    {
    	if ((! is_array($array)) || (! is_array($compareArray)) || (count($array) !== count($compareArray)))
    	{
    		return false;
    	}

    	foreach($array as $index => $field)
    	{
    		if (! array_key_exists($index, $compareArray))
    		{
    			return false;
    		}

    		$from = $compareArray[$index];

    		if (is_object($field))
    		{
    			if (! is_object($from))
    			{
    				return false;
    			}

    			$from = get_class($from);
    			$field = get_class($field);
    		}

    		if ($from !== $field)
    		{
    			return false;
    		}
    	}

    	return true;
    }

    /**
     * a_compareExpectedType
     *
     * Compare the value in a_data with the expected result, and expected type
     * @param boolean $type = type of comparison (true or false)
     * @param mixed $expected = expected result
     * @param mixed $data = (optional) data to compare to, a_data is used if null
     */
    public function a_compareExpectedType($type, $expected, $data=null)
    {
    	$this->labelBlock('Compare expected type.', 40, '*');

    	if ($expected === null)
    	{
    		return;
    	}

    	if ($data !== null)
    	{
    		$this->a_data = $data;
    	}

    	if (is_array($this->a_data) && is_array($expected))
    	{
			$this->a_localArray = $this->a_data;
			$this->a_compareArray($expected, $type);
    	}
		else
		{
   			$this->a_compareExpected($expected, $type);
		}
    }

    /**
     * a_compareExpected
     *
     * Compare the value in a_data with the expected result
     * @param mixed $expected = expected result
     * @param boolean $type = type of comparison (true or false)
     */
    public function a_compareExpected($expected, $type=true)
    {
    	$this->labelBlock('Compare expected.', 40, '*');

    	if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    		$this->a_showData($type, 'type');
    		$this->a_showData($this->a_data, 'a_data');
    	}

    	$this->a_expected = $expected;
		if (substr($this->a_expected, 0, 1) == '\\' && substr($this->a_data, 0, 1) !== '\\')
		{
			$this->a_expected = substr($this->a_expected, 1);
		}
		elseif (substr($this->a_data, 0, 1) == '\\' && substr($this->a_expected, 0, 1)!== '\\')
		{
			$this->a_data = substr($this->a_data, 1);
		}

    	$assertion = '$this->a_compareType($this->a_data, $this->a_expected);';
    	if ($type)
    	{
			if (! $this->assertTrue($assertion, sprintf('Checking result - Asserting: %s', $assertion)))
			{
				$this->a_outputAndDie();
			}
    	}
    	else
    	{
			if (! $this->assertFalse($assertion, sprintf('Checking result - Asserting: %s', $assertion)))
			{
				$this->a_outputAndDie();
			}
    	}
    }

    /**
     * a_compareType
     *
     * Compare the two parameters according to their types.
     * @param mixed $data = first parameter to compare.
     * @param mixed $compareTo = second parameter to compare.
     * @return boolean $result = true if equal, false if not.
     */
    public function a_compareType($data, $compareTo)
    {
    	$dataType = gettype($data);
    	if ($dataType === gettype($compareTo))
    	{
	    	switch($dataType)
    		{
    			case 'string':
	    		case 'integer':
    			case 'double':
    			case 'boolean':
    			case 'NULL':
    				if ($data === $compareTo)
    				{
    					return true;
    				}

	    			break;

	    		case 'array':
    				return $this->a_identicalArrays($data, $compareTo);

    			case 'object':
    				if (get_class($data) === get_class($compareTo))
    				{
    					return true;
    				}

    				break;

    			case 'resource':
    				if (@get_resource_type($data) === @get_resource_type($compareTo))
    				{
    					return true;
    				}

    				break;

    			default:
    				break;
    		}
    	}

    	return false;
    }

    /**
     * a_printArray
     *
     * Print the array contents
     * @param array $array = array to print
     * @param string $label = (optional) array label to print, null for none
     * @param boolean $sort = (optional) sort fields if true (default = false)
     * @param boolean $sortValues = (optional) sort values if true (default = false)
     * @param boolean $recurse = (optional) recursion allowed if true
     */
    public function a_printArray($array, $label=null, $sort=false, $sortValues=false, $recurse=false)
    {
		FormatVar::sort($sort);
		FormatVar::sortValues($sortValues);
		FormatVar::recurse($recurse);

    	$this->a_showData($array, $label);
    }

    /**
     * a_showData
     *
     * Output the value of the (optionally) named variable
     * @param mixed $value = value to output
     * @param string $name = (optional) name of the variable
     */
    public function a_showData($value, $name=null, $formatted=false)
    {
    	$this->assertLogMessage(rtrim(FormatVar::format($value, $name)));
    }

    /**
     * a_exceptionCaughtTrue
     *
     * check the exception caught for true status
     * @param boolean $exit = true to report and exit on failure, false to return
     */
    public function a_exceptionCaughtTrue($exit=true)
    {
    	$this->labelBlock('Exception caught true.', 40, '*');

    	$assertion = '$this->exceptionCaught()';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_printException();
			if ($exit)
			{
				$this->a_outputAndDie();
			}
		}
    }

    /**
     * a_exceptionCaughtFalse
     *
     * check the exception caught for false status
     * @param boolean $exit = true to report and exit on failure, false to return
     */
    public function a_exceptionCaughtFalse($exit=true)
    {
    	$this->labelBlock('Exception caught false.', 40, '*');

    	$assertion = '$this->exceptionCaught()';
		if (! $this->assertFalse($assertion, sprintf('Falsely asserting: %s', $assertion)))
		{
			$this->a_printException();
			if ($exit)
			{
				$this->a_outputAndDie();
			}
		}
    }

    /**
     * a_printException
     *
     * Output the exception to the log as a printable string
     */
    public function a_printException()
    {
    	$this->assertLogMessage((string)$this->exception);
    }

    /**
     * a_absoluteFileName
     *
     * Takes a workspace relative file name and returns an absolute path to the file
     * @param string $name = workspace relative file name
     * @return string $name = absolute path to the file
     */
    public function a_absoluteFileName($name)
    {
    	return $this->a_fileName = Support::absoluteFileName($name, true);
    }

    /**
     * a_outputAndDie
     *
     * Output a message and terminate the program
     * @param string $message = (optional) message to output
     * @param boolean $die = (optional) true to die after output (default), false to return
     */
	public function a_outputAndDie($message='', $die=true)
	{
		if ($message != '')
		{
			$this->assertLogMessage($message);
			if (is_object($this->properties->Logger_Object) && (get_class($this->properties->Logger_Object) == 'Library\Testing\Logger'))
			{
				$this->properties->Logger_Object->log($message, 'error');
			}
		}

		if ($die)
		{
			throw new Exception($message);
		}
	}

	/**
	 * a_wait
	 *
	 * Wait for requested number of seconds
	 * @param integer $pauseSeconds = (optional) seconds, default = 10
	 * @param integer $pauseNano = (optional) nano-seconds, default = 0
	 */
	public function a_wait($pauseSeconds=10, $pauseNano=0)
	{
		$this->labelBlock('Wait.', 40, '*');

		$this->a_waitSeconds = $pauseSeconds;
		$this->a_waitNano = $pauseNano;

		$this->a_showData($this->a_waitSeconds, 'Wait Seconds');
		$this->a_showData($this->a_waitNano, 'Wait Nano');

		$assertion = '(($this->a_data = time_nanosleep($this->a_waitSeconds, $this->a_waitNano)) === true);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse(false);

		$this->a_showData($this->a_data, 'Wait Result');
	}

}
