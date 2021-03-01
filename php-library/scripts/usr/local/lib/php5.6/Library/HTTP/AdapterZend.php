<?php
namespace Library\HTTP;
use Library\Error;

/*
 * 		HTTP\AdapterZend is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * AdapterZend.
 *
 * HTTP\AdapterZend client adapter.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage HTTP
 */
class AdapterZend extends AdapterAbstract
{
	/**
	 * getParameters
	 * 
	 * Array containing the parameters from the last GET request
	 * @var array $getParameters
	 */
	private		$getParameters;

	/**
	 * putParameters
	 * 
	 * Array containing the parameters from the last POST request
	 * @var array $putParameters
	 */
	private		$putParameters;

	/**
	 * __construct
	 *
	 * Class constructor.
	 * @param string  $uri = (optional) address of the CLIENT website
	 * @param string $config = (optional) connection configuration array
	 */
	public function __construct($uri=null, $config=null, $method='get')
	{
		$this->setClient();
		$this->setMethod($method);
		$this->setContentType('text/html');

		if ($config)
		{
			$this->setConfig($config);
		}

		if ($uri)
		{
			$this->setUri($uri);
		}

	}

	/**
	 * __destruct
	 *
	 * Class destructor.
	 */
	public function __destruct()
	{
		unset($this->client);
	}

	/**
	 * goodResponse
	 *
	 * throw exception if the response was not good
	 * @throws \Library\HTTP\Exception
	 */
	protected function goodResponse()
	{
		if ((! $this->response) || (! $this->response->isSuccessful()))
		{
			throw new Exception(Error::code('ClientRequestFailed'));
		}
	}

	/**
	 * executeRequest
	 *
	 * execute the client request and return reference to result object
	 * @return object reference to result (response) object
	 * @throws \Zend\HTTP\Client\Exception
	 */
	protected function executeRequest()
	{
		$this->response = $this->client->request();
		return $this->response;
//		return $this->client->request();
	}

	/**
	 * setClient
	 *
	 * Create a new client class to use for this driver
	 * @return object $client = new client object.
	 * @throws \Zend\HTTP\Client\Exception
	 */
	protected function setClient()
	{
		if (! $this->client)
		{
			$this->client = new \Zend\Http\Client();
			$this->initialize();
		}

		return $this->client;
	}

	/**
	 * setUri
	 *
	 * set server Uri
	 * @param string $uri = uri to connect to
	 */
	public function setUri($uri)
	{
		$this->uriSet = false;
		$this->checkClient();

		if (! $this->client->setUri($uri))
		{
			throw new Exception(Error::code('MissingUri'));
		}

		$this->uri = $uri;
		$this->uriSet = true;
	}

	/**
	 * setMethod
	 *
	 * set server Method
	 * @param string $method = method to use
	 * @throws \Library\HTTP\Exception
	 */
	public function setMethod($method='get')
	{
		$this->checkClient();
		$this->method = strtolower($method);
	}

	/**
	 * commitMethod
	 *
	 * send method to the client
	 * @throws \Library\HTTP\Exception
	 */
	public function commitMethod()
	{
		if ($this->method == 'get')
		{
			$this->client->setMethod(\Zend\HTTP\Client::GET);
		}
		else
		{
			$this->client->setMethod(\Zend\HTTP\Client::POST);
		}
	}

	/**
	 * setConfig
	 *
	 * set server configuration array
	 * @param array $config = configuration array
	 * @return null
	 */
	public function setConfig($config)
	{
		$this->checkClient();
		$this->client->setConfig($config);
	}

	/**
	 * setParameters
	 *
	 * set parameters from the associative parameters array
	 * @param array $parameters = associative parameters array ( <field name> => <field value>)
	 * @return null
	 */
	public function setParameters($parameters)
	{
		$this->checkClient();
		if ($this->method == 'get')
		{
			$this->client->setParameterGet($parameters);
			$this->getParameters = array_merge($this->getParameters, $parameters);
		}
		else
		{
			$this->client->setParameterPost($parameters);
			$this->putParameters = array_merge($this->putParameters, $parameters);
		}

	}

