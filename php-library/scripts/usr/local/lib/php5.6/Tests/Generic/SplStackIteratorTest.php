<?php
namespace Tests\Generic;

/*
 *		SplStackIteratorTest.php is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * Generic\SplStackIteratorTest
 *
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Generic
 */
class SplStackIteratorTest extends \Application\Launcher\Testing\UtilityMethods
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
    	
    	$this->a_stack = null;
    	$this->a_localStack = array();

    	$this->a_newStack();

    	$this->a_pushStack(array('name'=>'Naruto'));
		$this->a_pushStack(array('name'=>'Sakura'));
		$this->a_pushStack(array('name'=>'Neji'));
		$this->a_pushStack(array('name'=>'Sasuke'));

		$this->a_forEach();

		$this->a_setIteratorMode(\SplDoublyLinkedList::IT_MODE_FIFO | \SplDoublyLinkedList::IT_MODE_KEEP);
		$this->a_getIteratorMode(\SplDoublyLinkedList::IT_MODE_FIFO | \SplDoublyLinkedList::IT_MODE_KEEP);

		$this->assertLogMessage('FIFO Keep Mode: ' . $this->a_data);

		$this->a_forEach();

		$this->assertLogMessage('FIFO Traversing');

		$this->a_setIteratorMode(\SplDoublyLinkedList::IT_MODE_FIFO | \SplDoublyLinkedList::IT_MODE_DELETE);

		$this->a_getIteratorMode(\SplDoublyLinkedList::IT_MODE_FIFO | \SplDoublyLinkedList::IT_MODE_DELETE);

		$this->assertLogMessage('FIFO Delete Mode: ' . $this->a_data);

		$this->a_forEach();

    	$this->a_newStack();

    	$this->a_pushStack(array('name'=>'Naruto'));
		$this->a_pushStack(array('name'=>'Sakura'));
		$this->a_pushStack(array('name'=>'Neji'));
		$this->a_pushStack(array('name'=>'Sasuke'));

		$this->a_forEach();

		$this->assertLogMessage('LIFO Traversing - Keep mode');

		$this->a_setIteratorMode(\SplDoublyLinkedList::IT_MODE_FIFO | \SplDoublyLinkedList::IT_MODE_KEEP);
		$this->a_getIteratorMode(\SplDoublyLinkedList::IT_MODE_FIFO | \SplDoublyLinkedList::IT_MODE_KEEP);

		$this->assertLogMessage('FIFO Keep Mode: ' . $this->a_data);

		$this->a_forEach();

		$this->assertLogMessage('FIFO Traversing');

		$this->a_setIteratorMode(\SplDoublyLinkedList::IT_MODE_FIFO | \SplDoublyLinkedList::IT_MODE_DELETE);
		$this->a_getIteratorMode(\SplDoublyLinkedList::IT_MODE_FIFO | \SplDoublyLinkedList::IT_MODE_DELETE);

		$this->assertLogMessage('FIFO Delete Mode: ' . $this->a_data);

		$this->a_forEach(2);
	}

	public function a_forEach($exitKey=null)
	{
		foreach($this->a_stack as $key => $value)
		{
			if (($exitKey !== null) && ($exitKey == $key))
			{
				break;
			}
			
			$this->assertLogMessage(sprintf('Traversed: %s = %s', $key, $value));
		}
	}

    /**
     * a_getIteratorMode
     *
     * @param integer $expected = expected mode
     */
    public function a_getIteratorMode($expected)
    {
    	$this->labelBlock('Get Iterator Mode.', 40, '*');

    	$assertion = '(($this->a_data = $this->a_stack->getIteratorMode()) !== null);';
		if (! $this->assertTrue($assertion, sprintf('Get Iterator Mode - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_compareExpected($expected);
    }

    /**
     * a_setIteratorMode
     *
     * @param integer $expected = expected mode
     * @param integer $mode = mode to set
     */
    public function a_setIteratorMode($mode)
    {
    	$this->labelBlock('Set Iterator Mode.', 40, '*');

    	$assertion = sprintf('(($this->a_data = $this->a_stack->setIteratorMode(%u)) !== "query");', $mode);
		if (! $this->assertTrue($assertion, sprintf('Set Iterator Mode - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

	/**
     * a_pushStack
     *
     * Push item onto stack
     */
    public function a_pushStack($data)
    {
    	$this->labelBlock('Push stack.', 40, '*');

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
     * a_newStack
     *
     * Create a new stack object
     */
    public function a_newStack()
    {
    	$this->labelBlock('Create new stack object.', 40, '*');

    	if ($this->a_stack)
    	{
    		unset($this->a_stack);
    	}

    	$assertion = '$this->a_stack = new \SplDoublyLinkedList();';
		if (! $this->assertTrue($assertion, sprintf('Creating new stack - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_localStack = array();
		$this->a_stackLevels = 0;
    }

}
