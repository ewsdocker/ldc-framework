<?php
namespace Library\Accept;

use Library\Error\Messages as ErrorMessages;
use Library\PrintU\Output;
use Library\Properties;
use Library\Stream;

/*
 *		Library\Accept\Input is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  Refer to the file named License.txt provided with the source,
 *		 or from http://opensource.org/licenses/academic.php
*/
/**
 * Library\Accept\Input
 *
 * Accept input from the console with controlling options
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Accept
 */
class Input extends Output
{
  	/**
  	 * properties
  	 * 
  	 * A Properties object containing console parameters
  	 * @var object $properties
  	 */
	private $properties;

	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param string  $icase = (optional) input string case mapping (upper, lower, both)
	 * @param string  $trim  = (optional) input string trim setting (left, right, both, none)
	 * @param string  $prompt  = (optonal) prompt string to terminate prompt inputs
	 * @param integer $console = (optional) 1 to direct to console, 0 to direct to html
	 * @param string  $outDevice = (optional) output device, default = 'STDOUT'
	 */
	public function __construct($icase='both', $trim='both', $prompt=' < ', $console=1, $outDevice='STDOUT')
  	{
  		parent::__construct($outDevice);

		$this->properties = new Properties(array('console' 	=> $console,
												 'icase'   	=> $icase,
												 'trim'	   	=> $trim,
												 'propmt'	=> $prompt));

		$this->properties->stream = new Stream('fileobject', STDIN, 'r', false, $console);
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
	 * __get
	 * 
	 * Get the requested property value
	 * @param string $name = property name
	 * @return mixed $value = property value
	 */
	public function __get($name)
	{
		if ($name == 'properties')
		{
			return $this->properties;
		}

		if ($this->properties->exists($name))
		{
			return $this->properties->{$name};
		}

		return null;
	}
	
	/**
	 * __set
	 * 
	 * Set the requested property value
	 * @param string $name = property name
	 * @param mixed $value = property value
	 */
	public function __set($name, $value)
	{
   		$this->properties->{$name} = $value;
	}


	/**
	 * accept
	 * 
	 * get the next message in the input stream
	 * @param string $icase = (optional) case option
	 * @param string $trim = (optional) trim option
	 * @return string $acceptBuffer = current input buffer
	 */
	public function accept($icase=null,$trim=null)
	{
		$buffer = fgets(STDIN);

		$icase = $this->icase($icase);
		$trim = $this->trim($trim);

	  	switch($trim)
	  	{
	  		case 'left':
	  	  		$buffer = ltrim($buffer);
	  	  		break;

			case 'right':
				$buffer = rtrim($buffer);
				break;

			case 'both':
				$buffer = trim($buffer);
				break;

			default:
	  		case 'none':
	  	  		break;
		}

		switch($icase)
	  	{
			case 'upper':
				$buffer = strtoupper($buffer);
				break;

			case 'lower':
				$buffer = strtolower($buffer);
				break;

			default:
			case 'mixed':
			case 'both':
				break;
	  	}

	  	$this->properties->acceptBuffer = $buffer;

		return $buffer;
	}

	/**
	 * prompt
	 * 
	 * Output the prompt string
	 * @param string $prompt = (optional) prompt string
	 */
	public function prompt($prompt=null)
	{
		if ($prompt === null)
		{
			$prompt = $this->properties->prompt;
		}

		$this->printMessage($prompt);
	}

	/**
	 * promptAndAccept
	 * 
	 * prompt for the next message in the input stream
	 * @param string $prompt = message to display
	 * @param string $icase = case option
	 * @param string $trim = trim option
	 * @return string inputMessage
	 */
	public function promptAndAccept($prompt='', $icase=null, $trim=null)
	{
		$this->prompt($prompt);
		return $this->accept($icase, $trim);
	}

	/**
	 * newLineAndAccept
	 * 
	 * Prompt for the next message in the input stream after outputing a new line sequence
	 * @param string $prompt = (optional) output prompt message
	 * @param string $icase = (optional) input case conversion
	 * @param string $trim = (optional) trim conversion
	 * @return string $acceptBuffer = input message, false if no message
	 */
	public function newLineAndAccept($prompt='', $icase=null, $trim=null)
	{
		$this->printLine();
		$this->prompt($prompt);

		return $this->accept($icase,$trim);
	}

	/**
	 * acceptAvailable
	 * 
	 * waits for input to be available in the STDIN stream, or for the specified time
	 * @param integer $timeout = (optional) in sec (0 = return immediate status, null = wait forever)
	 * @return resource $selected = the selected resource or false if none selected
	 */
	public function acceptAvailable($timeout=null)
	{
		$this->selectArray = array('stdin' => $this->stream->handle);

		if ($timeout !== null)
		{
			$this->timeout = $timeout;
		}
		else
		{
			$this->timeout = null;
		}

		$this->selected = false;
		$keys = array();

		$null = null;

		if ($this->selected = $this->stream->streamSelect($this->selectArray, $keys, $this->timeout))
		{
			$this->keys = $keys;
			return $this->selectArray[$keys[0]];
		}

		return $this->selected;
	}

	/**
	 * icase
	 * 
	 * Set/get the input character case mapping
	 * @param string  $icase = (optional) input string case mapping (upper, lower, both)
	 * @return string  $icase = input string case mapping
	 */
	public function icase($icase=null)
	{
		if ($icase !== null)
		{
			$this->properties->icase = $icase;
		}
		
		return $this->properties->icase;
	}

	/**
	 * trim
	 * 
	 * Set/get the trim option setting (/left', 'right', 'both', 'none')
	 * @param string  $trim  = (optional) input string trim setting
	 * @return string  $trim  = input string trim setting
	 */
	public function trim($trim=null)
	{
		if ($trim !== null)
		{
			$this->properties->trim = $trim;
		}
		
		return $this->properties->trim;
	}

	/**
	 * handle
	 * 
	 * Returns the current input stream handle
	 * @return resource $handle
	 */
	public function handle()
	{
		return $this->properties->stream->handle;
	}

}

