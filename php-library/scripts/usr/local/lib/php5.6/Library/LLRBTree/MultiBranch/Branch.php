<?php
namespace Library\LLRBTree\MultiBranch;

use Library\Error;
use Library\LLRBTree;
use Library\Properties;

/*
 *		LLRBTree\MultiBranch\Branch is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *		Refer to the file named License.txt provided with the source, 
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * LLRBTree\MultiBranch\Branch
 *
 * MultiBranch tree Branch class
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package LLRBTree
 * @subpackage MultiBranch
 */
class Branch implements \Iterator, \Countable, \ArrayAccess
{
	/**
	 * path
	 * 
	 * The name of (path to) the branch
	 * @var string $path
	 */
	public $path;

	/**
	 * paths
	 * 
	 * Array containing the parsed $path
	 * @var array $paths
	 */
	public $paths;

	/**
	 * root
	 * 
	 * Root node of the tree, or null if empty
	 * @var object $root
	 */
	public $root;

	/**
	 * parentNode
	 * 
	 * The parent node - set by creating class AFTER the __construct method returns
	 * @var LLRBNode $parentNode;
	 */
	public $parentNode;

	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param string $path = absolute path to the branch
	 * @throws LLRBTree\Exception, Exception
	 */
	public function __construct($path)
	{
		$this->parentNode = null;

		$this->path = $path;
		$this->paths = explode("/", trim($this->path));

		$this->root = new LLRBTree();
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
		return $this->branchStructure(0);
	}

	/**
	 * branchStructure
	 * 
	 * Return a printable branch structure
	 * @param int $level = directory level (sub-directory number)
	 * @return string $buffer = printable branch structrue
	 */
	public function branchStructure($level)
	{
		$buffer = $this->indentLine($level, sprintf('path: %s', $this->paths[count($this->paths)-1]));

		$level++;
		foreach($this->root as $key => $data)
		{
			if (is_object($data) && (get_class($data) == get_class($this)))
			{
				$buffer .= $data->branchStructure($level);
			}
			elseif (is_object($data)) 
			{
				$buffer .= $this->indentLine($level, sprintf('name: %s', $key));
				if ($data->extension !== null)
				{
					$buffer .= sprintf('.%s', $data->extension);
				}
			}
		}
		
		return $buffer;
	}

	/**
	 * indentLine
	 * 
	 * Indent the string by $level tab characters
	 * @param integer $level = number of tabs
	 * @param string $string = string to print
	 * @return string $string = indented line
	 */
	private function indentLine($level, $string)
	{
		return sprintf("%s%s\n", str_repeat("\t", $level), $string);
	}

	/**
	 * search
	 * 
	 * Search for the requested $name.  If not found, and okayToAdd = true, add $name and $info.
	 * @param array   $name = array containing parsed name
	 * @param integer $type = (optional) SEARCH_NAME (0) = name (default), SEARCH_BRANCH (1) = directory
	 * @param mixed   $info = (optional) array/properties containing info to save (default = null)
	 * @param boolean $okayToAdd = (optional) true = okay to add if not found, false = not (default)
	 * @throws Exception
	 * @return LLRBNode $node = found/added node, null if not found and $okayToAdd=false
	 */
	public function search($name, $type=Tree::SEARCH_NAME, $info=null, $okayToAdd=false)
	{
		if (! is_array($name))
		{
			throw new Exception(Error::code('ArrayVariableExpected'));
		}

		$key = array_shift($name);
		$node = $this->root->search($key, null, false);

		if ($node === null)
		{
			if (! $okayToAdd)
			{
				return $node;
			}

			if (count($name) == 0)
			{
				if ($type === Tree::SEARCH_NAME)
				{
					if (($node = $this->root->search($key, new Properties($info), true)) === null)
					{
						throw new Exception(Error::code('NodeAllocationFailed'));
					}

					return $node;
				}
			}

			$newBranch = new Branch($this->path . "/" . $key);

			if (($node = $this->root->search($key, $newBranch, true)) === null)
			{
				throw new Exception(Error::code('NodeAllocationFailed'));
			}

			if ($node->data() !== $newBranch)
			{
				throw new Exception(Error::code('InvalidClassObject'));
			}
		
			$newBranch->parentNode = $node;

			if (count($name) > 0)
			{
				return $newBranch->search($name, $type, $info, $okayToAdd);
			}
		}
		elseif (count($name) > 0)
		{
			if (get_class($node->data) !== get_class($this))
			{
				throw new Exception(Error::code('InvalidClassObject'));
			}

			return $node->data->search($name, $type, $info, $okayToAdd);
		}
		
		return $node;
	}

	/**
	 * count
	 * 
	 * Return number of elements in this tree
	 * @return integer $count = number of elements
	 */
	public function count()
	{
		return $this->root->count();
	}

	/**
	 * valid.
	 *
	 * Returns the validity of the current node pointer
	 * @return boolean true = the current node pointer is valid.
	 */
	public function valid()
	{
		return $this->root->valid();
	}

	/**
	 * key.
	 *
	 * Returns the current tree node key value.
	 * @return $key = current tree node value.
	 */
	public function key()
	{
		return $this->root->key();
	}

	/**
	 * current.
	 *
	 * Returns the current tree node data.
	 * @return $current = current tree node data.
	 */
	public function current()
	{
		return $this->root->current();
	}

	/**
	 * next.
	 *
	 * Moves current to the next node in the tree in In-Order Traversal.
	 */
	public function next()
	{
		return $this->root->next();
	}

	/**
	 * rewind.
	 *
	 * Moves the current node pointer to the first item in the tree.
	 */
	public function rewind()
	{
		$this->root->rewind();
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
		$this->root->offsetSet($offset, $value);
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
		return $this->root->offsetGet($offset);
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
		return $this->root->offsetExists($offset);
	}

	/**
	 * offsetUnset
	 *
	 * Unset the provided offset
	 * @param mixed $offset = offset to unset
	 */
	public function offsetUnset($offset)
	{
		$this->root->offsetUnset($offset);
	}

}
