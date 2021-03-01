<?php
namespace Library\LLRBTree;

/*
 * 		LLRBTree\bTree is copyright � 2008, 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * bTree.
 *
 * Abstract class containing binary tree abstract methods.
 * @author Jay Wheeler.
 * @version 2.0
 * @copyright � 2008, 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage LLRBTree.
 */
abstract class bTree implements \ArrayAccess, \Iterator, \Countable
{
	/**
	 * root
	 *
	 * The current root node number
	 * @var integer $root
	 */
	protected $root;

	/**
	 * current
	 *
	 * The current node number
	 * @var integer $current
	 */
	protected $current;

	/**
	 * nodes
	 *
	 * Count of the number of unique nodes
	 * @var integer $nodes
	 */
	protected $nodes;

	/**
	 * __construct
	 *
	 * Create a class instance and initialize an empty tree
	 */
	public function __construct()
	{
		$this->nodes = 0;
		$this->root = null;
		$this->current = null;
	}

	/**
	 * __destruct.
	 *
	 * Class destructor.
	 * @return null
	 */
	public function __destruct()
	{
	}

	/**
	 * insert.
	 *
	 * insert a key node in the tree.
	 * @param $key is the key to add to the tree.
	 * @param $data is the (optional) data to add to the key node.
	 * @return $node = pointer to the inserted key node.
	 */
	abstract public function insert($key, $data=null);

	/**
	 * search.
	 *
	 * lookup a key in the tree.
	 * @param $key is the key to lookup in the tree.
	 * @param $data is the value to store in the key-ed node.
	 * @param $okayToAdd = true to add unfound node, false to return null if not found.
	 * @return $node = pointer to the node, null if not found.
	 */
	abstract public function search($key, $data=null, $okayToAdd=false);

	/**
	 * treeTop.
	 *
	 * Return the minimum item in the tree (the top).
	 * @return $node = node containing the smallest item.
	 */
	abstract public function treeTop();

	/**
	 * leaf.
	 *
	 * Return the left leaf in the supplied branch.
	 * @param $node = branch to get the left leaf from
	 * @return $node = node containing the smallest item.
	 */
	abstract public function leaf($node);

	/**
	 * deleteMin.
	 *
	 * Delete the smallest item in the tree.
	 */
	abstract public function deleteMin();

	/**
	 * delete.
	 *
	 * Delete a node in the tree.
	 * @param $key = name of the key to delete
	 * @return null
	 */
	abstract public function delete($key);

	/**
	 * root.
	 *
	 * Return the current tree root.
	 * @return $root = current tree root.
	 */
	abstract public function root();

	/**
	 * nodes.
	 *
	 * Return the number of unique nodes in the tree.
	 * @return $nodes = count of the unique nodes.
	 */
	abstract public function nodes();

	/**
	 * firstNode.
	 *
	 * Return the first item in the tree (in key order).
	 * @return $node = the first node in the tree, null if the tree is empty.
	 */
	abstract public function firstNode();

	/**
	 * nextNode.
	 *
	 * Return the next item in the tree (in key order).
	 * @return $node = the next node in the tree, null if at the tree end.
	 */
	abstract public function nextNode();

	/**
	 * currentNode.
	 *
	 * Returns the current tree node.
	 * @return $current = current tree node.
	 */
	abstract public function currentNode();

	/**
	 * rewind.
	 *
	 * Moves the current node pointer to the first item in the tree.
	 */
	public function rewind()
	{
		$this->firstNode();
	}

	/**
	 * current.
	 *
	 * Returns the current tree node data.
	 */
	public function current()
	{
	}

	/**
	 * key.
	 *
	 * Returns the current tree node key value.
	 * @return $key = current tree node value.
	 */
	public function key()
	{
		return null;
	}

	/**
	 * next.
	 *
	 * Moves current to the next node in the tree in In-Order Traversal.
	 * @return null.
	 */
	public function next()
	{
		return null;
	}

	/**
	 * valid.
	 *
	 * Returns the validity of the current node pointer
	 * @return boolean true = the current node pointer is valid.
	 */
	public function valid()
	{
		return false;
	}

	/**
	 * count
	 *
	 * Returns the number of unique nodes in the tree
	 * @return integer $count
	 */
	public function count()
	{
		return 0;
	}

	/**
	 * offsetSet
	 *
	 * Set the value at the indicated offset
	 * @param mixed $offset = offset to the entry (property name)
	 * @param mixed $value = value of property
	 */
	public function offsetSet($offset, $value)
	{
	}

	/**
	 * offsetGet
	 *
	 * Returns the value at the indicated offset
	 * @param mixed $offset = offset to the entry (property name)
	 * @returns mixed $value = value of property, null if not found
	 */
	public function offsetGet($offset)
	{
		return null;
	}

	/**
	 * offsetExists
	 *
	 * Returns true if the offset exists, false if not
	 * @param mixed $offset = offset (property) to test for existence
	 */
	public function offsetExists($offset)
	{
		return false;
	}

	/**
	 * offsetUnset
	 *
	 * Unset the provided offset
	 * @param mixed $offset = offset to unset
	 */
	public function offsetUnset($offset)
	{
	}

}
