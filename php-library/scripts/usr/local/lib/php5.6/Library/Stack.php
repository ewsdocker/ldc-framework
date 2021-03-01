<?php
namespace Library;

use Library\Error;
use Library\Utilities\FormatVar;

/*
 * 		Stack is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 			or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * Stack.
 *
 * A class wrapper for php array operations on a stack structure.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Stack.
 */
class Stack implements \Iterator, \Countable, \ArrayAccess, \Serializable
{
	/**
	 * $stack
	 *
	 * array to store information
	 * @var array $stack
	 */
	protected $stack;

	/**
	 * iteratorMode
	 * 
	 * SplDoublyLinkedList::IT_MODE_KEEP or SplDoublyLinkedList::IT_MODE_DELETE
	 * @var $iteratorMode
	 */
	protected $iteratorMode;

	/**
	 * __construct
	 *
	 * Class constructor.
	 * @return Stack class object
	 */
	public function __construct()
	{
		$this->stack = array();
		$this->iteratorMode = \SplDoublyLinkedList::IT_MODE_LIFO | \SplDoublyLinkedList::IT_MODE_KEEP;
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 * @return null
	 */
	public function __destruct()
	{
	}

	/**
	 * __toString
	 *
	 * Output the stack as a printable string
	 * @return string $buffer = printable string
	 */
	public function __toString()
	{
		FormatVar::sort(false);
		FormatVar::sortValues(false);
		FormatVar::recurse(true);

		return FormatVar::format($this->stack, 'Stack contents');
	}

	/**
	 * bottom
	 *
	 * Rewind the internal array pointer and return the first item pointed to
	 * @return mixed $current = item pointed to followind the rewind() operation
	 * @throws Stack\Exception
	 */
	public function bottom()
	{
		if (count($this->stack) == 0)
		{
			throw new Exception(Error::code('StackEmpty'));
		}

		$bottom = 	function($stack)
				  	{
				  		return array_shift($stack);
				  	};

		return $bottom($this->stack);

	}

	/**
	 * count.
	 *
	 * returns the number of elements in the stack
	 * @return integer $count = number of elements in the stack
	 */
	public function count()
	{
		return count($this->stack);
	}

	/**
	 * current.
	 *
	 * Returns the current stack element.
	 * @return mixed $value = value of the next element in the stack
	 */
	public function current()
	{
		return current($this->stack);
	}

	/**
	 * getIteratorMode
	 *
	 * Gets the mode of iteration
	 * @return integer $mode = iteration mode
	 */
	public function getIteratorMode()
	{
		return $this->iteratorMode;
	}

	/**
	 * isEmpty
	 *
	 * Return true if the stack is empty, false if not
	 * @return boolean $empty = true if stack is empty, false if not
	 */
	public function isEmpty()
	{
		return (count($this->stack) == 0) ? true : false;
	}

	/**
	 * key.
	 *
	 * Returns the current stack key value.
	 * @return mixed $key = current stack key value.
	 */
	public function key()
	{
		return key($this->stack);
	}

	/**
	 * next.
	 *
	 * advances the key to the next element and return its' value.
	 * @return mixed $next = next element in the stack.
	 */
	public function next()
	{
		if ($this->iteratorMode & \SplDoublyLinkedList::IT_MODE_DELETE)
		{
			$this->offsetUnset($this->key());
			return $this->current();
		}

		return next($this->stack);
	}

	/**
	 * offsetGet
	 *
	 * Get value at the specified $offset
	 * @param integer $offset = offset in the stack to fetch value from
	 * @return mixed $stack[$offset]
	 */
	public function offsetGet($offset)
	{
		return $this->offsetExists($offset) ? $this->stack[$offset] : null;
	}

	/**
	 * offsetExists
	 *
	 * return true if a value exists at stack location $offset
	 * @param integer $offset = offset location to check
	 * @return boolean true = exists, false = doesn't exist
	 */
	public function offsetExists($offset)
	{
		return array_key_exists($offset, $this->stack);
	}

	/**
	 * offsetSet
	 *
	 * Set the stack at $offset to $value. if $offset is null, set next stack entry to $value
	 * @param integer $offset = offset to stack location to set
	 * @param mixed $value = value to set stack at $offset to
	 */
	public function offsetSet($offset, $value)
	{
		if (is_null($offset))
		{
			$this->stack[] = $value;
		}
		else
		{
			$this->stack[$offset] = $value;
		}
	}

	/**
	 * offsetUnset
	 *
	 * Unset the entry at stack location $offset
	 * @param integer $offset = location to unset
	 */
	public function offsetUnset($offset)
	{
		unset($this->stack[$offset]);
	}

	/**
	 * pop
	 *
	 * extracts the last value in the array, decreases the array size by one and returns the element value
	 * @return mixed $element = the value extracted from the array
	 * @throws \Library\Stack\Exception
	 */
	public function pop()
	{
		if (count($this->stack) == 0)
		{
			throw new Exception(Error::code('StackEmpty'));
		}

		return array_pop($this->stack);
	}

	/**
	 * prev
	 *
	 * rewinds the stack pointer by one element and returns current stack value
	 * @return mixed $value = previous element in the stack.
	 */
	public function prev()
	{
		return prev($this->stack);
	}

	/**
	 * push
	 *
	 * push a new item onto the top (end) of the stack
	 * @param mixed $value = value to store on the stack
	 * @return mixed $value = value on the top of the stack
	 */
	public function push($value)
	{
		array_push($this->stack, $value);
		return end($this->stack);
	}

	/**
	 * rewind.
	 *
	 * Moves the current stack pointer to the first element in the stack.
	 */
	public function rewind()
	{
		return reset($this->stack);
	}

	/**
	 * serialize
	 *
	 * Serialize the storage
	 * @return string $serialized = serialized storage string
	 */
	public function serialize()
	{
		return serialize(get_object_vars($this));
	}

	/**
	 * setIteratorMode
	 *
	 * Sets the mode of iteration
	 * @param integer $mode = iteration mode
	 * @throws \Library\Stack\Exception
	 */
	public function setIteratorMode($mode)
	{
		if (($mode & \SplDoublyLinkedList::IT_MODE_LIFO) !== \SplDoublyLinkedList::IT_MODE_LIFO)
		{
			throw new Exception(Error::code('StackInvalidMode'));
		}

		$this->iteratorMode = $mode;
	}

	/**
	 * shift
	 *
	 * remove a value from the beginning of the stack
	 * @return mixed $value = value shifted off the stack
	 * @throws \Library\Stack\Exception
	 */
	public function shift()
	{
		if (count($this->stack) == 0)
		{
			throw new Exception(Error::code('StackEmpty'));
		}

		return array_shift($this->stack);
	}

	/**
	 * top
	 *
	 * return the top of stack value, throws exception if the stack is empty
	 * @return integer $top = top of the stack value
	 * @throws \Library\Stack\Exception
	 */
	public function top()
	{
		if (count($this->stack) == 0)
		{
			throw new Exception(Error::code('StackEmpty'));
		}

		return end($this->stack);
	}

	/**
	 * unserialize
	 *
	 * Unserialize a Library\Stack serialized string
	 */
	public function unserialize($serialized)
	{
		foreach(unserialize($serialized) as $key => $value)
		{
			$this->{$key} = $value;
		}
	}

	/**
	 * unshift
	 *
	 * add a value to the beginning of the stack
	 * @param mixed $value = value to shift onto the stack
	 */
	public function unshift($value)
	{
		array_unshift($this->stack, $value);
	}

	/**
	 * valid.
	 *
	 * Returns the validity of the current stack pointer
	 * @return boolean true = the current stack pointer is valid.
	 */
	public function valid()
	{
		return (current($this->stack) === false) ? False : true;
	}

}
