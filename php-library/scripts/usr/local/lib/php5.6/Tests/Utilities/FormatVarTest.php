<?php
namespace Tests\Utilities;

use Library\Utilities\FormatVar;

/*
 *		FormatVarTest.php is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * FormatVarTest
 *
 * FormatVar tests.
 * @author Jay Wheeler
 * @version 3.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Utilities
 */
class FormatVarTest extends \Application\Launcher\Testing\UtilityMethods
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

    	$this->a_formatted = null;

		$this->a_testArray = array('line',
  	                             'function',
  	                             'class',
  								 'object',
  								 'Log_Method',
  								 'type');

		$this->a_printArray($this->a_testArray);

		$this->a_testArray['test_array'] = $this->a_testArray;
		
		$this->a_indentCharacter("....");
		$this->a_printArray($this->a_testArray);

		$this->a_indentCharacter("\t");
		$this->a_printArray($this->a_testArray);
		
		$this->a_toStd($this->a_testArray);
		$this->a_printArray($this->a_stdClass, 'stdClass');
    }

	/**
	 * a_indentCharacter
	 *
	 * set the indent character string
	 * @param string $indent = indent character string
	 */
	public function a_indentCharacter($indent)
	{
    	$this->labelBlock('indentCharacter Tests.', 60, '*');

		$assertion = sprintf('$this->a_result = \Library\Utilities\FormatVar::indentCharacter("%s");', $indent);
   		if (! $this->assertTrue($assertion, sprintf("Setting indent character sequence - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_compareExpectedType(true, $indent, $this->a_result);
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
