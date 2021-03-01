<?php
namespace Library\INI;
use Library\Error;

/*
 * 		INI\ConvertArray is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * ConvertArray
 *
 * INI file conversion (to/from array).
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage INI.
 */
class ConvertArray
{
	/**
	 * toArray
	 *
	 * Converts a string to an array object
	 * @param string $iniString = string to convert
	 * @return array $iniArray
	 * @throws \Library\INI\Exception
	 */
	public static function toArray($iniString)
	{
		if (! is_string($iniString))
		{
			throw new Exception(Error::code('StringVariableExpected'));
		}

		if (! $iniArray = parse_ini_string($iniString, true))
		{
			throw new Exception(Error::code('ParseError'));
		}

		return $iniArray;
	}

	/**
	 * toXML
	 *
	 * convert the supplied hierarchical array to a valid XML string
	 * @param array $iniArray = INI formatted array to convert to an XML string
	 * @return string $xmlString
	 * @throws \Library\INI\Exception
	 */
	public static function toXML($iniArray)
	{
		return \Library\XML\ConvertArray::fromArray(self::parseINI($iniArray), \Library\DOM\ConvertArray::ATTRIBUTE_TAG, 'configdata');
	}

	/**
	 * parseINI
	 *
	 * parse the supplied INI array into an hierarchical multi-dimensional array and return the result
	 * @param array $iniArray = array to parse
	 * @return array $config
	 * @throws \Library\INI\Exception
	 */
	public static function parseINI($iniArray)
	{
		if (! is_array($iniArray))
		{
			if (! is_string($iniArray))
			{
				throw new Exception(Error::code('StringOrObjectExpected'));
			}

			$iniArray = self::toArray($iniArray);
		}

		$config = array();
		foreach($iniArray as $name => $section)
		{
			if (is_array($section))
			{
				$extends = '';

				if (strpos($name, ':') !== false)
				{
					if (($segments = explode(':', $name, 2)) && (count($segments) == 2))
					{
						$name = trim($segments[0]);
						$extends = trim($segments[1]);
					}
				}

				$config[$name] = self::parseSection($section);

				if ($extends)
				{
					$config[$name][\Library\DOM\ConvertArray::ATTRIBUTE_TAG] = array('extends' => $extends);
				}
			}
			else
			{
				$config[$name] = $section;
			}
		}

		return $config;
	}

	/**
	 * parseSection
	 *
	 * parse the section array and return as hierarchical array
	 * @param array $section = section array to parse
	 * @return array $config = parsed array
	 */
	private static function parseSection($section)
	{
		$config = array();
		foreach($section as $key => $value)
		{
			$config = array_merge($config, self::parseKey($key, $value, $config));
		}

		return $config;
	}

	/**
	 * parseKey
	 *
	 * parse the key to remove the constituent parts (a.b.c) and return as a multi-level array
	 * @param string $key = the key to parse
	 * @param mixed $value = value to assign to the final key level
	 * @param array $config = (optional) configuration array
	 * @return array $config
	 */
	private static function parseKey($key, $value, $config=array())
	{
		if ((! $segments = explode('.', $key, 2)) || (count($segments) == 1))
		{
			$config[$key] = $value;
		}
		else
		{
			if (! array_key_exists($segments[0], $config))
			{
				$config[$segments[0]] = array();
			}

			$config[$segments[0]] = array_merge($config[$segments[0]], self::parseKey($segments[1], $value, $config[$segments[0]]));
		}

		return $config;
	}

}
