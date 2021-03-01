<?php
namespace Application\TVDB;

use Application\TVDB\Series\Get as GetSeries;
use Application\TVDB\Series\GetZip as GetZip;

use Application\TVDB\DB\DbActors;
use Application\TVDB\DB\DbBanners;
use Application\TVDB\DB\DbControl;
use Application\TVDB\DB\DbEpisodes;
use Application\TVDB\DB\DbLanguages;
use Application\TVDB\DB\DbSeries;

use Library\Error;
use Library\Utilities\FormatVar;
use Library\MySql\Db;
use Library\MySql\Table;

/*
 *		Application\TVDB\ZipApi is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\ZipApi
 *
 * TVDB Zip API interface
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Application
 * @subpackage TVDB
 */
class ZipApi extends Api
{
	/**
	 * zipSeries
	 *
	 * GetZip object instance, if not null
	 * @var object $zipSeries
	 */
	protected $zipSeries;

	/**
	 * zipUrl
	 *
	 * Url to place downladed zip files
	 * @var string $zipUrl
	 */
	protected $zipUrl;

	/**
	 * zipExtractUrl
	 *
	 * Base url to place extracted zip files
	 * @var string $zipExtractUrl
	 */
	protected $zipExtractUrl;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param object $properties = TVDB properties class instance
	 * @throws Application\TVDB\Exception, Library\DOM\Exception
	 */
	public function __construct($properties, $defaultProperties, $cliMap, $cliDbMap, $series=null)
	{
		parent::__construct($properties, $defaultProperties, $cliMap, $cliDbMap, $series);

		$this->zipSeries = null;

		$this->zipUrl = $this->ZipDirectory;
		$this->zipExtractUrl = $this->ZipExtractDirectory;
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
		FormatVar::recurse(true);

		$buffer = sprintf("%s:\n", get_class());

		$vars = get_object_vars($this);
		foreach($vars as $name => $value)
		{
			$buffer .= FormatVar::format($value, $name);
		}

		return $buffer;
	}

	/**
	 * getSeriesZip
	 *
	 * Get Series and Episode(s), along with all Banners and Actors records, in zip format and extract
	 * @param string|integer $seriesId = TVDB series identifier
	 * @param string $zipUrl = (optional) url to store downloaded zip file
	 * @param string $extractUrl = (optional) url base location to store extracted files to
	 */
	public function getSeriesZip($seriesId, $zipUrl=null, $extractUrl=null)
	{
		if ($this->zipSeries)
		{
			unset($this->zipSeries);
		}

		$seriesId = $this->seriesId($seriesId);

		$sourceUrl = $this->createSeriesUrl($seriesId, '.zip');
		$zipUrl = $this->zipUrl($zipUrl);
		$extractUrl = $this->zipExtractUrl($extractUrl);

		$this->zipSeries = new GetZip($sourceUrl, $zipUrl, $extractUrl);

		$this->readSeriesZip(sprintf('%s.xml', $this->control->UserLanguage));
		$this->tvdbFullSeries = new GetSeries($this->xmlBuffer);

		return $this->zipSeries;
	}

	/**
	 * insertZipBanners
	 *
	 * Insert banner data from the banners.xml zip Entry
	 */
	public function insertZipBanners()
	{
		$this->readSeriesZip('banners.xml');
		$bannersData = new Banners($this->xmlBuffer);

		$this->insertBanners($bannersData);

		unset($bannersData);
	}

	/**
	 * insertZipActors
	 *
	 * Insert actors data from the actors.xml entry of the zip file
	 */
	public function insertZipActors()
	{
		$this->readSeriesZip('actors.xml');
		$actorsData = new Actors($this->xmlBuffer);

		$this->insertActors($actorsData);

		unset($actorsData);
	}

	/**
	 * readSeriesZip
	 *
	 * read the entry records from the specified xml entry in the current zip file
	 * @param string $entryName = name of the zip file entry to process
	 * @throws Exception
	 */
	public function readSeriesZip($entryName)
	{
		$this->checkZipOpen();

		$this->xmlBuffer = $this->zipSeries->getFromName($entryName);
		if ($this->xmlBuffer === false)
		{
			throw new Exception(Error::code('ZipArchiveNotZipFile'));
		}

		return $this->xmlBuffer;
	}

	/**
	 * checkZipOpen
	 *
	 * Throws exception if the zip file is not open
	 * @throws Exception
	 */
	protected function checkZipOpen()
	{
		if (! $this->zipSeries)
		{
			throw new Exception(Error::code('FileNotOpen'));
		}
	}

	/**
	 * zipSeries
	 *
	 * Returns the zipSeries property
	 * @return object|null $zipSeries = GetZip object instance, or null if none
	 */
	public function zipSeries()
	{
		return $this->zipSeries;
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
