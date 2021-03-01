<?php
namespace Tests\Autoload;

/*
 *		AutoloadTest.php is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * AutoloadTest
 *
 * Autoload tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2013 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Autoload
 */
class AutoloadTest extends \Application\Launcher\Testing\UtilityMethods
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

    	$this->a_genericInstantiateClass();

   		$this->a_genericLoadClass('\Library\Exception');

		$this->a_newClass('\Library\Stack');

		$this->a_getRegistered();

		$this->a_unRegister();

		$this->a_getRegistered();
    }

    /**
     * a_genericInstantiateClass
     *
     * Instantiate class
     */
    public function a_genericInstantiateClass()
    {
    	$this->labelBlock('Generic Instantiate Class', 40, '*');

    	$this->a_generic = array();
		$this->a_pathArray = explode(PATH_SEPARATOR, get_include_path());

		$assertion = '$this->a_generic = \Library\Autoload\Generic::instantiate($this->a_pathArray, true, true);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Prepending Generic autoloader, Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_genericLoadClass
     *
     * Load a class directly through autloader
     * @param string $className = name of class to load
     */
    public function a_genericLoadClass($className)
    {
    	$this->labelBlock('Generic Load Class', 40, '*');

    	$assertion = sprintf("\Library\Autoload\Generic::loadClass('%s')", $className);
		if (! $this->assertTrue($assertion, sprintf("Load class %s, Asserting: %s", $className, $assertion)))
		{
			$this->a_unRegister();
			$this->a_outputAndDie();
		}
    }

    /**
     * a_newClass
     *
     * create a new class
     * @param string $className = name of class to create
     */
    public function a_newClass($className)
    {
    	$this->labelBlock('New Class', 40, '*');

    	$this->a_class = null;

		$assertion = sprintf('$this->a_class = new %s();', $className);
		if (! $this->assertTrue($assertion, sprintf("Create new class %s, Asserting: %s", $className, $assertion)))
		{
			$this->a_unRegister();
			$this->a_outputAndDie();
		}
    }

    /**
     * a_getRegistered
     *
     * get all registered spl_autoloaders
     */
    public function a_getRegistered()
    {
    	$this->labelBlock('Get Registered', 40, '*');

    	$this->a_registered = array();

		$assertion = '$this->a_registered = \Library\Autoload\Spl::getRegistered();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Getting registered autoloaders, Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_printArray($this->a_registered, 'Spl Functions', false, true);
    }

    /**
     * a_unRegister
     *
     * unregister the Generic autoloader
     */
    private function a_unRegister()
    {
    	$this->labelBlock('Un-Register', 40, '*');

    	$this->a_generic = array(\Library\Autoload\Generic::instantiate(), 'loader');

		$assertion = '\Library\Autoload\Spl::unRegister($this->a_generic);';
		if (! $this->assertExceptionFalse($assertion, sprintf("Unregistering Autoload\Generic, Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

    }

}
