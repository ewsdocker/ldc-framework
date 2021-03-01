<?php
namespace Application\TVDB\Records;

/*
 *		Application\TVDB\Records\Banner is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\Records\Banner
 *
 * TVDB Base Record Banner information
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage Records
 */
class Banner extends Base
{
	/*
	 * 	The following properties are present in the record:
	 *
	 * 		id					TVDB-assigned banner id (unique)
	 * 		BannerPath			Relative path to the banner (i.e. - the banner name)
	 * 		ThumbnailPath		Relative path to the banner (i.e. - the banner name) - only if BannerType is 'fanart'
	 * 		VignettePath		Relative path to the banner (i.e. - the banner name) - only if BannerType is 'fanart'
	 *
	 * 		BannerType			poster, fanart, series, season
	 * 		BannerType2			additional banner type info
	 *
	 * 		Language			2 character language code
	 * 		Season				Season number for banner (or blank)
	 *
	 * 		Rating				Banner rating (nn.nnnn), or null
	 * 		RatingCount			Number of raters
	 *
	 * 		SeriesName			for BannerType = 'fanart', boolean true if name on the banner, false if not
	 *
	 * 		Colors				Null or 3 RGB decimal colors (delimited by | )
	 *
	 */

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param mixed $properties = array or properties object containing the Banner Record
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
