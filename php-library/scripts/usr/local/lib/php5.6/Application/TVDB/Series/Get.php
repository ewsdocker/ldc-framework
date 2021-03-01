<?php
namespace Application\TVDB\Series;

use Application\TVDB\Records\Episode as EpisodeRecord;
use Application\TVDB\Records\Series as SeriesRecord;
use Library\Utilities\FormatVar;
use Library\XML\LoadXML;

/*
 *		Application\TVDB\Series\Get is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\Series\Get
 *
 * TVDB Series\Get API interface
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage TVDB
 */
class Get extends LoadXML
{
	/**
	 * firstParse
	 *
	 * True if no items processed yet, else set to false
	 * @var boolean $firstParse
	 */
	private $firstParse;

	/**
	 * series
	 *
	 * The series record contents
	 * @var object $series
	 */
	protected $series;

	/**
	 * episodes
	 *
	 * An array of episode records
	 * @var array $episode
	 */
	protected $episodes;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $url = TVDB Full Series request
	 * @throws Library\XML\Exception
	 */
	public function __construct($url)
	{
		$this->firstParse = true;

		parent::__construct($url, array($this, 'parseToArray'));

		if ($this->records)
		{
			$this->episodes = $this->records;
			$this->series = array(array_shift($this->episodes));
		}
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

		if ($this->firstParse)
		{
			$this->firstParse = false;
			return new SeriesRecord($child);
		}

		return new EpisodeRecord($child);
	}

	/**
	 * seriesRecord
	 *
	 * Return the series record
	 * @return object $seriesRecord
	 */
	public function seriesRecord()
	{
		return $this->series;
	}

	/**
	 * episodeRecords
	 *
	 * Return an array of episode records
	 * @return array $episodeRecords = array of episodeRecords
	 */
	public function episodeRecords()
	{
		return $this->episodes;
	}

}
