<?php
namespace Library\ProgramExec;

use Library\Properties;

/*
 * 		ProgramExec\ProcStatus is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 			or from http://opensource.org/licenses/academic.php
 */

/**
 * ProgramExec\ProcStatus.
 *
 * Process status class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage ProgramExec
 */

class ProcStatus extends Properties
{
	/*
	 *	Fields:
	 *
	 * 		command		Command string passed to proc
	 * 		pid			Process id
	 * 		running		true if running
	 * 		signaled	true if terminated by uncaught signal
	 * 		stopped		true if stopped by a signal
	 * 		exitcode	exit code returned from process (when running is false)
	 * 		termsig		terminate signal number
	 * 		stopsig		stop signal number
	 */

	/**
	 * __construct
	 *
	 * Create a ProcStatus object instance from the Properties class
	 * @param string $name = name of the process
	 * @param mixed $properties = (optional) array or Properties object containing additional information to record
	 */
	public function __construct($name, $properties=null)
	{
		parent::__construct($properties);
		$this->name = $name;
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * __toString
	 * 
	 * Return a printable string of the status variables
	 * @return string $buffer
	 */
	public function __toString()
	{
		$buffer  = sprintf("%s (%u)", $this->name, $this->pid);
		if (! $this->running)
		{
			$buffer .= ': NOT RUNNING.';
		}
		
		if (! $this->running)
		{
			$buffer .= "\n\t";

			if ($this->signaled)
			{
				$buffer .= sprintf("Signaled: %s, Termsig: %s. ", $this->signaled, $this->termsig);
			}

			if ($this->stopped)
			{
				$buffer .= sprintf("Stopped: %s, Stopsig: %s. ", $this->stopped, $this->stopsig);
			}

			$buffer .= sprintf("Exitcode: %s.", $this->exitcode);
		}

		return $buffer;
	}

}
