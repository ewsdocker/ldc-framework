<?php
namespace Application\TVDB;

use Application\TVDB\Records\Episode;
use Library\Utilities\FormatVar;
use Library\XML\LoadXML;

/*
 *		Application\TVDB\Episodes is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\Episodes
 *
 * TVDB Episodes API interface
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage TVDB
 */
class Episodes extends LoadXML
{
	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $url = TVDB Episodes request
	 * @throws Application\TVDB\Exception
	 */
	public function __construct($url)
	{
		parent::__construct($url, array($this, 'parseToArray'));
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
	 * __toString
	 *
	 * Returns a printable list of properties and values
	 * @return string $buffer = list
	 */
	public function __toString()
	{
		FormatVar::sort(false);
		FormatVar::sortValues(false);
		FormatVar::recurse(false);
		FormatVar::formatted(true);

		return parent::__toString();
	}

	/**
	 * parseToArray
	 *
	 * Callback function to parse the currently selected xml element
	 * @param array $args = callback function arguement in an array
	 * @return object $value = value to store in the records array
	 */
	public function parseToArray($args)
	{
		$child = array();

		foreach($args[0] as $key => $value)
		{
			if ($value->count() == 0)
			{
				$child[$key] = (string)$value;
			}
		}

		return new Episode($child);
	}

}
