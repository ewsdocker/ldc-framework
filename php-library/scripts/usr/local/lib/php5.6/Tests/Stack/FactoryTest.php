<?php
namespace Tests\Stack;

/*
 *		Stack\FactoryTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * FactoryTest
 *
 * Stack factory tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Stack
 */
class FactoryTest extends \Tests\Factory\FactoryTest
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

    	$this->a_objectName = 'stack';

    	$this->a_className = \Library\Stack\Factory::getInstance()->availableClassName($this->a_objectName);
    	$this->a_instantiateClass($this->a_className);
    	
    	$this->a_getFactoryInstance();

    	$this->a_classInstance($this->a_factory->availableClassName($this->a_objectName));
	}

	/**
	 * a_getFactoryInstance
	 *
	 * Get a factory instance
	 */
	public function a_getFactoryInstance()
	{
		$this->labelBlock('Get Factory Instance.', 40, '*');

$this->a_factory = \Library\Stack\Factory::getInstance();
		$assertion = '$this->a_factory = \Library\Stack\Factory::getInstance();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Factory instance: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		
		$this->a_compareExpectedType(true, get_class($this->a_factory), 'Library\Stack\Factory');
	}

	/**
	 * a_instantiateClass
	 *
	 * Create a new factory object
	 * @param string $expected = expected class name
	 */
	public function a_instantiateClass($expected)
	{
		$this->labelBlock('Instantiate Stack Factory Object.', 40, '*');

		$assertion = sprintf('$this->a_object = \Library\Stack\Factory::instantiateClass("%s");', $this->a_objectName);
		if (! $this->assertExceptionTrue($assertion, sprintf("New Stack object - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		
		$this->a_compareExpectedType(true, $expected, get_class($this->a_object));
	}

}
