<?php
namespace Library\HTTP;
use Library\Error;

/*
 * 		HTTP\AdapterCurl is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * AdapterCurl.
 *
 * HTTP\AdapterCurl client adapter.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage HTTP
 */
class AdapterCurl extends AdapterAbstract
{
	private		$parser;

	/**
	 * __construct
	 *
	 * Class constructor.
	 * @param string $uri = (optional) address of the CLIENT website
	 * @param string $config = (optional) connection configuration array
	 * @param string $method = (optional) request method
	 * @return object = new class object reference
	 * @throws \Library\HTTP\Exception
	 */
	public function __construct($uri=null, $config=null, $method='get')
	{
		$this->result = array();

		$this->response = null;
		$this->parser = null;

		$this->uri = null;
		$this->method = $method;
		$this->parameters = null;

		$this->setClient($uri, $config, $method);
		$this->setContentType('text/html');

		$this->client->setopt(CURLOPT_RETURNTRANSFER, true);
		$this->client->setopt(CURLOPT_HEADER, true);
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
	}

	/**
	 * executeRequest
	 *
	 * execute the client request and return reference to result object
	 * @return object reference to result (response) object
	 * @throws \Library\HTTP\Exception
	 */
	protected function executeRequest()
	{
		$this->response = $this->client->exec();
		$this->parser = new \Library\HTTP\ParseResponse($this->response);
		return $this->response;
	}

	/**
	 * setClient
	 *
	 * Create a new client class to use for this driver
	 * @param string $uri     = (optional) address of the CLIENT website
	 * @param string $config  = (optional) connection configuration array
	 * @param string $method  = (optional) request method
	 * @return object $client = new client object.
	 * @throws \Library\cUrl\Exception
	 */
	protected function setClient($uri=null, $config=null, $method='get')
	{
		if (! $this->client)
		{
			$this->client = new \Library\cUrl($uri, $config, $method);
			if ($uri)
			{
				$this->setUri($uri);
			}

			if ($config)
			{
				$this->client->setopt_array($config);
			}

			$this->method = $method;
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
		$this->checkClient();
		$this->uri = $uri;
		$this->client->setopt(CURLOPT_URL, $uri);
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
	protected function commitMethod()
	{
		if ($this->method == 'get')
		{
			$this->client->setopt(CURLOPT_HTTPGET, true);
			if ($this->uri && $this->parameters)
			{
				if (is_array($this->parameters))
				{
					$getUri = sprintf('%s?%s', $this->uri, implode('&', $this->parameters));
				}
				else
				{
					$getUri = sprintf('%s?%s', $this->uri, $this->parameters);
				}

				$this->client->setopt(CURLOPT_URL, $getUri);
			}
			else
			{
				$this->client->setopt(CURLOPT_URL, $this->uri);
			}
		}
		else
		{
			$this->client->setopt(CURLOPT_POST, true);
			$this->client->setopt(CURLOPT_POSTFIELDS, $parameters);
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
		$this->client->setopt_array($config);
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
		$this->parameters = $parameters;

		if ($this->method == 'get')
		{
			$this->client->setopt(CURLOPT_HTTPGET, true);
		}
		else
		{
			$this->client->setopt(CURLOPT_POSTFIELDS, $parameters);
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
		if (! is_array($parameters))
		{
			throw new Exception(Error::code('MissingParametersArray'));
		}

		if (is_array($this->parameters))
		{
			$this->setParameters(array_merge($this->parameters, $parameters));
		}
		else
		{
			$this->setParameters($parameters);
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
		$this->client->setopt(CURLOPT_HTTPHEADER, array('Content-type' => $contentType));
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
		throw new Exception(Error::code('MethodNotImplemented'));
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
		return $this->client->getinfo(CURLINFO_HTTP_CODE);
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
		return $this->parser->body;
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
		return $this->parser->body;
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
		return $this->parser->header;
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
		throw new Exception(Error::code('MethodNotImplemented'));
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
		throw new Exception(Error::code('MethodNotImplemented'));
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

	/**
	 * parser
	 *
	 * get the parser object reference
	 * @return object $parser
	 */
	public function parser()
	{
		return $this->parser;
	}

}

