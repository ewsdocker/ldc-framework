<?php
namespace Library\DOM;

use Library\XML\ConvertArray as XMLConvert;
use Library\Error;

/*
 * 		DOM\ConvertArray is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * DOM\ConvertArray
 *
 * DOM array conversion class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage DOM.
 */
class ConvertArray extends \DOMDocument
{
	/**
	 * ATTRIBUTE_TAG
	 *
	 * constant value for the attribute tag element
	 */
	const				ATTRIBUTE_TAG = '@attributeTag';

	/**
	 * attributeTag
	 *
	 * The attribute tag to use as a key to store attributes array inside the xmlArray
	 * @var string $attributeTag
	 */
	private				$attributeTag;

	/**
	 * xmlString
	 *
	 * A copy of the xml string used in the processes
	 * @var string $xmlString
	 */
	private				$xmlString;

	/**
	 * xmlArray
	 *
	 * A copy of the xml array used in the processes
	 * @var array $xmlArray
	 */
	private				$xmlArray;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $attributeTag = (optional) attribute tag to use to parse the DOMDocument to an array
	 */
	public function __construct($attributeTag='@attributeTag')
	{
		parent::__construct();
		$this->attributeTag = $attributeTag;
		$this->resetXMLArray();
	}

	/**
	 * toArray
	 *
	 * Return an array representation of the DOMDocument or DOMNode
	 * @param DOMNode $node = (optinal) DOMNode to convert to array, null to use this DOMDocument
	 * @return array $domArray
	 * @throws Library\DOM\Exception
	 */
	public function toArray(\DOMNode $node=null)
	{
		$this->resetXMLArray();
		$this->xmlArray = $this->parseToArray($node);
		return $this->xmlArray;
	}

	/**
	 * parseToArray
	 *
	 * parse the DOMNode (or current DOMDocument) to an array
	 * @param mixed DOMNode = either a DOMNode with children to parse, or null to use the current DOMDocument
	 * @return array $xmlArray
	 * @throws Library\DOM\Exception
	 */
	private function parseToArray(\DOMNode $node)
	{
		if (is_null($node) && (! $this->hasChildNodes()))
		{
			throw new Exception(Error::code('DomNoChildren'));
		}

		$node = (is_null($node)) ? $this->documentElement : $node;

		if (! $node->hasChildNodes())
		{
			$result = $node->nodeValue;
		}
		else
		{
			$result = array();
			foreach ($node->childNodes as $childNode)
			{
				$childNodeList = $node->getElementsByTagName($childNode->nodeName);

				$childCount = 0;

				foreach ($childNodeList as $grandChildNode)
				{
					if ($grandChildNode->parentNode->isSameNode($childNode->parentNode))
					{
						$childCount++;
					}
				}

				try
				{
					$value = $this->parseToArray($childNode);
				}
				catch(Exception $exception)
				{
					return array();
				}

				$key   = ($childNode->nodeName{0} == '#') ? 0 : $childNode->nodeName;
				$value = is_array($value) ? $value[$childNode->nodeName] : $value;

				if ($childCount > 1)
				{
					$result[$key][] = $value;
				}
				else
				{
					$result[$key] = $value;
				}
			}

			if ((count($result) == 1) && isset($result[0]) && (! is_array($result[0])))
			{
				$result = $result[0];
			}
		}

		$attributes = array();

		if ($node->hasAttributes())
		{
			foreach ($node->attributes as $name => $attributeNode)
			{
				$attributes[$attributeNode->nodeName] = $attributeNode->nodeValue;
			}
		}

		if ($node instanceof \DOMElement && $node->getAttribute('xmlns'))
		{
			$attributes["xmlns"] = $node->getAttribute('xmlns');
		}

		if (count($attributes))
		{
			if (! is_array($result))
			{
				$result = (trim($result)) ? array($result) : array();
			}

			$result[$this->attributeTag] = $attributes;
		}

		return array($node->nodeName => $result);
	}

	/**
	 * fromArray
	 *
	 * Converts the array to an xml string and then loads the xmlString to the current DOMDocument
	 * @param array $array = xml array to convert and load
	 * @throws Library\DOM\Exception, Library\XML\Exception
	 */
	public function fromArray($array)
	{
		$this->resetXMLArray();
		$this->xmlArray = $array;

		$this->xmlString = XMLConvert::fromArray($array, $this->attributeTag);

		if (! $this->loadXML($this->xmlString))
		{
			throw new Exception(Error::code('DomLoadFailed'));
		}
	}

	/**
	 * xmlString
	 *
	 * get the xml string returned from conversion of the xml array to a string
	 * @return string $xmlString
	 */
	public function xmlString()
	{
		return $this->xmlString;
	}

	/**
	 * xmlArray
	 *
	 * get the xml array
	 * @return array $xmlArray
	 */
	public function xmlArray()
	{
		return $this->xmlArray;
	}

	/**
	 * resetXMLArray
	 *
	 * reset (unset) the attributes array
	 */
	private function resetXMLArray()
	{
		$this->xmlArray = array();
		$this->xmlString = '';
	}

	/**
	 * attributeTag
	 *
	 * get/set the attribute tag used in the xmlArray object
	 * @param string $attributeTag = (optional) attribute tag key, null to query
	 * @return string $attributeTag
	 */
	public function attributeTag($attributeTag=null)
	{
		if ($attributeTag !== null)
		{
			$this->attributeTag = $attributeTag;
		}

		return $this->attributeTag;
	}

}
