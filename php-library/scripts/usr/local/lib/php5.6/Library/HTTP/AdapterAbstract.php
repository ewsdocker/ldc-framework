<?php
namespace Library\HTTP;
use Library\Error;

/*
 * 	  	HTTP\AdapterAbstract is copyright © 2010. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 *      Refer to the file named License provided with the source.
 */

/**
 * HTTP_Client_Abstract.php
 *
 * @author Jay Wheeler <jaywheeler@users.sourceforge.net>
 * @copyright © 2010. EarthWalk Software
 * @license Licensed under the Academic Free License version 3.0.
 * @version 1.1
 * @package EWSLibrary
 * @subpackage HTTP
 */
abstract class AdapterAbstract
{
	/**
	 * client
	 *
	 * Points to the current Client class object.
	 * @var Client $client
	 */
	protected 	$client    = null;

	/**
	 * response
	 *
	 * Points to the response object returned by the last request.
	 * @var object $response
	 */
	protected 	$response  = null;

	/**
	 * result
	 *
	 * An associative array containing the field/value pairs decoded from the last client response.
	 * @var array[string]string $result
	 */
	protected 	$result    = array();

	/**
	 * errorMessage.
	 *
	 * A string containing the last received error message from the server.
	 * @var string $errorMessage
	 */
	protected	$errorMessage = '';

	/**
	 * configuration
	 *
	 * An associative array containing the optional server connection configuration parameters.
	 * @var array[string]string $configuration
	 */
	protected	$configuration = array();

	/**
	 * uriSet
	 *
	 * the uri-is-set flag
	 * @var boolean $uriSet = true if it is, false if it is not
	 */
	protected	$uriSet = false;

	/**
	 * uri
	 *
	 * the uri string
	 * @var string $uri
	 */
	protected	$uri = null;

	/**
	 * method
	 *
	 * method type for next access
	 * @var string $method (default = 'get')
	 */
	protected	$method = 'get';

	/**
	 * parameters
	 *
	 * The GET/POST parameters array
	 * @var array $parameters
	 */
	protected	$parameters = array();

	/**
	 * send
	 *
	 * send transaction to the URI server and wait for response
	 * @param array $parameters = (optional) GET/POST parameters array
	 * @param string $uri = (optional) location of the server
	 * @param string $method = (optional) message request method
	 * @return array $result = associative array with results
	 * @throws \Library\HTTP\Exception
	 */
	public function send($parameters=null, $uri=null, $method=null)
	{
		if ($method)
		{
			$this->setMethod($method);
		}

		if ($this->method)
		{
			$this->commitMethod();
		}

		if ($uri)
		{
			$this->setUri($uri);
		}

		$this->response = null;

		if ($parameters)
		{
			$this->setParameters($parameters);
		}

		$this->executeRequest();
		$this->goodResponse();

		$this->result = array();
		$this->result['status'] = $this->getStatus();
		$this->result['headers'] = $this->getHeaders();
		$this->result['body'] = $this->getBody();

		return $this->result;
	}

	/**
	 * checkClient
	 *
	 * check if client is valid
	 * @throws \Library\HTTP\Exception
	 */
	protected function checkClient()
	{
		if (! $this->client)
		{
			throw new Exception(Error::code('NotInitialized'));
		}
	}

	/**
	 * checkClientAndResponse
	 *
	 * check if client AND response is valid
	 * @throws \Library\HTTP\Exception
	 */
	protected function checkClientAndResponse()
	{
		$this->checkClient();
		if (! $this->response)
		{
			throw new Exception(Error::code('InvalidResult'));
		}
	}

	/**
	 * goodResponse
	 *
	 * throw exception if the response was not good
	 * @throws \Library\HTTP\Exception
	 */
	abstract protected function goodResponse();

	/**
	 * executeRequest
	 *
	 * execute the client request and return reference to result object
	 * @return object reference to result (response) object
	 * @throws \Library\HTTP\Exception
	 */
	abstract protected function executeRequest();

	/**
	 * setClient
	 *
	 * Create a new client class to use for this driver
	 * @return object $client = new client object.
	 * @throws \Library\HTTP\Exception
	 */
	abstract protected function setClient();

	/**
	 * setUri
	 *
	 * set server Uri
	 * @param string $url = url to connect to
	 * @throws \Library\HTTP\Exception
	 */
	abstract public function setUri($uri);

	/**
	 * setMethod
	 *
	 * set server Method
	 * @param string $method = method to use
	 * @throws \Library\HTTP\Exception
	 */
	abstract public function setMethod($method='get');

	/**
	 * commitMethod
	 *
	 * send method to the client
	 * @throws \Library\HTTP\Exception
	 */
	abstract protected function commitMethod();

	/**
	 * setConfig
	 *
	 * set server configuration array
	 * @param array $config = configuration array
	 * @throws \Library\HTTP\Exception
	 */
	abstract public function setConfig($config);

	/**
	 * setParameter
	 *
	 * set parameters from the associative parameters array
	 * @param array $parameters = associative parameters array ( <field name> => <field value>)
	 * @throws \Library\HTTP\Exception
	 */
	abstract public function setParameters($parameters);

	/**
	 * addParameters
	 *
	 * add parameters from the associative parameters array
	 * @param array $parameters = associative parameters array ( <field name> => <field value>)
	 * @throws \Library\HTTP\Exception
	 */
	abstract public function addParameters($parameters);

	/**
	 * setContentType
	 *
	 * set the content-type that the client should expect
	 * @param string $contentType = content-type string (primary/secondary)
	 * @throws \Library\HTTP\Exception
	 */
	abstract public function setContentType($contentType);

	/**
	 * setContent
	 *
	 * set the content as part of the body
	 * @param string $data = data string to send
	 * @throws \Library\HTTP\Exception
	 */
	abstract public function setContent($data);

	/**
	 * getStatus
	 *
	 * get status code
	 * @param none
	 * @return \Zend\Http\Response reference to response object
	 * @throws \Library\HTTP\Exception
	 */
	abstract public function getStatus();

	/**
	 * getBody
	 *
	 * get result body
	 * @return string $string = decoded body of the response
	 * @throws \Library\HTTP\Exception
	 */
	abstract public function getBody();

	/**
	 * getRawBody
	 *
	 * get raw body
	 * @return string $string = raw (possibly encoded) body of the response
	 * @throws \Library\HTTP\Exception
	 */
	abstract public function getRawBody();

	/**
	 * getHeaders
	 *
	 * get headers string
	 * @return string $string = string containing header lines separated by "\n"
	 * @throws \Library\HTTP\Exception
	 */
	abstract public function getHeaders();

	/**
	 * getResponse
	 *
	 * get \Zend\Http\Response object
	 * @param none
	 * @return \Zend\Http\Response reference to response object
	 * @throws \Library\HTTP\Exception
	 */
	abstract public function getResponse();

	/**
	 * getUrl
	 *
	 * Get the previously set url
	 * @return string $url
	 * @throws \Library\HTTP\Exception
	 */
	abstract public function getUrl();

	/**
	 * getQueryData
	 *
	 * Get the previously set query data
	 * @return string $data
	 * @throws \Library\HTTP\Exception
	 */
	abstract public function getQueryData();

	/**
	 * getClient
	 *
	 * get \Zend\Http\Client object
	 * @return \Zend\Http\Client reference to client object
	 * @throws \Library\HTTP\Exception
	 */
	abstract public function getClient();

	/**
	 * getResult
	 *
	 * get result array (decoded body)
	 * @return array returned fields and values
	 * @throws \Library\HTTP\Exception
	 */
	abstract public function getResult();

}
