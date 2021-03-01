<?php
namespace Library\Utilities;

/*
 *		Utilities\FormatVar is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  Refer to the file named License.txt provided with the source, 
 *		 or from http://opensource.org/licenses/academic.php
*/
/**
 * Utilities\FormatVar
 *
 * Format a variable for output
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Utilities
 */
class FormatVar
{
	/**
	 * sort
	 * 
	 * Sort if true
	 * @var boolean $sort
	 */
	protected static $sort = false;

	/**
	 * sortValues
	 * 
	 * Sort by value, not key, if $sort is true
	 * @var boolean $sortValues
	 */
	protected static $sortValues = false;

	/**
	 * formatted
	 * 
	 * Output is somewhat formatted if true, otherwise false
	 * @var boolean $formatted
	 */
	protected static $formatted = true;

	/**
	 * recurse
	 * 
	 * True to allow recursion in arrays and objects
	 * @var boolean $recurse
	 */
	protected static $recurse = false;

	/**
	 * recursionLevel.
	 *
	 * Current recursion level
	 * @var integer $recursionLevel = Class recursion level
	 */
	protected static 	$recursionLevel = 0;

	/**
	 * maxLevel.
	 *
	 * Maximum recursion level
	 * @var integer $maxLevel = maximum recursion level
	 */
    protected static  $maxLevel       = 10;

	/**
	 * buffer
	 *
	 * A static buffer to hold the formatted array
	 * @var string $buffer
	 */
	protected static	$buffer = '';

	/**
	 * indentCharacter
	 *
	 * The character sequence used to indent output based on recursionLevel
	 * @var string $indentCharacter
	 */
	protected static	$indentCharacter = "..";

	/**
	 * indentLevel.
	 *
	 * Current indent level
	 * @var integer $indentLevel = indent level
	 */
	protected static 	$indentLevel = 0;

	/**
	 * setup
	 * 
	 * True if already setup, otherwise false
	 * @var boolean $setup
	 */
	public static $setup = false;

	/**
     * format.
     *
     * Create a formatted buffer from the value.
     * @param array $value = value to format
     * @param string $name = (optional) name of the value
     * @return string $buffer
     */
 	public static function format($value, $name=null)
    {
  		self::$buffer = '';
		self::showData($value, $name);
		return self::$buffer;
    }

	/**
	 * showData
	 *
	 * Format the provided variable for output and return as a string
	 * @param mixed $value = value of the variable
	 * @param string $name = name (to print) of the variable
	 * @retrun string $buffer = formatted buffer
	 */
	public static function showData($value, $name)
	{
		self::setup();
		self::$buffer = '';

 		$type = gettype($value);

		switch($type)
		{
			case 'string':
				self::indentMessage($name, $type, (string)$value);
				break;

			case 'boolean':
				self::indentMessage($name, $type, sprintf('%b', $value));
				break;

			case 'integer':
				self::indentMessage($name, $type, sprintf('%d', $value));
				break;

			case 'double':
			case 'float':
				self::indentMessage($name, $type, sprintf('%f', $value));
				break;

			case 'object':
				$level = self::$indentLevel;
				
				self::formatValue(self::toArray($value, true), $type, $name, get_class($value));
				self::$indentLevel = $level;

				break;
				
			case 'array':
				$level = self::$indentLevel;
				
				self::formatValue($value, $type, $name, null);
				self::$indentLevel = $level;

				break;

			case 'resource':
				self::indentMessage($name, $type, get_resource_type($value));
				break;

			case 'NULL':
				self::indentMessage($name, $type, 'null');
				break;

			case 'unknown type':
			default:
				self::indentMessage($name, $type, sprintf('%s', $value));
				break;
		}
	
		return self::$buffer;
	}

