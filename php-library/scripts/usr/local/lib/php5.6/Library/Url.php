<?php
namespace Library;

/*
 *		Url is copyright © 2012, 2013. EarthWalk Software.
 *		Licensed under the Academic Free License version 3.0.
 *		Refer to the file named License.txt provided with the source, 
 *			  or from http://opensource.org/licenses/academic.php
 */
/**
 * Library\Url.
 *
 * Static convenience class to parse the supplied url using the Url\Parse class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Url
 */
class Url
{
	/**
	 * instance
	 * 
	 * Static variable containing the current instance of Url\Parse class, or null.
	 * @var object $instance
	 */
	private	static $instance;

	/**
	 * __construct
	 * 
	 * PRIVATE class constructor - creates and instance of the Url\Parse class and enforces singleton static class
	 */
	private function __construct()
	{
		self::$instance = new Url\Parse();
	}

	/**
	 * instance
	 *
	 * Create a class instance, if none exists, and return it's reference
	 * @return object $instance = singleton class instance.
	 * @throws Library\Url\Exception
	 */
	protected static function instance()
	{
		if (self::$instance == null)
		{
			new Url();
		}
		
		return self::$instance;
	}

	/**
	 * url
	 *
	 * Primary entry point to the parser.
	 * set (and parse) / get url
	 * @param string $url = (optional) url to set (and parse), null to query only
	 * @return string $url
	 */
	public static function url($url=null)
	{
		return self::instance()->url($url);
	}

	/**
	 * host
	 *
	 * get host name/adress
	 * @return string $host
	 */
	public static function host()
	{
		return self::instance()->host();
	}

	/**
	 * port
	 *
	 * get port number
	 * @return integer $port
	 */
	public static function port()
	{
		return self::instance()->port();
	}

	/**
	 * document
	 *
	 * Returns document location
	 * @return string $document
	 */
	public static function document()
	{
		return self::instance()->document();
	}

	/**
	 * path
	 *
	 * get path
	 * @return string $path
	 */
	public static function path()
	{
		return self::instance()->path();
	}

	/**
	 * scheme
	 *
	 * get the protocol scheme
	 * @return string $scheme
	 */
	public static function scheme()
	{
		return self::instance()->scheme();
	}

	/**
	 * query
	 *
	 * get the query
	 * @return string $query
	 */
	public static function query()
	{
		return self::instance()->query();
	}

	/**
	 * fragment
	 *
	 * get the fragment
	 * @return string $fragment
	 */
	public static function fragment()
	{
		return self::instance()->fragment();
	}

}
