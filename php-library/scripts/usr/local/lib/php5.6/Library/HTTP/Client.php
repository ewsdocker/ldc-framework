<?php
namespace Library\HTTP;
use Library\Error;
/*
 * 		HTTP\Client is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * Exception.
 *
 * HTTP\Client convenience class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage HTTP
 */
class Client
{
	/**
	 * adapter
	 *
	 * the current adapter instance
	 * @var object $adapter
	 */
	private		$adapter;

	/**
	 * __construct
	 *
	 * Class constructor.
	 * @param string $adapter = (optional) client adapter name, defaults to 'http'
	 * @param string $uri     = (optional) address of the CLIENT website
	 * @param string $config  = (optional) connection configuration array
	 * @param string $method  = (optional) message request method, defaults to 'get'
	 * @return object         = new class object reference
	 * @throws Library\HTTP\Exception, Library\Factory\Exception
	 */
	public function __construct($adapter='http', $uri=null, $config=null, $method='get')
	{
		$this->adapter = Factory::instantiateClass($adapter, $uri, $config, $method);
	}

	/**
	 * __destruct
	 *
	 * Class destructor.
	 */
	public function __destruct()
	{
		unset($this->adapter);
	}

	/**
	 * send
	 *
	 * send transaction to the URI server and wait for response
	 * @param array (optional) $parameters = GET/POST parameters array
	 * @param string (optional) $uri = location of the server
	 * @param string (optional) $method = message request method
	 * @return array $result = associative array with results
	 * @throws Exception
	 */
	public function send($parameters=null, $uri=null, $method=null)
	{
		return $this->adapter->send($parameters, $uri, $method);
	}

	/**
	 * setUri
	 *
	 * set server Uri
	 * @param string $url = url to connect to
	 * @throws Exception
	 */
	public function setUri($uri)
	{
		$this->adapter->setUri($uri);
	}

	/**
	 * setMethod
	 *
	 * set server Method
	 * @param string $method = method to use
	 * @throws Exception
	 */
	public function setMethod($method='get')
	{
		$this->adapter->setMethod($method);
	}

	/**
	 * setConfig
	 *
	 * set server configuration array
	 * @param array $config = configuration array
	 * @throws Exception
	 */
	public function setConfig($config)
	{
		$this->adapter->setConfig($config);
	}

	/**
	 * setParameters
	 *
	 * set parameters from the associative parameters array
	 * @param array $parameters = associative parameters array ( <field name> => <field value>)
	 * @throws Exception
	 */
	public function setParameters($parameters)
	{
		$this->adapter->setParameters($parameters);
	}

	/**
	 * addParameters
	 *
	 * add parameters from the associative parameters array
	 * @param array $parameters = associative parameters array ( <field name> => <field value>)
	 * @throws Exception
	 */
	public function addParameters($parameters)
	{
		$this->adapter->addParameters($parameters);
	}

	/**
	 * setContentType
	 *
	 * set the content-type that the client should expect
	 * @param string $contentType = content-type string (primary/secondary)
	 * @throws Exception
	 */
	public function setContentType($contentType)
	{
		$this->adapter->setContentType($contentType);
	}

	/**
	 * setContent
	 *
	 * Set the content (or body) of the request
	 * @param string $content = content to send
	 * @throws Exception
	 */
	public function setContent($content)
	{
	$this->adapter->setBody($content);
	}

	/**
	 * getStatus
	 *
	 * get status code
	 * @return integer $statusCode
	 */
	public function getStatus()
	{
		return $this->adapter->getStatus();
	}

	/**
	 * getBody
	 *
	 * get result body
	 * @return string $string = decoded body of the response
	 */
	public function getBody()
	{
		return $this->adapter->getBody();
	}

	/**
	 * getRawBody
	 *
	 * get raw body
	 * @return string $string = raw (possibly encoded) body of the response
	 */
	public function getRawBody()
	{
		return $this->adapter->getRawBody();
	}

	/**
	 * getHeaders
	 *
	 * get headers string
	 * @return string $string = string containing header lines separated by "\n"
	 */
	public function getHeaders()
	{
		return $this->adapter->getHeaders();
	}

	/**
	 * getResponse
	 *
	 * get response object
	 * @param none
	 * @return HttpResponse reference to response object
	 */
	public function getResponse()
	{
		return $this->adapter->getResponse();
	}

	/**
	 * getUrl
	 *
	 * Get the previously set url
	 * @return string $url
	 * @throws Exception
	 */
	public function getUrl()
	{
		return $this->adapter->getUrl();
	}

	/**
	 * getQueryData
	 *
	 * Get the previously set query data
	 * @return string $data
	 * @throws Exception
	 */
	public function getQueryData()
	{
		return $this->adapter->getQueryData();
	}

	/**
	 * getClient
	 *
	 * get HttpRequest object
	 * @param none
	 * @return HttpRequest reference to client object
	 */
	public function getClient()
	{
		return $this->adapter->getClient();
	}

	/**
	 * getAdapter
	 *
	 * get adapter object
	 * @return object $adapter = adapter object
	 */
	public function getAdapter()
	{
		return $this->adapter;
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
		return $this->adapter->result;
	}

}

