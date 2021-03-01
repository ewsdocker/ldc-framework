<?php
namespace Library\LLRBTree;

/*
 * 		LLRBTree\bNode is copyright � 2008, 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 * bNode.
 * 
 * Abstract class containing binary tree node abstract methods.
 * @author Jay Wheeler.
 * @version 2.0
 * @copyright � 2008, 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library.
 * @subpackage LLRBTree.
 */
abstract class bNode implements iComparable
{
	/**
	 * nkey
	 * 
	 * The key property.
	 * @var mixed $nkey
	 */
	protected $nkey;
	
	/**
	 * ndata
	 * 
	 * The data property
	 * @var mixed $ndata
	 */
	protected $ndata;

	/**
	 * nleft
	 * 
	 * The left node link
	 * @var integer $nleft
	 */
	protected $nleft;

	/**
	 * nright
	 * 
	 * The right node link
	 * @var integer $nright
	 */
	protected $nright;

	/**
	 * nflag
	 * 
	 * The node flag
	 * @var integer $nflag
	 */
	protected $nflag;

	/**
	 * __construct.
	 * 
	 * Create the class object.
	 * @param mixed $key = object key.
	 * @param mixed $data = (optional) object data.
	 */
	public function __construct($key, $data=null, $flag=true)
	{
		$this->nkey = $key;
		$this->ndata = $data;

		$this->nleft = null;
		$this->nright = null;
		$this->nflag = $flag;
	}

	/**
	 * __destruct.
	 * 
	 * Class destructor.
	 */
	public function __destruct()
	{
	}

	/**
	 * key.
	 * 
	 * (Sets and returns) the node key.
	 * @param mixed $key = (optional) the key to set, null to query only.
	 * @return mixed $key
	 */
	abstract public function key($key=null);

	/**
	 * data.
	 * 
	 * (Sets and) returns the node data value.
	 * @param mixed $data = (optional) the value of the data to set, null to query only.
	 * @return mixed $data
	 */
	abstract public function data($data=null);

	/**
	 * setNull.
	 * 
	 * Sets the node data value to null.
	 */
	abstract public function setNull();

	/**
	 * left.
	 * 
	 * (Sets and) returns the left child node.
	 * @param integer $link = (optional) left child node, null to query only.
	 * @return integer $link
	 */
	abstract public function left($link=null);

	/**
	 * right.
	 * 
	 * (Sets and) returns the right child node.
	 * @param integer $link = (optional) right child node, null to query only.
	 * @return integer $link
	 */
	abstract public function right($link=null);

	/**
	 * flag.
	 * 
	 * (Sets and) returns the flag.
	 * @param boolean $flag = (optional) general purpose flag, null = query only
	 * @return boolean $flag = is the current flag value
	 */
	abstract public function flag($flag=null);

	/**
	 * compare.
	 * 
	 * Compares the supplied key with the nodes key.  Returns 0 if equal, 1 if >, -1 if <
	 * @param mixed $keyNode = node containing the key to be compared.
	 * @return integer 0 = equal, 1 = >, -1 = <
	 */
	public function compare($key)
	{		
	}

}
