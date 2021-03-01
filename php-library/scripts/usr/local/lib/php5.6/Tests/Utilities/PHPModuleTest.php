<?php
namespace Tests\Utilities;

use Library\Utilities\PHPModule;
use Library\CliParameters;

/*
 *		PHPModuleTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source, 
 *	  		or from http://opensource.org/licenses/academic.php
*/
/**
 *
 * Library\Utilities\PHPModule tests
 *
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Utilities
 */
class PHPModuleTest extends \Application\Launcher\Testing\UtilityMethods
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
	 * @param string $format = (optional) format for logging
	 */
	public function assertionTests($logger=null, $format=null)
	{
		parent::assertionTests($logger, $format);

		$this->a_loaded(array('http', 'curl', 'zend'), 'curl');

		$this->a_loaded(array('http', 'curl', 'zend'), 'curl');
	}

	/**
	 * a_loaded
	 *
	 * Get first module discovered loaded in the provided array
	 * @param array $loaded = array containing module names to check for
	 * @param string $expected = name of the first module expected, or null to skip
	 */
	public function a_loaded($loaded, $expected=null)
	{
		$this->labelBlock('Modules loaded', 60, '*');

		$this->a_loaded = $loaded;
		$this->a_expected = $expected;

		$this->a_showData($this->a_loaded, 'Modules loaded');
		$this->a_showData($this->a_expected, 'Expected');

		$assertion = '$this->a_module = \Library\Utilities\PHPModule::loaded($this->a_loaded);';
		if ($this->a_expected === false)
		{
			if (! $this->assertFalse($assertion, sprintf('Asserting false: %s', $assertion)))
			{
				$this->a_outputAndDie();
			}
		}
		else
		{
			if (! $this->assertTrue($assertion, sprintf('Asserting true: %s', $assertion)))
			{
				$this->a_outputAndDie();
			}
		}

		$this->a_showData($this->a_module, 'module');
		$this->a_compareExpectedType(true, $this->a_expected, $this->a_module);
	}

}
