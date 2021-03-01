<?php
namespace Library\Directory;
use Library\Error;
use Library\Utilities\FormatVar;

/*
 *		Directory\Contents is copyright � 2012, 2015 EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *		Refer to the file named License.txt provided with the source, 
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * Directory\Contents
 *
 * Directory contents class
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Directory
 */
class Contents implements \Iterator, \Countable
{
	/**
	 * directoryName
	 * 
	 * Directory name
	 * @var string $directoryName
	 */
	protected 		$directoryName;

	/**
	 * directory
	 * 
	 * Directory array
	 * @var array $directory
	 */
	protected		$directory;

	/**
	 * key
	 * 
	 * The current directory key
	 * @var integer $key
	 */
	protected		$key;

	/**
	 * sortOrder
	 * 
	 * The directory sort order: SCANDIR_SORT_ASCENDING, SCANDIR_SORT_DESCENDING, SCANDIR_SORT_NONE
	 * @var integer $sortOrder
	 */
	protected		$sortOrder;

	/**
	 * context
	 * 
	 * A stream context resource, or null
	 * @var resource $context
	 */
	protected		$context;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @throws Directory\Exception
	 */
	public function __construct($directoryName, $order=SCANDIR_SORT_ASCENDING, $context=null)
	{
		if (! $directoryName)
		{
			throw new Exception(Error::code('DirectoryNotIntialized'));
		}

		$this->directory = null;
		$this->directoryName = $directoryName;
		$this->sortOrder = $order;
		$this->context = $context;

		$this->scan();
	}

	/**
	 * __destruct
	 *
	 * Destroy the current class instance
	 */
	public function __destruct()
	{
	}

	/**
	 * __toString
	 * 
	 * Return the directory as a formatted array string
	 * @return string $buffer
	 */
	public function __toString()
	{
		FormatVar::sort(false);
		FormatVar::sortValues(false);
		FormatVar::recurse(true);

		return FormatVar::format($this->directory, 'Directory');
	}

	/**
	 * scan
	 * 
	 * Scan the selected directory
	 * @throws Directory\Exception
	 */
	protected function scan()
	{
		if ($this->context == null)
		{
			$this->directory = @scandir($this->directoryName, $this->sortOrder);
		}
		else
		{
			$this->directory = @scandir($this->directoryName, $this->sortOrder, $this->context);
		}

		if (! $this->directory)
		{
			throw new Exception(Error::code('DirectoryNotIntialized'));
		}

		$this->rewind();
	}

	/**
	 * count
	 * 
	 * Get the number of items in the directory list array
	 * @return integer $count
	 */
	public function count()
	{
		if ($this->directory !== null)
		{
			$count = count($this->directory);
		}
		else
		{
			$count = 0;
		}

		return $count;
	}

	/**
	 * current
	 * 
	 * Return the current directory entry
	 * @return string $current
	 */
	public function current()
	{
		return $this->directory[$this->key];
	}

	/**
	 * key
	 * 
	 * Return the current directory entry key
	 * @return integer $key
	 */
	public function key()
	{
		return $this->key;
	}

	/**
	 * next
	 * 
	 * Increment the directory key
	 */
	public function next()
	{
		$this->key++;
	}

	/**
	 * rewind
	 * 
	 * Reset the directory pointer to the start of the directory
	 */
	public function rewind()
	{
		$this->key = 0;
	}

	/**
	 * valid
	 * 
	 * Return true if the current directory item is valid
	 * @return boolean $result = true if valid, false if not
	 */
	public function valid()
	{
		if ((! $this->directory) || (! isset($this->directory[$this->key])))
		{
			return false;
		}

		return true;
	}

	/**
	 * directoryName
	 * 
	 * Get the directory directoryName
	 * @return string $directoryName
	 */
	public function directoryName()
	{
		return $this->directoryName;
	}

	/**
	 * sortOrder
	 * 
	 * Get the directory sort order: SCANDIR_SORT_ASCENDING, SCANDIR_SORT_DESCENDING, SCANDIR_SORT_NONE
	 * @return integer $sortOrder
	 */
	public function sortOrder()
	{
		return $this->sortOrder;
	}

	/**
	 * context
	 * 
	 * Returns the stream context, or null if not set
	 * @return resource $context
	 */
	public function context()
	{
		return $this->context;
	}

	/**
	 * directory
	 * 
	 * Get the current directory as an array, null if not set
	 * @return array $directory
	 */
	public function directory()
	{
		return $this->directory;
	}

}
