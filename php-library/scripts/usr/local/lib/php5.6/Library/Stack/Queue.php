<?php
namespace Library\Stack;
use Library\Error;
use Library\Utilities\FormatVar;

/*
 * 		Stack\Queue is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 * Stack\Queue.
 *
 * Stack\Queue class implements the functions of a FIFO Stack, or queue, in the SplQueue structure.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2005, 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Stack.
 */
class Queue implements \Countable, \Iterator, \ArrayAccess
{
	/**
	 * queue
	 *
	 * SplQueue object
	 * @var SplQueue $queue
	 */
	protected	$queue;

	/**
	 * name
	 *
	 * optional name of the queue
	 * @var string $name
	 */
	protected	$name;

	/**
	 * __construct
	 *
	 * class constructor
	 * @param string $name = (optional) queue name
	 * @return Queue $queue
	 */
	public function __construct($name=null)
	{
		$this->queue = new \SplQueue();
		$this->name = $name;
		$this->mode(\SplDoublyLinkedList::IT_MODE_KEEP);
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 */
	public function __destruct()
	{
		$this->queue = null;
	}

	/**
	 * __toString
	 *
	 * return the queue as a printable string
	 * @return string $buffer
	 */
	public function __toString()
	{
		FormatVar::sort(false);
		FormatVar::sortValues(false);
		FormatVar::recurse(true);
		
		return FormatVar::format($this->queueArray(), $this->name);
	}

	/**
	 * queue
	 *
	 * get the SplQueue reference
	 * @return SplQueue $queue
	 */
	public function queue()
	{
		return $this->queue;
	}

	/**
	 * add
	 *
	 * add data to the TAIL of the queue
	 * @param mixed $data = data to add to the queue structure
	 */
	public function add($data)
	{
		$this->queue->enqueue($data);
	}

	/**
	 * prepend
	 *
	 * add data to (in front of) the HEAD of the queue
	 * @param mixed $data = data to prepend
	 */
	public function prepend($data)
	{
		if ($this->isEmpty())
		{
			$this->add($data);
		}
		else
		{
			$this->queue->unshift($data);
		}
	}

	/**
	 * remove
	 *
	 * remove data from the HEAD of the queue
	 * @param mixed $key = (optional) queue key to remove, null to remove the top element
	 * @return mixed $data = data from the HEAD of the queue
	 * @throws Stack\Exception
	 */
	public function remove($key=null)
	{
		if ($this->isEmpty())
		{
			throw new Exception(Error::code('QueueEmpty'));
		}

		if ($key !== null)
		{
			$value = $this->offsetGet($key);
			$this->offsetUnset($key);
			return $value;
		}

		return $this->queue->dequeue();
	}

	/**
	 * removeTail
	 *
	 * remove data from the TAIL of the queue
	 * @return mixed $data = data from the TAIL of the queue
	 * @throws Stack\Exception
	 */
	public function removeTail()
	{
		if ($this->isEmpty())
		{
			throw new Exception(Error::code('QueueEmpty'));
		}

		return $this->queue->pop();
	}

	/**
	 * removeAll
	 *
	 * delete all entries from the queue
	 */
	public function removeAll()
	{
		$mode = $this->mode();
		$this->queue = null;
		$this->queue = new \SplQueue();

		$this->mode($mode);
	}

	/**
	 * isEmpty
	 *
	 * return true if the queue is empty, false if it still contains items
	 * @return boolean $emptyStatus
	 */
	public function isEmpty()
	{
		return $this->queue->isEmpty();
	}

	/**
	 * mode
	 *
	 * set/get the current iterator mode - either SplDoublyLinkedList::IT_MODE_KEEP or SplDoublyLinkedList::IT_MODE_DELETE
	 * @param integer $mode = (optional) mode to set, null to query only
	 * @return integer $mode
	 * @throws RuntimeException
	 */
	public function mode($mode=null)
	{
		if ($mode !== null)
		{
			$this->queue->setIteratorMode($mode);
		}

		return $this->queue->getIteratorMode();
	}

	/**
	 * head
	 *
	 * return the data from the queue head item
	 * @return mixed $data
	 */
	public function head()
	{
		return $this->queue->bottom();
	}

	/**
	 * tail
	 *
	 * return the data from the queue tail item
	 * @return mixed $data
	 */
	public function tail()
	{
		return $this->queue->top();
	}

	/**
	 * name
	 *
	 * get/set the queue name
	 * @param string $name = (optional) queue name, null to query only
	 * @return string $name
	 */
	public function name($name=null)
	{
		if ($name !== null)
		{
			$this->name = $name;
		}

		return $this->name;
	}

	/**
	 * prev
	 *
	 * set the key to the previous queue item
	 */
	public function prev()
	{
		$this->queue->prev();
	}

	/**
	 * queueArray
	 *
	 * Assemble the queue contents into an array
	 * @return array $queueArray
	 */
	public function queueArray()
	{
		$queue = array();
		foreach($this->queue as $index => $value)
		{
			$queue[$index] = $value;
		}

		return $queue;
	}

	/**
	 * count
	 *
	 * return the number queue entries
	 * @return integer $entries
	 */
	public function count()
	{
		return $this->queue->count();
	}

	/**
	 * current
	 *
	 * get the current queue node's data
	 * @return mixed $data
	 */
	public function current()
	{
		return $this->queue->current();
	}

	/**
	 * key
	 *
	 * get the current queue node index
	 * @return mixed $key
	 */
	public function key()
	{
		return $this->queue->key();
	}

	/**
	 * next
	 *
	 * set the key to the next queue item
	 */
	public function next()
	{
		$this->queue->next();
	}

	/**
	 * rewind
	 *
	 * set the key to the HEAD of the queue
	 */
	public function rewind()
	{
		$this->queue->rewind();
	}

	/**
	 * valid
	 *
	 * query validity of the current node
	 * @return boolean $valid = true if key is valid, false if it is not
	 */
	public function valid()
	{
		return $this->queue->valid();
	}

	/**
	 * offsetSet
	 *
	 * Set the queue at $offset to $value. if $offset is null, set next queue entry to $value
	 * @param integer $offset = offset to queue location to set
	 * @param mixed $value = value to set queue at $offset to
	 * @throws \Library\Stack\Exception
	 */
	public function offsetSet($offset, $value)
	{
		$this->queue->offsetSet($offset, $value);
	}

	/**
	 * offsetExists
	 *
	 * return true if a value exists at queue location $offset
	 * @param integer $offset = offset location to check
	 * @return boolean true = exists, false = doesn't exist
	 */
	public function offsetExists($offset)
	{
		return $this->queue->offsetExists($offset);
	}

	/**
	 * offsetUnset
	 *
	 * Unset the entry at queue location $offset
	 * @param integer $offset = location to unset
	 */
	public function offsetUnset($offset)
	{
		$this->queue->offsetUnset($offset);
	}

	/**
	 * offsetGet
	 *
	 * Get value at the specified $offset
	 * @param integer $offset = offset in the queue to fetch value from
	 * @return mixed $queue[$offset]
	 */
	public function offsetGet($offset)
	{
		return $this->queue->offsetGet($offset);
	}

}
