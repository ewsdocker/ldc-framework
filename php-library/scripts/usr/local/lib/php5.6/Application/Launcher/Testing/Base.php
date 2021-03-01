<?php
namespace Application\Launcher\Testing;

use Library\Log;
use Library\Exception\Descriptor as ExceptionDescriptor;
use Library\CliParameters;

/*
 *		Base is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * Base
 *
 * Base class for assertion test classes
 * 		provides the assert functions and support methods
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Launcher
 * @subpackage Testing
 */
class Base
{
	/**
	 * properties
	 *
	 * Properties class instance
	 * @var object $properties
	 */
	protected 			$properties;

	/**
	 * exceptionCaught
	 *
	 * true = exception caught in last assert, false = exception not caught
	 * @var boolean $exceptionCaught
	 */
	protected			$exceptionCaught;

	/**
	 * exception
	 *
	 * @var Library\Exception\Descriptor
	 */
	protected			$exception;

	/**
	 * exceptionCallback
	 *
	 * contains the name of a user-defined callback processing method
	 * @var string $exceptionCallback
	 */
	protected			$exceptionCallback;

	/**
	 * verbose
	 *
	 * output: short messages when 1, detailed message when 2, no messages when 0
	 * @var integer $verbose
	 */
	protected			$verbose;

	/**
	 * reportFailures
	 *
	 * True to report assert failures, even if not Verbose.
	 * False to inhibit assert faiures, even if Verbose.
	 * @var boolean $reportFailures
	 */
	protected			$reportFailures;

	/**
	 * errorsOnly
	 *
	 * 0 = all messages, 1 = errors only
	 * @var integer $errorsOnly
	 */
	protected			$errorsOnly;

	/**
	 * testNumber
	 *
	 * The current subTest number
	 * @var integer $testNumber
	 */
	protected			$testNumber;

	/**
	 * testName
	 * 
	 * The name of the test
	 * @var string $testName
	 */
	protected			$testName;

	/**
	 * processDescriptor
	 * 
	 * A ProcessDescriptor object instance for the current test
	 * @var object $processDescriptor
	 */
	protected			$processDescriptor;

	/**
	 * assert
	 *
	 * An array containing information about the last false assertion processed
	 * @var array $assert
	 */
	protected			$assert;

	/**
	 * labelBlock
	 *
	 * True if ok to output a block label, false to not
	 * @var boolean $labelBlock
	 */
	protected			$labelBlock;

	/**
	 * cliParameters
	 * 
	 * Library\CliParameters instance containing test program cli parameters
	 * @var object $cliParameters
	 */
	protected			$cliParameters = null;

	/**
	 * eolSequence
	 * 
	 * The current end-of-line sequence
	 * @var string $eolSequence
	 */
	protected			$eolSequence = '';

	/**
	 * skipIncrement
	 * 
	 * Skip incrementing the subtest number when true
	 * @var boolean $skipIncrement
	 */
	protected			$skipIncrement;

	/**
	 * logger
	 *
	 * the assert logger instance
	 * @var object $logger
	 */
	protected			$logger;

	/**
	 * me
	 *
	 * This class instance
	 * @var object $me
	 */
	protected static	$me;

	/**
	 * __construct
	 *
	 * Class constructor
	 */
	public function __construct()
    {
    	$this->properties = null;

    	$this->exception = null;
		$this->exceptionCaught = false;
		$this->exceptionCallback = null;

    	$this->assertEvent = array();

    	$this->verbose = 0;
    	$this->errorsOnly = 1;

    	$this->logger = null;

    	$this->testNumber = 0;
		$this->testName = '<unknown>';
		$this->processDescriptor = null;

		$this->eolSequence = null;

		$this->reportFailures = true;
		$this->labelBlock = true;
		$this->skipIncrement = false;

    	\Library\Autoload::loadClass('\Library\CliParameters');

		self::$me = $this;
    }

	/**
	 * __destruct
	 *
	 * Destroy the current class instance
	 */
	public function __destruct()
	{
	}

