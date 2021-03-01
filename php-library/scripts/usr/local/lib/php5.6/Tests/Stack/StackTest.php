<?php
namespace Tests\Stack;
use Library\Stack;

/*
 *		StackTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * StackTest
 *
 * Stack tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2013 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Stack
 */
class StackTest extends \Application\Launcher\Testing\UtilityMethods
{
	/**
	 * __construct
	 *
	 * Class constructor
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

    	$this->a_stackName = \Library\CliParameters::parameterValue('object', 'stack');
    	$this->a_stackClass = \Library\Stack\Factory::getInstance()->className($this->a_stackName);

    	$this->a_stack = null;
    	$this->a_newStack($this->a_stackClass);

    	$this->a_pushStack($this->testName);

		$this->a_countStack($this->a_stackLevels);

    	$this->a_printStacks();

    	$this->a_newStack($this->a_stackClass);
    	
    	$this->a_setIteratorMode(\SplDoublyLinkedList::IT_MODE_LIFO | \SplDoublyLinkedList::IT_MODE_KEEP);

    	$testArray = array('lime', 'green', 'yellow', 'zebra', 'finch', 'purple');
    	foreach($testArray as $data)
    	{
	    	$this->a_pushStack($data);
			$this->a_countStack($this->a_stackLevels);

			$this->a_printStacks();
    	}

    	$this->a_shiftStack(array_shift($this->a_localStack));
		$this->a_countStack($this->a_stackLevels);

		$this->a_printStacks();

    	$this->a_arrayAccessRead(3, $this->a_localStack[3]);
		$this->a_printStacks();

    	$this->a_arrayAccessWrite(1, 'tan');
    	$this->a_printStacks();

    	$this->a_countStack($this->a_stackLevels);

    	$this->a_arrayAccessRead(1, $this->a_localStack[1]);
    	$this->a_countStack($this->a_stackLevels);

    	$this->a_printStacks();

    	$this->a_arrayAccessWrite(null, 'chartreuse');
    	
    	$key = array_search('chartreuse', $this->a_localStack);

    	$this->a_arrayAccessRead($key, $this->a_localStack[$key]);

    	$this->a_countStack($this->a_stackLevels);

    	$this->a_printStacks();

    	$this->a_popStack(array_pop($this->a_localStack));
    	$this->a_countStack($this->a_stackLevels);
    	$this->a_printStacks();

    	$this->a_unshiftStack('chartreuse');
    	$this->a_countStack($this->a_stackLevels);
    	$this->a_printStacks();

    	$this->a_bottom(reset($this->a_localStack));
    	$this->a_printStacks();

    	$testArray = $this->a_localStack;
    	$this->a_top(end($this->a_localStack));

    	$this->a_prev(prev($this->a_localStack));

    	array_shift($testArray);

    	$this->a_rewind(reset($this->a_localStack));

    	$next = array_shift($testArray);
    	$this->a_next($next);

    	$this->a_key(1);
    	$this->a_valid(true);
    	$this->a_current($next);
    	
    	$this->a_next(end($this->a_localStack), false);
    	$this->a_printStacks();

    	$this->a_offsetGet(2, $this->a_localStack[2]);

    	$this->a_localStack[1] = 'tan';
    	$this->a_stackLevels = count($this->a_localStack);

    	$this->a_offsetSet(1, 'tan');

    	$this->a_printStacks();
    	
		$this->a_countStack($this->a_stackLevels);

    	$this->a_offsetExists(1);
    	$this->a_offsetUnset(1, 'tan');

    	$this->a_printStacks();

    	$this->a_countStack($this->a_stackLevels);
    	
    	$this->a_offsetSet(null, 'new top');

    	$this->a_printStacks();

    	$this->a_countStack($this->a_stackLevels);

    	$this->a_setIteratorMode(\SplDoublyLinkedList::IT_MODE_FIFO | \SplDoublyLinkedList::IT_MODE_DELETE);
    	$this->a_setIteratorMode(\SplDoublyLinkedList::IT_MODE_LIFO | \SplDoublyLinkedList::IT_MODE_DELETE);

    	$this->a_serialize();

    	$this->a_forEach();
    	$this->a_countStack(0);

    	$this->a_newStack($this->a_stackClass);

    	$this->a_pushStack('New stack');

    	$this->a_printStacks();

    	$this->a_unserialize($this->a_serialized);

    	$this->a_printStacks();

    	$this->a_getIteratorMode(\SplDoublyLinkedList::IT_MODE_LIFO | \SplDoublyLinkedList::IT_MODE_DELETE);
    }

    /**
     * a_forEach
     *
     * Iterate through the stack
     */
    public function a_forEach()
    {
    	$this->labelBlock('ForEach.', 40, '*');

    	foreach($this->a_stack as $key => $value)
    	{
    		$this->assertLogMessage(sprintf('%s = %s', $key, $value));
    	}
    }

