<?php
namespace Application\jackAudioControl\Console;

use Application\JackAudioControl\Exception as jackException;

use Library\Error;

/*
 *		jackAudioControl\Console\Command is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * jackAudioControl\Console\Command
 *
 * Command-line command processor
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package jackAudioControl
 * @subpackage Console
 */
class Command
{
	/**
	 * commands
	 * 
	 * Array containing command names and descriptions
	 * @var array $commands
	 */
	public $commands;

	/**
	 * command
	 * 
	 * String containing the latest command string entered
	 * @var string $command
	 */
	public $command;

	/**
	 * changed
	 * 
	 * True = $command changed, False = $command not changed
	 * @var boolean $changed
	 */
	public $changed;

	/**
	 * __construct
	 * 
	 * Class constructor
	 */
	public function __construct($command='start')
	{
		$this->commands = array('?'			=> 'List commands',
								'help'		=> 'List commands',
								'start' 	=> 'Start all processes',
								'stop'		=> 'Stop all processes',
								'quit'		=> 'Stop all processes and exit',
								'restart'	=> 'Restart all terminated processes',
								'status'	=> 'Show status of all processes',
								);
		$this->validCommand($command);
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
	 * validCommand
	 * 
	 * check if the entered command is valid
	 * @param string $command
	 */
	public function validCommand($command)
	{
		if (array_key_exists($command, $this->commands))
		{
			$this->command = $command;
			$this->changed = true;
		}
	}

	/**
	 * getCommand
	 * 
	 * Get the command string and reset the changed flag
	 * @return string $command
	 */
	public function getCommand()
	{
		$this->changed = false;
		return $this->command();
	}

	/**
	 * command
	 * 
	 * Return the latest command
	 * @return string $command
	 */
	public function command()
	{
		return $this->command;
	}

	/**
	 * commands
	 * 
	 * Return an array containing command names and descriptions
	 * @return array $commands
	 */
	public function commands()
	{
		return $this->commands;
	}

	/**
	 * changed
	 * 
	 * Return command changed state
	 * @return boolean $changed
	 */
	public function changed()
	{
		return $this->changed;
	}

}