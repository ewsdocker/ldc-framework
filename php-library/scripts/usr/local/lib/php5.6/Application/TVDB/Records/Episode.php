<?php
namespace Application\TVDB\Records;

/*
 *		Application\TVDB\Records\Episode is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\Records\Episode
 *
 * TVDB Episode Base Record information
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage Records
 */
class Episode extends Base
{
	/*
	 * 	The following properties MUST always be present in the record:
	 *
	 * 		id				TVDB unique episode id
	 *
	 * 		seasonid		TVDB unique season id
	 * 		seriesid		TVDB unique series id
	 *
	 * 		Language		2-character language name
	 * 		EpisodeName		The name of the episode
	 * 		EpisodeNumber	The number of the episode
	 * 		SeasonNumber	Season Number
	 *
	 * 		Combined_episodenumber	Returns DVD_episodenumber (if not null), otherwise EpisodeNumber
	 * 		Combined_season			Returns DVD_season (if not null), otherwise SeasonNumber
	 *
	 * The following properties are OPTIONAL and may not be present, or may be set to null
	 *
	 * 		Overview				String containing the overview in Language text
	 *
	 * 		Director				List of directors (delimited by | )
	 * 		Writer					List of writers (delimited by | )
	 * 		GuestStars				List of guest stars (delimited by | )
	 *
	 * 		Rating					Series rating
	 * 		RatingCount				Number of ratings
	 * 		IMDB_ID					String containing IMDB id
	 * 		ProductionCode
	 *
	 * 		filename				Relative location of the episode image.
	 * 		EpImgFlag				Episode proper image flag (if 1 or 2, otherwise not)
	 * 		thumb_added				String containing time episode image was added
	 * 		thumb_height			Height of the episode image in pixels
	 * 		thumb_width				Width of the episode image in pixels
	 *
	 * 		FirstAired				Date the episode first aired
	 * 		airsafter_season		For special episodes, the season this special airs after
	 * 		airsbefore_episode		For special episodes, the episode this special airs before
	 * 		airsbefore_season		For special episodes, the season this special airs before
	 *
	 * 		DVD_chapter				<unusable info - usually null>
	 * 		DVD_discid				<unusable info - usually null>
	 * 		DVD_episodenumber		Used for combined episodes
	 * 		DVD_season				Season number (may be different than SeasonNumber)
	 *
	 * 		absolute_number			Absolute Episode number in the series
	 * 		lastupdated				Unix timestamp of last update to this episode
	 *
	 */

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param mixed $properties = array or properties object containing the file info
	 */
	public function __construct($properties)
	{
		parent::__construct($properties);
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
	 * Return printable string
	 * @return string $buffer
	 */
	public function __toString()
	{
		$buffer = '';
		$this->createBuffer($buffer);
		$buffer .= "\n";

		return $buffer;
	}

}
