<?php
namespace Application\TVDB;

use Library\Error;
use Library\HTTP\Client;

/*
 *		Application\TVDB\GetUpdateTime is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\GetUpdateTime
 *
 * Get DB Update Time as time last processed
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage TVDB
 */
class GetUpdateTime extends Client
{
	/**
	 * timestamp
	 *
	 * Unix timestamp returned from TVDB api
	 * @var integer $timestamp
	 */
	protected $timestamp;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $url = TVDB Mirrors API URL
	 * @throws Application\TVDB\Exception
	 */
	public function __construct($url)
	{
		parent::__construct('curl', $url, null, 'get');

		$result = $this->send();

		if ($result['status'] !== 200)
		{
			throw new Exception(Error::code('ClientRequestFailed'));
		}

		$time = explode("\n", $result['body']);
		if (($time[1] !== '<Items>') || (substr($time[2], 0, 6) !== '<Time>'))
		{
			throw new Exception(Error::code('ClientInvalidResponse'));
		}

		if ((($start = strpos($time[2], '<Time>')) === false) || (($end = strpos($time[2], '</Time>', $start)) === false))
		{
			throw new Exception(Error::code('ClientInvalidResponse'));
		}

		$this->timestamp = substr($time[2], $start + 6, $end - $start - 6);
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * __toString
	 *
	 * Returns a printable list of properties and values
	 * @return string $buffer = list
	 */
	public function __toString()
	{
		return sprintf("timestamp = %u", $this->timestamp);
	}

	/**
	 * timestamp
	 *
	 * Return the timestamp entry
	 * @return integer $timestamp
	 */
	public function timestamp()
	{
		return $this->timestamp;
	}

}
