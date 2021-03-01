<?php
namespace Tests\Log;
use Library\Log;

/*
 *		Log\LogTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 *
 * Library\Log Tests
 *
 * Library\Log tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Log
 */
class LogTest extends \Application\Launcher\Testing\UtilityMethods
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

    	$this->a_factoryInstance();
    	$this->a_getFactoryKeys();

    	$keys = $this->a_localArray;
    	foreach($keys as $index => $className)
    	{
    		$this->a_logTests($className);
    	}
	}
	
	public function a_logTests($className)
	{
		$this->a_absoluteFileName('Tests\Log\testlog');

		$this->a_createLogObject($className,
								 new \Library\Properties(array('Log_Format' 			=> 'xml',
														       'Log_Program'			=> $this->testName,
														   	   'Log_FileAdapter'		=> 'fileobject',
		                                                       'Log_FileDestination'	=> $this->a_fileName,
														       'Log_FileMode' 			=> 'w')),
								 $this->a_instance->className($className));

		$this->a_logMessage('This is a test message');
		
		$this->a_logMessageProperties('Another message with timestamp', new \Library\Properties(array('Log_Level'   => 'debug',
														 											  'Log_Format' => 'timestamp',
														 											  )));
		
		$this->a_logMessageProperties('A standard log message', new \Library\Properties(array('Log_Level'   => 'debug',
														 									  'Log_Format' => 'log',
														 									  )));
		
		$this->a_logMessageProperties('A XML formatted message', new \Library\Properties(array('Log_Level'   => 'debug',
														 									   'Log_Format' => 'xml',
														 									   )));
	}
	
	/**
	 * a_logMessageProperties
	 *
	 * Log a message
	 * @param string $message = message to log
	 * @param object $properties = log message properties
	 */
	public function a_logMessageProperties($message, $properties)
	{
    	$this->labelBlock('Log Message Properties.', 60, '*');

    	$properties->Log_Method     = substr(__METHOD__, strpos(__METHOD__, "::") + 2);
		$properties->Log_Class      = get_class();
		$properties->Log_SkipLevels = 0;

		$this->a_properties = $properties;

    	$assertion = sprintf('$this->a_object->log("%s", $this->a_properties);', $message);
		if (! $this->assertExceptionFalse($assertion, sprintf("Log Message Properties: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_logMessage
	 *
	 * Log a message
	 * @param string $message = message to log
	 */
	public function a_logMessage($message)
	{
    	$this->labelBlock('Log Message.', 60, '*');

    	$assertion = sprintf('$this->a_object->log("%s", "debug");', $message);
		if (! $this->assertExceptionFalse($assertion, sprintf("Log Message: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_createLogObject
	 *
	 * Create a new log object
	 * @param string $className = internal name of the log adapter class
	 * @param string $class = expected class name
	 */
	public function a_createLogObject($className, $adapterProperties, $expected)
	{
    	$this->labelBlock('Create Log Object.', 60, '*');

    	$this->a_properties = $adapterProperties;

    	$assertion = sprintf('$this->a_object = \Library\Log\Factory::instantiateClass("%s", $this->a_properties);', $className);
		if (! $this->assertExceptionTrue($assertion, sprintf("Create Log Object: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType(true, $expected, get_class($this->a_object));
	}

	/**
	 * a_getFactoryKeys
	 *
	 * Get an associative array of available class names (keys)
	 */
	public function a_getFactoryKeys()
	{
    	$this->labelBlock('Get Factory Keys.', 40, '*');

    	$assertion = '$this->a_localArray = $this->a_instance->availableKeys();';
		if (! $this->assertTrue($assertion, sprintf("Get Keys: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_printArray($this->a_localArray);
	}

	/**
	 * a_factoryInstance
	 * 
	 * Get an instance of the Library\Log\Factory class
	 */
	public function a_factoryInstance()
	{
    	$this->labelBlock('Get Factory Instance.', 40, '*');

    	$assertion = '$this->a_instance = \Library\Log\Factory::constructFactory();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Get Instance: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType(true, get_class($this->a_instance), 'Library\Log\Factory');
	}

}