	/**
	 * assertSetup
	 *
	 * Set assert options
	 * @param integer $active   = (optional) active flag: 0 ==> inactive, 1 ==> active. (Default: 1)
	 * @param integer $warning  = (optional) warning flag: 0 ==> silent, 1 ==> issue warning on assert failure. (Default: 0)
	 * @param integer $bail     = (optional) bail-out flag: 0 ==> keep processing, 1 ==> abort processing. (Default: 0)
	 * @param string  $callback = (optional) callback function name. (Default = \Application\Launcher\Testing\Base::assertCallback()).
	 */
	protected function assertSetup($active=1, $warning=0, $bail=0, $callback=array('\Application\Launcher\Testing\Base', 'assertCallback'))
	{
		assert_options(ASSERT_ACTIVE,	$active);
		assert_options(ASSERT_WARNING,	$warning);
		assert_options(ASSERT_BAIL,		$bail);
		assert_options(ASSERT_CALLBACK,	$callback);
	}

	/**
	 * assertTest
	 *
	 * Assert the assertion and return the result conditioned by $expected
	 * @param string $assertion = assertion to test
	 * @param string $message = (optional) string to print if $verbose is true, null for no message
	 * @param boolean $expected = (optional) expected result (true = a true result WAS expected; false = a false result WAS expected)
	 * @return boolean true = result was as expected, false = it was not as expected
	 */
	protected function assertTest($assertion, $message=null, $expected=true)
	{
		if (! $this->skipIncrement)
		{
			$this->incrementSubTest();
		}
		else
		{
			$this->skipIncrement = false;
		}

		$this->processDescriptor->subTest = $this->testNumber();

		if ($this->verbose && ($message !== null))
		{
			$this->assertLogMessage($message);
		}

		if (! assert($assertion))
		{
			if ($expected == false)
			{
				if ($this->verbose && (! $this->errorsOnly))
				{
					$this->assertLogMessage("Assertion is false");
				}

				return true;
			}

			$this->assertLogMessage("***  Assertion is FALSE but expected TRUE ***");
			return false;
		}

		if ($expected == true)
		{
			if ($this->verbose && (! $this->errorsOnly))
			{
				$this->assertLogMessage("Assertion is true");
			}

			return true;
		}

		$this->assertLogMessage("***  Assertion is TRUE but expected FALSE  ***");
		return false;
	}

	/**
	 * assertException
	 *
	 * Assert the assertion and return the result conditioned by $expected and $exceptionCaught
	 * @param string $assertion = assertion to test
	 * @param string $message = (optional) string to print if $verbose is true, null for no message
	 * @param boolean $expected = (optional) expected result (true = a true result WAS expected; false = a false result WAS expected)
	 * @return boolean true = result was as expected, false = it was not as expected, or an exception occurred
	 */
	protected function assertException($assertion, $message=null, $expected=true)
	{
		try
		{
			$this->exceptionCaught = false;
			return $this->assertTest($assertion, $message, $expected);
		}
		catch(\Exception $exception)
		{
			if ($this->exceptionCallback)
			{
				$this->{$this->exceptionCallback}($exception);
			}
			else
			{
				$this->exceptionCaught = true;
				$this->exception = new ExceptionDescriptor($exception,
														   array('testname'	  => $this->testName,
															     'testnumber' => $this->testNumber));
				if ($this->reportFailures)
				{
					$this->assertLogMessage(sprintf("%s Test #%u - %s",
											  		$this->exception->testname,
											  		$this->exception->testnumber,
											  		$this->exception));
				}
			}
		}

		return false;
	}

	/**
	 * assertTrue
	 *
	 * perform an assertion that the test is true
	 * @param string $assertion = assertion to test
	 * @param string $message = (optional) assert test message, null = none
	 * @return boolean true = successful, false = unsuccessful
	 */
	public function assertTrue($assertion, $message=null)
	{
		return $this->assertTest($assertion, $message, true);
	}

	/**
	 * assertFalse
	 *
	 * perform an assert that the test is false
	 * @param string $assertion = assertion to test
	 * @param string $message = (optional) assert test message, null for none
	 * @return boolean true = successful, false = unsuccessful
	 */
	public function assertFalse($assertion, $message=null)
	{
		return $this->assertTest($assertion, $message, false);
	}

	/**
	 * assertExceptionTrue
	 *
	 * perform an assert that the test is true, otherwise return false on exception or false assertion
	 * @param string $assertion = assertion to test
	 * @param string $message = assert test message, null for none
	 * @return boolean true = successful, false = unsuccessful
	 */
	public function assertExceptionTrue($assertion, $message=null)
	{
		if ($this->assertException($assertion, $message, true) || $this->exceptionCaught)
		{
			return true;
		}

		return false;
	}

