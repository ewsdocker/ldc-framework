<?php
namespace Library;
use Library\Error;
use Library\ProgramExec;
use Library\Properties;

/*
 * 		ProgramExec is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
 */

/**
 * ProgramExec.
 *
 * Static program execution wrapper class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage ProgramExec
 */

class ProgramExec
{
	/**
	 * exec
	 * 
	 * Execute a command string
	 * @param string $cmd
	 * @param array $output
	 * @param integer $returnVal
	 * @return string = The last line from the result of the command
	 */
	public static function exec($cmd, $output=null, $returnVar=null)
	{
		if ($output !== null)
		{
			if ($returnVar !== null)
			{
				return exec($cmd, $output, $returnVar);
			}
			else 
			{
				return exec($cmd, $output);
			}
		}

		return exec($cmd);
	}

	/**
	 * system
	 * 
	 * Execute an external program and display the output
	 * @param unknown $cmd
	 * @param string $returnVar
	 * @return string | boolean = the last line of the command output on success, and FALSE on failure.
	 */
	public static function system($cmd, $returnVar=null)
	{
		if ($returnVar !== null)
		{
			return system($cmd, $returnVar);
		}

		return system($cmd);
	}
	
	/**
	 * shell_exec
	 * 
	 * Execute command via shell and return the complete output as a string
	 * @param string $command = command string to execute
	 * @return string | null = output from the executed command or NULL if an error occurred 
	 * 							or the command produces no output.
	 */
	public static function shell_exec($command)
	{
		return shell_exec($command);
	}

	/**
	 * passthru
	 * 
	 * Execute an external program and display raw output
	 * @param string $command
	 * @param integer $returnVar
	 */
	public static function passthru($command, $returnVar=null)
	{
		if ($returnVar !== null)
		{
			passthru($command, $returnVar);
		}

		passthru($command);
	}

	/**
	 * escapeShellArg
	 * 
	 * Escape a string to be used as a shell argument
	 * @param string $arg
	 * @return string $arg
	 */
	public static function escapeShellArg($arg)
	{
		return escapeShellArg($arg);
	}

	/**
	 * escapeShellCmd
	 * 
	 * Escape shell metacharacters
	 * @param string $command
	 * @return string $command
	 */
	public static function escapeShellCmd($command)
	{
		return escapeShellCommand($command);
	}

}
