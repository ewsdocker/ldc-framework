<?php
namespace Application\jackAudioControl;

use Application\JackAudioControl\Exception as jackException;

use Library\Accept\Input as AcceptInput;
use Library\Error;

/*
 *		jackAudioControl\Console is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * jackAudioControl\Console
 *
 * Class to provide command-line functionality (CLI only) to jackAudioControl
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package jackAudioControl
 * @subpackage Console
 */
class Console extends AcceptInput
{
	/**
	 * select
	 * 
	 * Select class instance
	 * @var object $select
	 */
	public $select;

	/**
	 * command
	 * 
	 * Command class instance
	 * @var object $command
	 */
	public $command;

	/**
	 * inputBuffer
	 * 
	 * The console input buffer
	 * @var string $inputBuffer
	 */
	public $inputBuffer;

	/**
	 * outputBuffer
	 * 
	 * The console output buffer
	 * @var string $outputBuffer
	 */
	public $outputBuffer;

	/**
	 * executing
	 * 
	 * True = execution continues, False = abort requested
	 * @var boolean $executing
	 */
	public $executing;

	/**
	 * __construct
	 * 
	 * Class constructor
	 */
	public function __construct($select, &$executing, $command)
	{
		parent::__construct();

		$this->select = $select;
		$this->command = $command;
		$this->executing = &$executing;

		$this->select->register('jackAudio', 'read', $this->handle(), array('class' => $this, 'method' => 'input'));
		$this->select->bufferAddress('jackAudio', 'read', $this->inputBuffer);
		$this->select->enable('jackAudio', 'read');

//		$this->driver = new SelectDriver($this->select->storage());

		$this->select->register('jackAudio', 'write', STDOUT, array('class' => $this, 'method' => 'output'));
		$this->select->bufferAddress('jackAudio', 'write', $this->outputBuffer);
	}

	/**
	 * __destruct
	 * 
	 * Class destructor
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * input
	 * 
	 * Console input driver
	 * @param Select\Descriptor $descriptor
	 * @throws Exception
	 */
	public function input($descriptor)
	{
		$descriptor->buffer = parent::accept();
		$this->command->validCommand(trim(strtolower($descriptor->buffer)));
		$this->select->enable('jackAudio', 'read');
	}

	/**
	 * display
	 * 
	 * Display the message on the console output device
	 * @param string $message
	 */
	public function display($message)
	{
		$this->outputBuffer = $message;

		$this->select->bufferIndex('jackAudio', 'write', strlen($message));
		$this->select->enable('jackAudio', 'write');
	}

	/**
	 * output
	 * 
	 * Console output driver
	 * @param Select\Descriptor $descriptor
	 * @throws Exception
	 */
	public function output($descriptor)
	{
		parent::printMessage($this->outputBuffer);
	}

}