<?php
namespace Library\LLRBTree;

use Library\Utilities\FormatVar;

/*
 * 		LLRBNode is copyright � 2008, 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * LLRBNode.
 *
 * LLRBNode implements the bNode abstract class to create a LLRB node container (object).
 * @author Jay Wheeler.
 * @version 2.0
 * @copyright � 2008, 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library.
 * @subpackage LLRBTree.
 */
class LLRBNode extends bNode
{
	const RED = true;
	const BLACK = false;

	const LEFT = true;
	const RIGHT = false;

	/**
	 * detail
	 * 
	 * Show detail flag - true = node and flag info, false = no node and flag info
	 * @var boolean $detail
	 */
	public $ndetail;
	
	/**
	 * __construct.
	 *
	 * Create the class object.
	 * @param mixed $key = object key.
	 * @param mixed $data = (optional) object data.
	 * @param boolean $ndetail = (optional) detail flag (true = include links, false = no links)
	 */
	public function __construct($key, $data=null, $ndetail=false)
	{
		parent::__construct($key, $data, self::RED);

		$this->ndetail = $ndetail;
	}

	/**
	 * __destruct
	 *
	 * Class destructor.
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * __toString
	 *
	 * Return printable string of the data
	 * @return string printable data string
	 */
	public function __toString()
	{
		FormatVar::sort(false);
		FormatVar::sortValues(false);
		FormatVar::recurse(true);

		$key = $this->nkey;
		$data = $this->ndata;

		if (strtolower(trim($key)) == 'password')
		{
			$data = str_repeat('*', strlen($data));
		}

		if (is_object($data))
		{
			$data = get_class($data);
		}
		elseif (is_array($data))
		{
			$data = FormatVar::format($data, $key);
		}

		if ($this->ndetail)
		{
			return sprintf("%s = %s, left = %s, right = %s, flag = %s",
						   $key,
						   $data,
						   (($this->left() === null) ? 'null' : $this->left()->key()),
						   (($this->right() === null) ? 'null' : $this->right()->key()),
						   ($this->flag() ? 'RED' : 'BLACK'));
		}
		
		return sprintf("%s = %s", $key, $data);
	}

	/**
	 * __get
	 *
	 * Override the __get method to provide direct access to properties via methods
	 * @param string $key = the property to get
	 * @return mixed
	 */
	public function __get($key)
	{
		if (method_exists($this, $key))
		{
			return $this->{$key}();
		}

		return null;
	}

	/**
	 * __set
	 *
	 * override the __set method
	 * @param string $key = name of the property/method
	 * @param mixed $data = key value
	 */
	public function __set($key, $data)
	{
		if (method_exists($this, $key))
		{
			$this->{$key}($data);
		}
	}

	/**
	 * key.
	 *
	 * (Sets and returns) the node key.
	 * @param mixed $key = (optional) key to set, null to query only.
	 * @return mixed $key
	 */
	public function key($key=null)
	{
		if ($key !== null)
		{
			$this->nkey = $key;
		}

		return $this->nkey;
	}

	/**
	 * data.
	 *
	 * (Sets and) returns the node data value.
	 * @param mixed $data = (optional) value of the data to set, null to query.
	 * @return $data
	 */
	public function data($data=null)
	{
		if ($data !== null)
		{
			if ($data === 'null')
			{
				$data = null;
			}

			$this->ndata = $data;
		}

		return $this->ndata;
	}

	/**
	 * setNull.
	 *
	 * Set the node data value to null.
	 * @return null
	 */
	public function setNull()
	{
		$this->ndata = null;
	}

	/**
	 * compare.
	 *
	 * Compare the supplied with the nodes key.  Returns 0 if equal, 1 if >, -1 if <
	 * @param mixed $keyNode = node containing the key to be compared.
	 * @return integer 0 = equal, 1 = >, -1 = <
	 */
	public function compare($keyNode)
	{
		return (($keyNode->nkey == $this->nkey) ? 0 : (($keyNode->nkey > $this->nkey) ? 1 : -1));
	}

	/**
	 * left.
	 *
	 * (Sets and) returns the left child node.
	 * @param $link link to the left child (null to query).
	 * @return $link to the left pointer.
	 */
	public function left($link=false)
	{
		if ($link !== false)
		{
			$this->nleft = $link;
		}
		return $this->nleft;
	}

	/**
	 * right.
	 *
	 * (Sets and) returns the right child node.
	 * @param $link (optional) pointer to the right child, null to query only.
	 * @return unknown_type
	 */
	public function right($link=false)
	{
		if ($link !== false)
		{
			$this->nright = $link;
		}
		return $this->nright;
	}

	/**
	 * flag.
	 *
	 * (Sets and) returns the flag.
	 * @param $flag = RED or BLACK
	 * @return $flag = flag setting
	 */
	public function flag($flag=null)
	{
		if ($flag !== null)
		{
			$this->nflag = $flag;
		}
		return $this->nflag;
	}
	
	/**
	 * showDetail.
	 *
	 * (Sets and) returns the detail flag.
	 * @param boolean $detail = (optional) boolean flag setting, null to query
	 * @return boolean $detail = detail setting
	 */
	public function showDetail($detail=null)
	{
		if ($detail !== null)
		{
			$this->ndetail = $detail;
		}
		return $this->ndetail;
	}

}
