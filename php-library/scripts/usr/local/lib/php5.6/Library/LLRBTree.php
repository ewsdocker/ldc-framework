<?php
namespace Library;

use Library\LLRBTree\bTree;
use Library\LLRBTree\LLRBNode;

/*
 * 		LLRBTree is copyright � 2008, 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * LLRBTree (Left Leaning Red Black Tree).
 *
 * LLRBTree implements the bTree abstract class to create a LLRB Tree container (object).
 * @author Jay Wheeler.
 * @version 2.0
 * @copyright � 2008, 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage LLRBTree.
 */
class LLRBTree extends bTree
{
	/**
	 * keyNode
	 *
	 * A LLRBNode temporary node
	 * @var LLRBNode $keyNode
	 */
	private $keyNode;

	/**
	 * traversal
	 *
	 * traversal array - is maintained only when enumerating the tree
	 * @var array $traversal
	 */
	protected $traversal;

	/**
	 * tdetail
	 *
	 * detail flag - true = additional node linkage displayed, false = no additional info.
	 * @var boolean $tdetail
	 */
	protected $tdetail;

	/**
	 * tshowRoot
	 *
	 * show root name flag - true = show root name, false = show nothing
	 * @var boolean $tshowRoot
	 */
	protected $tshowRoot;

	/**
	 * __construct.
	 *
	 * Class constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->tdetail = false;
		$this->tshowRoot = false;

		$this->keyNode = null;
		$this->traversal = array();
	}

	/**
	 * __destruct.
	 *
	 * Class destructor.
	 */
	public function __destruct()
	{
		parent::__destruct();
		unset($this->keyNode);
	}

	/**
	 * __get
	 *
	 * Override the default routine to get the data from the given key
	 * @param string $key = key to get
	 * @return mixed $data
	 */
	public function __get($key)
	{
		if (! $node = $this->search($key))
		{
			return null;
		}

		return $node->data();
	}

	/**
	 * __set
	 *
	 * Override default routine to set the given data in the provided key
	 * @param string $key = the node key to set
	 * @param mixed $data = data to add to the node
	 */
	public function __set($key, $data)
	{
		$this->search($key, $data, true);
	}

	/**
	 * __toString
	 *
	 * return a printable buffer containing a representation of the tree structure
	 * @return string $buffer
	 */
	public function __toString()
	{
		$buffer = '';

		if ($this->tshowRoot)
		{
			$buffer .= sprintf("\tRoot = %s\n", ($this->root == null) ? 'null' : $this->root->key());
		}

		$nodeNumber = 0;
		foreach($this as $key => $data)
		{
			$node = $this->currentNode();
			$nodeNumber++;

			$buffer .= sprintf("\t% 2u - %s\n", $nodeNumber, (string)$node);

	    	if (is_object($data) && ($data instanceOf \Library\LLRBTree))
	    	{
	    		$tree = (string)$data;
	    		$lines = explode("\n", $tree);
	    		foreach($lines as $index => $text)
	    		{
	    			if (strlen(trim($text)) > 0)
	    			{
	    				$buffer .= sprintf("\t\t%s\n", $text);
	    			}
	    		}
	    	}
		}

		return $buffer;
	}

	/**
	 * search.
	 *
	 * lookup a key in the tree.
	 * @param string $key = the key to lookup in the tree.
	 * @param mixed $data = (optional) the value to store in the key-ed node.
	 * @param boolean $okayToAdd = (optional) true to add unfound node, false to return null if not found.
	 * @return LLRBTree\LLRBNode $node = pointer to the node, null if not found.
	 */
	public function search($key, $data=null, $okayToAdd=false)
	{
		$this->allocateKeynode($key, $data);
		$node = $this->root;
		while ($node != null)
		{
			switch($node->compare($this->keyNode))
			{
			case 0:
				return $node;

			case -1:
				$node = $node->left();
				break;

			case 1:
				$node = $node->right();
				break;
			}
		}

		if (! $okayToAdd)
		{
			return null;
		}

		return $this->insert($key, $data);
	}

	/**
	 * insert.
	 *
	 * insert a key node in the tree.
	 * @param string $key = key to add to the tree.
	 * @param mixed $data = (optional) data to add to the key node.
	 * @return LLRBNode $node = pointer to the inserted key node.
	 */
	public function insert($key, $data=null)
	{
		$this->allocateKeynode($key, $data);
		$this->root = $this->insertNode($this->root, $this->keyNode);
		if ($this->root != null)
		{
			$this->root->flag(LLRBNode::BLACK);
		}

		return $this->search($key);
	}

