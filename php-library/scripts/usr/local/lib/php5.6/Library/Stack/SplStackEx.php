<?php
namespace Library\Stack;

use Library\Error;
use Library\Utilities\FormatVar;

/*
 * 		SplStackEx is copyright � 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * SplStackEx.
 *
 * Additional methods for \SplStack class.
 * @author Jay Wheeler.
 * @version 1.1
 * @copyright � 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Stack.
 */
class SplStackEx extends \SplStack
{
	/**
	 * readOnly
	 *
	 * read only flag
	 * @var boolean $readOnly
	 */
	protected $readOnly;

	/**
	 * __construct
	 *
	 * Class constructor.
	 * @param array $stack = (optional) array to initialize the stack with
	 */
	public function __construct($stack=null)
	{
		$this->readOnly = false;
		$this->stack($stack);
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 */
	public function __destruct()
	{
	}

	/**
	 * key
	 *
	 * Get current node index
	 * @return integer $key = current node index, null if empty stack
	 */
	public function key()
	{
		if (! parent::isEmpty())
		{
			return parent::count - parent::key();
		}
		
		return null;
	}

	/**
	 * next
	 * 
	 * Move stack pointer to the next element
	 * @return mixed $current = the current node value, false if not valid
	 */
	public function next()
	{
		parent::next();
		
		if (($value = $this->current()) !== null)
		{
			return $value;
		}
		
		return false;
	}

	/**
	 * rewind
	 * 
	 * Move the stack pointer to the beginning of the stack
	 * @return mixed $current = the current node value, false if not valid
	 */
	public function rewind()
	{
		return $this->bottom();
	}

	/**
	 * top
	 * 
	 * 
	 * Move stack pointer to the top (last) element in the stack and return the value
	 * @return mixed $current = the current node value, false if not valid
	 */
	public function top()
	{
		parent::rewind();
		if (($value = $this->current()) !== null)
		{
			return $value;
		}
		
		return false;
	}

	/**
	 * bottom
	 * 
	 * 
	 * Move stack pointer to the bottom (first) element in the stack and return the value
	 * @return mixed $current = the current node value, false if not valid
	 *
	public function bottom()
	{
		$bottom = 	function($stack)
				  	{
				  		return array_shift($stack);
				  	};

		return $bottom($this->stack());
	}

	/**
	 * prev
	 * 
	 * Move stack pointer to the previous element
	 * @return mixed $current = the current node value, false if not valid
	 */
	public function prev()
	{
/*
		parent::prev();
		if (($value = $this->current()) !== null)
		{
			return $value;
		}

		return false;
*/
		return $this->next();
	}

	/**
	 * offsetExists
	 *
	 * Returns true if the offset exists, false if not
	 * @param integer $offset = stack location to check
	 * @return boolean true = exists, false = does not
	 */
	public function offsetExists($offset)
	{
		if (! parent::isEmpty())
		{
			$offset = $this->computeOffset($offset);
		}

		return parent::offsetExists($offset);
	}

	/**
	 * offsetGet
	 *
	 * Get the value at $offset.
	 * @param integer $offset = stack location to get
	 * @return mixed $value = value at $offset in the stack
	 * @throws \OutOfRangeException
	 */
	public function offsetGet($offset)
	{
		if ($offset < parent::count())
		{
			$offset = $this->computeOffset($offset);
		}

		return parent::offsetGet($offset);
	}

	/**
	 * offsetSet
	 *
	 * Set the stack at $offset to $value. if $offset is null, set next stack entry to $value
	 * @param integer $offset = (optional) stack location to set, null to set to next location
	 * @param mixed $value = value to set stack at $offset to
	 * @throws \Library\Stack\Exception
	 */
	public function offsetSet($offset=null, $value)
	{
		$this->isReadWrite();

		if ($offset === null)
		{
			$this->push($value);
		}
		else
		{
			parent::offsetSet($this->computeOffset($offset), $value);
		}
	}

	/**
	 * offsetUnset
	 *
	 * Unset the entry at stack location $offset
	 * @param integer $offset = location to unset
	 * @throws \Library\Stack\Exception
	 */
	public function offsetUnset($offset)
	{
		$this->isReadWrite();
		
		if ($offset <= parent::count())
		{
			$offset = $this->computeOffset($offset);
		}

		parent::offsetUnset($offset);
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
		$this->isReadWrite();
		return parent::pop();
	}

	/**
	 * push
	 *
	 * push a new item onto the top (end) of the stack
	 * @param mixed $value = value to store on the stack
	 * @return mixed $value = value on the top of the stack
	 * @throws \Library\Stack\Exception
	 */
	public function push($value)
	{
		$this->isReadWrite();
		
		$stackTop = parent::push($value);
		if (func_num_args() > 1)
		{
			$arguments = func_get_args();
			array_shift($arguments);

			foreach($arguments as $argument)
			{
				$stackTop = parent::push($argument);
			}
		}

		return $stackTop;
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
		$this->isReadWrite();
		return parent::shift();
	}

	/**
	 * unserialize
	 *
	 * Unserialize a Stack serialized string
	 * @param string $serialized = serializatin string created by Stack::serialize
	 */
	public function unserialize($serialized)
	{
		$this->isReadWrite();
		parent::unserialize($serialized);
	}

	/**
	 * unshift
	 *
	 * add a value to the beginning of the stack
	 * @param mixed $value = value to shift onto the stack
	 * @throws \Library\Stack\Exception
	 */
	public function unshift($value)
	{
		$this->isReadWrite();
		parent::unshift($value);
	}

	// ***************************************************************************************
	//
	//		Extensions
	//
	// ***************************************************************************************

	/**
	 * removeKey
	 *
	 * remove (unset) the requested stack key
	 * @param mixed $key = key to unset
	 * @throws \Library\Stack\Exception
	 */
	public function removeKey($key)
	{
		if (! $this->offsetExists($key))
		{
			throw new Exception(Error::code('UnknownStackElement'));
		}

		$this->offsetUnset($key);
	}

	/**
	 * topIndex
	 *
	 * return the top of stack index, throws exception if the stack is empty
	 * @return integer $topIndex = top of the stack index
	 * @throws \Library\Stack\Exception
	 */
	public function topIndex()
	{
		$this->top();
		return $this->key();
	}

	/**
	 * clear
	 *
	 * clear (unset) the stack
	 *
	 * @throws \Library\Stack\Exception
	 */
	public function clear()
	{
		$this->isReadWrite();
		$this->deleteStack();
	}

	/**
	 * readOnly
	 *
	 * set/get the read only flag
	 * @param boolean $readOnly = (optional) read only flag value, null to query
	 * @return boolean $readOnly
	 */
	public function readOnly($readOnly=null)
	{
		if ($readOnly !== null)
		{
			$this->readOnly = $readOnly;
		}

		return $this->readOnly;
	}

	/**
	 * stack
	 *
	 * get/set a copy of the stack
	 * @param array $stack = (optional) stack contents to set, null to query
	 * @return array $stack
	 */
	public function stack($stack=null)
	{
		if ($stack !== null)
		{
			$this->isReadWrite();
			$this->setStack($stack);
		}

		return $this->getStack();
	}

	/**
	 * keys
	 *
	 * returns an array containing all of the names (keys) from the stack
	 * @return array $keys
	 */
	public function keys()
	{
		$stack = array();
		$this->rewind();
		while($this->valid())
		{
			array_push($stack, $this->key());
			$this->next();
		}

		return $stack;
	}

	/**
	 * values
	 *
	 * returns an array containing all of the values from the stack
	 * @returns array $values
	 */
	public function values()
	{
		$stack = array();
		$this->rewind();
		while($this->valid())
		{
			array_push($stack, $this->current());
			$this->next();
		}

		return $stack;
	}

	/**
	 * merge
	 *
	 * merge the specified array with the current stack
	 * @param array $stack = stack contents to merge
	 * @return array $stack
	 * @throws \Library\Stack\Exception
	 */
	public function merge($stack)
	{
		if (! $stack)
		{
			throw new Exception(Error::code('StackError'));
		}

		return $this->stack(array_merge($this->stack(), $stack));
	}

	/**
	 * end.
	 *
	 * Returns the last item in the stack
	 * @return mixed $value = value of the last element in the stack
	 * @throws \RuntimeException
	 */
	public function end()
	{
		return $this->top();
	}

	/**
	 * exists
	 *
	 * check for key exists in the stack
	 * @param string $key = key to check for
	 * @return boolean true = exists, false = does not
	 */
	public function exists($key)
	{
		return $this->offsetExists($key);
	}

	/**
	 * inStack
	 *
	 * check for VALUE exists in the stack
	 * @param string $value = value to check for
	 * @return integer|boolean $index = index of value, if found, false if not found
	 */
	public function inStack($value)
	{
		if (! $this->isEmpty())
		{
			$this->rewind();
			while($this->valid())
			{
				if ($this->current() === $value)
				{
					return $this->key();
				}

				$this->next();
			}
		}

		return false;
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

		return FormatVar::format($this->stack(), 'Stack contents');
	}

	// ***************************************************************************************
	//
	//		local utility methods
	//
	// ***************************************************************************************

	/**
	 * isReadWrite
	 *
	 * Check read-only status, throw exception if the stack is not writeable
	 * @throws Library\Stack\Exception
	 */
	private function isReadWrite()
	{
		if ($this->readOnly)
		{
			throw new Exception(Error::code('ReadOnly'));
		}
	}

	/**
	 * setStack
	 * 
	 * Set the contents of the stack if $stack is not null, 
	 *   returns the contents of the stack as an array
	 * @param array $stack = (optinal) array to set as the stack, null to query only
	 */
	private function setStack($stack=null)
	{
		$this->deleteStack();

		foreach($stack as $offset => $value)
		{
			$this->offsetSet($offset, $value);
		}
	}

	/**
	 * deleteStack
	 * 
	 * Delete the contents of the SplStack
	 */
	private function deleteStack()
	{
		while(! $this->isEmpty())
		{
			$this->shift();
		}
	}

	/**
	 * getStack
	 * 
	 * Get the contents of the SplStack as an array
	 * @return array $stack = contents of the SplStack as an array
	 */
	private function getStack()
	{
		$stack = array();
		
		if (! $this->isEmpty())
		{
			$this->rewind();
			$local = array();

			while($this->valid())
			{
				array_unshift($stack, $this->current());
				$this->next();
			}
		}

		return $stack;
	}

	/**
	 * computeOffset
	 * 
	 * Compute the stack index from the requested offset into the stack
	 * @param integer $offset = offset into the stack
	 * @throws Exception
	 * @return integer $index = actual stack index
	 */
	private function computeOffset($offset)
	{
		$size = parent::count();
		if ($offset < $size)
		{
			return $size - $offset - 1;
		}
		
		throw new Exception();
	}

}
