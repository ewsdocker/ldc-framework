<?php
namespace Application\TVDB\Series;

use Library\Compress\GetZip as CompressGetZip;
use Library\Utilities\FormatVar;

/*
 *		Application\TVDB\Series\GetZip is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\Series\GetZip
 *
 * TVDB Series\GetZip API interface
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage TVDB
 */
class GetZip extends CompressGetZip
{
	/**
	 * sourceUrl
	 *
	 * URL of the zip file to download
	 * @var string $sourceUrl
	 */
	protected $sourceUrl;

	/**
	 * zipUrl
	 *
	 * URL of the zip folder to download the file to
	 * @var string $zipUrl
	 */
	protected $zipUrl;

	/**
	 * zipExtractUrl
	 *
	 * URL of the zip extract base folder, or null to not extract
	 * @var string $sourceUrl
	 */
	protected $zipExtractUrl;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $sourceUrl = url of the zip file to get
	 * @param string $zipUrl = url of the location to store the file (path + file name)
	 * @param string $extractUrl = (optional) url of the location to extract the zip to, null to not extract
	 * @throws Library\Compress\Exception
	 */
	public function __construct($sourceUrl, $zipUrl, $extractUrl=null)
	{
		$path = explode('/', $sourceUrl);

		$zipFileName = array_pop($path);
		$fields = explode('.', $zipFileName);
		$fields[1] = 'zip';
		$zipFileName = implode('.', $fields);
		array_push($path, $zipFileName);

		$this->sourceUrl = implode('/', $path);

		$series = $path[count($path) - 3];

		$zipUrl = $this->zipUrl(sprintf('%s/%s.zip', $zipUrl, $series));

		if ($extractUrl !== null)
		{
			$extractUrl = $this->zipExtractUrl(sprintf('%s/%s', $extractUrl, $series));
		}

		parent::__construct($sourceUrl, $zipUrl, $extractUrl);
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

		FormatVar::showData(get_object_vars($this), 'GetZip');
	}

	/**
	 * sourceUrl
	 *
	 * Get the sourceUrl property
	 * @return string $sourceUrl = current source url
	 */
	public function sourceUrl()
	{
		return $this->sourceUrl;
	}

	/**
	 * zipUrl
	 *
	 * Get/set the zipUrl property
	 * @param string|null $zipUrl = (optional) zip Url, null to query
	 * @return string $zipUrl = current zip url
	 */
	public function zipUrl($zipUrl=null)
	{
		if ($zipUrl !== null)
		{
			$this->zipUrl = $zipUrl;
		}

		return $this->zipUrl;
	}

	/**
	 * zipExtractUrl
	 *
	 * Get/set the zipExtractUrl property
	 * @param string|null $zipExtractUrl = (optional) zip Extract Url, null to query
	 * @return string $zipExtractUrl = current zip Extract url
	 */
	public function zipExtractUrl($zipExtractUrl=null)
	{
		if ($zipExtractUrl !== null)
		{
			$this->zipExtractUrl = $zipExtractUrl;
		}

		return $this->zipExtractUrl;
	}

}