	/**
	 * root.
	 *
	 * Return the current tree root.
	 * @return $root = current tree root.
	 */
	public function root()
	{
		return $this->root;
	}

	/**
	 * nodes.
	 *
	 * Return the number of unique nodes in the tree.
	 * @return integer $nodes = count of the unique nodes.
	 */
	public function nodes()
	{
		return $this->nodes;
	}

	/**
	 * firstNode.
	 *
	 * Return the first item in the tree (in key order).
	 * @return LLRBNode $node = the first node in the tree, null if the tree is empty.
	 */
	public function firstNode()
	{
		$this->traversal = array();
		$this->current = $this->traverseBranch($this->root);
		return $this->current;
	}

	/**
	 * nextNode.
	 *
	 * Return the next item in the tree (in key order).
	 * @return LLRBNode$node = the next node in the tree, null if at the tree end.
	 */
	public function nextNode()
	{
		$this->current = null;
		if (count($this->traversal) > 0)
		{
			$this->current = array_pop($this->traversal);
			if ($this->current != null)
			{
				if ($this->current->right() != null)
				{
					$this->current = $this->traverseBranch($this->current->right());
					return $this->current;
				}
			}

			if (count($this->traversal) > 0)
			{
				$this->current = array_pop($this->traversal);
				array_push($this->traversal, $this->current);
			}
			else
			{
				$this->current = null;
			}
		}

		return $this->current;
	}

	/**
	 * currentNode.
	 *
	 * Returns the current tree node.
	 * @return LLRBNode $current = current tree node.
	 */
	public function currentNode()
	{
		return $this->current;
	}

	/**
	 * valid.
	 *
	 * Returns the validity of the current node pointer
	 * @return boolean true = the current node pointer is valid.
	 */
	public function valid()
	{
		return ! is_null($this->current);
	}

	/**
	 * key.
	 *
	 * Returns the current tree node key value.
	 * @return string $key = current tree node key value.
	 */
	public function key()
	{
		return $this->current->key();
	}

	/**
	 * current.
	 *
	 * Returns the current tree node data.
	 * @return mixed $current = current tree node data.
	 */
	public function current()
	{
		return $this->current->data();
	}

	/**
	 * next.
	 *
	 * Moves current to the next node in the tree in In-Order Traversal.
	 * @return LLRBNode $node = next node
	 */
	public function next()
	{
		return $this->nextNode();
	}

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
	 * count
	 *
	 * get the number of unique nodes in the tree
	 * @return integer $count
	 */
	public function count()
	{
		$this->nodes = 0;
		$node = $this->firstNode();
		while($node)
		{
			$this->nodes++;
			$node = $this->nextNode();
		}

		return $this->nodes;
	}

	/**
	 * traverseBranch.
	 *
	 * Traverse the supplied branch to the left leaf, storing the nodes met along the way.
	 * Returns the first node with no left child (a left leaf).
	 * @param LLRBNode $node = root of the branch to be traversed.
	 * @return LLRBNode $node = the pointer to the left leaf found, null if none found.
	 */
	protected function traverseBranch($node)
	{
		while(($node != null) && ($node->left() != null))
		{
			array_push($this->traversal, $node);
			$node = $node->left();
		}

		if ($node != null)
		{
			array_push($this->traversal, $node);
		}

		return $node;
	}

	/**
	 * insertNode.
	 *
	 * Insert the node into the tree at the proper place, then balance the tree.
	 * @param LLRBNode $node = the tree root node.
	 * @param LLRBNode $keyNode = the key to insert.
	 * @return LLRBNode $node = the inserted node
	 */
	protected function insertNode($node, $keyNode)
	{
		if ($node == null)
		{
			$this->nodes++;
			return new LLRBTree\LLRBNode($keyNode->key(), $keyNode->data(), $this->tdetail);
		}

		switch($node->compare($keyNode))
		{
		case 0:  // equal
			$node->data($keyNode->data());
			break;

		case -1: // less
			$node->left($this->insertNode($node->left(), $keyNode));
			break;

		case 1:  // greater
			$node->right($this->insertNode($node->right(), $keyNode));
			break;
		}

		return $this->fixUp($node);
	}

