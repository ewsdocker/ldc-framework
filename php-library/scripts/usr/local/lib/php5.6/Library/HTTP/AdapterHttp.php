<?php
namespace Library\HTTP;
use Library\Error;

/*
 * 		HTTP\AdapterHttp is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * AdapterHttp.
 *
 * HTTP\AdapterHttp client adapter.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage HTTP
 */
class AdapterHttp extends AdapterAbstract
{
	/**
	 * __construct
	 *
	 * Class constructor.
	 * @param string $uri    = (optional) address of the CLIENT website
	 * @param mixed  $config = (optional) connection configuration properties/array
	 * @param string $method = (optional) http method ('get', 'post', ...)
	 * @throws \Library\HTTP\Exception
	 */
	public function __construct($uri=null, $config=null, $method='get')
	{
		$this->result = array();
		$this->response = null;
		$this->uriSet = false;

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
		if (! $this->response)
		{
			throw new Exception(Error::code('ClientRequestFailed'));
		}

		if (is_array($this->response))
		{
			if (array_key_exists('error', $this->response) && ($this->response['error'] !== ''))
			{
				throw new Exception(Error::code('ClientRequestFailed'));
			}
		}

		if (! is_object($this->response))
		{
			throw new Exception(Error::code('ObjectExpected'));
		}
	}

	/**
	 * executeRequest
	 *
	 * execute the client request and return reference to result object
	 * @return object reference to result (response) object
	 * @throws HttpException
	 */
	protected function executeRequest()
	{
		$this->response = $this->client->send();
		return $this->response;
	}

	/**
	 * setClient
	 *
	 * Create a new client class to use for this driver
	 * @return object $client = new client object.
	 * @throws \Library\HTTP\Exception
	 */
	protected function setClient()
	{
		if (! $this->client)
		{
			if (! extension_loaded('http'))
			{
				throw new Exception(Error::code('PhpExtensionNotAvailable'));
			}

			$this->client = new \HttpRequest();
		}

		return $this->client;
	}

	/**
	 * setUri
	 *
	 * set server Uri
	 * @param string $url = url to connect to
	 * @throws \Library\HTTP\Exception
	 */
	public function setUri($uri)
	{
		$this->uriSet = false;
		$this->checkClient();

		if (! $this->client->setUrl($uri))
		{
			throw new Exception(Error::code('MissingUri'));
		}

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
			$this->client->setMethod(\HttpRequest::METH_GET);
		}
		else
		{
			$this->client->setMethod(\HttpRequest::METH_POST);
		}
	}

	/**
	 * setConfig
	 *
	 * set server configuration array
	 * @param array $config = configuration array
	 * @throws \Library\HTTP\Exception
	 */
	public function setConfig($config)
	{
		$this->checkClient();
		if (! $this->client->setOptions($config))
		{
			throw new Exception(Error::code('ClientConfigError'));
		}
	}

	/**
	 * setParameters
	 *
	 * set parameters from the associative parameters array
	 * @param array $parameters = associative parameters array ( <field name> => <field value>)
	 * @throws \Library\HTTP\Exception
	 */
	public function setParameters($parameters)
	{
		$this->checkClient();

		if ($this->method == 'get')
		{
			if (! $this->client->setQueryData($parameters))
			{
				throw new Exception(Error::code('InvalidParameterValue'));
			}
		}
		else
		{
			if (! $this->client->setPostFields($parameters))
			{
				throw new Exception(Error::code('InvalidParameterValue'));
			}
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
		$this->checkClient();

		if ($this->method == 'get')
		{
			if (! $this->client->addQueryData($parameters))
			{
				throw new Exception(Error::code('InvalidParameterValue'));
			}
		}
		else
		{
			if (! $this->client->addPostFields($parameters))
			{
				throw new Exception(Error::code('InvalidParameterValue'));
			}
		}

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
		if (! $this->client->setContentType($contentType))
		{
			throw new Exception(Error::code('ClientInvalidContenttype'));
		}
	}

	/**
	 * setContent
	 *
	 * Set the content (or body) of the request
	 * @param string $content = content to send
	 * @throws \Library\HTTP\Exception
	 */
	public function setContent($content)
	{
		$this->checkClient();
		if (! $this->client->setBody($content))
		{
			throw new Exception(Error::code('InvalidParameterValue'));
		}
	}

	/**
	 * getStatus
	 *
	 * get status code
	 * @return integer $statusCode
	 * @throws \Library\HTTP\Exception
	 */
	public function getStatus()
	{
		$this->checkClientAndResponse();
		return $this->client->getResponseCode();
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
		return $this->client->getResponseBody();
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
		return $this->client->getResponseBody();
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
		return $this->client->getResponseHeader();
	}

	/**
	 * getResponse
	 *
	 * get response object
	 * @param none
	 * @return HttpResponse reference to response object
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
		$this->checkClient();
		return $this->client->getUrl();
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
		$this->checkClient();
		return $this->client->getQueryData();
	}

	/**
	 * getClient
	 *
	 * get HttpRequest object
	 * @param none
	 * @return HttpRequest reference to client object
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
	 * @param none
	 * @return array returned fields and values
	 */
	public function getResult()
	{
		return $this->result;
	}

}

