<?php
namespace Library\Directory;

use Library\Directory;
use Library\Error;
use Library\LLRBTree;
use Library\Properties;

/*
 *		Directory\DirectoryFolder is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *		Refer to the file named License.txt provided with the source, 
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * Directory\DirectoryFolder
 *
 * Directory content Folder class
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Directory
 */
class DirectoryFolder implements \Iterator, \Countable, \ArrayAccess
{
	/**
	 * directoryName
	 * 
	 * The name of (path to) the directory folder
	 * @var string $directoryName
	 */
	public $directoryName;

	/**
	 * directoryTree
	 * 
	 * Root node of the directory tree, or null if empty
	 * @var object $directoryTree
	 */
	public $directoryTree;

	/**
	 * contents
	 * 
	 * A Directory\Contents instance
	 * @var object $contents
	 */
	public $contents;

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
	 * @param string $directoryName = absolute path to the directory
	 * @param LLRBNode $parentNode = parent node, or null if no parent
	 * @param integer $order = (optional) sort order constant (default = SCANDIR_SORT_ASCENDING)
	 * @param resource $context = (optional) stream context (default = null)
	 * @throws Directory\Exception, LLRBTree\Exception, Exception
	 */
	public function __construct($directoryName, $order=SCANDIR_SORT_ASCENDING, $context=null)
	{
		Directory::change($directoryName);

		$this->parentNode = null;
		$this->directoryName = $directoryName;
		$this->directoryTree = null;
		$this->contents = null;

		$directoryTree = new LLRBTree();
		$contents = new Contents($directoryName, $order, $context);

		foreach($contents as $key => $file)
		{
			if (($file === '.') || ($file === '..'))
			{
				continue;
			}

			if (Directory::isDir($file))
			{
				$directoryNode = $directoryTree->search($file, null, false);
				if ($directoryNode !== null)
				{
					throw new Exception(Error::code('DirectoryDuplicateEntry'));
				}

				$newDirectory = $directoryName . "/" . $file;
				$newFolder = new DirectoryFolder($newDirectory, $order, $context);

				if (($directoryNode = $directoryTree->search($file, $newFolder, true)) === null)
				{
					throw new Exception(Error::code('NodeAllocationFailed'));
				}
				
				$newTree = $directoryNode->data();
				
				if ($newTree !== $newFolder)
				{
					throw new Exception(Error::code('InvalidClassObject'));
				}

				$newFolder->parentNode = $directoryNode;

				Directory::change('..');

				continue;
			}

			if (($node = $directoryTree->search($file, null, false)) !== null)
			{
				throw new Exception(Error::code('DirectoryDuplicateEntry'));
			}

			$directoryTree->search($file, new Properties(Directory::pathInfo($file)), true);
		}
		
		$this->directoryTree = $directoryTree;
		$this->contents = $contents;
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
	 * Pass on unknown method calls to $directoryTree
	 * @param string $name
	 * @param array $arguments
	 * @return mixed $result = method return result, or null if method not found
	 */
	public function __call($name, $arguments)
	{
		if (($this->directoryTree !== null) && (method_exists($this->directoryTree, $name)))
		{
			return $this->directoryTree->{$name}(implode(",", $arguments));
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
		return $this->folderStructure(0);
	}

	/**
	 * folderStructure
	 * 
	 * Return a printable folder structure
	 * @param int $level = directory level (sub-directory number)
	 * @return string $buffer = printable folder structrue
	 */
	public function folderStructure($level)
	{
		$directories = explode("/", trim($this->directoryName));

		$buffer = $this->indentLine($level, sprintf('directory: %s', $directories[count($directories)-1]));

		$level++;
		foreach($this->directoryTree as $key => $data)
		{
			if (is_object($data) && (get_class($data) == get_class($this)))
			{
				$buffer .= $data->folderStructure($level);
			}
			elseif (is_object($data)) 
			{
				$buffer .= $this->indentLine($level, sprintf('file     : %s.%s', $data->filename, $data->extension));
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
		return sprintf("%s%s\n", str_repeat("\t\t", $level), $string);
	}

	/**
	 * count
	 * 
	 * Return number of elements in this tree
	 * @return integer $count = number of elements
	 */
	public function count()
	{
		return $this->directoryTree->count();
	}

	/**
	 * valid.
	 *
	 * Returns the validity of the current node pointer
	 * @return boolean true = the current node pointer is valid.
	 */
	public function valid()
	{
		return $this->directoryTree->valid();
	}

	/**
	 * key.
	 *
	 * Returns the current tree node key value.
	 * @return $key = current tree node value.
	 */
	public function key()
	{
		return $this->directoryTree->key();
	}

	/**
	 * current.
	 *
	 * Returns the current tree node data.
	 * @return $current = current tree node data.
	 */
	public function current()
	{
		return $this->directoryTree->current();
	}

	/**
	 * next.
	 *
	 * Moves current to the next node in the tree in In-Order Traversal.
	 */
	public function next()
	{
		return $this->directoryTree->next();
	}

	/**
	 * rewind.
	 *
	 * Moves the current node pointer to the first item in the tree.
	 */
	public function rewind()
	{
		$this->directoryTree->rewind();
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
		$this->directoryTree->offsetSet($offset, $value);
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
		return $this->directoryTree->offsetGet($offset);
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
		return $this->directoryTree->offsetExists($offset);
	}

	/**
	 * offsetUnset
	 *
	 * Unset the provided offset
	 * @param mixed $offset = offset to unset
	 */
	public function offsetUnset($offset)
	{
		$this->directoryTree->offsetUnset($offset);
	}

}