	/**
	 * fixUp.
	 *
	 * Balance the tree and fix up the colors on the way up the tree.
	 * @param LLRBNode $node is the tree root node.
	 * @param boolean $deleting = true if deleting a node, false if inserting
	 * @return LLRBNode $node = the root node following all fixUps.
	 */
	protected function fixUp($node, $deleting=false)
	{
		if ($this->isRed($node->right()))
	    {
	    	if ($deleting ||
	    	    ((! $deleting) && (! $this->isRed($node->left()))))
	    	{
	    		$node = $this->rotateLeft($node);
	    	}
		}

		if (($this->isRed($node->left())) &&
		    ($this->isRed($node->left()->left())))
	    {
	    	$node = $this->rotateRight($node);
	    }

	    if (($this->isRed($node->left())) &&
	        ($this->isRed($node->right())))
        {
        	$this->flipColors($node);
        }

		return $node;
	}

	/**
	 * isRed.
	 *
	 * Return true if the node is valid and colored red.
	 * @param LLRBNode $node = the node to check
	 * @return boolean $result = validation result (true = red).
	 */
	protected function isRed($node)
	{
		if (($node != null) && ($node->flag() == LLRBTree\LLRBNode::RED))
		{
			return true;
		}
		return false;
	}

	/**
	 * rotateLeft.
	 *
	 * Perform a rotate left operation around the node
	 * @param LLRBNode $node = node to rotate to root.
	 * @return LLRBNode$root is the new root node
	 */
	protected function rotateLeft($node)
	{
		$root = $node->right();
		$node->right($root->left());
		$root->left($node);
		$root->flag($node->flag());
		$node->flag(LLRBTree\LLRBNode::RED);
		return $root;
	}

	/**
	 * rotateRight.
	 *
	 * Perform a rotate right operation around the node
	 * @param LLRBNode $node = node to rotate to root.
	 * @return LLRBNode $root is the new root node
	 */
	protected function rotateRight($node)
	{
		$root = $node->left();
		$node->left($root->right());
		$root->right($node);
		$root->flag($node->flag());
		$node->flag(LLRBTree\LLRBNode::RED);
		return $root;
	}

	/**
	 * flipColors.
	 *
	 * Flip the colors of the current node and its (2) children.
	 * @param LLRBNode $node = node to flip colors in.
	 */
	protected function flipColors($node)
	{
		$node->flag(! $node->flag());
		$child = $node->left();
		$child->flag(! $child->flag());
		$child = $node->right();
		$child->flag(! $child->flag());
	}

	/**
	 * treeTop.
	 *
	 * Return the minimum item in the tree (the top).
	 * @return LLRBNode $node = node containing the smallest item.
	 */
	public function treeTop()
	{
		return $this->leaf($this->root);
	}

	/**
	 * leaf.
	 *
	 * Return the left leaf in the supplied branch.
	 * @param LLRBNode $node = branch to get the left leaf from
	 * @return LLRBNode $node = node containing the smallest item.
	 */
	public function leaf($node)
	{
		if ($node != null)
		{
			while($node->left() !== null)
			{
				$node = $node->left();
			}
		}
		return $node;
	}

	/**
	 * deleteMin.
	 *
	 * Delete the smallest item in the tree.
	 */
	public function deleteMin()
	{
		$this->root = $this->deleteMinNode($this->root);
		if ($this->root != null)
		{
			$this->root->flag(LLRBTree\LLRBNode::BLACK);
		}
	}

	/**
	 * delete.
	 *
	 * Delete a node in the tree.
	 * @param string $key = name of the key to delete
	 */
	public function delete($key)
	{
		$this->allocateKeynode($key);
		$this->root = $this->deleteNode($this->root, $this->keyNode);
		if ($this->root != null)
		{
			$this->root->flag(LLRBTree\LLRBNode::BLACK);
		}
	}

	/**
	 * deleteMinNode.
	 *
	 * Delete the minimum node in the branch.
	 * @param LLRBNode $node = branch to delete minimum node in.
	 * @return LLRBNode $node = new root after deletion.
	 */
	protected function deleteMinNode($node)
	{
		if ($node->left() == null)
		{
			return null;
		}

		if ((! $this->isRed($node->left())) && (! $this->isRed($node->left()->left())))
		{
			$node = $this->moveRedLeft($node);
		}

		$node->left($this->deleteMinNode($node->left()));
		if ($this->nodes > 0)
		{
			$this->nodes--;
		}
		return $this->fixUp($node, true);
	}

