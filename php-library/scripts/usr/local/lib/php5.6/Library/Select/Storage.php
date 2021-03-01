<?php
namespace Library\Select;

use Library\Error;
use Library\Utilities\FormatVar;
use Library\Stack\Factory as StackFactory;

/*
 *		Library\Select\Storage is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * Library\Select\Storage
 *
 * stream_select storage class for the Select class.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Select
 */
class Storage
{
	/**
	 * selectDescriptors
	 * 
	 * List of SelectDescriptor's defining select requests
	 * @var SplDoublyLinkedList $selectDescriptors
	 */
	public $selectDescriptors;

	/**
	 * timeout
	 * 
	 * select wait timeout as a real (seconds.msec)
	 * @var real $timeout;
	 */
	public $timeout;

	/**
	 * pollSetupRequired
	 * 
	 * Call Poll::setupPollArrays() before processing a stream_select
	 * @var boolean $pollSetupRequired
	 */
	public $pollSetupRequired;

	/**
	 * __construct
	 * 
	 * Class constructor
	 */
	public function __construct()
	{
		$this->selectDescriptors = StackFactory::instantiateClass('stackex');

		$this->descriptorsCompleted = 0;

		$this->timeout = (real)0.1;
		$this->pollSetupRequired = true;
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

}
