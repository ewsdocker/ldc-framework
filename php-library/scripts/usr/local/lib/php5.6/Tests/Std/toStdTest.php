<?php
namespace Tests\Std;
/*
 *		Std\toStdTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 *
 * Library\Std Tests
 *
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Std
 */
class toStdTest extends \Application\Launcher\Testing\UtilityMethods
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

		$nested = array('properties'		=> array('Log_Value'		=> 200,
		                    						 'File_Adapter'		=> 'fileobject',
													 'File_Mode'		=> 'r'),
		                'Log_Adapter'		=> 'stream',
						'streamProperties'	=> array('Stream_Adapter'	=> 'stream',
													 'Stream_Mode'		=> 'w'));

		$this->a_toStd($nested);
		$this->a_printArray($this->a_stdClass, 'stdClass', false, false, true);

		$this->a_fromStdClass($this->a_stdClass, new testObject());
		var_dump($this->a_object);
		$this->a_printArray($this->a_object, get_class($this->a_object), false, false, true);
		
		$this->a_fromObject($this->a_object);
		
		var_dump($this->a_stdClass);
		$this->a_printArray($this->a_stdClass, 'stdClass', false, false, true);
    }

	/**
	 * a_fromObject
	 *
	 * Convert an object to a stdClass object
	 * @param object $test = test class object to convert to
	 */
	public function a_fromObject($test)
	{
    	$this->labelBlock('fromObject.', 60, '*');

    	$this->a_stdClass = null;
		$this->a_object = $test;

    	$assertion = '$this->a_stdClass = \Library\Std\Convert::fromObject($this->a_object);';
		if (! $this->assertExceptionTrue($assertion, sprintf('fromObject - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

    /**
	 * a_fromStdClass
	 *
	 * Convert a stdClass object to an object
	 * @param object $test = test class object to convert to
	 * @param object $stdClass = stdClass object to convert from
	 */
	public function a_fromStdClass($stdClass, $test)
	{
    	$this->labelBlock('fromStdClass Tests.', 60, '*');

    	$this->a_stdClass = $stdClass;
		$this->a_object = $test;

    	$assertion = '$this->a_object = \Library\Std\Convert::fromStd($this->a_stdClass, $this->a_object);';
		if (! $this->assertExceptionTrue($assertion, sprintf('fromStdClass - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_toStd
	 *
	 * Convert an array to a stdClass object
	 * @param array $array = array to convert
	 */
	public function a_toStd($array)
	{
    	$this->labelBlock('toStd Tests.', 60, '*');

    	$this->a_array = $array;

    	$assertion = '$this->a_stdClass = \Library\Std\Convert::toStd($this->a_array);';
		if (! $this->assertExceptionTrue($assertion, sprintf('toStd - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

}
