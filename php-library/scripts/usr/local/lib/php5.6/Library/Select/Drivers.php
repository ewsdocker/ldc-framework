<?php
namespace Library\Select;

use Library\Utilities\FormatVar;

/*
 *		Library\Select\Drivers is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  Refer to the file named License.txt provided with the source,
 *		 or from http://opensource.org/licenses/academic.php
*/
/**
 * Library\Select\Drivers
 *
 * Default input/output drivers for select classes
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Select
 */
class Drivers
{
	/**
	 * storage
	 * 
	 * Select storage container
	 * @var object $storage
	 */
	public $storage;

	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param object $storage = a Storage container object
	 */
	public function __construct($storage)
	{
		$this->storage = $storage;
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
	 * read
	 * 
	 * Default read process
	 * @param string $descriptor = Select\Descriptor object to process
	 * @return boolean $result = true if complete, false if more to read
	 */
	public function read($descriptor)
	{
		$descriptor->ready = false;
		$descriptor->enabled = false;
		$descriptor->complete = false;

		if (! @feof($descriptor->resource))
		{
			if (($read = @fread($descriptor->resource, 8192)) !== false)
			{
				$descriptor->buffer .= $read;
				$descriptor->enabled = true;
			}
		}

		$descriptor->index = strlen($descriptor->buffer);

		return true;
	}

	/**
	 * write
	 * 
	 * Default write process
	 * @param string $descriptor = Select\Descriptor object to process
	 * @return boolean $result = true if complete, false if more to write
	 */
	public function write($descriptor)
	{
		$descriptor->ready = false;
		$descriptor->enabled = false;
		$descriptor->complete = false;

		$result = false;
		if ((! @feof($descriptor->resource)) && ($descriptor->index < strlen($descriptor->buffer)))
		{
			$bufferLength = strlen($descriptor->buffer);

			if (($result = @fwrite($descriptor->resource, substr($descriptor->buffer, $descriptor->index), $bufferLength - $descriptor->index)) !== false)
			{
				$descriptor->index += $result;
				if ($descriptor->index < $bufferLength)
				{
					$descriptor->enabled = true;
				}
			}	
		}

		if ($result === false)
		{
			$descriptor->complete = true;
			return true;
		}

		return false;
	}

	/**
	 * except
	 * 
	 * Default except process
	 * @param string $descriptor = Select\Descriptor object to process
	 * @return boolean $result = true if complete, false if more to read
	 */
	public function except($descriptor)
	{
		return $this->read($descriptor);
	}

}