    /**
     * a_getIteratorMode
     *
     * @param integer $expected = expected mode
     */
    public function a_getIteratorMode($expected=null)
    {
    	$this->labelBlock('Get Iterator Mode.', 40, '*');

    	if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '(($this->a_data = $this->a_stack->getIteratorMode()) !== null);';
		if (! $this->assertTrue($assertion, sprintf('Get Iterator Mode - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($expected)
		{
			$this->a_compareExpected($expected);
		}
		elseif ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}
    }

    /**
     * a_setIteratorMode
     *
     * @param integer $mode = mode to set
     * 
     */
    public function a_setIteratorMode($mode)
    {
    	$this->labelBlock('Set Iterator Mode.', 40, '*');

    	$this->a_mode = $mode;

        if ($this->verbose > 1)
    	{
    		$this->a_showData($this->a_mode, 'a_mode');
    	}

    	$assertion = sprintf('$this->a_stack->setIteratorMode(%u);', $this->a_mode);
		$this->assertExceptionFalse($assertion, sprintf('Set Iterator Mode - Asserting: %s', $assertion));
		
		$this->a_exceptionCaughtFalse(false);
    }

    /**
     * a_unserialize
     *
     * Unserialize the serialized stack
     */
    public function a_unserialize()
    {
    	$this->labelBlock('UnSerialize.', 40, '*');

    	$assertion = '$this->a_stack->unserialize($this->a_serialized);';
		if (! $this->assertFalse($assertion, sprintf('Unserialize - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_serialize
     *
     * Serialize the stack for storage
     */
    public function a_serialize()
    {
    	$this->labelBlock('Serialize.', 40, '*');

    	$assertion = '$this->a_serialized = $this->a_stack->serialize();';
		if (! $this->assertTrue($assertion, sprintf('Serialize - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_serialized, 'a_serialized');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_serialized, 'a_serialized');
		}
    }
    
    /**
     * a_offsetUnset
     *
     * Delete (unset) the specified key
     * @param mixed $key = key to access
     */
    public function a_offsetUnset($key)
    {
    	$this->labelBlock('Offset Unset.', 40, '*');

    	$this->a_arrayKey = $key;
    	if ($this->verbose > 1)
    	{
    		$this->a_showData($this->a_arrayKey, 'a_arrayKey');
    	}
    	 
    	$assertion = '$this->a_stack->offsetUnset($this->a_arrayKey);';
		if (! $this->assertExceptionFalse($assertion, sprintf('Deleting offset at %s - Asserting: %s', $key, $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		unset($this->a_localStack[$key]);
		$this->a_stackLevels = count($this->a_localStack);
    }

    /**
     * a_offsetExists
     *
     * Check for existence of a stack key
     * @param mixed $key = key to access
     */
    public function a_offsetExists($key)
    {
    	$this->labelBlock('Offset Exists.', 40, '*');

    	$this->a_arrayKey = $key;
       	if ($this->verbose > 1)
    	{
    		$this->a_showData($this->a_arrayKey, 'a_arrayKey');
    	}

    	$assertion = sprintf('(($this->a_data = $this->a_stack->offsetExists("%s")) != null);', $this->a_arrayKey);
		if (! $this->assertTrue($assertion, sprintf('Checking key %s - Asserting: %s', $key, $assertion)))
		{
    		$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}
		
		$this->a_compareExpected(true);
    }

    /**
     * a_offsetSet
     *
     * Test array write access-ability
     * @param mixed $key = key to access
     * @param mixed $data = value to store at $key
     */
    public function a_offsetSet($key, $data)
    {
    	$this->labelBlock('Offset Set.', 40, '*');

        if ($this->verbose > 1)
    	{
    		$this->a_showData($data, 'data');
    	}

    	if ($key === null)
    	{
    		$assertion = sprintf('$this->a_stack->offsetSet(null, "%s");', $data);
    		$this->a_localStack[] = $data;
    	}
    	else
    	{
    		$assertion = sprintf('$this->a_stack->offsetSet("%s", "%s");', $key, $data);
    		$this->a_localStack[$key] = $data;
    	   	if ($this->verbose > 1)
    		{
    			$this->a_showData($key, 'key');
    		}

    	}

		if (! $this->assertExceptionFalse($assertion, sprintf('OffsetSet at %s - Asserting: %s', $key, $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_stackLevels = count($this->a_localStack);
    }

    /**
     * a_offsetGet
     *
     * Test array read access-ability
     * @param mixed $key = key to access
     * @param mixed $expected = value expected at $key
     */
    public function a_offsetGet($key, $expected)
    {
    	$this->labelBlock('Offset Get.', 40, '*');

    	$this->a_arrayKey = $key;

        if ($this->verbose > 1)
    	{
    		$this->a_showData($this->a_arrayKey, 'a_arrayKey');
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = sprintf('$this->a_data = $this->a_stack->offsetGet("%s");', $this->a_arrayKey);
		if (! $this->assertExceptionTrue($assertion, sprintf('Getting stack at %s - Asserting: %s', $key, $assertion)))
		{
   			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpected($expected);
    }

    /**
     * a_current
     *
     * Get value at current stack pointer
     * @param mixed $expected = expected value
     */
    public function a_current($expected)
    {
    	$this->labelBlock('Current.', 40, '*');

        if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '$this->a_data = $this->a_stack->current();';
		if (! $this->assertTrue($assertion, sprintf('Current - Asserting: %s', $assertion)))
		{
   			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
    }

    /**
     * a_valid
     *
     * Check if key is valid
     * @param mixed $expected = expected result
     */
    public function a_valid($expected)
    {
    	$this->labelBlock('Valid.', 40, '*');

        if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '(($this->a_data = $this->a_stack->valid()) !== null);';
		if (! $this->assertTrue($assertion, sprintf('Key - Asserting: %s', $assertion)))
		{
    		$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

    	if ($this->verbose > 1)
    	{
    		$this->a_showData($this->a_data, 'a_data');
    	}

		$this->a_compareExpected($expected);
    }

    /**
     * a_key
     *
     * Get the current key
     * @param mixed $expected = expected key
     */
    public function a_key($expected)
    {
    	$this->labelBlock('Key.', 40, '*');

        if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '$this->a_data = $this->a_stack->key();';
		if (! $this->assertTrue($assertion, sprintf('Key - Asserting: %s', $assertion)))
		{
    		$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

      	if ($this->verbose > 1)
    	{
    		$this->a_showData($this->a_data, 'a_data');
    	}

		$this->a_compareExpected($expected);
    }

    /**
     * a_next
     *
     * Advance the stack pointer to the next element
     * @param mixed $expected = expected value
     * @oaran boolean $result = expected assert result
     */
    public function a_next($expected, $result=true)
    {
    	$this->labelBlock('Next.', 40, '*');

        if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    		$this->a_showData($result, 'result');
    	}

    	$assertion = '(($this->a_data = $this->a_stack->next()) !== null);';
		if (! $this->assertTrue($assertion, sprintf('Next - Asserting: %s', $assertion)))
		{
    		$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

        if ($this->verbose > 1)
    	{
    		$this->a_showData($this->a_data, 'a_data');
    	}

		$this->a_compareExpectedType($result, $expected);
    }

    /**
     * a_rewind
     *
     * Rewind the stack pointer to the first element (0)
     * @param mixed $expected = (optional) expected value
     */
    public function a_rewind($expected=null)
    {
    	$this->labelBlock('Rewind.', 40, '*');

    	if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '$this->a_data = $this->a_stack->rewind();';
		if (! $this->assertTrue($assertion, sprintf('Rewind - Asserting: %s', $assertion)))
		{
    		$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

        if ($this->verbose > 1)
    	{
    		$this->a_showData($this->a_data, 'a_data');
    	}

    	if ($expected != null)
    	{
			$this->a_compareExpected($expected);
    	}
    }

    /**
     * a_prev
     *
     * retrieve the previous item from the stack
     * @param mixed $expected
     */
    public function a_prev($expected)
    {
    	$this->labelBlock('Previous item.', 40, '*');

        if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

//$this->a_data = $this->a_stack->prev();
    	$assertion = '$this->a_data = $this->a_stack->prev();';
		if (! $this->assertTrue($assertion, sprintf('Previous item - Asserting: %s', $assertion)))
		{
    		$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

        if ($this->verbose > 1)
    	{
    		$this->a_showData($this->a_data, 'a_data');
    	}

		$this->a_compareExpected($expected);
    }


       /**
     * a_top
     *
     * Get index of the top of the stack
     * @param integer $expected = expected top of stack index
     */
    public function a_top($expected)
    {
    	$this->labelBlock('Stack top.', 40, '*');

        if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '$this->a_data = $this->a_stack->top();';
		if (! $this->assertExceptionTrue($assertion, sprintf('Top of stack index - Asserting: %s', $assertion)))
		{
    		$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

        if ($this->verbose > 1)
    	{
    		$this->a_showData($this->a_data, 'a_data');
    	}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpected($expected);
    }

    /**
     * a_bottom
     *
     * Get item from the bottom of the stack
     * @param integer $expected = expected top of stack index
     */
    public function a_bottom($expected)
    {
    	$this->labelBlock('Stack bottom.', 40, '*');

        if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '$this->a_data = $this->a_stack->bottom();';
		if (! $this->assertExceptionTrue($assertion, sprintf('Bottom of stack - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

        if ($this->verbose > 1)
    	{
    		$this->a_showData($this->a_data, 'a_data');
    	}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpected($expected);
    }

    /**
     * a_arrayAccesswrite
     *
     * Test array write access-ability
     * @param mixed $key = key to access
     * @param mixed $data = value to store at $key
     */
    public function a_arrayAccessWrite($key, $data)
    {
    	$this->labelBlock('Array access write.', 40, '*');

    	if ($this->verbose > 1)
    	{
    		$this->a_showData($key, 'key');
    		$this->a_showData($data, 'data');
    	}

		$this->a_data = $data;
		if ($key)
		{
	    	$assertion = sprintf('$this->a_stack[%u] = "%s";', $key, $this->a_data);
		}
		else
		{
	    	$assertion = sprintf('$this->a_stack[] = "%s";', $this->a_data);
		}

		if (! $this->assertExceptionTrue($assertion, sprintf('Writing stack at %s - Asserting: %s', $key, $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		if ($key)
		{
			$this->a_localStack[$key] = $data;
		}
		else
		{
			$this->a_localStack[] = $data;
		}

		$this->a_stackLevels = count($this->a_localStack);
    }

    /**
     * a_arrayAccessRead
     *
     * Test array read access-ability
     * @param mixed $key = key to access
     * @param mixed $expected = value expected at $key
     */
    public function a_arrayAccessRead($key, $expected)
    {
    	$this->labelBlock('Array access read.', 40, '*');

    	$this->a_arrayKey = $key;

    	if ($this->verbose > 1)
    	{
    		$this->a_showData($key, 'key');
    		$this->a_showData($expected, 'expected');
    	}

	   	$assertion = sprintf('$this->a_data = $this->a_stack[%s];', $this->a_arrayKey);
		if (! $this->assertExceptionTrue($assertion, sprintf('Getting stack at %s - Asserting: %s', $key, $assertion)))
		{
    		$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

        if ($this->verbose > 1)
    	{
    		$this->a_showData($this->a_data, 'a_data');
    	}

		$this->a_exceptionCaughtFalse();

		$this->a_printStacks();

		$this->a_compareExpected($expected);
    }

    /**
     * a_shiftStack
     *
     * Shift the stack head item off the stack
     * @param mixed $expected = expected value of item removed from stack
     */
    public function a_shiftStack($expected)
    {
    	$this->labelBlock('Shift stack.', 40, '*');

        if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '$this->a_data = $this->a_stack->shift();';
		if (! $this->assertExceptionTrue($assertion, sprintf('Removing stack head item - Asserting: %s', $assertion)))
		{
    		$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

        if ($this->verbose > 1)
    	{
    		$this->a_showData($this->a_data, 'a_data');
    	}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpected($expected);

		$this->a_stackLevels--;
    }

    /**
     * a_unshiftStack
     *
     * Shift an item onto the top of the stack stack
     * @param mixed $data = data to unshift onto the stack
     */
    public function a_unshiftStack($data)
    {
    	$this->labelBlock('UnShift stack.', 40, '*');

        if ($this->verbose > 1)
    	{
    		$this->a_showData($data, 'data');
    	}

    	$assertion = sprintf('$this->a_stack->unshift("%s");', $data);
		if (! $this->assertExceptionFalse($assertion, sprintf('Inserting at stack head - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		array_unshift($this->a_localStack, $data);
		$this->a_stackLevels++;
    }

    /**
     * a_popStack
     *
     * Pop item off the stack
     */
    public function a_popStack($expected)
    {
    	$this->labelBlock('Pop stack.', 40, '*');

		if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '$this->a_data = $this->a_stack->pop();';
		if (! $this->assertExceptionTrue($assertion, sprintf('Popping off stack - Asserting: %s', $assertion)))
		{
    		$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

    	if ($this->verbose > 1)
    	{
    		$this->a_showData($this->a_data, 'a_data');
    	}

		$this->a_exceptionCaughtFalse();
		$this->a_stackLevels--;

		$this->a_compareExpected($expected);
    }

    /**
     * a_pushStack
     *
     * Push item onto stack
     */
    public function a_pushStack($data)
    {
    	$this->labelBlock('Push stack.', 40, '*');

    	if ($this->verbose > 1)
    	{
    		$this->a_showData($data, 'data');
    	}

    	$assertion = sprintf('$this->a_stack->push("%s");', $data);
		if (! $this->assertExceptionTrue($assertion, sprintf('Pushing onto stack - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_stackLevels++;
		array_push($this->a_localStack, $data);
    }

    
    /**
     * a_countStack
     *
     * Count items in stack
     * @param integer $expected = expected number of items
     */
    public function a_countStack($expected)
    {
    	$this->labelBlock('Stack size.', 40, '*');

   		if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	$assertion = '(($this->a_data = count($this->a_stack)) !== null);';
		if (! $this->assertTrue($assertion, sprintf('Getting stack size - Asserting: %s', $assertion)))
		{
    		$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

    	if ($this->verbose > 1)
    	{
    		$this->a_showData($this->a_data, 'a_data');
    	}

		$this->a_compareExpected($expected);
    }

    /**
     * a_printStacks
     * 
     * Print the contents of both stacks
     */
    public function a_printStacks()
    {
    	$this->a_printArray($this->a_localStack, 'Local Stack');
    	$stack = new \ReflectionClass($this->a_stack);
    	if ($stack->hasMethod('__toString'))
    	{
    		$this->assertLogMessage((string)$this->a_stack);
    	}
    	else
    	{
    		$this->a_printArray($this->a_stack->stack(), 'Stack');
    	}
    }

    /**
     * a_newStack
     *
     * Create a new stack object
     * @param string $expected = expected stack class name
     */
    public function a_newStack($expected)
    {
    	$this->labelBlock('Create new stack object.', 40, '*');

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($expected, 'expected');
    	}

    	if ($this->a_stack)
    	{
    		unset($this->a_stack);
    	}

    	$assertion = sprintf('$this->a_stack = \Library\Stack\Factory::instantiateClass("%s");', $this->a_stackName);
    	if (! $this->assertTrue($assertion, sprintf('Creating new stack - Asserting: %s', $assertion)))
		{
    		$this->a_showData($this->a_stack, 'a_stack');
			$this->a_outputAndDie();
		}

       	if ($this->verbose > 1)
    	{
    		$this->a_showData($this->a_stack, 'a_stack');
    	}

		$this->a_compareExpectedType(true, $expected, get_class($this->a_stack));

		$this->a_localStack = array();
		$this->a_stackLevels = 0;
    }

}
