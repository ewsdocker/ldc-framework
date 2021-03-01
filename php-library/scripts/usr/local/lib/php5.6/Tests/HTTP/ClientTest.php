<?php
namespace Tests\HTTP;
use Library\HTTP;
use Tests\FileIO\ObjectFactoryTest;

/*
 * 		HTTP\ClientTest is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * ClientTest.
 *
 * HTTP client class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage HTTP.
 */

class ClientTest extends ObjectFactoryTest // \Application\Launcher\Testing\UtilityMethods
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
    	$this->a_doNotRunTests = true;

    	parent::assertionTests($logger, $format);

    	$this->labelBlock('ClientTest Assertion Tests.', 40, '*');

    	$this->a_binary = \Library\CliParameters::parameterValue('binary', false);
		if ($this->a_binary == '1')
		{
			$this->a_binary = true;
		}
		else
		{
			$this->a_binary = false;
		}

		$this->a_fileDest = \Library\CliParameters::parameterValue('dest', 'Library/Tests/FileIO/Files');
		$this->a_fileAdapter = \Library\CliParameters::parameterValue('fileadapter', 'splobject');
    	$this->a_fileObject = \Library\CliParameters::parameterValue('object', 'fileio');

    	$this->a_clientAdapter = \Library\CliParameters::parameterValue('adapter', 'curl');

		$this->a_validAdapter($this->a_clientAdapter);
    	if (! $this->a_data)
		{
			$this->assertLogMessage(sprintf('The %s adapter module is not present! Skipping tests.', $this->a_clientAdapter));
			return;
		}

		if (! $this->a_uri = \Library\CliParameters::parameterValue('uri', 'http://localhost/'))
    	{
			$this->a_outputAndDie(Error::code('MissingUri'));
    	}

    	$this->assertLogMessage(sprintf('URI: %s', $this->a_uri));

    	$this->a_className();

    	$this->a_newClient($this->a_adapterClass);

    	$this->a_setUri();
    	$this->a_setMethod();
    	$this->a_send();

    	$this->a_getStatus(200);
    	$this->a_getHeaders();
		$this->a_getBody();
