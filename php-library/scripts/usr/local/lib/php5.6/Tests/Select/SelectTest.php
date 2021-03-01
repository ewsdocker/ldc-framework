<?php
namespace Tests\Select;

use \Application\Launcher\Testing\UtilityMethods;

use Library\Select\Process;

/*
 *		Select\ProcessTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * Select\ProcessTest
 *
 * Select\Process class tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Select
 */
class SelectTest extends UtilityMethods
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

		$this->a_startSelect();
		$this->a_sequencedPoll();
		$this->a_endSelect();
		
		$this->a_startSelect();
		$this->a_automaticSelect();
		$this->a_endSelect();
	}

	/**
	 * a_endSelect
	 * 
	 * Unregister all descriptors and delete $a_select instance.
	 */
	public function a_endSelect()
	{
		$this->a_unregisterAll();
		$this->a_deleteSelect();
	}

	/**
	 * a_startSelect
	 * 
	 * Create a new $a_select instance and register 2 streams for testing
	 */
	public function a_startSelect()
	{
		$this->a_newSelect();
		$this->a_getStorage();

		$this->a_inputHandle = STDIN;
		$this->a_registerStream('stdin', 'read', $this->a_inputHandle, null);
		$this->a_enableStream('stdin', 'read');

		$this->a_outputHandle = STDOUT;
		$this->a_registerStream('stdout', 'write', $this->a_outputHandle, null);
		$this->a_enableStream('stdout', 'write');

		$this->a_disableStream('stdout', 'write');
		$this->a_unregister('stdout', 'write');
		
		$this->a_registerStream('stdout', 'write', $this->a_outputHandle, null);

		$this->a_buffer('stdin', 'read', '');
		$this->a_bufferAddress('stdout', 'write', "\n\n\tEnter password:\n\n");
		
		$this->a_enableStream('stdout', 'write');
		$this->a_enableStream('stdin', 'read');

		$this->a_setTimeout(20.0);
	}

	/**
	 * a_automaticSelect
	 * 
	 * Automatically sequenced select poll
	 */
	public function a_automaticSelect()
	{
		$this->a_processDescriptors();
		$this->a_selectReady();
		$this->a_selected();
		
		$this->a_processDescriptors();
		$this->a_buffer('stdin', 'read');
	}

	/**
	 * a_sequencedPoll
	 * 
	 * Step-by-step sequenced poll test
	 */
	public function a_sequencedPoll()
	{
		$this->a_pollSelected();
		
		$this->a_selectReady();
		$this->a_selected();
		
		$this->a_readyDescriptors();
		
		$this->a_isEnabled('stdin', 'read', true);
		
		$this->a_pollSelected();
		
		$this->a_selectReady();
		$this->a_selected();
		
		$this->a_readyDescriptors();
		$this->a_buffer('stdin', 'read');
	}

	/**
	 * a_processDescriptors
	 * 
	 * Process ready descriptors
	 */
	public function a_processDescriptors()
	{
		$this->labelBlock('Process Descriptors', 40);

		$assertion = '(($this->a_data = $this->a_select->processDescriptors()) === true);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_showData($this->a_data, 'Data');
		$this->a_showData((string)$this->a_select, "Select");
	}

	/**
	 * a_readyDescriptors
	 * 
	 * Process ready descriptors
	 */
	public function a_readyDescriptors()
	{
		$this->labelBlock('Ready Descriptors', 40);

		$assertion = '(($this->a_data = $this->a_select->readyDescriptors()) === true);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_showData((string)$this->a_select, "Select");
	}

	/**
	 * a_pollSelected
	 * 
	 * Poll the selected arrays
	 * @return boolean true = requests pending, false = nothing to do
	 */
	public function a_pollSelected()
	{
		$this->labelBlock('Poll Selected', 40);

		$assertion = '(($this->a_data = $this->a_select->pollSelect()) > 0);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->assertLogMessage('No events ready.');
			return true;
		}

		$this->a_exceptionCaughtFalse(false);

		if ($this->exceptionCaught())
		{
			return false;
		}

		$this->a_showData($this->a_select->selectDescriptors(), 'SelectDescriptors');
		
		return true;
	}

	/**
	 * a_selected
	 * 
	 * Get the count of selected streams
	 */
	public function a_selected()
	{
		$this->labelBlock('Selected', 40);

		$assertion = '($this->a_data = $this->a_select->selected());';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->assertLogMessage('No events ready.');
		}

		$this->a_showData($this->a_data, 'Selected');
	}

	/**
	 * a_selectReady
	 * 
	 * Get the count of selected streams in a ready state
	 */
	public function a_selectReady()
	{
		$this->labelBlock('SelectReady', 40);

		$assertion = '(($this->a_data = $this->a_select->selectReady()) !== true);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->assertLogMessage('No events ready.');
		}

		$this->a_showData($this->a_data, 'SelectReady');
	}

	/**
	 * a_setTimeout
	 * 
	 * Set select timeout
	 * @param real $timeout = the timeout value (sec.msec) to set
	 */
	public function a_setTimeout($timeout)
	{
		$this->labelBlock('Set Timeout', 40);

		$this->a_timeout = $timeout;
		$this->a_showData($this->a_timeout, 'Timeout');

		$assertion = '(($this->a_data = $this->a_select->timeout($this->a_timeout)) !== false);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($this->a_timeout, true);
	}

	/**
	 * a_buffer
	 * 
	 * Set/get the current buffer
	 * @param string $name = select name
	 * @param string $type = select type
	 * @param string $descriptorBuffer = (optional) message to place in the descriptor buffer, null to query only
	 */
	public function a_buffer($name, $type, $buffer=null)
	{
		$this->labelBlock('Buffer', 40);

		$this->a_name = $name;
		$this->a_type = $type;
		$this->a_descriptorBuffer = $buffer;

		$this->a_showData($this->a_name, 'Name');
		$this->a_showData($this->a_type, 'Type');
		$this->a_showData($this->a_descriptorBuffer, 'Buffer');

		$assertion = '$this->a_descriptorBuffer = $this->a_select->buffer($this->a_name, $this->a_type, $this->a_descriptorBuffer);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_descriptorBuffer = '';
		}

		$this->a_exceptionCaughtFalse();
		$this->a_showData($this->a_descriptorBuffer, 'Buffer');
	}

	/**
	 * a_bufferAddress
	 * 
	 * Set/get the current buffer
	 * @param string $name = select name
	 * @param string $type = select type
	 * @param string $descriptorBuffer = (optional) message to place in the descriptor buffer, null to query only
	 */
	public function a_bufferAddress($name, $type, $buffer=null)
	{
		$this->labelBlock('Buffer', 40);

		$this->a_name = $name;
		$this->a_type = $type;
		$this->a_addressBuffer = $buffer;

		$this->a_showData($this->a_name, 'Name');
		$this->a_showData($this->a_type, 'Type');
		$this->a_showData($this->a_addressBuffer, 'Buffer');

		$assertion = '($this->a_select->bufferAddress($this->a_name, $this->a_type, $this->a_descriptorBuffer) !== true);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_descriptorBuffer = '';
		}

		$this->a_exceptionCaughtFalse();
		$this->a_showData($this->a_addressBuffer, 'Buffer');
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

		$assertion = '$this->a_select->enable($this->a_name, $this->a_type);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_getDescriptor($name, $type);
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

		$assertion = '($this->a_select->disable($this->a_name, $this->a_type) === false);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_getDescriptor($name, $type);
	}

	/**
	 * a_getDescriptor
	 * 
	 * Get the select Descriptor
	 * @param string $name = descriptor name
	 * @param string $type = descriptor type
	 */
	public function a_getDescriptor($name, $type)
	{
		$this->labelBlock('Get Descriptor', 40);
		
		$this->a_name = $name;
		$this->a_type = $type;
		
		$this->a_showData($this->a_name, 'name');
		$this->a_showData($this->a_type, 'type');

		$assertion = '$this->a_descriptor = $this->a_select->getDescriptor($this->a_name, $this->a_type);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_displayDescriptor($this->a_descriptor, 'Descriptor');
	}

	/**
	 * a_displayDescriptor
	 * 
	 * Display the current descriptor's
	 * @param object $descriptor = descriptor to display
	 */
	public function a_displayDescriptor($descriptor)
	{
		$this->labelBlock('Display Descriptor', 40);
		
		$this->a_localDescriptor = $descriptor;
		$this->a_type = $descriptor->type;
		
		$assertion = '$this->a_buffer = (string)$this->a_descriptor;';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_showData($this->a_buffer, 'Descriptor');
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
		
		$assertion = '(($this->a_data = $this->a_select->isEnabled($this->a_name, $this->a_type)) !== null);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_compareExpectedType(true, $this->a_expected);
	}

	/**
	 * a_unregisterAll
	 * 
	 * Unregister all streams
	 */
	public function a_unregisterAll()
	{
		$this->labelBlock('unregisterAll', 40);

		$assertion = '($this->a_select->unregisterAll() !== true);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_storage->selectDescriptors, 'Select Descriptors');
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

		$assertion = '($this->a_select->unregister($this->a_name, $this->a_type) !== true);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_storage->selectDescriptors, 'Select Descriptors');
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

		$assertion = '(($this->a_data = $this->a_select->register($this->a_name, $this->a_type, $this->a_resource, $this->a_callback)) !== true);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_storage->selectDescriptors, 'Select Descriptors');
	}

	/**
	 * a_getStorage
	 * 
	 * get Storage object
	 */
	public function a_getStorage()
	{
		$this->labelBlock('Get Storage', 60);

		$assertion = '$this->a_storage = $this->a_select->storage();';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, 'Library\Select\Storage', get_class($this->a_storage));
	}

	/**
	 * a_deleteSelect
	 * 
	 * Delete Select object
	 */
	public function a_deleteSelect()
	{
		$this->labelBlock('Delete Select', 60);

		$assertion = '(($this->a_select = null) === null);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_newSelect
	 * 
	 * Create a new Select object
	 */
	public function a_newSelect()
	{
		$this->labelBlock('New Select', 60);

		$assertion = '$this->a_select = new \Library\Select();';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, 'Library\Select', get_class($this->a_select));
	}

}
