<?php
namespace Tests\Debug;
use Library\Debug;

/*
 *		Debug\TracebackTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * TracebackTest
 *
 * Debug\Traceback tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2013 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Debug
 */
class TracebackTest extends \Application\Launcher\Testing\UtilityMethods
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

		$this->assertFailures(false);

		$this->a_newTraceback();

		$this->a_listElements();

		$this->a_searchAndValidate('Tests\Debug\TracebackTest', 'a_newTraceback');
		$this->a_searchAndValidate('Tests\Debug\TracebackTest', 'assertionTests');

		$this->a_prev($this->a_element - 1);
		$this->a_printArray($this->a_currentElement);

		$this->a_getCurrentElement($this->a_currentElement);

		$this->a_classObject($this->a_elementArray['object']);
		$this->a_callArgs($this->a_elementArray['args']);
		$this->a_classFile($this->a_elementArray['file']);
		$this->a_lineNumber($this->a_elementArray['line']);
		$this->a_methodName($this->a_elementArray['function']);
		$this->a_className($this->a_elementArray['class']);
		$this->a_classType($this->a_elementArray['type']);
	}

	/**
	 * a_classType
	 *
	 * Check current class type matches expected
	 * @param string $expected = class type expected
	 */
	public function a_classType($expected)
	{
		$this->labelBlock('Class Type.', 40, '*');

   		$assertion = '$this->a_data = $this->a_traceback->type();';
		if (! $this->assertTrue($assertion, sprintf('Get class type - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_checkString($expected);
	}

	/**
	 * a_className
	 *
	 * Check current class name matches expected
	 * @param string $expected = class name expected
	 */
	public function a_className($expected)
	{
		$this->labelBlock('Class Name.', 40, '*');

   		$assertion = '$this->a_data = $this->a_traceback->className();';
		if (! $this->assertTrue($assertion, sprintf('Get class name - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_checkString($expected);
	}

	/**
	 * a_methodName
	 *
	 * Check current class method (function) matches expected
	 * @param string $expected = class method expected
	 */
	public function a_methodName($expected)
	{
		$this->labelBlock('Class Method.', 40, '*');

   		$assertion = '$this->a_data = $this->a_traceback->methodName();';
		if (! $this->assertTrue($assertion, sprintf('Get class method - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_checkString($expected);
	}

	/**
	 * a_lineNumber
	 *
	 * Check current line number matches expected
	 * @param object $expected = line number expected
	 */
	public function a_lineNumber($expected)
	{
		$this->labelBlock('Line Number.', 40, '*');

   		$assertion = '$this->a_data = $this->a_traceback->lineNumber();';
		if (! $this->assertTrue($assertion, sprintf('Line Number - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
	}

	/**
	 * a_classFile
	 *
	 * Check current class file matches expected
	 * @param object $expected = class file expected
	 */
	public function a_classFile($expected)
	{
		$this->labelBlock('Class File.', 40, '*');

   		$assertion = '$this->a_data = $this->a_traceback->classFile();';
		if (! $this->assertTrue($assertion, sprintf('Get class file - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_checkString($expected);
	}

	/**
	 * a_callArgs
	 *
	 * Check current class arguements matches expected
	 * @param array $expected = arguements expected
	 */
	public function a_callArgs($expected)
	{
		$this->labelBlock('Call Args.', 40, '*');

		if (! $expected)
		{
	   		$assertion = '$this->a_testArray = $this->a_traceback->callArgs();';
			if (! $this->assertFalse($assertion, sprintf('No Call Args - False assertion: %s', $assertion)))
			{
				$this->a_outputAndDie();
			}

		}
		else
		{
	   		$assertion = '$this->a_testArray = $this->a_traceback->callArgs();';
			if (! $this->assertTrue($assertion, sprintf('Call Args - Asserting: %s', $assertion)))
			{
				$this->a_outputAndDie();
			}

			$assertion = '($this->a_testArray == $expected)';
			if (! $this->assertTrue($assertion, sprintf('Comparing Args - Asserting: %s', $assertion)))
			{
				$this->a_outputAndDie();
			}
		}
	}

	/**
	 * a_classObject
	 *
	 * Check current class object matches expected
	 * @param object $expected = object expected
	 */
	public function a_classObject($expected)
	{
		$this->labelBlock('Class object.', 40, '*');

   		$assertion = '$this->a_object = $this->a_traceback->classObject();';
		if (! $this->assertTrue($assertion, sprintf('Get current object - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$assertion = '($this->a_object == $expected)';
		if (! $this->assertTrue($assertion, sprintf('Comparing objects - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_checkString
	 *
	 * Check the string in a_data with the expected string
	 * @param string $expected = string to expect
	 */
	public function a_checkString($expected)
	{
		$this->labelBlock('Check String.', 40, '*');

   		$assertion = sprintf('($this->a_data == "%s");', $expected);
		if (! $this->assertTrue($assertion, sprintf('Checking string - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_searchAndValidate
	 *
	 * Search for class and method, validate results
	 * @param string $class = class name
	 * @param string $method = method/function name
	 */
	public function a_searchAndValidate($class, $method)
	{
		$this->labelBlock('Search and Validate.', 40, '*');

		$this->a_searchStack($class, $method);

		$this->a_getElement();
		$this->a_printArray($this->a_elementArray);
		$testArray = $this->a_elementArray;

		$this->a_skipLevels($this->a_element);
		$this->a_printArray($this->a_elementArray);

		$this->a_compareArray($testArray, true, $this->a_elementArray);

		$this->a_key($this->a_element);
	}

	/**
	 * a_prev
	 *
	 * move to previous level and compare with expected
	 * @param integer $expected = key to compare against result
	 */
	public function a_prev($expected)
	{
		$this->labelBlock('Previous.', 40, '*');

   		$assertion = '$this->a_currentElement = $this->a_traceback->prev();';
		if (! $this->assertTrue($assertion, sprintf('Get previous level - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_key($expected);
	}

	/**
	 * a_key
	 *
	 * Check current level (stack key) against expected level
	 * @param integer $expected
	 */
	public function a_key($expected)
	{
		$this->labelBlock('Key.', 40, '*');

   		$assertion = '$this->a_data = $this->a_traceback->key();';
		if (! $this->assertTrue($assertion, sprintf('Get current level - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
	}

	/**
	 * a_skipLevels
	 *
	 * Skip to the requested level and get the element array
	 */
	public function a_skipLevels($skip)
	{
		$this->labelBlock('Skip levels.', 40, '*');

		$this->a_element = $skip;
   		$assertion = sprintf('$this->a_elementArray = $this->a_traceback->skip(%u);', $this->a_element);
		if (! $this->assertTrue($assertion, sprintf('Getting element - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$assertion = '(is_array($this->a_elementArray));';
		if (! $this->assertTrue($assertion, sprintf('Check for array - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_getCurrentElement
	 *
	 * Get the current element and check for expected
	 */
	public function a_getCurrentElement($expected)
	{
		$this->labelBlock('Get Current Element.', 40, '*');

   		$assertion = '$this->a_traceback->currentElement();';
		if (! $this->assertTrue($assertion, sprintf('Checking currentElement is valid - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$assertion = '$this->a_elementArray = $this->a_traceback->element();';
		if (! $this->assertTrue($assertion, sprintf('Get the current array - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$assertion = '(is_array($this->a_elementArray));';
		if (! $this->assertTrue($assertion, sprintf('Check for array - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareArray($expected, true, $this->a_elementArray);
	}

	/**
	 * a_getElement
	 *
	 * Get the element indicated by a_element
	 */
	public function a_getElement()
	{
		$this->labelBlock('Get element.', 40, '*');

		$this->a_showData($this->a_element, 'a_element');

   		$assertion = sprintf('$this->a_elementArray = $this->a_traceback["%s"];', $this->a_element);
		if (! $this->assertTrue($assertion, sprintf('Getting element - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$assertion = '(($this->a_data = is_array($this->a_elementArray)));';
		if (! $this->assertTrue($assertion, sprintf('Check for array - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_showData($this->a_data, 'a_data');
	}

	/**
	 * a_searchStack
	 *
	 * Search the stack for the given class and method
	 * @param string $class = name of class to search for
	 * @param string $method = name of method in $class to search for
	 */
	public function a_searchStack($class, $method)
	{
		$this->labelBlock('Search Stack.', 40, '*');

		$assertion = sprintf('$this->a_element = $this->a_traceback->searchStack("%s", "%s");', $class, $method);
		if (! $this->assertTrue($assertion, sprintf('Search stack - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$assertion = '($this->a_element !== false)';
		if (! $this->assertTrue($assertion, sprintf('Check result - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_newTraceback
	 *
	 * Create a new Debug\Traceback object
	 */
	public function a_newTraceback()
	{
		$this->labelBlock('New Traceback.', 40, '*');

		$this->a_traceback = null;

		$assertion = '$this->a_traceback = new \Library\Debug\Traceback();';
		if (! $this->assertTrue($assertion, sprintf('New traceback class - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_listElements
	 *
	 * List the elements in the stack
	 */
	public function a_listElements()
	{
		$this->labelBlock('List elements.', 40, '*');

		$this->a_stack = null;
		$assertion = '$this->a_stack = sprintf("%s", $this->a_traceback);';
		if (! $this->assertTrue($assertion, sprintf('Getting traceback stack listing - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$assertion = sprintf('($this->a_stack == "%s");', $this->a_stack);
		if (! $this->assertTrue($assertion, sprintf('Checking traceback stack - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

}