    /**
     * formatValue.
     *
     * Format and output an array to the buffer
     * @param array   $array        = array to print
     * @param string  $type			= original variable type
     * @param string  $name  		= name of the array
     * @param string  $class		= name of the class (for type = object)
     */
    private static function formatValue($array, $type, $name, $class)
    {
		if (($name !== null) && (self::$recursionLevel == 0))
		{
			self::indentMessage();
			self::bufferMessage(sprintf("%s (%s) = ", $name, $type));

			if ($type == 'object')
			{
				self::bufferMessage(sprintf("%s", $class));
			}
			
			self::bufferLine();
		}

		self::$indentLevel++;

		if (count($array) == 0)
		{
			self::indentMessage();
			if (self::$formatted)
			{
				self::bufferLine("NO ELEMENTS");
			}
			else 
			{
				self::bufferMessage("NO ELEMENTS");
			}

		  	return;
		}

		if (self::$sort)
		{
			if (self::$sortValues)
			{
				$array = self::sortableArray($array);
				@asort($array, SORT_REGULAR);
			}
			else
			{
	  			@ksort($array, SORT_REGULAR);
			}
		}

		reset($array);

		foreach($array as $key => $value)
		{
			$type = gettype($value);

			if ($type == 'object')
			{
				$label = get_class($value);
			}
    		elseif ($type == 'array')
			{
				$label = ' ';
			}
			else
			{
				$label = $value;
			}

			self::indentMessage($key, $type, $label);

			if ((is_object($value) || (is_array($value) && (count($value) > 0))) && self::$recurse && (self::$recursionLevel < self::$maxLevel))
			{
				self::$recursionLevel++;
				self::$indentLevel++;

				if (is_object($value))
				{
					self::formatValue(self::toArray($value), 'object', $key, get_class($value));
				}
				else
				{
					self::formatValue($value, gettype($value), $key, 'array', null);
				}

				if (self::$recursionLevel > 0)
				{
					self::$recursionLevel--;
				}

				if (self::$indentLevel > 0)
				{
					self::$indentLevel--;
				}
			}
		}

		if (self::$recursionLevel > 0)
		{
			self::$recursionLevel--;
		}

    	if (self::$indentLevel > 0)
		{
			self::$indentLevel--;
		}
    }

    /**
     * sortableArray
     *
     * The purpose of this method is to keep from getting bad results
     * when sorting an array containing class objects
     * @param array $array = array to be converted to 'sortable'
     * @return array $array
     */
    private static function sortableArray($array)
    {
  		foreach($array as $key => $value)
  		{
  			if (is_array($value))
  			{
  				$array[$key] = self::sortableArray($value);
  			}
  			elseif (is_object($value))
 			{
 				$array[$key] = get_class($value);
  			}
  		}
  	
  		return $array;
    }

	/**
	 * indentAndBuffer
	 * 
	 * Indent the proper number of spaces (recursion level) and buffer the name/value/type
	 * @param unknown $name = name of the variable
	 * @param unknown $type = type of the variable
	 * @param unknown $value = value of the variable
	 *
	public static function indentAndBuffer($name, $type, $value)
	{
		self::indentMessage();
		if ($name === null)
		{
			$name = 'VALUE';
		}

		self::bufferLine(sprintf("%s (%s) = %s", $name, $type, $value));
	}

    /**
     * indentMessage
     * 
     * A static function to output $recursionLevel $indentCharacters, optionally followed by name/value/type, 
     * 		if $value is not null
	 * @param unknown $name  = (optional) name of the variable
	 * @param unknown $type  = (optional) type of the variable
	 * @param unknown $value = (optional) value of the variable
     */
    public static function indentMessage($name=null, $type=null, $value=null)
    {
		$tab = 0;
		do
		{
			self::bufferMessage(self::indentCharacter());
		}
		while(++$tab <= self::$indentLevel);

		if ($value !== null)
		{
			self::bufferLine(sprintf("%s (%s) = %s", $name, $type, $value));
		}
    }

