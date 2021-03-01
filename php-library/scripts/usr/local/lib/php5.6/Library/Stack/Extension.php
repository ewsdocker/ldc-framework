<?php
namespace Library\Stack;

use Library\Error;
use Library\Utilities\FormatVar;

/*
 * 		Extension is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * Extension.
 *
 * Additional methods for Stack class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Stack.
 */
class Extension extends \Library\Stack implements \Iterator, \Countable, \ArrayAccess, \Serializable
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
	 * @param array $stack = (optional) array with which to initialize the stack
	 */
	public function __construct($stack=null)
	{
		parent::__construct();
		if (($stack !== null) && is_array($stack))
		{
			$this->stack = $stack;
		}

		$this->readOnly = false;
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * offsetSet
	 *
	 * Set the stack at $offset to $value. if $offset is null, set next stack entry to $value
	 * @param integer $offset = offset to stack location to set
	 * @param mixed $value = value to set stack at $offset to
	 * @throws \Library\Stack\Exception
	 */
	public function offsetSet($offset, $value)
	{
		$this->isReadWrite();
		parent::offsetSet($offset, $value);
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
				$stackTop = array_push($this->stack, $argument);
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
	 * @return null
	 * @throws \Library\Stack\Exception
	 */
	public function removeKey($key)
	{
		if (! array_key_exists($key, $this->stack))
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
		$this->stack = array();
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
			$this->stack = $stack;
		}

		return $this->stack;
	}

	/**
	 * keys
	 *
	 * returns an array containing all of the names (keys) from the stack
	 * @return array $keys
	 */
	public function keys()
	{
		return array_keys($this->stack);
	}

	/**
	 * values
	 *
	 * returns an array containing all of the values from the stack
	 * @returns array $values
	 */
	public function values()
	{
		return array_values($this->stack);
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

		$this->isReadWrite();
		$this->stack = array_merge($this->stack, $stack);
		return $this->stack;
	}

	/**
	 * end.
	 *
	 * Returns the last item in the stack
	 * @return mixed $value = value of the last element in the stack
	 */
	public function end()
	{
		return end($this->stack);
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
		return array_search($value, $this->stack);
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
	protected function isReadWrite()
	{
		if ($this->readOnly)
		{
			throw new Exception(Error::code('ReadOnly'));
		}
	}

}
