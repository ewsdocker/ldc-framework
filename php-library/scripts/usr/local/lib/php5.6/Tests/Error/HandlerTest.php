<?php
namespace Tests\Error;
use Library\PrintU;

/*
 *		Error\HandlerTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * Error\HandlerTest.
 *
 * The Error_Code tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Error
 */
class HandlerTest extends \Application\Launcher\Testing\UtilityMethods
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

		$this->labelBlock('Testing ErrorHandler from Library\Testing\Setup.', 60, '*');

    	$this->a_notDefined();

		$this->a_vector1 = array(2, 3, "foo");
		$this->a_vector2 = array(5.5, 4.3, -1.6);
		$this->a_vector3 = array(1, -3);

		$this->a_distance($this->a_vector3, $this->a_vector2);

		$this->a_distance($this->a_vector2, "i am not an array");

		$this->a_distance($this->a_vector1, $this->a_vector2);

		$this->a_syntaxError();

		$this->labelBlock('Testing NEW ErrorHandler.', 60, '*');

		$this->a_newErrorHandler();

		$this->a_setHandler();
		$this->a_getErrorLevel(E_ALL);

		$this->a_phpErrorLevel(E_ALL | E_PARSE | E_COMPILE_ERROR);

		$this->a_reportErrors(true);
		$this->a_displayErrors(true);
		$this->a_formatXML(true);

		$this->a_logErrors(false);
		$this->a_setExceptions(false);

		$this->a_initializeHandler();

		$this->a_notDefined();

		$this->a_vector1 = array(2, 3, "foo");
		$this->a_vector2 = array(5.5, 4.3, -1.6);
		$this->a_vector3 = array(1, -3);

		$this->a_distance($this->a_vector3, $this->a_vector2);

		$this->a_distance($this->a_vector2, "i am not an array");

		$this->a_distance($this->a_vector1, $this->a_vector2);

		$this->a_syntaxError();

		$this->a_formatXML(false);
		$this->a_syntaxError();

		$this->a_deleteHandler();
    }

    /**
     * a_syntaxError
     *
     * Create a syntax error
     */
    public function a_syntaxError()
    {
    	$this->labelBlock('Syntax Error.', 40, '*');

    	$this->a_level = $level;

    	$assertion = '(($this->a_data = $this->a_handler->phpErrorLevel($this->a_level) !== null);';
		if (! $this->assertExceptionFalse($assertion, sprintf("Syntax Error - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_syntaxError
     *
     * Delete the current handler and restore the old one
     */
    public function a_deleteHandler()
    {
    	$this->labelBlock('Delete Handler.', 40, '*');

    	$assertion = '$this->a_handler->deleteHandler();';
		if (! $this->assertFalse($assertion, sprintf("Delete handler - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		unset($this->a_handler);
    }

    /**
     * a_setExceptions
     *
     * Set/reet Error Exceptions
     * @param boolean $set = true to allow error exceptions, false to not
     */
    public function a_setExceptions($set)
    {
    	$this->labelBlock('Set Exceptions.', 40, '*');

    	$this->a_set = $set;
    	$assertion = '(($this->a_data = $this->a_handler->enableExceptions($this->a_set)) !== null);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Set Exceptions - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($set);
    }

    /**
     * a_notDefined
     *
     * Test not defined error
     */
    public function a_notDefined()
    {
    	$this->labelBlock('Not Defined.', 40, '*');

		$assertion = '$this->a_data = I_AM_NOT_DEFINED;';
		if (! $this->assertExceptionTrue($assertion, sprintf("Not Defined - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpected('I_AM_NOT_DEFINED');
	}

    /**
     * a_logErrors
     *
     * set/get the error logging flag
     * @param boolean $set = true to set, false to reset, null to query
     */
    public function a_logErrors($set=null)
    {
    	$this->labelBlock('Log Errors.', 40, '*');

    	$this->a_flag = $set;
    	$assertion = '(($this->a_data = $this->a_handler->logErrors($this->a_flag)) !== null);';
		if (! $this->assertTrue($assertion, sprintf("Log Errors - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		if ($set !== null)
		{
			$this->a_compareExpected($set);
		}
    }

    /**
     * a_formatXML
     *
     * set the format
     * @param boolean $set = true to set, false to reset, null to query
     */
    public function a_formatXML($set=null)
    {
    	$this->labelBlock('Format XML.', 40, '*');

    	$this->a_flag = $set;
    	$assertion = '(($this->a_data = $this->a_handler->formatXML($this->a_flag)) !== null);';
		if (! $this->assertTrue($assertion, sprintf("Format XML - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		if ($set !== null)
		{
			$this->a_compareExpected($set);
		}
    }

    /**
     * a_displayErrors
     *
     * set/get the display errors flag
     * @param boolean $set = true to set, false to reset, null to query
     */
    public function a_displayErrors($set=null)
    {
    	$this->labelBlock('Display Errors.', 40, '*');

    	$this->a_flag = $set;
    	$assertion = '(($this->a_data = $this->a_handler->displayErrors($this->a_flag)) !== null);';
		if (! $this->assertTrue($assertion, sprintf("Display Errors - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		if ($set !== null)
		{
			$this->a_compareExpected($set);
		}
    }

    /**
     * a_reportErrors
     *
     * set/get the report errors flag
     * @param boolean $set = true to set, false to reset, null to query
     */
    public function a_reportErrors($set=null)
    {
    	$this->labelBlock('Report Errors.', 40, '*');

    	$this->a_flag = $set;
    	$assertion = '(($this->a_data = $this->a_handler->reportErrors($this->a_flag)) !== null);';
		if (! $this->assertTrue($assertion, sprintf("Report Errors - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		if ($set !== null)
		{
			$this->a_compareExpected($set);
		}
    }

    /**
     * a_getErrorLevel
     *
     * Get the current error level
     * @param integer $expected = expected error level
     */
    public function a_getErrorLevel($expected)
    {
    	$this->labelBlock('Get Error Level.', 40, '*');

    	$assertion = '(($this->a_data = $this->a_handler->phpErrorLevel()) !== null);';
		if (! $this->assertTrue($assertion, sprintf("getErrorLevel - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
    }

    /**
     * a_setHandler
     *
     * Set the error handler and error level
     * @param integer $level = (optional) error level (see php ErrorHandling)
     */
    public function a_phpErrorLevel($level=E_ALL)
    {
    	$this->labelBlock('Error Level.', 40, '*');

    	$this->a_level = $level;

    	$assertion = '(($this->a_data = $this->a_handler->phpErrorLevel($this->a_level)) !== null);';
		if (! $this->assertTrue($assertion, sprintf("phpErrorLevel - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($level);
    }

    /**
     * a_setHandler
     *
     * Set the error handler and error level
     * @param integer $level = (optional) error level (see php ErrorHandling)
     */
    public function a_setHandler($level=E_ALL)
    {
    	$this->labelBlock('Set Handler.', 40, '*');

    	$this->a_level = $level;

    	$assertion = '$this->a_handler->setHandler($this->a_level);';
		if (! $this->assertFalse($assertion, sprintf("setHandler - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_initializeHandler
     *
     * Initialize the error handler
     */
    public function a_initializeHandler()
    {
    	$this->labelBlock('Initialize Handler.', 40, '*');

    	$assertion = '$this->a_handler->Initialize();';
		if (! $this->assertFalse($assertion, sprintf("initialize - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_newFimctopmTest
     *
     * Create a new class method object
     */
    public function a_newErrorHandler()
    {
    	$this->labelBlock('Creating NEW ErrorHandler.', 40, '*');

    	$assertion = '(get_class($this->a_handler = new \Library\Error\Handler()) == "Library\Error\Handler");';
		if (! $this->assertExceptionTrue($assertion, sprintf("NEW ErrorHandler - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_distance
     *
     * A function to trigger errors with
     * @param array $vect1
     * @param array $vect2
     */
	public function a_distance($vect1, $vect2)
	{
    	$this->labelBlock('Distance.', 40, '*');

		if (! is_array($vect1) || ! is_array($vect2))
		{
			trigger_error("Incorrect parameters, arrays expected", E_USER_ERROR);
			return NULL;
		}

		if (count($vect1) != count($vect2))
		{
			trigger_error("Vectors need to be of the same size", E_USER_ERROR);
			return NULL;
		}

		for ($i = 0; $i < count($vect1); $i++)
		{
			$c1 = $vect1[$i];
			$c2 = $vect2[$i];

			$d = 0.0;

			if (! is_numeric($c1))
			{
				trigger_error("Coordinate $i in vector 1 is not a number, using zero", E_USER_WARNING);
				$c1 = 0.0;
			}

			if (!is_numeric($c2))
			{
				trigger_error("Coordinate $i in vector 2 is not a number, using zero", E_USER_WARNING);
				$c2 = 0.0;
			}

			$d += $c2 * $c2 - $c1 * $c1;
		}

		return sqrt($d);
	}

}
