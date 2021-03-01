<?php
namespace Library\Records;
use Library\Exception\Descriptor as ExceptionDescriptor;
use Library\Error;
use Library\FileIO\Factory as FileIOFactory;
use Library\Parse\Range;
use Library\Stack\Factory as StackFactory;

/*
 *		Records\LoadFile is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *		Refer to the file named License.txt provided with the source, 
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * Records\LoadFile
 *
 * Load records from a file into an array for processing using a select queue
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Records
 * @subpackage LoadFile
 */
class LoadFile implements \Countable, \Iterator, \ArrayAccess
{
	/**
	 * recordsFile
	 * 
	 * A string containing the ABSOLUTE location of the record file to process
	 * @var string $recordsFile
	 */
	protected $recordsFile;

	/**
	 * records
	 *
	 * An extended stack structure containing all records
	 * @var Stack $records
	 */
	protected $records;

	/**
	 * includedRecords
	 * 
	 * An extended stack structure containing the list of record numbers to process
	 * @var Stack $includedRecords
	 */
	protected $includedRecords;

	/**
	 * includeList
	 * 
	 * List of files to include
	 * @var string $includeList
	 */
	protected $includeList;

	/**
	 * excludeList
	 * 
	 * List of files to exclude
	 * @var string $excludeList
	 */
	protected $excludeList;

	/**
	 * prepared
	 * 
	 * True = the record set has been read/included/excuted and is ready to process, false = not prepared
	 * @var boolean $prepared
	 */
	private $prepared;