    /**
     * bufferLine
     *
     * Add a message followed by an end-of-line (eol) sequence to the buffer
     * @param string $message = (optional) message + eol to add to the buffer
     */
    protected static function bufferLine($message='')
    {
  		self::bufferMessage($message);
  		self::bufferMessage(PHP_EOL);
    }

    /**
     * bufferMessage
     *
     * Add a message to the format buffer
     * @param string $message = (optional) message to add to the buffer
     */
    protected static function bufferMessage($message)
    {
  		self::$buffer .= $message;
    }

    /**
     * buffer
     *
     * get a copy of the result buffer
     * @return string $buffer
     */
    public static function buffer()
    {
  		return self::$buffer;
    }

    /**
     * indentCharacter
     *
     * get/set the indent character sequence
     * @param string $char = (optional) character indent sequence, null to query
     * @return string $char
     */
    public static function indentCharacter($char=null)
    {
  		if ($char !== null)
  		{
  			self::$indentCharacter = $char;
  		}

  		return self::$indentCharacter;
    }

    /**
     * maxLevel
     *
     * get/set the maximum recursion level
     * @param integer $maxLevel = (optional) maximum recursion levels, null to query
     * @return integer $maxLevel
     */
    public static function maxLevel($maxLevel=null)
    {
  		if ($maxLevel !== null)
  		{
  			self::$maxLevel = $maxLevel;
  		}

  		return self::$maxLevel;
    }

	/**
	 * toArray
	 *
	 * Static class to convert a stdClass object to an array
	 * @param object $object = an stdClass (or stdClass subclass) object to convert to an array
	 * @param boolean $recurse = (optional) boolean indicating ok to recurse if true, else not ok
	 * @return array $array
	 */
	public static function toArray($object, $recurse=true)
	{
		if (! is_object($object))
		{
			throw new Exception('ObjectExpected');
		}
		
		$array = get_object_vars($object);
		
		if ($recurse)
		{
			foreach($array as $name => $value)
			{
				if (is_object($value))
				{
					$array[$name] = self::toArray($value);
				}
			}
		}
		
		return $array;
	}

    /**
	 * setup
	 * 
	 * Setup some global values
	 */
	protected static function setup()
	{
		if (! self::$setup)
		{
			if (self::indentCharacter() === null)
			{
				self::indentCharacter('..');
			}
			
			self::$recursionLevel = 0;
			self::$setup = true;
		}

		self::$indentLevel = 0;
	}
	
	/**
	 * sort
	 * 
	 * Get/set the sort class property
	 * @param boolean $sort = (optional) sort flag setting, null to query
	 * @return boolean $sort = sort flag setting
	 */
	public static function sort($sort=null)
	{
		if ($sort !== null)
		{
			self::$sort = $sort;
		}
		
		return self::$sort;
	}

	/**
	 * sortValues
	 * 
	 * Get/set the sortValues class property
	 * @param boolean $sortValues = (optional) sortValues flag setting, null to query
	 * @return boolean $sortValues = sortValues flag setting
	 */
	public static function sortValues($sortValues=null)
	{
		if ($sortValues !== null)
		{
			self::$sortValues = $sortValues;
		}
		
		return self::$sortValues;
	}

	/**
	 * formatted
	 * 
	 * Get/set the formatted class property
	 * @param boolean $formatted = (optional) formatted flag setting, null to query
	 * @return boolean $formatted = formatted flag setting
	 */
	public static function formatted($formatted=null)
	{
		if ($formatted !== null)
		{
			self::$formatted = $formatted;
		}
		
		return self::$formatted;
	}

	/**
	 * recurse
	 * 
	 * Get/set the recurse class property
	 * @param boolean $recurse = (optional) recurse flag setting, null to query
	 * @return boolean $recurse = recurse flag setting
	 */
	public static function recurse($recurse=null)
	{
		if ($recurse !== null)
		{
			self::$recurse = $recurse;
		}
		
		return self::$recurse;
	}

}