	/**
	 * addParameters
	 *
	 * add parameters from the associative parameters array
	 * @param array $parameters = associative parameters array ( <field name> => <field value>)
	 * @throws \Library\HTTP\Exception
	 */
	public function addParameters($parameters)
	{
		$this->setParameters($parameters);
	}

	/**
	 * setContentType
	 *
	 * set the content-type that the client should expect
	 * @param string $contentType = content-type string (primary/secondary)
	 * @throws \Library\HTTP\Exception
	 */
	public function setContentType($contentType)
	{
		$this->checkClient();
		$this->client->setHeaders(\Zend\HTTP\Client::CONTENT_TYPE, $contentType);
	}

	/**
	 * setContent
	 *
	 * set the content as part of the body
	 * @param string $data = data string to send
	 * @throws \Library\HTTP\Exception
	 */
	public function setContent($data)
	{
		$this->client->setRawData($data);
	}

	/**
	 * getStatus
	 *
	 * get status code
	 * @param none
	 * @return Zend_Http_Response reference to response object
	 * @throws \Library\HTTP\Exception
	 */
	public function getStatus()
	{
		$this->checkClientAndResponse();
		return $this->response->getStatus();
	}

	/**
	 * getBody
	 *
	 * get result body
	 * @return string $string = decoded body of the response
	 * @throws \Library\HTTP\Exception
	 */
	public function getBody()
	{
		$this->checkClientAndResponse();
		return $this->response->getBody();
	}

	/**
	 * getRawBody
	 *
	 * get raw body
	 * @return string $string = raw (possibly encoded) body of the response
	 * @throws \Library\HTTP\Exception
	 */
	public function getRawBody()
	{
		$this->checkClientAndResponse();
		return $this->response->getRawBody();
	}

	/**
	 * getHeaders
	 *
	 * get headers string
	 * @return string $string = string containing header lines separated by "\n"
	 * @throws \Library\HTTP\Exception
	 */
	public function getHeaders()
	{
		$this->checkClient();
		return $this->response->getHeadersAsString(true, "\n");
	}

	/**
	 * getResponse
	 *
	 * get Zend_Http_Response object
	 * @return Zend_Http_Response reference to response object
	 * @throws \Library\HTTP\Exception
	 */
	public function getResponse()
	{
		$this->checkClientAndResponse();
		return $this->response;
	}

	/**
	 * getUrl
	 *
	 * Get the previously set url
	 * @return string $url
	 * @throws \Library\HTTP\Exception
	 */
	public function getUrl()
	{
		return $this->uri;
	}

	/**
	 * getQueryData
	 *
	 * Get the previously set query data
	 * @return string $data
	 * @throws \Library\HTTP\Exception
	 */
	public function getQueryData()
	{
		foreach($this->getParameters as $parameter => $value)
		{
			if (! $buffer)
			{
				$buffer = '?';
			}
			else
			{
				$buffer .= '&';
			}

			$buffer .= sprintf('%s=%s', $parameter, $value);
		}

		return $buffer;
	}

	/**
	 * getClient
	 *
	 * get \Zend\HTTP\Client object
	 * @return \Zend\HTTP\Client reference to client object
	 * @throws \Library\HTTP\Exception
	 */
	public function getClient()
	{
		$this->checkClient();
		return $this->client;
	}

	/**
	 * getResult
	 *
	 * get result array (decoded body)
	 * @return array returned fields and values
	 */
	public function getResult()
	{
		return $this->result;
	}

	/**
	 * initialize
	 *
	 * Class initialization.
	 */
	private function initialize()
	{
		$this->getParameters = array();
		$this->putParameters = array();
		$this->result = array();
		$this->response = null;
		$this->uriSet = false;
	}

}
