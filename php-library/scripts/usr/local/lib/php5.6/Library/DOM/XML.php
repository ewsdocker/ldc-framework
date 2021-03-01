<?php
namespace Library\DOM;

use Library\Error;

/*
 * 		DOM\XML is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * DOM\XML.
 *
 * DOM XML class methods and constants.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage DOM.
 */
class XML
{
	const    XML_ELEMENT_NODE			=  1;
	const    XML_ATTRIBUTE_NODE 		=  2;
	const    XML_TEXT_NODE 				=  3;
	const    XML_CDATA_SECTION_NODE 	=  4;
	const    XML_ENTITY_REF_NODE 		=  5;
	const    XML_ENTITY_NODE 			=  6;
	const    XML_PI_NODE 				=  7;
	const    XML_COMMENT_NODE 			=  8;
	const    XML_DOCUMENT_NODE 			=  9;
	const    XML_DOCUMENT_TYPE_NODE 	= 10;
	const    XML_DOCUMENT_FRAG_NODE 	= 11;
	const    XML_NOTATION_NODE 			= 12;
	const    XML_HTML_DOCUMENT_NODE 	= 13;
	const    XML_DTD_NODE 				= 14;
	const    XML_ELEMENT_DECL_NODE 		= 15;
	const    XML_ATTRIBUTE_DECL_NODE 	= 16;
	const    XML_ENTITY_DECL_NODE 		= 17;
	const    XML_NAMESPACE_DECL_NODE 	= 18;

	const    XML_ATTRIBUTE_CDATA 		=  1;
	const    XML_ATTRIBUTE_ID 			=  2;
	const    XML_ATTRIBUTE_IDREF 		=  3;
	const    XML_ATTRIBUTE_IDREFS 		=  4;
	const    XML_ATTRIBUTE_ENTITY 		=  5;
	const    XML_ATTRIBUTE_NMTOKEN 		=  7;
	const    XML_ATTRIBUTE_NMTOKENS 	=  8;
	const    XML_ATTRIBUTE_ENUMERATION  =  9;
	const    XML_ATTRIBUTE_NOTATION     = 10;

	const	LOADDOM_TYPE_XML 			=  1;
	const	LOADDOM_TYPE_RSS 			=  2;
	const	LOADDOM_TYPE_CAP 			=  3;
	const	LOADDOM_TYPE_CAPINDEX 		=  4;
	const	LOADDOM_TYPE_JSLOCAL 		=  5;

	const	LOADDOM_TYPE_ALERT			=  3;

	protected	$loadDomTypes = array('xml'			=> self::LOADDOM_TYPE_XML,
									  'rss'			=> self::LOADDOM_TYPE_RSS,
									  'alert'		=> self::LOADDOM_TYPE_ALERT,
									  'capindex'	=> self::LOADDOM_TYPE_CAPINDEX,
									  'jslocal'		=> self::LOADDOM_TYPE_JSLOCAL);

	/**
	 * domDocument
	 *
	 * The DOMDocument object
	 * @var DOMDocument $domDocument
	 */
	protected		$domDocument;

	/**
	 * domDocumentBuffer
	 *
	 * The DOMAdapter buffer copy
	 * @var string $domDocumentBuffer
	 */
	protected		$domDocumentBuffer;

	/**
	 * __construct
	 *
	 * class constructor
	 * @param string $xml = (optional) xml formatted string to load to DOMDocument or DOMDocument to save
	 * @param string  $type = (optional) type of operation: 'load' or 'save'
	 * @throws Library\DOM\Exception
	 */
	public function __construct($xml=null, $type='load')
	{
		$this->domDocument = null;
		$this->domDocumentBuffer = '';

		if ($xml)
		{
			switch($type)
			{
				case 'load':
					$this->loadXML($xml);
					break;

				case 'save':
					$this->saveXML($xml);
					break;

				default:
					throw new Exception(Error::code('InvalidParameterValue'));
			}
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
		$this->unsetDomDocument();
	}

	/**
	 * unsetDomDocument
	 *
	 * Unset the current dom document, if it exists
	 * @return null
	 */
	public function unsetDomDocument()
	{
		if ($this->domDocument)
		{
			unset($this->domDocument);
		}
	}

	/**
	 * loadXML
	 *
	 * load the dom document from the supplied xml string
	 * @param string $xmlString = xml string to create dom document from
	 * @throws Library\DOM\Exception
	 */
	public function loadXML($xmlString)
	{
		if (! $xmlString)
		{
			throw new Exception(Error::code('StringOrObjectExpected'));
		}

		$this->domDocument = new ConvertArray();
		$this->domDocument->preserveWhiteSpace = false;

		$errorLevel = error_reporting(E_ERROR | E_PARSE);

		if (! $this->domDocument->loadXML($this->domDocumentBuffer($xmlString)))
      	{
        	error_reporting($errorLevel);
			throw new Exception(Error::code('DomLoadFailed'));
      	}

		error_reporting($errorLevel);
	}

	/**
	 * saveXML
	 *
	 * Save the current DOM document in a string
	 * @param DOMDocument $domDocument = (optional) DOM document object
	 * @return string $domDocumentBuffer = stored DOM document
	 * @throws Library\DOM\Exception
	 */
	public function saveXML($domDocument=null)
	{
		$domDocument = $this->domDocument($domDocument);
		if ((! $domDocument) || (! ($domDocument instanceof \Library\DOM\ConvertArray)))
		{
			throw new Exception(Error::code('DomObjectExpected'));
		}

		$errorLevel = error_reporting(E_ERROR | E_PARSE);

		if (! $this->domDocumentBuffer($this->domDocument->saveXML()))
		{
        	error_reporting($errorLevel);
			throw new Exception(Error::code('DomSaveFailed'));
		}

		error_reporting($errorLevel);

		return $this->domDocumentBuffer;
	}

    /**
     * domDocument
     *
     * get/set the current DOM object
     * @param DOMDocument $domDocument = (optional) dom document, null to query
     * @return DOMDocument $domDocument
     */
	public function domDocument($domDocument=null)
	{
		if ($domDocument !== null)
		{
			if (! ($this->domDocument instanceOf \Library\DOM\ConvertArray))
			{
				throw new Exception(Error::code('InvalidClassObject'));
			}

			$this->domDocument = $domDocument;
		}

		return $this->domDocument;
	}

	/**
	 * domDocumentBuffer
	 *
	 * get/set the domDocument buffer contents
	 * @param string $domDocumentBuffer = (optional) buffer contents
	 * @return string $domDocumentBuffer
	 */
	public function domDocumentBuffer($domDocumentBuffer=null)
	{
		if ($domDocumentBuffer !== null)
		{
			$this->domDocumentBuffer = $domDocumentBuffer;
		}

		return $this->domDocumentBuffer;
	}

	/**
	 * loadDomTypes
	 *
	 * @return array $loadDomTypes
	 */
	public function loadDomTypes()
	{
		return $this->loadDomTypes;
	}

}
