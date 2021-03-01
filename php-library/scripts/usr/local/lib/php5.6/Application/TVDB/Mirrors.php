<?php
namespace Application\TVDB;

use Library\Error;
use Library\Utilities\FormatVar;
use Application\TVDB\Records\Mirror;
use Library\XML\LoadXML;

/*
 *		Application\TVDB\Mirrors is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\Mirrors
 *
 * TVDB Mirrors API interface
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage TVDB
 */
class Mirrors extends LoadXML
{
	/**
	 * xmlUrl
	 * 
	 * The url of the TVDB XML API server
	 * @var string $xmlUrl
	 */
	public $xmlUrl;

	/**
	 * bannerUrl
	 * 
	 * The url of the TVDB Banners server
	 * @var string $bannerUrl
	 */
	public $bannerUrl;
	
	/**
	 * zipUrl
	 * 
	 * The url of the TVDB Zips API server
	 * @var string $zipUrl
	 */
	public $zipUrl;

	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param string $url = TVDB Mirrors API URL
	 * @throws Application\TVDB\Exception
	 */
	public function __construct($url)
	{
		parent::__construct($url, array($this, 'parseToArray'));
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
		FormatVar::sort(false);
		FormatVar::sortValues(false);
		FormatVar::recurse(false);
		FormatVar::formatted(true);

		return parent::__toString();
	}

	/**
	 * parseMirrors
	 * 
	 * Parse the mirrors records and produce the Url arrays and the random keys
	 */
	public function parseMirrors()
	{
		$bannersUrl = array();
		$zipsUrl = array();
		$xmlsUrl = array();
		
		foreach($this->records as $mirrorRecord)
		{
			if (($mirrorRecord->typemask / 4) % 2)
			{
				array_push($zipsUrl, $mirrorRecord->mirrorpath);
			}
		
			if (($mirrorRecord->typemask / 2) % 2)
			{
				array_push($bannersUrl, $mirrorRecord->mirrorpath);
			}
		
			if ($mirrorRecord->typemask % 2)
			{
				array_push($xmlsUrl, $mirrorRecord->mirrorpath);
			}
		}
		
		$this->xmlUrl    = $this->randomUrl($xmlsUrl, 'TVDB_NoXmlUrls');
		$this->zipUrl    = $this->randomUrl($zipsUrl, 'TVDB_NoZipUrls');
		$this->bannerUrl = $this->randomUrl($bannersUrl, 'TVDB_NoBannerUrls');
		
		return array('xml'    => $this->xmlUrl,
					 'zip'    => $this->zipUrl,
					 'banner' => $this->bannerUrl);
	}

	/**
	 * randomUrl
	 * 
	 * Select a random url from the array and return it
	 * @param array $urlArray = array of url's to select from
	 * @param string $error = error name, if an exception is thrown
	 * @return string $url = selected url
	 * @throws Application\TVDB\Exception if the array is empty
	 */
	public function randomUrl($urlArray, $error)
	{
		if (count($urlArray) == 0)
		{
			throw new Exception(Error::code($error));
		}
		
		if (count($urlArray) == 1)
		{
			return $urlArray[0];
		}
		
		$index = rand(1, count($urlArray));
		return $urlArray[$index - 1];
	}

	/**
	 * xmlUrl
	 * 
	 * Returns the current XML URL property
	 * @return string $xmlUrl
	 */
	public function xmlUrl()
	{
		return $this->xmlUrl;
	}

	/**
	 * bannerUrl
	 * 
	 * Returns the current BANNER URL property
	 * @return string $bannerUrl
	 */
	public function bannerUrl()
	{
		return $this->bannerUrl;
	}

	/**
	 * zipUrl
	 * 
	 * Returns the current ZIP URL property
	 * @return string $zipUrl
	 */
	public function zipUrl()
	{
		return $this->zipUrl;
	}

	/**
	 * parseToArray
	 * 
	 * Callback function to parse the currently selected xml element
	 * @param array $args = callback function arguement in an array
	 * @return object $value = value to store in the records array
	 */
	public function parseToArray($args)
	{
		$child = array();

		foreach($args[0] as $key => $value)
		{
			if ($value->count() == 0)
			{
				$child[$key] = (string)$value;
			}
		}

		return new Mirror($child);
	}

}
