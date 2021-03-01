<?php
namespace Tests\Url;

/*
 *		ParseTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * ParseTest
 *
 * Url parse tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Url
 */
class UrlTest extends \Application\Launcher\Testing\UtilityMethods
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

    	$this->a_parseUrl('https://fedora13.ewdesigns.lan/~jay/HauntedHollow/index.html?user=jay#start');

    	$this->a_host('fedora13.ewdesigns.lan');

    	$this->a_path('/~jay/HauntedHollow/index.html?user=jay#start');

    	$this->a_port(80, false);

    	$this->a_port(443);
    	
    	$this->a_scheme('https');
    	
    	$this->a_query('user=jay');
    	
    	$this->a_fragment('start');
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

    	$assertion = '(($this->a_data = \Library\Url::fragment()) !== null);';
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

    	$assertion = '(($this->a_data = \Library\Url::query()) !== null);';
		if (! $this->assertTrue($assertion, sprintf("Query: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType($type, $expected);
    }

    /**
     * a_scheme
     *
     * Get the protocol scheme
     * @param integer $expected = protocol scheme expected
     * @param boolean $type = type of test (true or false), default=true
     */
    public function a_scheme($expected, $type=true)
    {
    	$this->labelBlock('Scheme.', 40, '*');

    	$assertion = '(($this->a_data = \Library\Url::scheme()) !== null);';
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

    	$assertion = '(($this->a_data = \Library\Url::port()) !== null);';
		if (! $this->assertTrue($assertion, sprintf("Port: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType($type, $expected);
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

    	$assertion = '$this->a_data = \Library\Url::path();';
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

    	$assertion = '$this->a_data = \Library\Url::host();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Host: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($expected);
    }

    /**
     * a_parseUrl
     *
     * Parse the url using Library\Url\Parse
     * @param string $url = url to parse
     */
    public function a_parseUrl($url)
    {
    	$this->labelBlock('Parse Url.', 60, '*');

    	$assertion = sprintf("\\Library\\Url::url('%s');", $url);
		if (! $this->assertExceptionTrue($assertion, sprintf("Parse Url: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }
}
