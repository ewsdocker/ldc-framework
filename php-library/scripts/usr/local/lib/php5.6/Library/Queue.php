<?php
namespace Library;
use Library\Error;

/*
 * 		Queue is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
 */
/**
 * Queue.
 *
 * Queue class implements the functions of a FIFO stack, or queue, in the SPLQueue structure.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2005, 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Stack.
 */
class Queue implements \Countable, \Iterator, \ArrayAccess
{
	/**
	 * instance
	 *
	 * Stack\Queue object
	 * @var object $instance
	 */
	protected static	$instance=null;

	/**
	 * name
	 * 
	 * The (optional) name of this stack.
	 * @var string $name
	 */
	protected static	$name='';

	/**
	 * me
	 * 
	 * This class instance
	 * @var object $me
	 */
	protected static	$me=null;

	/**
	 * __construct
	 *
	 * class constructor
	 * @param string $name = (optional) queue name
	 * @return Queue $instance
	 */
	private function __construct($name=null)
	{
		self::$me = $this;
		self::$instance = new Stack\Queue($name);
		self::$name = $name;
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 * @return null
	 */
	public function __destruct()
	{
		self::$instance = null;
	}

	/*
	 * classInstance
	 * 
	 * The instance of this class
	 * @param string $name = (optional) queue name
	 * @return object Libray\Queue object
	 */
	public static function classInstance($name=null)
	{
		self::instance($name);
		return self::$me;
	}

	/**
	 * instance
	 *
	 * Return class instance, create new one if none exists.
	 * @return object $instance
	 */
	protected static function instance($name=null)
	{
		if (! self::$instance)
		{
			new Queue($name);
		}
		
		return self::$instance;
	}

	/**
	 * add
	 *
	 * add data to the TAIL of the queue
	 * @param mixed $data = data to add to the queue structure
	 */
	public static function add($data)
	{
		self::instance()->add($data);
	}

	/**
	 * prepend
	 *
	 * add data to (in front of) the HEAD of the queue
	 * @param mixed $data = data to prepend
	 * @return null
	 */
	public static function prepend($data)
	{
		self::instance()->prepend($data);
	}

	/**
	 * remove
	 *
	 * remove data from the HEAD of the queue
	 * @param mixed $key = (optional) queue key to remove, null to remove the top element
	 * @return mixed $data = data from the HEAD of the queue
	 * @throws Queue\Exception
	 */
	public static function remove($key=null)
	{
		return self::instance()->remove($key);
	}

	/**
	 * removeTail
	 *
	 * remove data from the TAIL of the queue
	 * @return mixed $data = data from the TAIL of the queue
	 * @throws Queue_Exception
	 */
	public static function removeTail()
	{
		return self::instance()->removeTail();
	}

	/**
	 * removeAll
	 *
	 * delete all entries from the queue
	 * @param string $name = (optional) name of the new queue, null to keep current name
	 * @return object $instance
	 */
	public static function removeAll($name=null)
	{
		self::$instance = null;
		if ($name)
		{
			self::$name = $name;
		}

		return self::classInstance(self::$name);
	}

	/**
	 * isEmpty
	 *
	 * return true if the queue is empty, false if it still contains items
	 * @return boolean $emptyStatus
	 */
	public static function isEmpty()
	{
		return self::instance()->isEmpty();
	}

	/**
	 * mode
	 *
	 * set/get the current iterator mode - either SplDoublyLinkedList::IT_MODE_KEEP or SplDoublyLinkedList::IT_MODE_DELETE
	 * @param integer $mode = (optional) mode to set, null to query only
	 * @return integer $mode
	 */
	public static function mode($mode=null)
	{
		return self::instance()->mode($mode);
	}

	/**
	 * head
	 *
	 * return the data from the queue head item
	 * @return mixed $data
	 */
	public static function head()
	{
		return self::instance()->head();
	}

	/**
	 * tail
	 *
	 * return the data from the queue tail item
	 * @return mixed $data
	 */
	public static function tail()
	{
		return self::instance()->tail();
	}

	/**
	 * name
	 *
	 * get/set the queue name
	 * @param string $name = (optional) queue name, null to query only
	 * @return string $name
	 */
	public static function name($name=null)
	{
		if ($name !== null)
		{
			self::$name = self::instance()->name($name);
		}

		return self::$name;
	}

	/**
	 * prev
	 *
	 * set the key to the previous queue item
	 */
	public static function prev()
	{
		self::instance()->prev();
	}

	/**
	 * queueArray
	 *
	 * Assemble the queue contents into an array
	 * @return array $queueArray
	 */
	public static function queueArray()
	{
		return self::instance()->queueArray();
	}

	// ***************************************************************************************
	//
	//		Countable interface
	//
	// ***************************************************************************************

	/**
	 * count
	 *
	 * return the number queue entries
	 * @return integer $entries
	 */
	public function count()
	{
		return self::instance()->count();
	}

	// ***************************************************************************************
	//
	//		Iterator interface
	//
	// ***************************************************************************************

	/**
	 * current
	 *
	 * get the current queue node's data
	 * @return mixed $data
	 */
	public function current()
	{
		return self::instance()->current();
	}

	/**
	 * key
	 *
	 * get the current queue node index
	 * @return mixed $key
	 */
	public function key()
	{
		return self::instance()->key();
	}

	/**
	 * next
	 *
	 * set the key to the next queue item
	 */
	public function next()
	{
		self::instance()->next();
	}

	/**
	 * rewind
	 *
	 * set the key to the HEAD of the queue
	 */
	public function rewind()
	{
		self::instance()->rewind();
	}

	/**
	 * valid
	 *
	 * query validity of the current node
	 * @return boolean $valid = true if key is valid, false if it is not
	 */
	public function valid()
	{
		return self::instance()->valid();
	}

	// ***************************************************************************************
	//
	//		ArrayAccess interface
	//
	// ***************************************************************************************

	/**
	 * offsetSet
	 *
	 * Set the queue at $offset to $value. if $offset is null, set next queue entry to $value
	 * @param integer $offset = offset to queue location to set
	 * @param mixed $value = value to set queue at $offset to
	 * @throws \Library\Queue\Exception
	 */
	public function offsetSet($offset, $value)
	{
		self::instance()->offsetSet($offset, $value);
	}

	/**
	 * offsetExists
	 *
	 * return true if a value exists at queue location $offset
	 * @param integer $offset = offset location to check
	 * @return boolean true = exists, false = doesn't exist
	 */
	public function offsetExists($offset)
	{
		return self::instance()->offsetExists($offset);
	}

	/**
	 * offsetUnset
	 *
	 * Unset the entry at queue location $offset
	 * @param integer $offset = location to unset
	 * @throws \Library\Stack\Exception
	 */
	public function offsetUnset($offset)
	{
		self::instance()->offsetUnset($offset);
	}

	/**
	 * offsetGet
	 *
	 * Get value at the specified $offset
	 * @param integer $offset = offset in the queue to fetch value from
	 * @return mixed $instance[$offset]
	 */
	public function offsetGet($offset)
	{
		return self::instance()->offsetGet($offset);
	}

	// ***************************************************************************************
	//
	//		Magic functions: __toSTring()
	//
	// ***************************************************************************************


	public function __toString()
	{
		return self::instance()->__toString();
	}

}
