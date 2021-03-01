<?php
namespace Tests\Autoload;

/*
 *		LibrariesTest is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * Autoload\LibrariesTest
 *
 * Autoload_Libraries tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Autoload
 * @subpackage Libraries
 */
class LibrariesTest extends \Application\Launcher\Testing\UtilityMethods
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

		$this->a_instantiateLibrary();

		$this->a_functionExists(array($this->a_library, 'loader'));

		$this->a_setLibraries($this->properties->Roots);

    	$this->a_loadClass('GeoCalc');

		$this->a_newClass('GeoCalc');

   		$this->a_loadClass('\Zend\Session\AbstractManager');

   		$this->a_loadClass('\Zend\Session\SessionManager');

    }

    /**
     * a_instantiateLibrary
     * 
     * Instantiate the autoload library class
     */
    public function a_instantiateLibrary()
    {
    	$this->labelBlock('Instantiate Library', 40, '*');

    	$assertion = '$this->a_library = \Library\Autoload\Libraries::instantiate()';
		if (! $this->assertTrue($assertion, sprintf("Getting Autoload\Libraries reference, Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_functionExists
     *
     * Check if function exists
     * @param array $function = function array to check
     */
    public function a_functionExists($function)
    {
    	$this->labelBlock('Function Exists', 40, '*');

    	$this->a_function = $function;

    	$assertion = '\Library\Autoload\Spl::exists($this->a_function);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_setLibraries
     *
     * Set library search directories
     * @param array $directories = array of directory names to set
     */
    public function a_setLibraries($directories)
    {
    	$this->labelBlock('Set Libraries', 40, '*');

    	$this->a_directories = $directories;

		$assertion = '\Library\Autoload\Libraries::instantiate()->setLibraries($this->a_directories);';
		if (! $this->assertTrue($assertion, sprintf("Set libraries, Asserting: %s", $assertion)))
		{
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

    	$assertion = sprintf('$this->a_data = new %s();', $className);
		if (! $this->assertTrue($assertion, sprintf("New instance of class %s, Asserting: %s", $className, $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_loadClass
     *
     * Load a class
     * @param string $className = name of class to load
     */
    public function a_loadClass($className)
    {
    	$this->labelBlock('Load Class', 40, '*');

    	$assertion = sprintf("\Library\Autoload\Libraries::loadClass('%s')", $className);
		if (! $this->assertTest($assertion, sprintf("Load class %s, Asserting: %s", $className, $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

}