	/**
	 * assertExceptionFalse
	 *
	 * perform an assert that the test is false, return false if assert is true or on exception
	 * @param string $assertion = assertion to test
	 * @param string $message = assert test message, null = none
	 * @return boolean true = successful, false = unsuccessful
	 */
	public function assertExceptionFalse($assertion, $message=null)
	{
		if ($this->assertException($assertion, $message, false))
		{
			return true;
		}

		if ($this->exceptionCaught)
		{
			if ($this->reportFailures)
			{
				$this->assertLogMessage(sprintf("***  Assertion caught EXCEPTION: (%u) %s  ***",
												$this->exception->code,
												$this->exception->message));
				$this->assertLogMessage((string)$this->exception);
			}

			return true;
		}

		return false;
	}

	/**
	 * getException
	 *
	 * return the current exception
	 * @return \Library\Exception\Descriptor or null if not assigned.
	 */
	public function getException()
	{
		return $this->exception;
	}

	/**
	 * getExceptionCode
	 *
	 * get the last exception code
	 * @return integer $exceptionCode
	 */
	public function getExceptionCode()
	{
		if (! $this->exception)
		{
			return null;
		}

		return $this->exception->code;
	}

	/**
	 * getExceptionMessage
	 *
	 * get the last exception message
	 * @return string $exceptionMessage
	 */
	public function getExceptionMessage()
	{
		if (! $this->exception)
		{
			return null;
		}

		return $this->exception->message;
	}

	/**
	 * getExceptionClass
	 *
	 * get the exception class name
	 * @return string $exceptionClass
	 */
	public function getExceptionClass()
	{
		if (! $this->exception)
		{
			return null;
		}

		return $this->exception->className;
	}

	/**
	 * exceptionCaught
	 *
	 * get the current exceptionCaught flag setting
	 * @return boolean $exceptionCaught
	 */
	public function exceptionCaught()
	{
		return $this->exceptionCaught;
	}

	/**
	 * labelBlock
	 *
	 * Output a separator block containing a label
	 * @param string $label = label to put in the separator block
	 * @param integer $blockLength = (optional) width of the block in length($blockChars) bytes (must be greater than 9) (default = 10)
	 * @param string $blockChars = (optional) character(s) to use for the separator block (default = "*")
	 */
	public function labelBlock($label, $blockLength=10, $blockChars='*')
	{
		if ((! $this->verbose) || (! $this->labelBlock) || (! $label))
		{
			return;
		}

		if ($blockLength < 10)
		{
			$blockLength = 10;
		}

		if (! $blockChars)
		{
			$blockChars = '*';
		}

		$block = str_repeat($blockChars, $blockLength);

		$this->incrementSubTest();
		$this->skipIncrement = true;

		$this->assertLogMessage($block);
		$this->assertLogMessage($blockChars);
		$this->assertLogMessage(sprintf("%s\t%s", $blockChars, $label));
		$this->assertLogMessage($blockChars);
		$this->assertLogMessage($block);
	}

	/**
	 * labelBlockFlag
	 *
	 * set/get label-block flag
	 * @param boolean $labelBlock = (optional) true = set, false = reset, null to query
	 * @return boolean $labelBlock
	 */
	public function labelBlockFlag($labelBlock=null)
	{
		if ($labelBlock !== null)
		{
			$this->labelBlock = $labelBlock;
		}

		return $this->labelBlock;
	}

	/**
	 * assertCallback
	 *
	 * Assert test callback function to handle failures, if ASSERT_WARNING != 0
	 * @param string $script = script name
	 * @param integer $line = line number
	 * @param string $message = assert message (or null)
	 * @return boolean false
	 */
	public static function assertCallback($script, $line, $message=null)
	{
		$self = self::$me;

		if ($self->reportFailures)
		{
			$self->assertLogMessage(sprintf("Assert failure in\n\tScript:\t%s\n\tLine:\t%s\n\tCondition:\t%s\n",
											$script,
											$line,
											$message));
		}

		$self->assertEvent = array('script'		=> $script,
								   'line'		=> $line,
								   'message'	=> $message);

		return false;
	}

	/**
	 * properties
	 *
	 * Set/get the properties object
	 * @param object $properties = (optional) properties object to store, null to query only
	 * @return object $properties
	 */
	public function properties($properties=null)
	{
		if ($properties !== null)
		{
			$this->properties = $properties;
		}

		return $this->properties;
	}

