<?php
namespace Library\LLRBTree\MultiBranch;

use Library\Error;
use Library\LLRBTree;

/*
 * 		LLRBTree\MultiBranch\Tree is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * LLRBTree\MultiBranch\Tree
 * 
 * Multi-branch tree
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package LLRBTree
 * @subpackage MultiBranch
 */
class Tree
{
	/**
	 * SEARCH_BRANCH
	 * 
	 * Directory search
	 * @var SEARCH_BRANCH
	 */
	const	SEARCH_BRANCH = 0;

	/**
	 * SEARCH_NAME
	 * 
	 * File search
	 * @var SEARCH_NAME
	 */
	const	SEARCH_NAME = 1;

	/**
	 * branch
	 * 
	 * Root node of the tree plex, or null if empty
	 * @var object $branch
	 */
	public $branch;

	/**
	 * path
	 * 
	 * The path to the directory
	 * @var string $path
	 */
	public $path;

	/**
	 * paths
	 * 
	 * Array containing $path parsed into an array of paths
	 * @var array $paths
	 */
	public $paths;

	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param string $path = absolute path to the directory
	 * @throws Directory\Exception, LLRBTree\Exception, Exception
	 */
	public function __construct($path)
	{
		if (($this->path = $path) === '')
		{
			$this->path = "/";
		}

		$this->paths = explode("/", $this->path);

		$this->branch = new Branch($this->path);
		if ($this->branch->root === null)
		{
			throw new Exception(Error::code('DirectoryTreeError'));
		}
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
	 * __toString
	 * 
	 * Return a string representation (for debugging purposes)
	 * @return string $buffer
	 */
	public function __toString()
	{
		$buffer = get_class($this);
		$buffer .= ":\n";
		$buffer .= (string)$this->branch;

		return $buffer;
	}

	/**
	 * treeStructure
	 * 
	 * Returns a printable string of the complete tree structure
	 * @return string $buffer
	 */
	public function treeStructure()
	{
		return sprintf("%s\n", $this->branch->branchStructure(0));
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
	public function search($name, $type=self::SEARCH_NAME, $info=null, $okayToAdd=false)
	{
		return $this->branch->search($name, $type, $info, $okayToAdd);
	}

}