	/**
	 * moveRedLeft.
	 *
	 * Move the left red node.
	 * @param LLRBNode $node = node to be moved.
	 * @return LLRBNode $node is the new root.
	 */
	protected function moveRedLeft($node)
	{
		$this->flipColors($node);
		if (($node->right() != null) &&
		    ($this->isRed($node->right()->left())))
		{
			$node->right($this->rotateRight($node->right()));
			$node = $this->rotateLeft($node);
			$this->flipColors($node);
		}
		return $node;
	}

	/**
	 * moveRedRight.
	 *
	 * Move the right red node.
	 * @param LLRBNode $node = node to be moved.
	 * @return LLRBNode $node is the new root.
	 */
	protected function moveRedRight($node)
	{
		$this->flipColors($node);
		if (($node->left() != null) && ($this->isRed($node->left()->left())))
		{
			$node = $this->rotateRight($node);
			$this->flipColors($node);
		}
		return $node;
	}

	/**
	 * deleteNode.
	 *
	 * Delete a node in the tree.
	 * @param LLRBNode $node = node to delete.
	 * @param LLRBNode $keyNode = node containing the name of the key to delete
	 * @return LLRBNode $node = root node
	 */
	protected function deleteNode($node, $keyNode)
	{
		if ($node->compare($keyNode) < 0)
		{
			if ((! $this->isRed($node->left())) &&
			   	(! $this->isRed($node->left()->left())))
			{
				$node = $this->moveRedLeft($node);
			}
			$node->left($this->deleteNode($node->left(), $keyNode));
		}
		else
		{
			if ($this->isRed($node->left()))
			{
				$node = $this->rotateRight($node);
			}

			if (($node->compare($keyNode) == 0) && ($node->right() == null))
			{
				return null;
			}

			if ((! $this->isRed($node->right())) &&
			    (($node->right() != null) && (! $this->isRed($node->right()->left()))))
			{
				$node = $this->moveRedRight($node);
			}

			if ($node->compare($keyNode) == 0)
			{
				$leafNode = $this->leaf($node->right());
				$node->data($leafNode->data());
				$node->key($leafNode->key());
				$node->right($this->deleteMinNode($node->right()));
			}
			else
			{
				$node->right($this->deleteNode($node->right(), $keyNode));
			}
		}

		if ($this->nodes > 0)
		{
			$this->nodes--;
		}

		return $this->fixUp($node, true);
	}

	/**
	 * allocateKeynode.
	 *
	 * Create a new class keynode if none exists.  Add key and data to the keynode.
	 * @param string $key = node key
	 * @param mixed $data = (optional) node value, default = null
	 */
	protected function allocateKeynode($key, $data=null)
	{
		if ($this->keyNode == null)
		{
			$this->keyNode = new LLRBTree\LLRBNode($key, $data, $this->tdetail);
		}
		else
		{
			$this->keyNode->key($key);
			$this->keyNode->data($data);
		}
	}

	/**
	 * showDetail
	 *
	 * (Set and) return the show detail flag setting.
	 * @param boolean $detail = (optional) flag setting, null to query
	 * @return boolean $detail
	 */
	public function showDetail($detail=null)
	{
		if ($detail !== null)
		{
			$this->tdetail = $detail;
		}

		return $this->tdetail;
	}

	/**
	 * showRoot
	 *
	 * (Set and) return the show root name flag setting
	 * @param boolean $show = (optional) show root flag setting, null to query
	 * @return boolean $show
	 */
	public function showRoot($show=null)
	{
		if ($show !== null)
		{
			$this->tshowRoot = $show;
		}

		return $this->tshowRoot;
	}

	/* *****************************************************************************
	 *
	 * 		ArrayAccess Interface
	 *
	 *  **************************************************************************** */

	/**
	 * offsetSet
	 *
	 * Set the value at the indicated node
	 * @param mixed $offset = offset to the entry (property name)
	 * @param mixed $value = value of property
	 */
	public function offsetSet($offset, $value)
	{
		$this->__set($offset, $value);
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
		return $this->__get($offset);
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
		if (($result = $this->__get($offset)) !== null)
		{
			return true;
		}

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
		$this->delete($offset);
	}

}
