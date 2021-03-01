<?php
namespace Tests\Select;

use \Application\Launcher\Testing\UtilityMethods;

use Library\Select\Storage;

/*
 *		StorageTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * Select\StorageTest
 *
 * Select\Storage class tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Select
 */
class StorageTest extends DescriptorTest
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

		$this->a_newStorage();

		$this->a_newDescriptor(array('name' 	=> 'stdin',
								     'type'		=> 'read',
									 'resource'	=> STDIN,
									 'enabled'	=> false,
									 'ready'	=> false,
									 'callback'	=> null,
									 'buffer'	=> '',
									 'index'	=> 0,
									 ));

		$this->a_storeDescriptor($this->a_descriptor);
	}

	/**
	 * a_storeDescriptor
	 * 
	 * Store the descriptor in the descriptor stack
	 * @param Select\Descriptor $descriptor
	 */
	public function a_storeDescriptor($descriptor)
	{
		$this->labelBlock('Store Descriptor', 40);

		$this->a_selectDescriptor = $descriptor;
		
		$this->a_key = sprintf("%s.%s", $descriptor->name, $descriptor->type);
		$this->a_showData($this->a_key, "Key");

		$this->a_showData($this->a_selectDescriptor, 'Select Descriptor');

		$assertion = '(($this->a_storage->selectDescriptors[$this->a_key] = $this->a_selectDescriptor) !== false);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_showData((string)$this->a_storage, "Storage");
	}

	/**
	 * a_newStorage
	 * 
	 * Create a new Storage object
	 */
	public function a_newStorage()
	{
		$this->labelBlock('New Storage', 40);

		$assertion = '(($this->a_storage = new \Library\Select\Storage()) !== false);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, 'Library\Select\Storage', get_class($this->a_storage));
		$this->a_showData((string)$this->a_storage, "Storage");
	}

}
