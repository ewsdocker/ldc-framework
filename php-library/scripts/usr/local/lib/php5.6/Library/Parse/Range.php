<?php
namespace Library\Parse;
use Library\Error;

/*
 *		Parse\Range is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *		Refer to the file named License.txt provided with the source, 
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * Parse\Range
 *
 * Parse a numeric range specification
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Parse
 * @subpackage Range
 */
class Range
{
	/**
	 * limits
	 * 
	 * limits is a STATIC function to parse the supplied range to extract the next (first) and last numeric values (i.e. - the limits)
	 * 
	 * if range is 'all', next and last are reduced by 1.
	 * otherwise if range is a single value, last is set to range and next is set to range - 1
	 * otherwise if range is value, a dash, and a value, next is set to first value - 1, last is set to second value -1
	 * otherwise if range is value and a dash, next is set to range - 1 and last is reduced by 1;
	 * otherwise if range is dash and a value, next is reduced by 1 and last is set to range - 1.
	 * otherwise exception.
	 * 
	 * @param string $range = a string containing the record list
	 * @param integer $first = pass-by-address and modified to the first record to process (preset value to minimum before calling)
	 * @param integer $last = pass-by-address and modified to the last record to process (preset value to maximum before calling)
	 * @return integer $first = first record (same as $first parameter value)
	 * @throws Parse\Exception
	 */
	public static function limits($range, &$first, &$last)
	{
		if (strtolower($range) == 'all')
		{
			return $first;
		}

		if (($dash = strpos($range, '-', 0)) === false)
		{
			if (! is_numeric($range))
			{
				throw new Exception(Error::code('NumericVariableExpected'));
			}
			
			$first = $range;
			if ($first > 0)
			{
				$first = $range - 1;
			}

			$last = $range;
			return $first;
		}

		if ($dash == 0)
		{
			if (++$dash >= strlen($range))
			{
				throw new Exception(Error::code('NumericVariableExpected'));
			}
			
			$last = substr($range, $dash);
			if (! is_numeric($last))
			{
				throw new Exception(Error::code('NumericVariableExpected'));
			}
			
			return $first;
		}

		$first = substr($range, 0, $dash);
		if (! is_numeric($first))
		{
			throw new Exception(Error::code('NumericVariableExpected'));
		}

		if ($first > 0)
		{
			$first--;
		}

		$dash++;
		if ($dash >= strlen($range))
		{
			return $first;
		}
		
		$last = substr($range, $dash);
		if (! is_numeric($last))
		{
			throw new Exception(Error::code('NumericVariableExpected'));
		}

		return $first;
	}

}
