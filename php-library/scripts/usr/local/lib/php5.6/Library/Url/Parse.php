<?php
namespace Library\Url;
use Library\Properties;

/*
 *		Url\Parse is copyright � 2012, 2013. EarthWalk Software.
 *		Licensed under the Academic Free License version 3.0.
 *		Refer to the file named License.txt provided with the source, 
 *			  or from http://opensource.org/licenses/academic.php
 */
/**
 * Library\Url\Parse.
 *
 * Parse the supplied url.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Url
 */
class Parse extends Properties
{
	/*
	 * 
	 * 		'url' 		= URL (unparsed)
	 *		'host' 		= host name or address
	 *		'port'		= port number
	 *		'path'		= URL path
	 *		'query'		= Query portion
	 *		'fragment'	= fragment portion
	 *		'document'	= path to the document
	 *		'scheme'	= URL scheme (http, https, ...)
	 *
	 */
	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param string $url = (optional) url to parse.
	 * @throws Library\Url\Exception
	 */
	public function __construct($url=null)
	{
		parent::__construct();
		if ($url !== null)
		{
			$this->parser($url);
		}
	}

	/**
	 * __call
	 * 
	 * Trap call to 
	 * @param string $name
	 * @param array $arguments
	 * @return mixed $value = property value
	 */
	public function __call($name, $arguments)
	{
		if (method_exists($this, $name))
		{
			return $this->{$name}(implode(',', $arguments));
		}

		if ($this->exists($name))
		{
			return $this->{$name};
		}
		
		return null;
	}

	/**
	 * url
	 *
	 * Primary entry point to the parser.
	 * set (and parse) / get url
	 * @param string $url = (optional) url to set (and parse), null to query only
	 * @return string $url
	 */
	public function url($url=null)
	{
		if ($url !== null)
		{
			$this->parser($url);
		}

		return $this->url;
	}

	/**
	 * parser
	 *
	 * Parse the url
	 * @param string $url = url address
	 * @throws \Library\Url\Exception
	 */
	protected function parser($url)
	{
		$this->deleteAll();
		$this->setProperties(array('url' 		=> $url,
								  'host' 		=> null,
								  'port'		=> null,
								  'path'		=> '',
								  'query'		=> '',
								  'fragment'	=> '',
								  'document'	=> '',
								  'scheme'		=> 'http'));

		$urlFields = parse_url($this->url);
		if (! is_array($urlFields))
		{
			throw new Exception(Error::code('UrlMalformed'));
		}

		if (! isset($urlFields['host']))
		{
			$urlFields['host'] = 'localhost';
		}

		$this->host = $urlFields['host'];
		
		if (isset($urlFields['scheme']))
		{
			$this->scheme = strtolower($urlFields['scheme']);
		}

		if (array_key_exists('port', $urlFields))
		{
			$this->port = $urlFields['port'];
		}
		elseif ($this->scheme == "https")
		{
			$this->port = 443;
		}
		else
		{
			$this->port = 80;
		}

		if (isset($urlFields['path']))
		{
			if (substr($urlFields['path'], 0, 1) != '/')
			{
				$this->path = '/';
			}
			
			$this->path .= $urlFields['path'];
			$this->document = $this->path;
		}

		if (isset($urlFields['query']))
		{
			$this->query = $urlFields['query'];
			$this->path .= '?' . $this->query;
		}
		
		if (isset($urlFields['fragment']))
		{
			$this->fragment = $urlFields['fragment'];
			$this->path .= '#' . $this->fragment;
		}
	}

}