/*
		if ($this->a_binary)
		{
			$this->a_saveBody($this->a_fileDest);
		}
*/
		$this->a_destroyClient();
    }

    /**
	 * a_saveBody
	 *
	 * Save the body of the input in the specified file
	 */
    public function a_saveBody($dest)
    {
    	$this->labelBlock('Save Body.', 40, '*');

    	set_time_limit(0);

    	$this->a_absoluteFileName($this->a_fileDest);

    	$this->a_newFactoryObject($this->a_fileName, $this->a_fileAdapter, $this->a_fileObject);

    	$this->a_updateFlags();
    	$this->a_buffer = $this->a_data;

    	$this->a_fwrite();
    	$this->a_destroyFileObject();
    }

    /**
	 * a_validAdapter
	 *
	 * Check for validity of requested adapter.
	 * @param string $adapter = adapter to check
	 * @param boolean $expected = expected result, default = true
	 */
    public function a_validAdapter($adapter)
    {
    	$this->labelBlock('Valid Adapter.', 40, '*');

    	$this->a_localArray = array($adapter);
    	$this->a_showData($this->a_localArray, 'Adapter Array');

		$assertion = '$this->a_data = \Library\Utilities\PHPModule::loaded($this->a_localArray);';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			;
		}

		$this->a_showData($this->a_data, 'a_data');
    }

    /**
	 * a_getHeaders
	 *
	 * Get the result headers.
	 */
    public function a_getHeaders()
    {
    	$this->labelBlock('Get Headers.', 40, '*');

		$assertion = '$this->a_localArray = $this->a_client->getHeaders();';
		if (! $this->assertTrue($assertion, sprintf("Get Headers - Asserting: %s", $assertion)))
		{
			$this->a_showData($this->a_localArray, 'a_localArray');
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_localArray, 'Headers Array');
    }

	/**
	 * a_getBody
	 *
	 * Get the result body.
	 */
    public function a_getBody()
    {
    	$this->labelBlock('Get Body.', 40, '*');

		$assertion = '$this->a_data = $this->a_client->getBody();';
		if (! $this->assertTrue($assertion, sprintf("Get Body - Asserting: %s", $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($this->a_binary)
		{
			$this->assertLogMessage('Binary data loaded.');
		}
		else
		{
			$this->a_showData($this->a_data, 'Body');
		}
    }

    /**
	 * a_getStatus
	 *
	 * Get the result status code.
	 * @param integer $expected = expected status code
	 */
    public function a_getStatus($expected=null)
    {
    	$this->labelBlock('Get Status.', 40, '*');

    	$this->a_expected = $expected;
   		$this->a_showData($this->a_expected, 'expected');

		$assertion = '$this->a_data = $this->a_client->getStatus();';
		if (! $this->assertTrue($assertion, sprintf("Get Status - Asserting: %s", $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_data, 'Status');

		$this->a_compareExpected($this->a_expected);
    }

	/**
	 * a_send
	 *
	 * Send the transaction.
	 */
    public function a_send()
    {
    	$this->labelBlock('Send.', 40, '*');

   		$assertion = '$this->a_localArray = $this->a_client->send();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Send - Asserting: %s", $assertion)))
		{
			$this->a_showData($this->a_localArray, 'a_localArray');
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_localArray, 'Response Array');
		$this->a_exceptionCaughtFalse();
    }

	/**
	 * a_setMethod
	 *
	 * Set the access method for the next access.
	 * @param string $method = (optional) access method, defaults to 'get'
	 */
    public function a_setMethod($method='get')
    {
    	$this->labelBlock('Set Method.', 40, '*');

    	$this->a_method = $method;
    	if ($this->verbose > 1)
    	{
    		$this->a_showData($this->a_method, 'a_method');
    	}

		$assertion = '$this->a_client->setMethod($this->a_method);';
		if (! $this->assertExceptionFalse($assertion, sprintf("Set Method - Asserting: %s", $assertion)))
		{
			$this->a_showData($this->a_client, 'a_client');
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_showData($this->a_client, 'a_client');
    }

    /**
	 * a_setUri
	 *
	 * Set the uri for the next access.
	 */
    public function a_setUri()
    {
    	$this->labelBlock('Set Uri.', 40, '*');

		$assertion = '$this->a_client->setUri($this->a_uri);';
		if (! $this->assertExceptionFalse($assertion, sprintf("Set Uri - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
	 * a_className
	 *
	 * Lookup a class name for the current adapter.
	 */
    public function a_className()
    {
    	$this->labelBlock('Class Name.', 40, '*');

		$assertion = '$this->a_adapterClass = \Library\HTTP\Factory::getInstance()->className($this->a_clientAdapter);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Class Name - Asserting: %s", $assertion)))
		{
			$this->a_showData($this->a_adapterClass, 'a_adapterClass');
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_showData($this->a_adapterClass, 'a_adapterClass');
    }

	/**
	 * a_destroyClient
	 *
	 * Destroy the current client
	 */
	public function a_destroyClient()
	{
    	$this->labelBlock('Destroy Client.', 40, '*');

    	unset($this->a_client);
		$this->a_client = null;
	}

	/**
	 * a_newClient
	 *
	 * Create a new HTTP client for the provided adapter name
	 */
	public function a_newClient($expected)
	{
    	$this->labelBlock('New Client.', 40, '*');

    	$this->a_expected = $expected;

   		$this->a_showData($this->a_clientAdapter, 'a_clientAdapter');
   		$this->a_showData($this->a_expected, 'expected');

    	$this->a_client = null;

    	$assertion = '$this->a_client = new \Library\HTTP\Client($this->a_clientAdapter);';
		if (! $this->assertExceptionTrue($assertion, sprintf("New Client - Asserting: %s", $assertion)))
		{
			$this->a_showData($this->a_client, 'a_client');
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, $this->a_expected, get_class($this->a_client->getAdapter()));

		$this->a_exceptionCaughtFalse();
		$this->a_showData($this->a_client, 'a_client');
	}

}
