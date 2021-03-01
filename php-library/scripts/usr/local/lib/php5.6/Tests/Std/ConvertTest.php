<?php
namespace Tests\Std;
/*
 *		Std\ConvertTest is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, 
 *      	or from http://opensource.org/licenses/academic.php
*/
/**
 *
 * Library\Std Tests
 *
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Std
 */
class ConvertTest extends \Application\Launcher\Testing\UtilityMethods
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

		$properties = array('Log_Value'		=> 200,
		                    'File_Adapter'	=> 'fileobject',
							'File_Mode'		=> 'r');
		$this->a_toStd($properties);

		$this->a_printArray(get_object_vars($this->a_stdClass), 'stdClass', false, false);

		$this->a_toArray($this->a_stdClass);

		$this->a_printArray($this->a_localArray, 'array', false, false);

		$nested = array('properties'		=> $properties,
		                'Log_Adapter'		=> 'stream',
						'streamProperties'	=> array('Stream_Adapter'	=> 'stream',
													 'Stream_Mode'		=> 'w'));

		$this->a_toStd($nested);
		$this->a_printArray($this->a_stdClass, 'stdClass', false, false);

		$this->a_toArray($this->a_stdClass);
		$this->a_printArray($this->a_localArray, 'array', false, false);
    }

    /**
     * a_toArray
     *
     * Convert the stdClass to an array
     * @param object $stdClass = stdClass object to convert to array
     */
    public function a_toArray($stdClass)
	{
    	$this->labelBlock('toArray Tests.', 60, '*');

    	$this->a_class = $stdClass;

    	$assertion = '$this->a_localArray = \Library\Std\Convert::toArray($this->a_class);';
		if (! $this->assertExceptionTrue($assertion, sprintf('toArray - Asserting: %s', $assertion)))
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
		if (! $this->assertExceptionTrue($assertion, sprintf('toStdClass - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

}
