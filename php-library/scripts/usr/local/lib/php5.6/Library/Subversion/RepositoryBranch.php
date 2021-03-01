<?php
namespace Library\Subversion;

use Library\Error;
use Library\LLRBTree\MultiBranch\Branch as MBBranch;
use Library\Properties;

/*
 *		Subversion\RepositoryBranch is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *		Refer to the file named License.txt provided with the source, 
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * Subversion\RepositoryBranch
 *
 * Repository content Branch tree class
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Subversion
 */
class RepositoryBranch extends MBBranch implements \Iterator, \Countable, \ArrayAccess
{
	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param string $path = absolute path to the directory
	 * @throws LLRBTree\MultiBranch\Exception, LLRBTree\Exception, Exception
	 */
	public function __construct($path)
	{
		parent::__construct($path);
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
	 * __call
	 * 
	 * Pass on unknown method calls to $root
	 * @param string $name
	 * @param array $arguments
	 * @return mixed $result = method return result, or null if method not found
	 */
	public function __call($name, $arguments)
	{
		if (($this->root !== null) && (method_exists($this->root, $name)))
		{
			return $this->root->{$name}(implode(",", $arguments));
		}
		
		return null;
	}

	/**
	 * __toString
	 * 
	 * Return the tree as a printable string
	 * @return string $buffer
	 */
	public function __toString()
	{
		return parent::__toString();
	}

	/**
	 * count
	 * 
	 * Return number of elements in this tree
	 * @return integer $count = number of elements
	 */
	public function count()
	{
		return parent::count();
	}

	/**
	 * valid.
	 *
	 * Returns the validity of the current node pointer
	 * @return boolean true = the current node pointer is valid.
	 */
	public function valid()
	{
		return parent::valid();
	}

	/**
	 * key.
	 *
	 * Returns the current tree node key value.
	 * @return $key = current tree node value.
	 */
	public function key()
	{
		return parent::key();
	}

	/**
	 * current.
	 *
	 * Returns the current tree node data.
	 * @return $current = current tree node data.
	 */
	public function current()
	{
		return parent::current();
	}

	/**
	 * next.
	 *
	 * Moves current to the next node in the tree in In-Order Traversal.
	 */
	public function next()
	{
		return parent::next();
	}

	/**
	 * rewind.
	 *
	 * Moves the current node pointer to the first item in the tree.
	 */
	public function rewind()
	{
		parent::rewind();
	}

	/**
	 * offsetSet
	 *
	 * Set the value at the indicated node
	 * @param mixed $offset = offset to the entry (property name)
	 * @param mixed $value = value of property
	 */
	public function offsetSet($offset, $value)
	{
		parent::offsetSet($offset, $value);
	}

	/**
	 * offsetGet
	 *
	 * Get the value at the indicated offset
	 * @param mixed $offset = offset to the entry (property name)
	 * @returns mixed $value = value of property, null if not found
	 */
	public function offsetGet($offset)
	{
		return parent::offsetGet($offset);
	}

	/**
	 * offsetExists
	 *
	 * Returns true if the offset exists, false if not
	 * @param mixed $offset = offset (property) to test for existence
	 * @return boolean true = successful, false = failed.
	 */
	public function offsetExists($offset)
	{
		return parent::offsetExists($offset);
	}

	/**
	 * offsetUnset
	 *
	 * Unset the provided offset
	 * @param mixed $offset = offset to unset
	 */
	public function offsetUnset($offset)
	{
		parent::offsetUnset($offset);
	}

}
