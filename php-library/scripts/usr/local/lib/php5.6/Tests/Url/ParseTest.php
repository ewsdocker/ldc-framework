<?php
namespace Tests\Url;

/*
 *		ParseTest is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * ParseTest
 *
 * Url parse tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Url
 */
class ParseTest extends \Application\Launcher\Testing\UtilityMethods
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

		$this->a_newParseUrl('https://fedora13.ewdesigns.lan/~jay/HauntedHollow/index.html?user=jay#start');

		$this->a_host('fedora13.ewdesigns.lan');
		$this->a_document('/~jay/HauntedHollow/index.html');
		$this->a_path('/~jay/HauntedHollow/index.html?user=jay#start');
		$this->a_port(80, false);
		$this->a_port(443);
		$this->a_scheme('https');
		$this->a_query('user=jay');
		$this->a_fragment('start');
		$this->a_showData((string)$this->a_object, 'Properties');
		
		$this->a_parser('http://10.10.10.2/');
	
		$this->a_host('10.10.10.2');
		$this->a_document('/');
		$this->a_path('/');
		$this->a_port(443, false);
		$this->a_port(80);
		$this->a_scheme('http');
		$this->a_query(null);
		$this->a_fragment(null);
		$this->a_showData((string)$this->a_object, 'Properties');
		
	}

	/**
	 * a_fragment
	 *
	 * Get the fragment string, if it exists
	 * @param integer $expected = fragment expected
	 * @param boolean $type = type of test (true or false), default=true
	 */
	public function a_fragment($expected, $type=true)
	{
		$this->labelBlock('Fragment.', 40, '*');

		$assertion = '(($this->a_data = $this->a_object->fragment()) !== null);';
		if (! $this->assertTrue($assertion, sprintf("Fragment: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType($type, $expected);
	}

	/**
	 * a_query
	 *
	 * Get the query, if it exists
	 * @param integer $expected = query string expected
	 * @param boolean $type = type of test (true or false), default=true
	 */
	public function a_query($expected, $type=true)
	{
		$this->labelBlock('Query.', 40, '*');

		$assertion = '(($this->a_data = $this->a_object->query()) !== null);';
		if (! $this->assertTrue($assertion, sprintf("Query: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType($type, $expected);
	}

	/**
	 * a_scheme
	 *
	 * Get the protocol scheme from the parser
	 * @param integer $expected = protocol scheme expected
	 * @param boolean $type = type of test (true or false), default=true
	 */
	public function a_scheme($expected, $type=true)
	{
		$this->labelBlock('Scheme.', 40, '*');

		$assertion = '(($this->a_data = $this->a_object->scheme()) !== null);';
		if (! $this->assertTrue($assertion, sprintf("Scheme: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType($type, $expected);
	}

	/**
	 * a_port
	 *
	 * Get the port number from the parser
	 * @param integer $expected = port number expected
	 * @param boolean $type = type of test (true or false), default=true
	 */
	public function a_port($expected, $type=true)
	{
		$this->labelBlock('Port.', 40, '*');

		$assertion = '(($this->a_data = $this->a_object->port()) !== null);';
		if (! $this->assertTrue($assertion, sprintf("Port: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType($type, $expected);
	}

	/**
	 * a_document
	 *
	 * Get the url document
	 * @param string $expected = expected document
	 */
	public function a_document($expected)
	{
		$this->labelBlock('Document.', 40, '*');

		$assertion = '$this->a_data = $this->a_object->document();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Document: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
	}

	/**
	 * a_path
	 *
	 * Get the url path
	 * @param string $expected = expected path
	 */
	public function a_path($expected)
	{
		$this->labelBlock('Path.', 40, '*');

		$assertion = '$this->a_data = $this->a_object->path();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Path: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
	}

	/**
	 * a_host
	 *
	 * Get the host name
	 * @param string $expected = expected host name
	 */
	public function a_host($expected)
	{
		$this->labelBlock('Host.', 40, '*');

		$assertion = '$this->a_data = $this->a_object->host();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Host: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
	}

	/**
	 * a_parser
	 *
	 * Parse the url
	 * @param string $url = url to parse
	 */
	public function a_parser($url)
	{
		$this->labelBlock('Parser.', 40, '*');
		
		$this->a_url = $url;
		$this->a_showData($this->a_url, 'URL');
		
		$assertion = sprintf('(($this->a_object->parser("%s")) !== true);', $this->a_url);
		if (! $this->assertExceptionTrue($assertion, sprintf("parser: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_exceptionCaughtFalse();
		
		$this->a_showData((string)$this->a_object, 'UrlParse');
	}

	/**
	 * a_newParseUrl
	 *
	 * Parse the url using Library\Url\Parse
	 * @param string $url = url to parse
	 */
	public function a_newParseUrl($url)
	{
		$this->labelBlock('NEW ParseUrl.', 60, '*');

		$this->a_url = $url;
		$this->a_showData($this->a_url, 'URL');

		$assertion = sprintf('$this->a_object = new \Library\Url\Parse("%s");', $this->a_url);
		if (! $this->assertExceptionTrue($assertion, sprintf("NEW ParseUrl: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData((string)$this->a_object, 'UrlParse');
   }

}
