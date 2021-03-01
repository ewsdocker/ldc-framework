<?php
namespace Application\TVDB;

use Application\TVDB\Records\Language;
use Library\Utilities\FormatVar;
use Library\XML\LoadXML;

/*
 *		Application\TVDB\UserLanguage is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\UserLanguage
 *
 * TVDB UserLanguage API interface to get the current account default language
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage TVDB
 */
class UserLanguage extends LoadXML
{
	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $languagesUrl = TVDB Mirrors API URL
	 * @throws Application\TVDB\Exception
	 */
	public function __construct($tvdbAccount, $api='http://thetvdb.com/api/')
	{
		$languageUrl = sprintf("%sUser_PreferredLanguage.php?accountid=%s", $api, $tvdbAccount);
		parent::__construct($languageUrl, array($this, 'parseToArray'));
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
	 * __get
	 *
	 * Returns a field from the $records array if it is not a declared class property
	 * @param string $name
	 * @return mixed|null $result = value stored in $name, null if $name does not exist as a field in $records
	 */
	public function __get($name)
	{
		return $this->records[0]->{$name};
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
