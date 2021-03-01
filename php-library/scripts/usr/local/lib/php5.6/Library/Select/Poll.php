<?php
namespace Library\Select;

use Library\Error;
use Library\Utilities\FormatVar;

/*
 *		Library\Select\Poll is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  Refer to the file named License.txt provided with the source,
 *		 or from http://opensource.org/licenses/academic.php
*/
/**
 * Library\Select\Poll
 *
 * Poll the select streams
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Select
 */
class Poll
{
	/**
	 * store
	 * 
	 * Select storage container
	 * @var object $storage
	 */
	public $storage;

	/**
	 * readSelected
	 * 
	 * Copy of readArray containing only active requests
	 * @var array $readSelected
	 */
	public $readSelected;

	/**
	 * readKeys
	 * 
	 * keys to map readSelected to original readArray
	 * @var array $readKeys
	 */
	public $readKeys;

	/**
	 * readBackup
	 * 
	 * Copy of readSelected - used to reload readSelected when poll yields no events and no new requests are placed
	 * @var array $readBackup
	 */
	public $readBackup;

	/**
	 * writeSelected
	 * 
	 * Copy of writeArray containing only active requests
	 * @var array $writeSelected
	 */
	public $writeSelected;

	/**
	 * writeKeys
	 * 
	 * keys to map writeSelected to original writeArray
	 * @var array $writeKeys
	 */
	public $writeKeys;

	/**
	 * writeBackup
	 * 
	 * Copy of writeSelected - used to reload writeSelected when poll yields no events and no new requests are placed
	 * @var array $writeBackup
	 */
	public $writeBackup;

	/**
	 * exceptSelected
	 *
	 * Copy of exceptArray containing only active requests
	 * @var array $exceptSelected
	 */
	public $exceptSelected;

	/**
	 * exceptKeys
	 * 
	 * keys to map exceptSelected to original exceptArray
	 * @var array $exceptKeys
	 */
	public $exceptKeys;

	/**
	 * exceptBackup
	 * 
	 * Copy of exceptSelected - used to reload exceptSelected when poll yields no events and no new requests are placed
	 * @var array $exceptBackup
	 */
	public $exceptBackup;

	/**
	 * selected
	 * 
	 * The number of items in selected queue
	 * @var integer $selected
	 */
	public $selected;