	/**
	 * __construct
	 *
	 * Class constructor.  
	 * Also load the file and setsup the process stack if $recordsFile is not null.
	 * @param string $recordsFile = (optional) records file name.  Auto opened if not null
	 * @param string $includeList = (optional) list of files to include (default='all')
	 * @param string $excludeList = (optional) list of files to exclude (default=null)
	 * @throws Exception
	 */
	public function __construct($recordsFile=null, $includeList='all', $excludeList=null)
	{
		$this->records = null;
		$this->includedRecords = null;

		$this->prepared = false;

		$this->includeList($includeList);
		$this->excludeList($excludeList);
		
		if ($this->recordsFile($recordsFile))
		{
			$this->prepare();
		}
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
	 * prepare
	 *
	 * Load the requested file to $records and setup process stack in $includedRecords
	 * @param string $recordsFile = (optional) (absolute path) name of the file to process
	 * @param string $includeList = (optional) list of files to include
	 * @return integer $count = number of valid records to process
	 * @throws Exception for various reasons (see the code)
	 */
	public function prepare($recordsFile=null, $includeList=null, $excludeList=null)
	{
		$includeList = $this->includeList($includeList);
		$excludeList = $this->excludeList($excludeList);

		if ($this->loadRecords($recordsFile) == 0)
   		{
   			throw new Exception(Error::code('FileNoRecords'));
   		}

   		if (($recordsCount = $this->includeRecords($includeList)) == 0)
		{
   			throw new Exception(Error::code('NoRecordsSelected'));
		}
		
   		if (($recordsCount = $this->excludeRecords($excludeList)) == 0)
		{
   			throw new Exception(Error::code('NoRecordsRemaining'));
		}
		
		$this->prepared = true;

		return $recordsCount;
	}

	/**
	 * loadRecords
	 * 
	 * Load the complete file into the records array
	 * @param string $recordsFile = (optional) records file name to load
	 * @return integer $count = number of records loaded
	 * @throws Exception
	 */
	public function loadRecords($recordsFile=null)
	{
		$this->prepared = false;

		$this->includedRecords = null;
		$this->records = null;

		if (! ($recordsFile = $this->recordsFile($recordsFile)))
		{
			throw new Exception(Error::code('MissingFileName'));
		}

		$descriptor = null;

		try
		{
			$records = FileIOFactory::instantiateClass('fileobject', $recordsFile, 'r', false);
		}
		catch(\Library\FileIO\Exception $exception)
		{
			$descriptor = new Descriptor($exception);
		}
		catch(\Library\Factory\Exception $exception)
		{
			$descriptor = new Descriptor($exception);
		}
		catch(\Library\Exception $exception)
		{
			$descriptor = new Descriptor($exception);
		}
		catch(\Exception $exception)
		{
			$descriptor = new Descriptor($exception);
		}
		
		if ($descriptor)
		{
			throw new Exception($descriptor->message, $descriptor->code, $descriptor->previous);
		}
		
		$this->records = StackFactory::instantiateClass('stackex');

		foreach($records as $key => $line)
		{
			$this->records->push(rtrim($line));
		}
		
		$records = null;

		return count($this->records);
	}

	/**
	 * includeRecords
	 * 
	 * Get the list of the record numbers to include in includedRecords to process
	 *    Note: automatically excludes blank (empty) records and comment records
	 * 
	 * @param string $includeList = list of records to be included
	 * @return integer $count = number of records included
	 * @throws Parse\Exception, Stack\Exception
	 */
	public function includeRecords($includeList=null)
	{
		$this->includedRecords = null;
		$this->includedRecords = StackFactory::instantiateClass('stackex');
		$this->prepared = false;

		$recordList = trim($includeList = $this->includeList($includeList));
		if (strlen($recordList) > 0)
		{
			$recordNumbers = explode(',', $recordList);
			foreach($recordNumbers as $range)
			{
				$next = 0;
				$last = count($this->records);

				Range::limits($range, $next, $last);
				for($next; $next < $last; $next++)
				{
					$record = trim($this->records[$next]);
					if (($record !== '') && (substr($record, 0, 1) !== '#'))
					{
						$this->includedRecords[] = $next;
					}
				}
			}
		}

		return count($this->includedRecords);
	}

	/**
	 * excludeRecords
	 * 
	 * Get the list of records to be excluded
	 * @param string $excludeList = (optional) list of records to exclude
	 * @return integer $count = count of the number of tests remaining
	 * @throws Parse\Exception
	 */
	public function excludeRecords($excludeList=null)
	{
		if (($recordList = trim($excludeList = $this->excludeList($excludeList))) > 0)
		{
			$recordNumbers = explode(',', $recordList);
			foreach($recordNumbers as $range)
			{
				$next = 0;
				$last = count($this->includedRecords);

				Range::limits($range, $next, $last);
				for($next; $next < $last; $next++)
				{
					if ($key = $this->includedRecords->inStack($next))
					{
						unset($this->includedRecords[$key]);
					}
				}
			}
		}

		$this->prepared = true;

		return count($this->includedRecords);
	}

	/**
	 * recordsFile
	 * 
	 * Get/set the name (absolute path) of the records file to process
	 * @param string $recordsFile = (optiona) name of the records file, null to query
	 * @returns string $recordsFile = current name of the records file
	 */
	public function recordsFile($recordsFile=null)
	{
		if ($recordsFile !== null)
		{
			$this->recordsFile = $recordsFile;
		}

		return $this->recordsFile;
	}

	/**
	 * includeList
	 * 
	 * Get/set the includeList property
	 * @param string $includeList = (optiona) string containing comma separated list(s) of records to include
	 * @returns string $includeList = current includeList
	 */
	public function includeList($includeList=null)
	{
		if ($includeList !== null)
		{
			$this->includeList = $includeList;
		}
		
		return $this->includeList;
	}

	/**
	 * excludeList
	 * 
	 * Get/set the excludeList property
	 * @param string $excludeList = (optiona) string containing comma separated list(s) of records to exclude
	 * @returns string $excludeList = current excludeList
	 */
	public function excludeList($excludeList=null)
	{
		if ($excludeList !== null)
		{
			$this->excludeList = $excludeList;
		}
		
		return $this->excludeList;
	}

	/**
	 * checkPrepared
	 * 
	 * If prepared flag is false, throws an exception
	 * @throws Exception
	 */
	protected function checkPrepared()
	{
		if (! $this->isPrepared())
		{
			throw new Exception(Error::code('NotInitialized'));
		}
	}

	/**
	 * isPrepared
	 * 
	 * Return value of the prepared flag (true = prepared to process, false = not prepared)
	 * @return boolean $prepared = true if prepared, false if not prepared
	 */
	public function isPrepared()
	{
		return $this->prepared;
	}

	/**
	 * includedRecords
	 * 
	 * Returns the includedRecords object (Stack\Extension class)
	 * @return object $includedRecords
	 * @throws Exception
	 */
	public function includedRecords()
	{
		if (! $this->includedRecords)
		{
			throw new Exception(Error::code('NotInitialized'));
		}

		return $this->includedRecords;
	}

	/**
	 * records
	 * 
	 * Returns the records object (Stack\Extension class)
	 * @return object $records
	 * @throws Exception
	 */
	public function records()
	{
		if (! $this->records)
		{
			throw new Exception(Error::code('NotInitialized'));
		}

		return $this->records;
	}

	/*										   */
	/* ************   Countable   ************ */
	/*										   */

	/**
	 * count
	 *
	 * return the number queue entries
	 * @return integer $entries
	 */
	public function count()
	{
		$this->checkPrepared();
		return $this->includedRecords->count();
	}

	/*										  */
	/* ************   Iterator   ************ */
	/*										  */

	/**
	 * current
	 *
	 * get the current record queue data
	 * @return mixed $data
	 */
	public function current()
	{
		$this->checkPrepared();
		return $this->records[$this->includedRecords->current()];
	}

	/**
	 * key
	 *
	 * get the current record queue index
	 * @return mixed $key
	 */
	public function key()
	{
		$this->checkPrepared();
		return $this->includedRecords->key();
	}

	/**
	 * next
	 *
	 * set the key to the next record queue element
	 */
	public function next()
	{
		$this->checkPrepared();
		$this->includedRecords->next();
	}

	/**
	 * rewind
	 *
	 * set the key to the HEAD of the queue
	 */
	public function rewind()
	{
		$this->checkPrepared();
		$this->includedRecords->rewind();
	}

	/**
	 * valid
	 *
	 * query validity of the current node
	 * @return boolean $valid = true if key is valid, false if it is not
	 */
	public function valid()
	{
		$this->checkPrepared();
		return $this->includedRecords->valid();
	}

	/*											  */
	/* ************   Array Access   ************ */
	/*											  */

	/**
	 * offsetSet
	 *
	 * Set the queue at $offset to $value. if $offset is null, set next queue entry to $value
	 * @param integer $offset = offset to queue location to set
	 * @param mixed $value = value to set queue at $offset to
	 * @throws \Library\Stack\Exception
	 */
	public function offsetSet($offset, $value)
	{
		$this->checkPrepared();
		$this->includedRecords[$offset] = $value;
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
		$this->checkPrepared();
		return isset($this->includedRecords[$offset]);
	}

	/**
	 * offsetUnset
	 *
	 * Unset the entry at queue location $offset
	 * @param integer $offset = location to unset
	 */
	public function offsetUnset($offset)
	{
		$this->checkPrepared();
		unset($this->includedRecords[$offset]);
	}

	/**
	 * offsetGet
	 *
	 * Get value at the specified $offset
	 * @param integer $offset = offset in the queue to fetch value from
	 * @return mixed $queue[$offset]
	 */
	public function offsetGet($offset)
	{
		$this->checkPrepared();
		return $this->includedRecords[$offset];
	}

}
