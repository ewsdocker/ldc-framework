<?php
namespace Library\XML;
use Library\Error;

/*
 * 		XML\ConvertArray is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * XML\ConvertArray.
 *
 * Convert from/to XML and an array.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage XML.
 */
class ConvertArray
{
	/**
	 * toArray
	 *
	 * convert XML formatted data to an array
	 * @param string $xml = an xml formatted string or one of the following objects: DOM_XML, DOM_Array, DOMDocument
	 * @return array $xmlArray
	 * @throws \Library\XML\Exception
	 */
	public static function toArray($xml)
	{
		if ((! is_string($xml) && (! is_object($xml))))
		{
			throw new Exception(Error::code('StringOrObjectExpected'));
		}

		$xmlObject = $xml;
		if (is_string($xml))
		{
			$xmlObject = new \Library\DOM\XML($xml, 'load');
		}

		if (is_object($xmlObject))
		{
			if ((! $xmlObject instanceof Library\DOM\ConvertArray) &&
				(! $xmlObject instanceof \DOMDocument) &&
				(! $xmlObject instanceof \Library\DOM\XML))
			{
				throw new Exception(Error::code('ObjectExpected'));
			}

			if ($xmlObject instanceof \Library\DOM\XML)
			{
				$xmlObject = $xmlObject->domDocument();
			}

		}

		if (! $xmlObject instanceof \DOMDocument)
		{
			throw new Exception(Error::code('InvalidClassObject'));
		}

		$xmlArray = $xmlObject->toArray($xmlObject);

		return $xmlArray;

	}

	/**
	 * fromArray
	 *
	 * convert the supplied array to an XML formatted string
	 * @param array $array = multi-dimensional array
	 * @return string $xml
	 * @throws \Library\XML\Exception
	 */
	public static function fromArray($array, $attributeTag=null, $root=null)
	{
		if ((! $array) || (! is_array($array)))
		{
			throw new Exception(Error::code('ArrayVariableExpected'));
		}

		$xml = "<?xml version=\"1.0\"?>\n";
		if ($root)
		{
			$xml = sprintf("<%s>\n", $root);
		}

		$xml .= self::parseArray($array, $attributeTag);

		if ($root)
		{
			$xml .= sprintf("</%s>\n", $root);
		}

		return $xml;
	}

	/**
	 * parseArray
	 *
	 * Recursive descent function to parse heirarchical, multi-dimensional arrays into a coherent xml string
	 * @param array $array = array containing the data to be parsed
	 * @param string $attributeTag = (optional) attribute tag string
	 * @return string $xml = string containing an xml representation of the array
	 */
	private static function parseArray($array, $attributeTag=null, $arrayName=null)
	{
		if (self::arrayType($array) === 'numeric')
		{
			return self::parseNumericArray($array, $attributeTag, $arrayName);
		}

		$xml = '';
		foreach($array as $key => $value)
		{
			if ($key === $attributeTag)
			{
				continue;
			}

			if (is_array($value) && (self::arrayType($value) === 'numeric'))
			{
				$xml .= self::parseArray($value, $attributeTag, $key);
			}
			else
			{
				$xml .= sprintf("<%s", $key);

				if (is_array($value) && $attributeTag && array_key_exists($attributeTag, $value) && is_array($value[$attributeTag]))
				{
					foreach($value[$attributeTag] as $attributeName => $attributeValue)
					{
						$xml .= sprintf(' %s=', $attributeName);
						$xml .= (is_numeric($attributeValue)) ? $attributeValue : sprintf('"%s"', $attributeValue);
					}
				}

				$xml .= ">";

				if (is_array($value))
				{
					if ($parsed = self::parseArray($value, $attributeTag, $key))
					{
						$xml .= self::indentFields($parsed);
					}
				}
				else
				{
					$xml .= sprintf("%s", trim($value));
				}

				$xml .= sprintf("</%s>\n", $key);
			}
		}

		return $xml;
	}

	/**
	 * parseNumericArray
	 *
	 * Recursive descent function to parse heirarchical, multi-dimensional arrays into a coherent xml string
	 * @param array $array = array containing the data to be parsed
	 * @param string $attributeTag = (optional) attribute tag string
	 * @return string $xml = string containing an xml representation of the array
	 */
	private static function parseNumericArray($array, $attributeTag=null, $arrayName=null)
	{
		$xml = '';
		foreach($array as $key => $value)
		{
			$xml .= sprintf("<%s>", $arrayName);

			if (is_array($value))
			{
				if ($parsed = self::parseArray($value, $attributeTag, $key))
				{
					$xml .= self::indentFields($parsed);
				}
			}
			else
			{
				$xml .= sprintf("%s", trim($value));
			}

			$xml .= sprintf("</%s>\n", $arrayName);
		}

		return $xml;
	}

	/**
	 * indentFields
	 *
	 * Reformat the provided string by indenting the fields in the string
	 * @param string $string = string to reformat
	 * @return string $string
	 */
	private static function indentFields($string)
	{
		$xml = "\n";
		$fields = explode("\n", $string);
		foreach($fields as $field)
		{
			if ($field !== '')
			{
				$xml .= sprintf("    %s\n", $field);
			}
		}

		return $xml;
	}

	/**
	 * arrayType
	 *
	 * scan the passed array for numeric-only keys
	 * @param array $array = array to scan
	 * @return string $type = 'numeric', 'assoc', or false (if not an array)
	 */
	private static function arrayType($array)
	{
		if (! is_array($array))
		{
			return false;
		}

		foreach($array as $index => $value)
		{
			if (! is_numeric($index))
			{
				return 'assoc';
			}
		}

		return 'numeric';
	}

}
