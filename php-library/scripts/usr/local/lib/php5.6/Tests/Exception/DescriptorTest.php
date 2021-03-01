<?php
namespace Tests\Exception;

/*
 *		Exception\DescriptorTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * Exception\DescriptorTest
 *
 * Exception Descriptor tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Exception
 */
class DescriptorTest extends \Application\Launcher\Testing\UtilityMethods
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

    	$this->a_setExceptionCallback('a_exceptionCallback');

    	$this->a_throwException();

    	$this->a_decodeException();

		$this->a_exceptionClassName();
		$this->a_exceptionCode();
		$this->a_exceptionMessage();
		$this->a_exceptionProperties();
    }
    
    /**
     * a_exceptionCode
     *
     * Get exception class name
     */
    public function a_exceptionCode()
    {
    	$assertion = '$this->a_data = $this->exception->code;';
		if (! $this->assertTrue($assertion, sprintf("Exception code - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->assertLogMessage(sprintf('Exception Code: %s', $this->a_data));
    }
    
    /**
     * a_exceptionMessage
     *
     * Get exception class name
     */
    public function a_exceptionMessage()
    {
    	$assertion = '$this->a_data = $this->exception->message;';
		if (! $this->assertTrue($assertion, sprintf("Exception message - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->assertLogMessage(sprintf('Exception Message: %s', $this->a_data));
    }
    
    /**
     * a_exceptionProperties
     *
     * Get exception properties
     */
    public function a_exceptionProperties()
    {
		$assertion = '$this->a_testArray = $this->exception->properties();';
		if ($this->assertTrue($assertion, sprintf("Get optional parameters - Asserting: %s", $assertion)))
		{
			$this->a_printArray($this->a_testArray);
		}
    }

    /**
     * a_exceptionClassName
     *
     * Get exception class name
     */
    public function a_exceptionClassName()
    {
    	$this->labelBlock('Exception Class Name', 40, '*');

    	$assertion = '$this->a_data = $this->exception->className;';
		if (! $this->assertTrue($assertion, sprintf("Exception class - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->assertLogMessage(sprintf('Exception Class Name: %s', $this->a_data));
    }

    /**
     * a_decodeException
     *
     * Decode the exception descriptor
     */
    public function a_decodeException()
    {
    	$this->labelBlock('Decode Exception', 40, '*');

    	$assertion = sprintf('$this->a_data = "%s";', $this->exception);
		if (! $this->assertTrue($assertion, sprintf("Decode exception, Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->assertLogMessage($this->a_data);
    }

    /**
     * a_throwException
     *
     * Throw an exception to test a_exceptionCallback
     */
    public function a_throwException()
    {
    	$this->labelBlock('Throw Exception', 40, '*');

    	$assertion = "new \\Library\\Factory('null');";
		if (! $this->assertExceptionFalse($assertion, sprintf("Throw exception, Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtTrue();
    }

    /**
     * a_setExceptionCallback
     *
     * Set exception callback method
     * @param string $callback = callback method name
     */
    public function a_setExceptionCallback($callback)
    {
    	$this->labelBlock('Set Exception Callback.', 40, '*');

    	$this->assertExceptionCallback('a_exceptionCallback');
    }

    /**
     * a_exceptionCallback
     *
     * Exception callback method
     * @param object $exception = exception object
     */
    protected function a_exceptionCallback($exception)
    {
		$this->exceptionCaught = true;
		$this->exception = new \Library\Exception\Descriptor($exception, $this->properties);
    }

    /**
     * a_detailException
     *
     * Format and output an exception descriptor to the logger
     */
    public function a_detailException()
    {
    	$this->labelBlock('Detail Exception.', 40, '*');

    	$this->assertLogMessage(sprintf('Exception: %s, code: %u, message: %s',
    				   			 		$this->exception->className,
    				   			 		$this->exception->code,
    				   			 		$this->exception->message));
    }

}
