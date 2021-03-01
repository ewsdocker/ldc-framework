<?php
namespace Library\Debug;

use Library\Utilities\FormatVar;

/*
 *		Debug\Traceback is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source.
*/
/**
 * Debug\Traceback
 *
 * Debug\Traceback is a wrapper class for the debug_backtrace command
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Debug
 */
class Traceback extends \Library\Stack\Extension
{
  	/**
  	 * stackFields
  	 *
  	 * Array of known field names
  	 * @var array $stackFields
  	 */
  	private 		$stackFields;

  	/**
  	 * element
  	 *
  	 * Array to hold the current stack element
  	 * @var array $element
  	 */
  	private 	  $element;

  	/**
  	 * main
  	 *
  	 * Array of main labels
  	 * @var array $main
  	 */
  	private 		$main;

  	/**
  	 * skipLevels
  	 *
  	 * Number of levels to skip before analyzing
  	 * @var integer $skipLevels
  	 */
  	private			$skipLevels;

  	/**
  	 * __construct
  	 *
  	 * Class constructor
  	 * @param integer $skip = (optional) number of levels to skip
  	 */
  	public function __construct($skip=0)
  	{
  		parent::__construct(debug_backtrace());

  		$this->stackFields = array('line',
  	                         	   'function',
  	                         	   'class',
  							 	   'object',
  							 	   'method',
  							 	   'type');
  		$this->element = array();
  		$this->main = array('class'    	=> 'main',
							'function' 	=> '',
							'type' 		=> '');

  		$this->skipLevels = $skip;
  		$this->adjustStack();
  		if ($this->skipLevels > 0)
  		{
  			$this->skip($this->skipLevels);
  		}
  	}

	/**
	 * __get
	 *
	 * get an element field value from element array, or null if it doesn't exist
	 * @param string $name = name of field to get from element array
	 * @return mixed $value = value of element[$name] if it exists, null if it doesn't
	 */
	public function __get($name)
	{
		if ((! $this->currentElement()) || (! array_key_exists($name, $this->element)))
		{
			return null;
		}

		return $this->element[$name];
	}

	/**
	 * __toString.
	 *
	 * Walks through the stack adding the class name, type and method name at each level to the buffer.
	 * @param integer $skip = (optional) number of elements to skip. Default = 0.
	 * @return string $buffer
	 */
	public function __toString()
	{
		FormatVar::sort(false);
		FormatVar::sortValues(false);
		FormatVar::recurse(false);

		$buffer = "";

		$index = 0;
		$this->rewind();
	    while($this->current())
	    {
	  	    if ($index >= $this->skipLevels)
	  	    {
  	            $buffer .= sprintf("\nlevel: #%d\t%s%s%s @ %s", $index, $this->className(), $this->type(), $this->methodName(), $this->lineNumber());
	        }

	        $index++;
	  	    if (! $this->next())
	  	    {
	  	        break;
	  	    }
	    }

	    $buffer .= "\n";
	    return $buffer;
	}

	/**
	 * adjustStack
	 *
	 * adjust traceback stack entries
	 */
	protected function adjustStack()
	{
		$this->push($this->main);
		$stackSize = $this->count();

		$copyLine = '';
		for($index = 0; $index < $stackSize; $index++)
		{
			$element = $this->offsetGet($index);

			foreach($this->stackFields as $key => $field)
			{
				if (! array_key_exists($field, $element))
				{
					$element[$field] = '';
				}
			}

			$stackLine = $element['line'];
			$element['line'] = $copyLine;
			$copyLine = $stackLine;

			$this->offsetSet($index, $element);
		}
	}

	/**
	 * element
	 *
	 * get the current element
	 * @return integer|boolean $element = current element, false if invalid
	 */
	public function element()
	{
		return $this->element;
	}

	/**
     * skip.
     *
     * Skip the array pointer the required number of elements and return the new stack element.
     * @param integer $skipCount number of elements to skip
     * @return array|boolean $element is the first element in the stack, or false if empty.
     */
	public function skip($skipCount=1)
	{
		$this->rewind();

	    if ($skipCount != 0)
	    {
		    $index = 0;
		    while ($index < $skipCount)
	    	{
	  	    	if (! $this->next())
	  	    	{
	  	        	return false;
	  	    	}

		  	    $index++;
		    }
	    }

	    return $this->element();
	}

