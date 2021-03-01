<?php
namespace Tests\DOM;

use Library\CliParameters;
use Library\DOM;
use Library\Utilities\FormatVar;
use Library\HTTP\Factory as HTTPFactory;
use Library\Properties;
use Library\Utilities\PHPModule;

/*
 * 		DOM\DocumentIOTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * DocumentIOTest.
 *
 * DocumentIO class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage DOM.
 */

class DocumentIOTest extends \Application\Launcher\Testing\UtilityMethods
{
	/**
	 * __construct
	 *
	 * Class constructor - pass parameters on to parent class
	 */
	public function __construct()
	{
		parent::__construct();
		$this->assertSetup(1, 0, 0, array('Application\Launcher\Testing\Base', 'assertCallback'));
	}

	/**
	 * __destruct
	 *
	 * Class destructor.
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
     * assertionTests
     *
     * run the current assertion test steps
     * @parm string $logger = (optional) name of the logger to use, null for none 
     */
    public function assertionTests($logger=null, $format=null)
    {
    	parent::assertionTests($logger, $format);
    	
    	$this->a_testFile = CliParameters::parameterValue('url', 'Tests\DOM\Testfiles\XMLDocument.xml');
    	$this->a_testAdapter = CliParameters::parameterValue('adapter', 'file');

    	$this->a_setDefaultProperties($this->a_testFile, $this->a_testAdapter);

    	$this->a_openDOM($this->a_defaultProperties);
    	
    	$this->a_loadDOM($this->a_defaultProperties);
    	$this->assertLogMessage(sprintf("Buffer:\n%s", $this->a_dom->buffer()));

    	$this->a_domDocumentBuffer();

    	$this->a_domXMLType(\Library\DOM\XML::LOADDOM_TYPE_XML);
    	
    	if ($this->a_testAdapter != 'http')
    	{
	    	$this->a_defaultProperties->FileIO_Source = 'Tests\DOM\Testfiles\XMLSaved.xml';
	    	$this->a_defaultProperties->FileIO_Mode = 'w';
    	
	    	$this->a_setProperties($this->a_defaultProperties);
	    	$this->a_saveDOM();
    	
	    	$this->a_defaultProperties->FileIO_Source .= 'f';
	    	$this->a_setProperties($this->a_defaultProperties);
    	
	    	$this->a_getDOMDocument();
	    	$this->a_parseToArray();
	    	$this->a_fromArray();
    	
	    	$this->a_compareXML($this->a_dom->buffer(), $this->a_xmlString);
    	}
    }

    /**
     * a_compareXML
     *
     * Compares the content of each sub-string in each string, ignoring leading and trailing blanks, cr, tab
     * @param string $original = original file contents
     * @param string $reconstructed = reconstructed (from DOM document) file contents
     */
    public function a_compareXML($original, $reconstructed)
    {
    	$this->labelBlock('CompareXML.', 40, '*');

    	$oArray = explode("\n", $original);
    	$rArray = explode("\n", $reconstructed);

    	foreach($oArray as $index => $text)
    	{
    		$text = trim($text);
    		if (! array_key_exists($index, $rArray))
    		{
    			$this->a_outputAndDie(sprintf("Index does not exist: %s", $index));
    		}
    		
    		$rtext = trim($rArray[$index]);
    		if ($text !== $rtext)
    		{
    			$this->a_outputAndDie(sprintf("\nOriginal[%s]: '%s'\nReconstructed[%s]: '%s'", $index, $text, $index, $rtext));
    		}
    	}

    	$this->assertLogMessage('Strings are identical');
    }

