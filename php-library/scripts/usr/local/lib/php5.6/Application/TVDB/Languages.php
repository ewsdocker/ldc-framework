<?php
namespace Application\TVDB;

use Application\TVDB\Records\Language;
use Library\Utilities\FormatVar;
use Library\XML\LoadXML;

/*
 *		Application\TVDB\Languages is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\Languages
 *
 * TVDB Languages API interface
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage TVDB
 */
class Languages extends LoadXML
{
	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $languagesUrl = TVDB Languages API URL
	 * @throws Application\TVDB\Exception
	 */
	public function __construct($languagesUrl)
	{
		parent::__construct($languagesUrl, array($this, 'parseToArray'));
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
	 * languages
	 *
	 * Returns an array of language abbreviations and corresponding names
	 * @return array $languages
	 */
	public function languages()
	{
		$languages = array();

		foreach($this->records as $language)
		{
			$languages[$language->abbreviation] = $language->name;
		}

		return $languages;
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

		return new Language($child);
	}
}
