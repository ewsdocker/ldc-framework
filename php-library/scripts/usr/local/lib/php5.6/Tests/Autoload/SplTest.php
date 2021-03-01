<?php
namespace Tests\Autoload;

/*
 *		Autoload\SplTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * Autoload\SplTest
 *
 * Autoload tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Autoload
 */
class SplTest extends \Application\Launcher\Testing\UtilityMethods
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
     * @parm string $logger = (optional) name of the logger to use, null for none 
     */
    public function assertionTests($logger=null, $format=null)
    {
    	parent::assertionTests($logger, $format);

		$this->a_getRegistered();
		
		$this->a_simple = array(\Library\Autoload\Simple::instantiate(), 'loader');

		$this->a_register($this->a_simple);

		$this->a_getRegistered();

		$this->a_exists($this->a_simple, true);
		$this->a_existsAt($this->a_simple, true);

		$this->a_unRegister($this->a_simple);
		$this->a_getRegistered();

		$this->a_existsAt($this->a_simple, false);
		$this->a_exists($this->a_simple, false);
    }

    /**
     * a_register
     *
     * Register the class array
     */
    public function a_register()
    {
    	$this->labelBlock('Register', 40, '*');

    	$assertion = '\Library\Autoload\Spl::register($this->a_simple, false, true);';
		if (! $this->assertExceptionFalse($assertion, sprintf("Prepending simple autoloader, Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

	}

    /**
     * a_unRegister
     *
     * Un-register the supplied object class
     * @param object $object = class to un-register
     */
    public function a_unRegister($object)
    {
    	$this->labelBlock('UnRegister', 40, '*');

    	$this->a_object = $object;

		$assertion = '\Library\Autoload\Spl::unRegister($this->a_object);';
		if (! $this->assertExceptionFalse($assertion, sprintf("Unregistering autoloader, Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

    }

    /**
     * a_getRegistered
     *
     * Get registered autoloaders
     */
    public function a_getRegistered()
    {
    	$this->labelBlock('Get Registered', 40, '*');

    	$assertion = '$this->a_registered = \Library\Autoload\Spl::getRegistered();';
		if (! $this->assertTrue($assertion, sprintf("Getting registered autoloaders, Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_printArray($this->a_registered, 'Spl Functions', false);
    }

    /**
     * a_existsAt
     *
     * Check for existence of $object
     * @param object $object = object to for existence
     * @param boolean $test = (optional) test to perform: true = test for existence, false = non-existence, default = true
     */
    public function a_existsAt($object, $test=true)
    {
    	$this->labelBlock('ExistsAt', 40, '*');

    	$this->a_object = $object;

		$assertion = '(\Library\Autoload\Spl::existsAt($this->a_object) === 0);';

		if ($test)
		{
			if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
			{
				$this->a_outputAndDie();
			}
		}
		else
		{
			if (! $this->assertExceptionFalse($assertion, sprintf("Falsely asserting: %s", $assertion)))
			{
				$this->a_outputAndDie();
			}
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_exists
     *
     * Check for existence of $object
     * @param object $object = object to for existence
     * @param boolean $test = (optional) test to perform: true = test for existence, false = non-existence, default = true
     */
    public function a_exists($object, $test=true)
    {
    	$this->labelBlock('Exists', 40, '*');

    	$this->a_object = $object;

		$assertion = '\Library\Autoload\Spl::exists($this->a_object);';

		if ($test)
		{
			if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
			{
				$this->a_outputAndDie();
			}
		}
		else
		{
			if (! $this->assertExceptionFalse($assertion, sprintf("Falsely asserting: %s", $assertion)))
			{
				$this->a_outputAndDie();
			}
		}

		$this->a_exceptionCaughtFalse();
    }

}
