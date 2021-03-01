<?php
namespace Library;
use Library\Error;
/*
 * 		cUrl\Multi is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * cUrl.
 *
 * cUrl multi-client.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage cUrl
 */
class Multi extends \Library\cUrl
{
	/**
	 * multiClient
	 *
	 * multi-client handle
	 * @var handle $multiClient
	 */
	private		$multiClient;

	/**
	 * multiError
	 *
	 * The last multi-client error recorded
	 * @var integer $multiError
	 */
	private		$multiError;

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
		parent::__construct($uri, $config, $method);
		if (! $this->multiClient = curl_multi_init())
		{
			throw new Exception(Error::code('PhpExtensionNotAvailable'));
		}
	}

	/**
	 * __destruct
	 *
	 * Class destructor.
	 */
	public function __destruct()
	{
		curl_close($this->multiClient);
		parent::__destruct();
	}

	/**
	 * add_handle
	 *
	 * Add a normal cURL handle to a cURL multi handle
	 * @param resource $client = cURL object to add
	 * @return integer 0
	 * @throws \Library\cUrl\Exception
	 */
	public function add_handle($client)
	{
		$result = curl_multi_add_handle($this->multiClient, $client);
		if (($result = curl_multi_add_handle($this->multiClient, $client)) != 0)
		{
			$this->multiError = $result;
			throw new Exception(Error::code('CurlAddHandle'));
		}

		return $result;
	}

	/**
	 * exec
	 *
	 * Run the sub-connections of the current cURL handle
	 * @param integer $stillRunning = address of location to store the stillRunning flag state
	 * @return integer $error = cURL error code
	 */
	public function exec(&$stillRunning)
	{
		return curl_multi_exec($this->multiClient, $stillRunning);
	}

	/**
	 * getcontent
	 *
	 * Return the content of a cURL handle if CURLOPT_RETURNTRANSFER is set
	 * @param resource $client = client handle of multi-client to query
	 * @return string $content
	 */
	public function getcontent($client)
	{
		return curl_multi_getcontent($client);
	}

	/**
	 * info_read
	 *
	 * Get information about the current transfers
	 * @param integer $msgs_in_queue = (optional) address to store a count of the messages in the queue
	 * @return array $result = associative array of read info
	 * @throws \Library\cUrl\Exception
	 */
	public function info_read(&$msgs_in_queue=null)
	{
		if (curl_multi_info_read($this->multiClient, $msgs_in_queue) === false)
		{
			throw new Exception(Error::code('CurlReadInfoError'));
		}

		return true;
	}

	/**
	 * remove_handle
	 *
	 * Remove a multi handle from a set of cUrl handles
	 * @param resource $client = cUrl client handle
	 * @return integer $descriptors
	 * @throws \Library\cUrl\Exception
	 */
	public function remove_handle($client)
	{
		$result = curl_multi_remove_handle($this->multiClient, $client);
		if ($result !== 0)
		{
			$this->multiError = $result;
			throw new Exception(Error::code('CurlRemoveHandle'));
		}

		return $result;
	}

	/**
	 * select
	 *
	 * Waits (blocks) for activity on any curl_multi connection
	 * @param integer $timeout = number of seconds to wait before activating timeout
	 * @return integer $descriptors
	 * @throws \Library\cUrl\Exception
	 */
	public function select($timeout = 1.0)
	{
		$result = curl_multi_select($timeout);
		if ($result == -1)
		{
			throw new Exception(Error::code('CurlSelectError'));
		}

		return $result;
	}

	/**
	 * multiError
	 *
	 * return a copy of the multi-error variable
	 * @return integer $multiError
	 */
	public function multiError()
	{
		return $this->multiError;
	}

	/**
	 * multiClient
	 *
	 * get a copy of the multiClient resource
	 * @return handle $multiClient
	 */
	public function multiClient()
	{
		return $this->multiClient;
	}

}
