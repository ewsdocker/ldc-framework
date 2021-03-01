<?php
namespace Library\DOM;
use Library\Error;

/*
 * 		DOM\Query is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * DOM Query.
 *
 * A generic XML DOM Loader implementation.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage DOM.
 */
class Query extends XML
{
	/**
	 * nodeList
	 *
	 * An array containing the results of the XPath Query
	 * @var array $nodeList
	 */
	protected 	$nodeList;

	/**
	 * query
	 *
	 * Stores the last query issued.
	 * @var string $query
	 */
	protected	$query;

	/**
	 * xmlType
	 *
	 * The xml type of the dom document
	 * @var integer $xmlType
	 */
	protected	$xmlType;

	/**
	 * __construct
	 *
	 * class constructor
	 * @param string $xmlString = (optional) xml formatted string to load to DOMDocument or DOMDocument to save
	 * @param string  $type = (optional) type of operation: 'load' or 'save'
	 */
    public function __construct($xmlString=null, $type='load', $queryString=null)
	{
		parent::__construct($xmlString, $type);
		$this->nodeList = null;
		$this->xmlType = null;

		if ($queryString)
		{
			$this->query($queryString);
		}
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 * @return null
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

    /**
     * determineXmlType
     *
     * determine the xml load type
     * @param string $query = (optional) XPath query string, defaults to '.'
     * @return string $xmlType = type of xml file loaded ('rss', 'xml', 'capIndex', 'alert', etc.)
     * @throws Library\DOM\Exception
     */
	public function determineXmlType($queryString='.')
	{
		$node = $this->query($queryString);
		if (($node->nodeType == XML::XML_ELEMENT_NODE) && $node->hasChildNodes())
		{
			while ($node)
			{
				if ($node->nodeType == XML::XML_ELEMENT_NODE)
				{
					$nodeName = $node->localName;
					switch($node->localName)
					{
						default:
							$this->xmlType = XML::LOADDOM_TYPE_XML;                 //  'xml';
							break;

						case 'channel':
						case 'rss':
							$this->xmlType = XML::LOADDOM_TYPE_RSS;                 // 'rss';
							break;

						case 'capIndex':
							$this->xmlType = XML::LOADDOM_TYPE_CAPINDEX;            // 'capIndex';
							break;

						case 'alert':
							$this->xmlType = XML::LOADDOM_TYPE_CAP;                 // 'alert';
							break;
					}

					return $this->xmlType;
				}

				$node = $node->nextSibling;
			}
		}

		$this->xmlType = XML::LOADDOM_TYPE_XML;
		return $this->xmlType;
	}

    /**
     * query
     *
     * execute the requested XPATH query
     * @param string $queryString = (optional) XPath query string, defaults to '.'
     * @return DOMElement $node = first element in the xpath query result
     * @throws Library\DOM\Exception
     */
	public function query($queryString=null)
	{
		$xpath = new \DOMXPath($this->domDocument);
		$this->nodeList = $xpath->query($this->queryString($queryString));
		return $this->resultNode();
	}

	/**
	 * queryString
	 *
	 * get/set the query string
	 * @param string $queryString = (optional) query string to set, null to query
	 * @return string $queryString
	 */
	public function queryString($queryString=null)
	{
		if ($queryString !== null)
		{
			$this->queryString = $queryString;
		}

		return $this->queryString;
	}

	/**
     * resultNode
     *
     * get the first result node from the xpath query
     * @return DOMElement $resultNode
     */
	public function resultNode()
	{
		if ((! $this->nodeList) || ($this->nodeList->length == 0))
		{
			throw new Exception(Error::code('DomXPathQueryError'));
		}

		return $this->nodeList->item(0);
	}

	/**
     * nodeList
     *
     * get the node list array
     * @return array $nodeList
     */
	public function nodeList()
	{
		return $this->nodeList;
	}

	/**
	 * xmlType
	 *
	 * set/get the xmlType
	 * @param integer $xmlType = (optional) xml type, null to query
	 * @return integer $xmlType
	 */
	public function xmlType($xmlType=null)
	{
		if ($xmlType !== null)
		{
			$this->xmlType = $xmlType;
		}

		return $this->xmlType;
	}

	/**
	 * loadDomTypes
	 *
	 * get the loadDomTypes array
	 * @return array $loadDomTypes
	 */
	public function loadDomTypes()
	{
		return $this->loadDomTypes;
	}

}
