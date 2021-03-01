<?php
namespace Library\Select;

use Library\Utilities\FormatVar;

/*
 *		Library\Select\Process is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  Refer to the file named License.txt provided with the source,
 *		 or from http://opensource.org/licenses/academic.php
*/
/**
 * Library\Select\Process
 *
 * Main Select process class
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Select
 */
class Process
{
	/**
	 * storage
	 * 
	 * Select storage container
	 * @var object $storage
	 */
	public $storage;

	/**
	 * descriptorsCompleted
	 * 
	 * The number of descriptors completed in the last readyDescriptors scan
	 * @var integer $descriptorsCompleted;
	 */
	public $descriptorsCompleted;

	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param object $storage = a SelectStore container object
	 */
	public function __construct($storage)
	{
		$this->storage = $storage;

		$this->descriptorsCompleted = 0;
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
	 * readyDescriptors
	 * 
	 * Process all ready descriptors
	 * @return boolean $ready = true
	 * @throws Exception
	 */
	public function readyDescriptors()
	{
		$this->descriptorsCompleted = 0;

		foreach($this->storage->selectDescriptors as $key => $descriptor)
		{
			if ($descriptor->ready)
			{
				$class = $descriptor->callback['class'];
				$method = $descriptor->callback['method'];

				if ($class->$method($descriptor))
				{
					$this->descriptorsCompleted++;
				}

				$descriptor->ready = false;
			}
		}
		
		return true;
	}

	/**
	 * descriptorsCompleted
	 * 
	 * Returns the number of descriptors completed on the last call to readyDescriptors
	 * @return integer $descriptorsCompleted
	 */
	public function descriptorsCompleted()
	{
		return $this->descriptorsCompleted;
	}

}