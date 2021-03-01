<?php
namespace Library;
use Library\Error;

/*
 * 		cUrl is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * cUrl.
 *
 * cUrl client.
 * @author Jay Wheeler.
 * @version 1.1
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage cUrl
 */
class cUrl
{
	/**
	 * client
	 *
	 * cURL resource
	 * @var resource $client
	 */
	private		$client;

	/**
	 * __construct
	 *
	 * Class constructor.
	 * @param string  $uri = (optional) address of the CLIENT website
	 * @param string $config = (optional) connection configuration array
	 * @return object = new class object reference
	 * @throws \Library\cUrl\Exception
	 */
	public function __construct($uri=null, $config=null, $method='get')
	{
		if (! $this->client = curl_init())
		{
			throw new Exception(Error::code('PhpExtensionNotAvailable'));
		}

		if ($uri)
		{
			$this->setopt(CURLOPT_URL, $uri);
		}

		if ($config)
		{
			if (is_array($config))
			{
				$this->setopt_array($config);
			}
			elseif (is_string($config))
			{
				$this->setopt($config);
			}
			else
			{
				throw new Exception(Error::code('ArrayVariableExpected'));
			}
		}

		if (strtolower($method) !== 'get')
		{
			$this->setopt(CURLOPT_POST, true);
		}
	}

	/**
	 * __destruct
	 *
	 * Class destructor.
	 */
	public function __destruct()
	{
		$this->close();
	}
	
	/**
	 * close
	 * 
	 * Close the current session and void the handle
	 */
	public function close()
	{
		curl_close($this->client);
	}

	/**
	 * copy_handle
	 *
	 * Copy a cURL handle along with all of its preferences
	 * @return handle $client
	 */
	public function copy_handle()
	{
		return curl_copy_handle($this->client);
	}

	/**
	 * errno
	 *
	 * get the latest error number
	 * @return integer $error
	 */
	public function errno()
	{
		return curl_errno($this->client);
	}

	/**
	 * error
	 *
	 * Return a string containing the last error for the current session
	 * @return string $error
	 */
	public function error()
	{
		return curl_error($this->client);
	}

	/**
	 * exec
	 *
	 * Execute the cURL session
	 * @return mixed $result
	 */
	public function exec()
	{
		return curl_exec($this->client);
	}

	/**
	 * getinfo
	 *
	 * Get information regarding a specific transfer
	 * @param integer $opt = (optional) cURL option
	 * @return string $result
	 */
	public function getinfo($opt=0)
	{
		return curl_getinfo($this->client, $opt);
	}

	/**
	 * setopt
	 *
	 * set cURL option value
	 * @param integer $option = option to set
	 * @param mixed $value = value to set option to
	 * @throws \Library\cUrl\Exception
	 */
	public function setopt($option, $value)
	{
		if (! curl_setopt($this->client, $option, $value))
		{
			throw new Exception(Error::code('CurlSetOption'));
		}
	}

	/**
	 * setopt_array
	 *
	 * Set multiple options for a cURL transfer
	 * @param array $options = associative array of option/value pairs
	 * @throws \Library\cUrl\Exception
	 */
	public function setopt_array($options)
	{
		if (! curl_setopt_array($this->client, $options))
		{
			throw new Exception(Error::code('CurlSetOption'));
		}
	}

	/**
	 * version
	 *
	 * return the current cURL version
	 * @return array $versionInfo
	 */
	public function version($age=CURLVERSION_NOW)
	{
		return curl_version($age);
	}

}
