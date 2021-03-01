<?php
namespace Tests\Stack;

/*
 *		QueueTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * QueueTest
 *
 * Library\Stack\Queue class tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Stack
 */
class QueueTest extends \Application\Launcher\Testing\UtilityMethods
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

    	$queueName = 'testqueue';
    	$this->a_newQueue($queueName);

    	$this->a_setMode(\SplDoublyLinkedList::IT_MODE_KEEP);

    	$this->a_addQueue("First message");
    	$this->a_printQueue();

    	$this->a_addQueue("Second message");
    	$this->a_printQueue();

    	$this->a_prependQueue("Third message");
    	$this->a_printQueue();

    	$this->a_removeQueue(1, "First message");
    	$this->a_printQueue();

    	$this->a_offsetSet(null, $this->a_data);
    	$this->a_printQueue();

    	$this->a_offsetExists(2);
    	$this->a_offsetUnset(1);
    	$this->a_printQueue();

    	$this->a_addQueue("Next message");
    	$this->a_addQueue("Another message");
       	$this->a_addQueue("Final message");

       	$this->a_isEmpty(false);

    	$this->a_printQueue();

    	$this->a_removeTail('Final message');
    	$this->a_printQueue();

    	$localArray = $this->a_localArray;
    	$this->a_removeAll();

    	$this->a_isEmpty();

    	$this->a_addQueue($this->a_data);
    	foreach($localArray as $index => $data)
    	{
    		$this->a_addQueue($data);
    	}

    	$this->a_printQueue();

    	$this->a_offsetGet(2, 'First message');

    	$localArray = $this->a_localArray;

    	$this->a_iteratorTest(false);
    	$this->a_setMode(\SplDoublyLinkedList::IT_MODE_DELETE);
    	$this->a_iteratorTest(true);
    	$this->a_setMode(\SplDoublyLinkedList::IT_MODE_KEEP);

    	foreach($localArray as $index => $data)
    	{
    		$this->a_addQueue($data);
    	}

    	$this->a_printQueue();

    	$this->a_head($this->a_localArray[0]);
    	$this->a_tail($this->a_localArray[count($this->a_localArray) - 1]);

    	$this->a_queueName($queueName);

    	$this->a_rewind();
    	$this->a_current($this->a_localArray[0]);

    	$this->a_next();
    	$this->a_current($this->a_localArray[1]);

    	$this->a_next();
    	$this->a_current($this->a_localArray[2]);

    	$this->a_prev();
    	$this->a_current($this->a_localArray[1]);

    	$this->a_key(1);

    	$this->a_valid(true);

    	$this->a_count(count($this->a_localArray));

    	$this->a_setMode(\SplDoublyLinkedList::IT_MODE_DELETE);
    	$this->a_iteratorTest(true);

    	$this->a_queue = null;
    }

    /**
     * a_count
     *
     * Check queue size
     * @param mixed $expected = expected result
     */
    public function a_count($expected)
	{
    	$this->labelBlock('Count.', 40, '*');

    	$assertion = '$this->a_data = $this->a_queue->count();';
		if (! $this->assertTrue($assertion, sprintf('Count - Asserting: %s', $assertion)))
		{
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

    	$assertion = '$this->a_data = $this->a_queue->valid();';
		if (! $this->assertTrue($assertion, sprintf('Valid - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
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

    	$assertion = '(($this->a_data = $this->a_queue->key()) !== null);';
		if (! $this->assertTrue($assertion, sprintf('Key - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
    }

    /**
     * a_next
     *
     * Advance the queue pointer to the next element
     */
    public function a_next()
    {
    	$this->labelBlock('Next.', 40, '*');

    	$assertion = '$this->a_queue->next();';
		if (! $this->assertFalse($assertion, sprintf('Next - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
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

    	$assertion = '$this->a_data = $this->a_queue->current();';
		if (! $this->assertTrue($assertion, sprintf('Current - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
    }

    /**
     * a_rewind
     *
     * Rewind the queue pointer to the first element (0)
     */
    public function a_rewind()
    {
    	$this->labelBlock('Rewind.', 40, '*');

    	$assertion = '$this->a_queue->rewind();';
		if (! $this->assertFalse($assertion, sprintf('Rewind - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_prev
     *
     * retrieve the previous item from the queue
     */
    public function a_prev()
    {
    	$this->labelBlock('Previous item.', 40, '*');


    	$assertion = '$this->a_queue->prev();';
		if (! $this->assertFalse($assertion, sprintf('Previous item - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_tail
     *
     * Retrieve the tail item of the queue
     * @param mixed $expected = expected item
     */
    public function a_tail($expected)
    {
    	$this->labelBlock('Queue TAIL.', 40, '*');

    	$assertion = '$this->a_data = $this->a_queue->tail();';
		if (! $this->assertExceptionTrue($assertion, sprintf('Get TAIL - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpected($expected);
    }

    /**
     * a_head
     *
     * Retrieve the head item of the queue
     * @param mixed $expected = expected item
     */
    public function a_head($expected)
    {
    	$this->labelBlock('Queue HEAD.', 40, '*');

    	$assertion = '$this->a_data = $this->a_queue->head();';
		if (! $this->assertExceptionTrue($assertion, sprintf('Get HEAD - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpected($expected);
    }

    /**
	 * a_iteratorTest
	 *
	 * Iterate throught the queue elements
	 * @param boolean $iterate = expected iterator action (true = iterate and delete, false = iterate only)
	 */
	public function a_iteratorTest($iterate=false)
	{
    	$this->labelBlock('Iterator test.', 40, '*');

    	$assertion = '$this->a_testArray = $this->a_queue->queueArray();';
		if (! $this->assertExceptionTrue($assertion, sprintf('Iterator test - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_isEmpty($iterate);
		if ($iterate)
		{
			$this->a_localArray = array();
		}
	}

    /**
     * a_setMode
     *
     * set iterator mode
     * @param integer $mode = (optional) Iterator mode (default = \SplDoublyLinkedList::IT_MODE_KEEP)
     */
    public function a_setMode($mode=\SplDoublyLinkedList::IT_MODE_KEEP)
    {
    	$this->labelBlock('Set MODE.', 40, '*');

    	$this->a_mode = $mode;

       	$assertion = '(($this->a_data = $this->a_queue->mode($this->a_mode)) !== null);';
		if (! $this->assertTrue($assertion, sprintf('Set mode - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpected($this->a_mode);
    }

    /**
     * a_isEmpty
     *
     * Check if queue is empty
     * @param boolean $state = (optional) queue state (true = empty, false = not empty) (default = true)
     */
    public function a_isEmpty($state=true)
    {
    	$this->labelBlock('Is empty.', 40, '*');

    	$assertion = '$this->a_queue->isEmpty();';
    	if ($state)
    	{
			if (! $this->assertTrue($assertion, sprintf('Check empty - Asserting: %s', $assertion)))
			{
				$this->a_outputAndDie();
			}
    	}
    	else
    	{
			if (! $this->assertFalse($assertion, sprintf('Check NOT empty - Asserting: %s', $assertion)))
			{
				$this->a_outputAndDie();
			}
    	}
    }

    /**
     * a_removeAll
     *
     * Deleted all items from the queue
     */
    public function a_removeAll()
    {
    	$this->labelBlock('Remove ALLL.', 40, '*');

    	$assertion = '$this->a_queue->removeAll();';
		if (! $this->assertFalse($assertion, sprintf('Removing ALL - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_localArray = array();
		$this->a_compareArray($this->a_queue->queueArray());
    }

    /**
     * a_removeTail
     *
     * Deleted tail item from the queue
     * @param mixed $expected = expected item to be deleted
     */
    public function a_removeTail($expected)
    {
    	$this->labelBlock('Remove queue TAIL.', 40, '*');

    	$assertion = '$this->a_data = $this->a_queue->removeTail();';
		if (! $this->assertExceptionTrue($assertion, sprintf('Removing tail - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpected($expected);

		unset($this->a_localArray[count($this->a_localArray) - 1]);
		$this->a_localArray = array_merge($this->a_localArray);
		$this->a_compareArray($this->a_queue->queueArray());
    }

    /**
     * a_removeQueue
     *
     * Deleted item from the queue
     * @param integer $key = key to remove
     * @param mixed $expected = expected item to be deleted
     */
    public function a_removeQueue($key, $expected)
    {
    	$this->labelBlock('Remove queue.', 40, '*');

    	$this->a_key = $key;
    	$assertion = '$this->a_data = $this->a_queue->remove($this->a_key);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Removing - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpected($expected);

		unset($this->a_localArray[$key]);
		$this->a_localArray = array_merge($this->a_localArray);
		$this->a_compareArray($this->a_queue->queueArray());
    }

    /**
     * a_prependQueue
     *
     * Add item to the front of the queue
     * @param mixed $data = item to add to the queue
     */
    public function a_prependQueue($data)
    {
    	$this->labelBlock('Prepend queue.', 40, '*');

    	$this->a_data = $data;
    	$assertion = '$this->a_queue->prepend($this->a_data);';
		if (! $this->assertFalse($assertion, sprintf('Prepending - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		array_unshift($this->a_localArray, $data);
		$this->a_localArray = array_merge($this->a_localArray);
		$this->a_compareArray($this->a_queue->queueArray());
    }

    /**
     * a_addQueue
     *
     * Add item to the queue
     * @param mixed $data = item to add to the queue
     */
    public function a_addQueue($data)
    {
    	$this->labelBlock('Add queue.', 40, '*');

    	$this->a_data = $data;
    	$assertion = '$this->a_queue->add($this->a_data);';
		if (! $this->assertFalse($assertion, sprintf('Adding - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		array_push($this->a_localArray, $data);
		$this->a_compareArray($this->a_queue->queueArray());
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
    	$assertion = '$this->a_queue->offsetUnset($this->a_arrayKey);';
		if (! $this->assertExceptionFalse($assertion, sprintf('Deleting offset at %s - Asserting: %s', $key, $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		if (array_key_exists($key, $this->a_localArray))
		{
			unset($this->a_localArray[$key]);
			$this->a_localArray = array_merge($this->a_localArray);
			$this->a_compareArray($this->a_queue->queueArray());
		}
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
    	$assertion = sprintf('$this->a_data = $this->a_queue->offsetExists("%s");', $this->a_arrayKey);
		if (! $this->assertTrue($assertion, sprintf('Checking key %s - Asserting: %s', $key, $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_offsetSet
     *
     * write to the queue
     * @param mixed $key = key to access
     * @param mixed $data = value to store at $key
     */
    public function a_offsetSet($key, $data)
    {
    	$this->labelBlock('Offset Set.', 40, '*');

    	$this->a_key = $key;
		$this->a_data = $data;

    	$assertion = sprintf('$this->a_queue->offsetSet($this->a_key, "%s");', $this->a_data);
		if (! $this->assertExceptionFalse($assertion, sprintf('OffsetSet at %s - Asserting: %s', $key, $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		if (($key === null) || ($key >= count($this->a_localArray)))
		{
			array_push($this->a_localArray, $data);
		}
		else
		{
			$this->a_localArray[$key] = $data;
		}

		$this->a_localArray = array_merge($this->a_localArray);
		$this->a_compareArray($this->a_queue->queueArray());
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
    	$assertion = sprintf('$this->a_data = $this->a_queue->offsetGet("%s");', $this->a_arrayKey);
		if (! $this->assertTrue($assertion, sprintf('Getting stack at %s - Asserting: %s', $key, $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpected($expected);
    }

    /**
     * a_queueName
     *
     * Set/check queue name
     * @param string $expected = expected queue name
     * @param string $name = (optional) new queue name ($expected is ignored)
     */
    public function a_queueName($expected, $name=null)
    {
    	$this->labelBlock('Queue NAME.', 40, '*');

    	$this->a_name = $name;

    	$assertion = '$this->a_data = $this->a_queue->name($this->a_name);';
		if (! $this->assertTrue($assertion, sprintf('Queue Name - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		if ($name !== null)
		{
			$expected = $name;
		}

		$this->a_compareExpected($expected);
    }

    /**
     * a_newQueue
     *
     * Create a new queue object
     */
    public function a_newQueue($name)
    {
    	$this->labelBlock('Create new queue object.', 40, '*');

    	$this->a_data = $name;

    	$assertion = '$this->a_queue = new \Library\Stack\Queue($this->a_data);';
    	if (! $this->assertTrue($assertion, sprintf('Creating new Queue - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_localArray = array();
    }

    /**
     * a_printQueue
     *
     * Print the queue contents
     */
    public function a_printQueue()
    {
    	$this->labelBlock('Print queue.', 40, '*');

    	$this->a_queueBuffer = null;
    	$assertion = '$this->a_queueBuffer = (string)$this->a_queue;';
		if (! $this->assertTrue($assertion, sprintf('Getting queue - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->assertLogMessage($this->a_queueBuffer);
    }

}
