<?php
namespace Tests\Select;

use \Application\Launcher\Testing\UtilityMethods;

use Library\Select\Register;

/*
 *		RegisterTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * Select\RegisterTest
 *
 * SelectRegister class tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Select
 */
class RegisterTest extends StorageTest
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

		$this->a_newRegister();

		$this->a_inputHandle = STDIN;

		$this->a_registerStream('stdin', 'read', $this->a_inputHandle, null);
		$this->a_enableStream('stdin', 'read');

		$this->a_outputHandle = STDOUT;
		$this->a_registerStream('stdout', 'write', $this->a_outputHandle, null);
		$this->a_enableStream('stdout', 'write');

		$this->a_disableStream('stdout', 'write');
		$this->a_disableStream('stdin', 'read');

		$this->a_unregister('stdin', 'read');
		$this->a_unregister('stdout', 'write');
	}

	/**
	 * a_descriptorBuffer
	 * 
	 * Set/get the current buffer
	 * @param string $name = select name
	 * @param string $type = select type
	 * @param string $buffer = (optional) message to place in the descriptor buffer, null to query only
	 */
	public function a_descriptorBuffer($name, $type, $buffer=null)
	{
		$this->labelBlock('Descriptor Buffer', 40);

		$this->a_name = $name;
		$this->a_type = $type;
		$this->a_buffer = $buffer;

		$this->a_showData($this->a_name, 'Name');
		$this->a_showData($this->a_type, 'Type');
		$this->a_showData($this->a_buffer, 'Buffer');

		$assertion = '$this->a_buffer = $this->a_register->buffer($this->a_name, $this->a_type, $this->a_buffer);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_buffer = '';
		}

		$this->a_exceptionCaughtFalse();
		$this->a_showData($this->a_buffer, 'Buffer');
	}

	/**
	 * a_setOutputMessage
	 * 
	 * Set the output message
	 * @param string $name = select name
	 * @param string $type = select type
	 * @param string $message = message to place in the descriptor buffer
	 */
	public function a_setOutputMessage($name, $type, $message)
	{
		$this->labelBlock('Set OutputMessage', 40);

		$this->a_name = $name;
		$this->a_type = $type;
		$this->a_message = $message;

		$this->a_showData($this->a_name, 'Name');
		$this->a_showData($this->a_type, 'Type');
		$this->a_showData($this->a_message, 'Message');

		$assertion = '$this->a_data = $this->a_register->buffer($this->a_name, $this->a_type, $this->a_message);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_compareExpectedType(true, $this->a_message);
	}

	/**
	 * a_unregister
	 * 
	 * Unregister the stream
	 * @param string $name = select name
	 * @param string $type = select type
	 */
	public function a_unregister($name, $type)
	{
		$this->labelBlock('unregister', 40);

		$this->a_name = $name;
		$this->a_type = $type;

		$this->a_showData($this->a_name, 'Name');
		$this->a_showData($this->a_type, 'Type');

		$assertion = '($this->a_register->unregister($this->a_name, $this->a_type) !== true);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData((string)$this->a_storage, 'Storage');
	}

	/**
	 * a_registerStream
	 * 
	 * Register a new stream
	 * @param string $name = select process name
	 * @param string $type = stream select type ('read', 'write', 'except')
	 * @param resource $resource = stream to register
	 * @param mixed $callback = (optional) callback process
	 */
	public function a_registerStream($name, $type, $resource, $callback=null)
	{
		$this->labelBlock('Register Stream', 40);

		$this->a_name = $name;
		$this->a_type = $type;
		$this->a_resource = $resource;
		$this->a_callback = $callback;

		$this->a_showData($this->a_name,     'Name');
		$this->a_showData($this->a_type,     'Type');
		$this->a_showData($this->a_resource, 'Resource');
		$this->a_showData($this->a_callback, 'Callback');

		$assertion = '(($this->a_data = $this->a_register->register($this->a_name, $this->a_type, $this->a_resource, $this->a_callback)) !== true);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		
		$this->a_showData((string)$this->a_storage, 'Storage');
	}

	/**
	 * a_enableStream
	 * 
	 * Enable registered stream select
	 * @param string $name = select name
	 * @param string $type = select type
	 */
	public function a_enableStream($name, $type)
	{
		$this->labelBlock('Enable Stream', 40);

		$this->a_name = $name;
		$this->a_type = $type;

		$this->a_showData($this->a_name, 'Name');
		$this->a_showData($this->a_type, 'Type');

		$assertion = '$this->a_register->enable($this->a_name, $this->a_type);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_showData((string)$this->a_storage, 'Storage');
	}

	/**
	 * a_disableStream
	 * 
	 * Disable registered stream select
	 * @param string $name = select name
	 * @param string $type = type of select ('read', 'write', 'except')
	 */
	public function a_disableStream($name, $type)
	{
		$this->labelBlock('Disable Stream', 40);

		$this->a_name = $name;
		$this->a_type = $type;

		$this->a_showData($this->a_name, 'Name');
		$this->a_showData($this->a_type, 'Type');

		$assertion = '($this->a_register->disable($this->a_name, $this->a_type) === false);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_showData((string)$this->a_storage, 'Storage');
	}

	/**
	 * a_isEnabled
	 * 
	 * Check the enabled status
	 * @param string $name = select name
	 * @param string $type = type of select ('read', 'write', 'except')
	 * @param mixed $expected = expected returned value
	 */
	public function a_isEnabled($name, $type, $expected)
	{
		$this->labelBlock('isEnabled', 40);

		$this->a_name = $name;
		$this->a_type = $type;
		$this->a_expected = $expected;

		$this->a_showData($this->a_name, 'Name');
		$this->a_showData($this->a_type, 'Type');
		$this->a_showData($this->a_expected, 'Expected');
		
		$assertion = '(($this->a_data = $this->a_register->isEnabled($this->a_name, $this->a_type)) !== null);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_compareExpectedType(true, $this->a_expected);
	}

	/**
	 * a_newRegister
	 * 
	 * Create a new SelectRegister object
	 */
	public function a_newRegister()
	{
		$this->labelBlock('New Register', 40);

		$this->a_showData((string)$this->a_storage, "Storage");

		$assertion = '$this->a_register = new \Library\Select\Register($this->a_storage);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, 'Library\Select\Register', get_class($this->a_register));
		$this->a_showData((string)$this->a_storage, "Storage");
	}

}