	/**
     * prev.
     *
     * Decrement the array pointer and return the element pointed to.
     * @return array|boolean $element is the previous element in the stack, or false if empty.
     */
	public function prev()
	{
		return $this->element = parent::prev();
	}

	/**
     * currentElement.
     *
     * Check that the current element is valid
     * @return boolean true = it is valid, false = it is not valid
     */
	public function currentElement()
	{
	    if (! $this->element)
	    {
	        if (! $this->current())
	        {
	            return false;
	        }
	    }

	    return true;
	}

    /**
     * classObject.
     *
     * Retrieve the class object from the current stack element.
     * @return object|boolean the class object of the current stack level, false if not valid
     */
	public function classObject()
	{
	    if (! $this->currentElement())
	    {
            return false;
	    }

        return $this->element['object'];
	}

    /**
     * callArgs.
     *
     * Retrieve the class call arguements from the current stack element.
     * @return array|boolean the class arguements of the current stack level, false if not valid
     */
	public function callArgs()
	{
	    if (! $this->currentElement())
	    {
            return false;
	    }

        return $this->element['args'];
	}

	/**
     * classFile.
     *
     * Retrieve the class file name from the current stack element.
     * @return string|boolean the class file name of the current stack level, false if not valid
     */
	public function classFile()
	{
	    if (! $this->currentElement())
	    {
            return false;
	    }

        return $this->element['file'];
	}

	/**
     * lineNumber.
     *
     * Retrieve the line number from the current stack element.
     * @return integer|boolean the line number of the current stack level, false if not valid
     */
	public function lineNumber()
	{
	    if (! $this->currentElement())
	    {
            return false;
	    }

	    return $this->element['line'];
	}

	/**
     * methodName.
     *
     * Retrieve the method name from the current stack element.
     * @return string|boolean the method name of the current stack level, false if not valid
     */
	public function methodName()
	{
	    if (! $this->currentElement())
	    {
            return false;
	    }

        return $this->element['function'];
	}

	/**
     * className.
     *
     * Retrieve the class name from the current stack element.
     * @return string|boolean the class name of the current stack level, false if not valid
     */
	public function className()
	{
	    if ((! $this->currentElement()) || (! array_key_exists('class', $this->element)))
	    {
            return false;
	    }

        return $this->element['class'];
	}

    /**
     * type.
     *
     * Returns the type field from the current element.
     * @return string|boolean type of the current element or false if not valid
     */
	public function type()
	{
	    if (! $this->currentElement())
	    {
            return false;
	    }

        return $this->element['type'];
	}

	/**
	 * searchStack
	 *
	 * search the debug stack for a given class name and optional function name
	 * @param string $className = name of the class to search for
	 * @param string $methodName = name of the method in className to search for
	 * @return integer|boolean $level = stack level of class::method(), false if not found
	 */
	public function searchStack($className, $methodName=null)
	{
		if ((! $className) || ($this->count() == 0))
		{
			return false;
		}

		$this->rewind();

		while($this->element() !== false)
		{
			if (strpos($this->className(), $className) !== false)
			{
				if ((! $methodName) || ($this->methodName() === $methodName))
				{
					break;
				}
			}

			$this->next();
		}

		return $this->key();
	}

	// ***************************************************************************************
	//
	//		Iterator interface
	//
	// ***************************************************************************************

	/**
     * current.
     *
     * Return the current element in the stack.
     * @return array|boolean $element is the current element in the stack, or false if empty.
     */
	public function current()
	{
	    return $this->element = parent::current();
	}

	/**
     * next.
     *
     * Increment the array pointer and return the element pointed to.
     * @return array|boolean $element is the next element in the stack, or false if empty.
     */
	public function next()
	{
		return $this->element = parent::next();
	}

	/**
     * rewind.
     *
     * Reset the array pointer and return the first element
     * @return array|boolean $element is the first element in the stack, or false if empty.
     */
	public function rewind()
	{
		return $this->element = parent::rewind();
	}

}