	/**
	 * selectReady
	 * 
	 * The number of items returned as being ready from the last stream_select call
	 * @var integer $selectReady
	 */
	public $selectReady;

	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param object $storage = a SelectStore container object
	 */
	public function __construct($storage)
	{
		$this->storage = $storage;

		$this->storage->timeout = 0.2;
		$this->storage->pollSetupRequired = true;

		$this->readSelected = array();
		$this->readKeys = array();
		$this->readBackup = array();

		$this->writeSelected = array();
		$this->writeKeys = array();
		$this->writeBackup = array();

		$this->exceptSelected = array();
		$this->exceptKeys = array();
		$this->exceptBackup = array();

		$this->selected = 0;
		$this->selectReady = 0;
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
	 * Returns a printable string of all properties
	 * @return string $buffer
	 */
	public function __toString()
	{
		FormatVar::sort(false);
		FormatVar::sortValues(false);
		FormatVar::recurse(true);

		return FormatVar::format(get_object_vars($this), get_class($this));
	}

	/**
	 * poll
	 * 
	 * poll (select) the selected arrays for a completed event, or until timeout expires
	 * @return mixed $result = count of all ready events, 0 if timeout
	 * @throws Exception if selected arrays are empty
	 */
	public function poll()
	{
		$this->selectReady = 0;

		if ($this->storage->pollSetupRequired)
		{
			if ($this->setupPollArrays() == 0)
			{
				throw new Exception(Error::code('StreamSelectEmpty'));
			}
		}
		else
		{
			$this->readSelected = $this->readBackup;
			$this->writeSelected = $this->writeBackup;
			$this->exceptSelected = $this->exceptBackup;

			if ((count($this->readSelected) == 0) && (count($this->writeSelected) == 0) && (count($this->exceptSelected) == 0))
			{
				throw new Exception(Error::code('StreamSelectEmpty'));
			}
		}

		$timeoutSec = (integer)$this->storage->timeout;
		$timeoutMSec = (integer)(1000000000 * ($this->storage->timeout - $timeoutSec));

		if (($this->selectReady = stream_select($this->readSelected, $this->writeSelected, $this->exceptSelected, $timeoutSec, $timeoutMSec)) !== 0)
		{
			$this->selectedResources('readSelected', 'readKeys');
			$this->selectedResources('writeSelected', 'writeKeys');
			$this->selectedResources('exceptSelected', 'exceptKeys');

			$this->storage->pollSetupRequired = true;
		}

		return $this->selectReady;
	}

	/**
	 * selectedResources
	 * 
	 * Copy selected resources key to selectedKeys
	 * @param array $nameSelected = selected array name
	 * @param array $nameKeys = selected keys name
	 */
	private function selectedResources($nameSelected, $nameKeys)
	{
		if (count($this->{$nameSelected}) > 0)
		{
			foreach($this->{$nameSelected} as $resource)
			{
				$key = array_search($resource, $this->{$nameKeys}, false);

				$descriptor = $this->storage->selectDescriptors[$key];

				$descriptor->enabled = false;
				$descriptor->ready = true;
			}
		}
	}

	/**
	 * setupPollArrays
	 * 
	 * Setup the arrays required to poll the select type
	 * @throws Exception if a descriptor->type is invalid or unknown
	 */
	public function setupPollArrays()
	{
		$this->readSelected = array();
		$this->readKeys = array();

		$this->writeSelected = array();
		$this->writeKeys = array();

		$this->exceptSelected = array();
		$this->exceptKeys = array();

		$this->selected = 0;

		if (count($this->storage->selectDescriptors) > 0)
		{
			foreach($this->storage->selectDescriptors as $key => $descriptor)
			{
				if ($descriptor->enabled)
				{
					switch($descriptor->type)
					{
						case 'read':
							$this->updateArrays("readSelected", $descriptor->resource, "readKeys", $key);
							break;
							
						case 'write':
							$this->updateArrays("writeSelected", $descriptor->resource, "writeKeys", $key);
							break;
							
						case 'except':
							$this->updateArrays("exceptSelected", $descriptor->resource, "exceptKeys", $key);
							break;
							
						default:
							throw new Exception(Error::code('StreamSelectUnknown'));
					}
					
					$this->selected++;
				}
				
				$descriptor->ready = false;
			}
		}

		$this->readBackup = $this->readSelected;
		$this->writeBackup = $this->writeSelected;
		$this->exceptBackup = $this->exceptSelected;

		$this->storage->pollSetupRequired = false;
		
		return $this->selected;
	}

	/**
	 * updateArrays
	 * 
	 * Update the select array and select key arrays
	 * @param string $name = array name ('readArray', 'writeArray', 'exceptArray')
	 * @param resource $resource = resource to store in the $name array
	 * @param string $keys = keys array name ('readKeys', 'writeKeys', 'exceptKeys')
	 * @param string $key = key to store in $keys array
	 */
	private function updateArrays($name, $resource, $keys, $key)
	{
		array_push($this->{$name}, $resource);
		$this->{$keys}[$key] = $resource;
	}

	/**
	 * timeout
	 * 
	 * The wait timeout in seconds (real) to nanosec resolution
	 * @param real $timeout = (optional) wait timeout, null to query
	 * @return real $timeout = current wait timeout
	 */
	public function timeout($timeout=null)
	{
		if ($timeout !== null)
		{
			$this->storage->timeout = (real)$timeout;
		}

		return $this->storage->timeout;
	}

	/**
	 * selectReady
	 * 
	 * Returns the number of items selected in the last call to poll()
	 * @return integer $selectReady
	 */
	public function selectReady()
	{
		return $this->selectReady;
	}

	/**
	 * selected
	 * 
	 * Returns the number of items scheduled to poll
	 * @return integer $selected
	 */
	public function selected()
	{
		return $this->selected;
	}

}

