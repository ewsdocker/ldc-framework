<?php
namespace Library\Log;
use Library\Error;

/*
 * 	 	  Log\Format is copyright © 2012, 2013. EarthWalk Software.
 * 		  Licensed under the Academic Free License version 3.0.
 *        Refer to the file named License provided with the source, 
 *         or from http://opensource.org/licenses/academic.php.
 */
/**
 * Library\Log\Format.
 *
 * Format the log message for output using previously set flags and options.  
 * Class must be extended by an adapter class to output the formatted message.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Log.
 */
class Format extends Base
{
	/**
	 * logBuffer
	 *
	 * log output buffer contents
	 * @var string $logBuffer
	 */
	protected	$logBuffer;

	/**
	 * xmlIndent
	 *
	 * Conatins the number of 'indents' to apply to XML items
	 * @var integer $xmlIndent
	 */
	protected $xmlIndent;

	/**
	 * __construct
	 *
	 * Create a new instance of the Log\Format class
	 * @param object $properties = Properties object instance containing required properties and their values
	 */
	public function __construct($properties)
	{
		parent::__construct($properties);

		$this->xmlIndent	= 0;
		$this->logBuffer	= '';
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 * @return null
	 */
	public function __destruct()
	{
	}

	/**
	 * log
	 *
	 * Log a message to the current log device
	 * @param string $message = message to write to the log file
	 * @param mixed $logProperties = (optional):
	 * 		'Log_Level'      => <(optional) error level (default = Library\Log::LOG_BSD_ERR)>,
	 * 		'Log_Format'	 => <(optional) format type: 'none', 'timestamp', 'log', 'xml' (default = 'none')
	 *      'Log_LineNumber' => <linenumber>,
	 *      'Log_Method'     => <(optional) name of the calling method>,
	 *      'Log_Class'      => <(optional) name of the calling class>,
	 *      'Log_Program'    => <(optional) name of the program to log>
	 *      'Log_SkipLevels' => <(optional) number of levels to skip in traceback stack
	 *      'Log_Timestamp'  => <(optional) timestamp of the event>
	 * @return string $logBuffer = string contains formatted message.
	 * @throws Library\Log\Exception
	 */
	public function log($message, $logProperties=Library\Log::LOG_BSD_ERR)
	{
		$this->logProperties($logProperties);

		$this->logBuffer = '';

		if (! $timestamp = $this->logProperties->Log_Timestamp)
		{
			$timestamp = $this->getTimestamp();
		}

		if (! $this->logProperties->exists('Log_LineSeparator'))
		{
			$this->logProperties->Log_LineSeparator = $this->properties->exists('Log_LineSeparator') ? $this->properties->Log_LineSeparator : '|';
		}

		if (! $logProgram = $this->logProperties->Log_Program)
		{
			$logProgram = $this->properties->Log_Program ? $this->properties->Log_Program : '<unspecified>';
		}

		if (! $format = $this->logProperties->Log_Format)
		{
			$format = $this->properties->exists('Log_Format') ? $this->properties->Log_Format : 'none';
		}

		switch($format)
		{
			default:
			case 'none':
				$this->bufferLine($message);
				break;

			case 'timestamp':
				$this->bufferLine(sprintf("%s: %s", $timestamp, $message));
				break;

			case 'log':
				$this->logFormat($message, $logProgram, $this->skipToLevel());
				break;

			case 'xml':
				$this->xmlFormat($message, $logProgram, $this->skipToLevel());
				break;
		}
		
		return $this->logBuffer;
	}

	/**
	 * skipToLevel
	 *
	 * Skip the number levels indicated, or until proper calling method is reached
	 * @return array $level = the final level, or null if not found.
	 */
	protected function skipToLevel()
	{
		$traceback = new \Library\Debug\Traceback();

		if (! $level = $traceback->searchStack($this->logProperties->Log_Class, 
					 						   $this->logProperties->Log_Method))
		{
			if (! $level = $traceback->searchStack('Library\Log', 'message'))
			{
				$this->logProperties->Log_Format = 'none';
			}
		}

		if ($level)
		{
			if (($skips = $this->logProperties->Log_SkipLevels) === null)
			{
				if (($skips = $this->properties->Log_SkipLevels) === null)
				{
					$skips = 0;
				}
			}

			while($skips > 0)
			{
				$traceback->next();
				
				$object = $traceback->classObject();
				if (is_object($object))
				{
					if (get_class($object) == $traceback->className())
					{
						break;
					}
				}
				else
				{
					if ($object == $traceback->className())
					{
						break;
					}
				}

				$skips--;
			}

			$level = $traceback->current();
		}
		
		return $level;
	}

	/**
	 * logFormat
	 *
	 * Format the log message to the output buffer as a 'log' entry
	 * @param string $message = the message to output to the log
	 * @param string $program = the name of the program doing the logging
	 * @param array $level = debug traceback level information
	 */
	protected function logFormat($message, $program, $level)
	{
		if ($level !== null)
		{
			$this->addField($this->logProperties->Log_Timestamp);
			$this->addField($program);
			$this->addField($this->logProperties->Log_Level);
			$this->addField($level['class']);
			$this->addField($level['function']);
			$this->addField($level['line']);
			$this->bufferLine($message);
		}
		else
		{
			$this->bufferLine($message);
		}
	}

	/**
	 * addField
	 *
	 * Add the field to the logBuffer followed by the current Line Separator character(s)
	 * @param string $field = field contents to add
	 */
	protected function addField($field)
	{
		if (! is_string($field))
		{
			$field = (string)$field;
		}

		$this->bufferMessage($field . $this->logProperties->Log_LineSeparator);
	}

	/**
	 * xmlFormat
	 *
	 * Format the log message to the output buffer as an xml entry
	 * @param string $message = the message to output to the log
	 * @param string $program = the name of the program doing the logging
	 * @param array $level = debug traceback level information
	 */
	private function xmlFormat($message, $program, $level)
	{
		if ($level !== null)
		{
			$this->bufferLine('<logentry>');
			$this->xmlIndent++;

			$this->bufferXMLItem('timestamp', $this->logProperties->Log_Timestamp);
			$this->bufferXMLItem('program', $program);
			$this->bufferXMLItem('level', $this->logProperties->Log_Level);
			$this->bufferXMLItem('class', $level['class']);
			$this->bufferXMLItem('method', $level['function']);
			$this->bufferXMLItem('line', $level['line']);
			$this->bufferXMLItem('message', $message);

			$this->xmlIndent--;
			$this->bufferLine('</logentry>');
		}
		else
		{
			$this->bufferLine($message);
		}
	}

	/**
	 * bufferXMLItem
	 *
	 * Add the item to the buffer, indented by $indent spaces
	 * @param string $itemName = name of the item to add
	 * @param mixed $itemValue = value of the named item
	 */
	protected function bufferXMLItem($itemName, $itemValue)
	{
		$this->bufferMessage($this->xmlIndent());
		$this->bufferMessage('<' . $itemName . '>');

		if (is_string($itemValue) && (strlen($itemValue) > 0))
		{
			$itemValue = str_replace(PHP_EOL, $this->logProperties->Line_Separator, $itemValue);
			$itemValue = rtrim($itemValue);
		}

		$this->bufferMessage($itemValue);
		$this->bufferLine('</' . $itemName . '>');
	}

	/**
	 * xmlIndent
	 *
	 * returns the number of spaces (x 2) in $xmlIndent
	 * @return string
	 */
	protected function xmlIndent()
	{
		return str_repeat('  ', $this->xmlIndent);
	}

	/**
	 * bufferLine
	 *
	 * Add a (possibly empty) message to the log buffer followed by PHP_EOL sequence
	 * @param string $message = (optional) message to add
	 */
	protected function bufferLine($message='')
	{
		$this->bufferMessage($message . PHP_EOL);
	}

	/**
	 * bufferMessage
	 *
	 * Add a message to the log buffer
	 * @param string $message = message to add
	 */
	protected function bufferMessage($message)
	{
		$this->logBuffer .= $message;
	}

	/**
	 * getTimestamp
	 *
	 * get the current timestamp
	 * @return string $timestamp
	 */
	public function getTimestamp()
	{
		return date('Y-m-d H:i:s') . substr((string)microtime(), 1, 6);
	}

}