	/**
	 * verbose
	 *
	 * set/get verbosity setting
	 * @param integer $verbose = (optional) verbose setting (0 = silent, non-zero = output ok), null to query only
	 * @return integer $verbose
	 */
	public function verbose($verbose=null)
	{
		if ($verbose !== null)
		{
			$this->verbose = $verbose;
		}

		return $this->verbose;
	}

	/**
	 * errorsOnly
	 *
	 * Errors only flag: 0 = display all messages, <> 0 = don't display errors
	 * @params integer $errorsOnly = (optional) errors only flag, null to query only
	 * @return integer $errorsOnly
	 */
	public function errorsOnly($errorsOnly=null)
	{
		if ($errorsOnly !== null)
		{
			$this->errorsOnly = $errorsOnly;
		}

		return $this->errorsOnly;
	}

	/**
	 * incrementSubTest
	 * 
	 * Increment the subtest number class property and update the Run_SubtestNumber property 
	 */
	protected function incrementSubTest()
	{
		$this->properties->Run_SubtestNumber = ++$this->testNumber;
	}

	/**
     * testNumber
     *
     * get/set test number
     * @param integer $testNumber = (optinal) test number, null to query only
     * @return integer $testNumber
     */
    public function testNumber($testNumber=null)
    {
    	if ($testNumber !== null)
    	{
    		$this->testNumber = $testNumber;
    		$this->properties->Run_SubtestNumber = $this->testNumber;
    	}

    	return $this->testNumber;
    }

    /**
     * testName
     *
     * get/set test name
     * @param string $testName = (optinal) test name, null to query only
     * @return string $testName
     */
    public function testName($testName=null)
    {
    	if ($testName !== null)
    	{
    		$this->testName = $testName;
    	}

    	return $this->testName;
    }

    /**
     * processDescriptor
     * 
     * Get/set the processDescriptor instance for this object
     * @param object $processDescriptor = (optional) test stats object to set, null to query
     * @return object $processDescriptor
     */
    public function processDescriptor($processDescriptor=null)
    {
    	if ($processDescriptor !== null)
    	{
    		$this->processDescriptor = $processDescriptor;
    	}

    	return $this->processDescriptor;
    }

   	/**
   	 * logger
   	 * 
   	 * Get the logger property
   	 * @param object $logger = (optional) logger property setting, null to query
   	 * @return object $logger
   	 * @throws Library\Testing\Exception
   	 */
    public function logger($logger=null)
    {
    	if (($logger !== null) && ($this->logger === null))
    	{
    		if ((! is_object($logger)) || (get_class($logger) !== 'Application\Launcher\Testing\AssertLog'))
    		{
    			throw new Exception(Error::getcode('InvalidClassObject'));
    		}

    		$this->logger = $logger;
    	}

    	return $this->logger;
    }

    /**
     * assertEventArray
     *
     * get the array containing information about the last assert failure
     * @return array $assertEvemt
     */
    public function assertEventArray()
    {
    	return $this->assertEvent;
    }

    /**
     * assertFailures
     *
     * True to report assert failures, false to not, regardless of $verbose
     * @param boolean $report = (optional) report setting, null to query
     * @return boolean $report
     */
    public function assertFailures($report=null)
    {
    	if ($report !== null)
    	{
    		$this->reportFailures = $report;
    	}

    	return $this->reportFailures;
    }

    /**
     * assertExceptionDescriptor
     *
     * get a copy of the exception object
     * @return \Library\Exception\Descriptor
     */
    public function assertExceptionDescriptor()
    {
    	return $this->exception;
    }

    /**
     * assertExceptionCallback
     *
     * Set/get exception callback function
     * @param string $callback = name of the callback function
     */
    public function assertExceptionCallback($callback=null)
    {
    	if ($callback !== null)
    	{
    		if (method_exists($this, $callback))
    		{
    			$this->exceptionCallback = $callback;
    		}
    	}

    	return $this->exceptionCallback;
    }

    /**
     * assertLogMessage
     *
     * Output a message + newline to the current output device
     * @param string $message = message to output
     * @param integer $level = (optional) log level ('debug', 'error', <user defined>) DEFAULT: debug
     */
    public function assertLogMessage($message, $level='debug')
    {
    	$this->logger->logMessage($message, $level);
    }

}
