<?php
namespace Application\Launcher\Utility;

use Application\Utility\Support;

/*
 *		Launcher\Utility\AnalyzeErrors is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	    Refer to the file named License.txt provided with the source, 
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * AnalyzeErrors
 *
 * Error analysis and output
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Launcher
 * @subpackage Utility
 */
class AnalyzeErrors
{
	/**
	 * queue
	 * 
	 * A StackQueue object containing the error descriptors
	 * @param StackQueue $queue
	 */
	public	$queue;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param object $queue = Library\Stack\Queue object containing error descriptors for the process, if any.
	 */
	public function __construct($queue)
	{
		$this->queue($queue);
		if (count($this->queue) > 0)
		{
			$this->analyzer();
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
	 * analyzer
	 * 
	 * Analyze the error descriptors
	 */
	public function analyzer()
	{
		if (count($this->queue) > 0)
		{
			Support::writeLog('******************');
			Support::writeLog('');
			Support::writeLog('    ERROR LIST');
			Support::writeLog('');
			Support::writeLog('******************');
			
			foreach($this->queue as $descriptor)
			{
				Support::writeLog($descriptor);
				Support::writeLog('');
				Support::writeLog('******************');
			}
		}
	}

	/**
	 * queue
	 * 
	 * Set/get the queue property
	 * @param StackQueue $queue = (optional) queue property to set, null to query only
	 * @return StackQueue $queue = current queue property
	 */
	public function queue($queue=null)
	{
		if ($queue !== null)
		{
			$this->queue = $queue;
		}
		
		return $this->queue;
	}
}
