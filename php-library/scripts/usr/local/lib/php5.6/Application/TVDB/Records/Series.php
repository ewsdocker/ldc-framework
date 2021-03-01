<?php
namespace Application\TVDB\Records;

/*
 *		Application\TVDB\Series\Records\Series is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\Records\Series
 *
 * TVDB Series Base Record information
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage Records
 */
class Series extends Base
{
	/*
	 * 	The following properties MUST always be present in the record:
	 *
	 * 		id				TVDB unique series id
	 * 		Actors			List of series actors (delimited by | )
	 * 		Language		2 character language abbreviation
	 *
	 * The following properties are OPTIONAL and may not be present, or may be set to null
	 *
	 * 		banner			Relative location of the banner artwork
	 * 		fanart			Relative location of the fanart artwork
	 * 		posters			Relative location of the poster artwork
	 *
	 * 		Airs_DayOfWeek	Name of the week day
	 * 		Airs_Time		Time of day the series originally aired
	 *
	 * 		FirstAired		The date the series first aired
	 * 		Runtime			Series run time in minutes
	 * 		Status			"Continuing" or "Ended"
	 *
	 * 		ContentRating	US Rating System rating
	 * 		Genre			List of genres (delimited by | )
	 *
	 * 		IMDB_ID			IMDB Identifier
	 * 		zap2it_id		String containing the zap2it identifier, or null
	 *
	 * 		Network			Network the series aired on
	 * 		NetworkId		<n/a>
	 *
	 * 		Overview		Overview of the series
	 *
	 * 		Rating			User's rating
	 * 		RatingCount		Number of ratings
	 *
	 * 		SeriesID		<n/a>
	 * 		SeriesName		The series name
	 *
	 * 		added			Date added to db
	 * 		addedBy			User adding series
	 *
	 * 		lastupdated		Last time series was updated
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