    /**
     * a_fromArray
     *
     * Get the DOM Document as a string
     * @param string $expected = expected dom document
     */
    public function a_fromArray()
    {
    	$this->labelBlock('From Array.', 40, '*');

    	$assertion = '$this->a_xmlString = \Library\XML\ConvertArray::fromArray($this->a_domArray, $this->a_domDocument->attributeTag());';
		if (! $this->assertExceptionTrue($assertion, sprintf("From Array - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();
		
		$this->assertLogMessage(sprintf("DOM Document:\n%s", $this->a_xmlString));
    }

    /**
     * a_parseToArray
     *
     * Get the DOM Document as an array
     */
    public function a_parseToArray()
    {
    	$this->labelBlock('Parse To Array.', 40, '*');

    	$assertion = '$this->a_domArray = $this->a_domDocument->toArray($this->a_dom->query("/configdata"));';
		if (! $this->assertExceptionTrue($assertion, sprintf("Parse To Array - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();
		
		$this->assertLogMessage(FormatVar::format($this->a_domArray, 'DOM Document'));
    }

    /**
     * a_getDOMDocument
     *
     * Get the DOM Document
     */
    public function a_getDOMDocument()
    {
    	$this->labelBlock('Get DOM Document.', 40, '*');

    	$assertion = '$this->a_domDocument = $this->a_dom->domDocument();';
		if (! $this->assertTrue($assertion, sprintf("Get DOM Document - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_domXMLType
     *
     * Get the DOM XML type
     * @param string $expected = expected result
     */
    public function a_domXMLType($expected)
    {
    	$this->labelBlock('DOM Document Buffer.', 40, '*');

    	$assertion = '$this->a_data = $this->a_dom->determineXmlType("/configdata");';
		if (! $this->assertTrue($assertion, sprintf("DOM XML Type - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_compareExpected($expected);
    }

    /**
     * a_domDocumentBuffer
     *
     * Get the DOM document buffer
     */
    public function a_domDocumentBuffer()
    {
    	$this->labelBlock('DOM Document Buffer.', 40, '*');

    	$assertion = '$this->a_domDocumentBuffer = $this->a_dom->domDocumentBuffer();';
		if (! $this->assertTrue($assertion, sprintf("DOM Document Buffer - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->assertLogMessage(sprintf("DOM Document Buffer:\n%s", $this->a_domDocumentBuffer));
    }

    /**
     * a_saveDOM
     *
     * Save the DOM document
     */
    public function a_saveDOM()
    {
    	$this->labelBlock('Save DOM.', 40, '*');

    	$this->a_properties->a_mode = 'w';
		$this->a_properties->FileIO_Source = $this->a_absoluteFileName($this->a_properties->FileIO_Source);
		
		$this->a_setProperties($this->a_properties);

    	$assertion = '$this->a_dom->saveDom();';
		if (! $this->assertExceptionFalse($assertion, sprintf("Save DOM - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_loadDOM
     *
     * Load the DOM document
     */
    public function a_loadDOM($properties)
    {
    	$this->labelBlock('Load DOM.', 40, '*');

    	$this->a_properties = $properties;
    	$this->a_properties->a_mode = 'r';

    	if (stream_is_local($this->a_properties->FileIO_Source))
    	{
			$this->a_properties->FileIO_Source = $this->a_absoluteFileName($this->a_properties->FileIO_Source);
    	}

//$this->a_dom->loadDom($this->a_properties);
		$assertion = '$this->a_dom->loadDom($this->a_properties);';
		if (! $this->assertExceptionFalse($assertion, sprintf("Load DOM - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_openDOM
     *
     * Open the DOM document
     * @param object $properties = Properties class containing the connection properties
     */
    public function a_openDOM($properties)
    {
    	$this->labelBlock('Open DOM.', 40, '*');

    	$this->a_domAdapter = $properties->DOM_Adapter;
    	$this->a_properties = $properties;

    	$assertion = '$this->a_dom = new \Library\DOM\DocumentIO($this->a_domAdapter, $this->a_properties);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Open DOM - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_setProperties
     *
     * Set the properties
     */
    public function a_setProperties($properties)
    {
    	$this->labelBlock('Set Properties.', 40, '*');

    	$assertion = '$this->a_data = $this->a_dom->properties($this->a_properties);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Set Properties - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		
		$this->a_compareExpected($properties);
    }

    /**
     * a_setDefaultProperties
     *
     * Set default property values
     * @param string $source = name of the test file
     * @param string $adapter = name of the DOM adapter to use
     * @param string $fileAdapter = (optional) name of the file adapter, if needed
     * @param string $mode = (optional) file open mode
     */
    public function a_setDefaultProperties($source, $adapter, $fileAdapter='fileobject', $mode='r')
    {
    	switch($adapter)
    	{
    		case 'http':
    			$ioadapter = PHPModule::loaded(array_keys(HTTPFactory::getInstance()->availableClasses()));
				$this->a_defaultProperties = new Properties(array('DOM_Adapter'		=> 'http',
	                    									   	  'FileIO_Mode'		=> $mode,
	            	        									  'FileIO_Adapter'	=> $ioadapter,
	                	    									  'FileIO_Source'	=> $source,
																  'HTTP_Driver'  	=> $ioadapter,
																  'HTTP_Uri'		=> $source));
    			break;

    		case 'stream':
				$this->a_defaultProperties = new Properties(array('DOM_Adapter'			=> 'stream',
										 						  'StreamIO_Mode'		=> $mode,
		                                 						  'StreamIO_Type'		=> 't',
		                                 						  'StreamIO_Suppress'	=> true,
		                                 						  'StreamIO_Adapter'	=> $fileAdapter));
    			break;

    		default:
   			case 'file':
				$this->a_defaultProperties = new Properties(array('DOM_Adapter'		=> 'file',
	                    									   	  'FileIO_Mode'		=> $mode,
	            	        									  'FileIO_Adapter'	=> $fileAdapter,
	                	    									  'FileIO_Source'	=> $source));
				break;
    	}
    }
}
