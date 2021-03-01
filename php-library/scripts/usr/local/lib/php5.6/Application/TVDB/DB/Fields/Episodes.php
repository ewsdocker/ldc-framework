<?php
namespace Application\TVDB\DB\Fields;

use Application\TVDB\DB\DbFields;
use Application\TVDB\DB\Fields\FieldDescriptor;
use Application\TVDB\DB\Exception;

use Library\Properties;
use Library\Utilities\FormatVar;

/*
 *		Application\TVDB\DB\Fields\Episodes is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\DB\Fields\Episodes
 *
 * TVDB Episode Field definitions
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage DB
 */
class Episodes extends DbFields
{
	/*
	 * 		Field Name				Field Type		Null Allowed
	 * 
	 * 		recordNumber			int(7)				no
	 * 
	 * 		id						int(7)				NO
	 * 		seasonid				int(7)				no
	 * 		seriesid				int(7)				no
	 * 
	 * 		Language				char(2)				no
	 * 		EpisodeName				varchar(256)		no
	 * 		EpisodeNumber			int(7)				no
	 * 		SeasonNumber			int(7)				no
	 * 
	 * 		Combined_episodenumber	int(7)				yes
	 * 		Combined_season			int(7)				yes
	 * 
	 * 		Overview				varchar(1024)		yes
	 * 
	 * 		Director				varchar(1024)		yes
	 * 		Writer					varchar(1024)		yes
	 * 		GuestStars				varchar(1024)		yes
	 * 
	 * 		Rating					decimal(7.4)		yes
	 * 		RatingCount				int(7)				yes
	 * 		IMDB_ID					varchar(64)			yes
	 * 		ProductionCode			varchar(128)		yes
	 * 
	 * 		filename				varchar(256)		yes
	 * 		EpImgFlag				int(7)				yes
	 * 		thumb_added				varchar(16)			yes
	 * 		thumb_height			int(7)				yes
	 * 		thumb_width				int(7)				yes
	 * 
	 * 		FirstAired				char(10)			yes
	 * 		airsafter_season		int(7)				yes
	 * 		airsbefore_episode		int(7)				yes
	 * 		airsbefore_season		int(7)				yes
	 * 
	 * 		DVD_episodenumber		int(7)				yes
	 * 		DVD_season				int(7)				yes
	 * 
	 * 		absolute_number			int(7)				yes
	 * 		lastupdated				int(10)				yes
	 * 
	 */

	/**
	 * __construct
	 * 
	 * Class constructor
	 * @throws \Library\MySql\Exception, \Library\DBO\Exception
	 */
	public function __construct()
	{
		parent::__construct('Episodes');

		/*
		 *     add($fieldName,      $fieldDefinition,        $nullAllowed,  $deaultValue);
		 */
		$this->add('recordNumber', 				'int(7) auto_increment', false, 		0);

		$this->add('id',		 				'int(7)',			 	 false, 		0);
		$this->add('seasonid',					'int(7)', 				 false,  		0);
		$this->add('seriesid',					'int(7)', 				 false,  		0);

		$this->add('Language',					'char(2)', 				 false,  		'en');

		$this->add('EpisodeName',				'varchar(256)',			 false,  		'');
		$this->add('EpisodeNumber',				'int(7)', 				 false,  		0);
		$this->add('SeasonNumber',				'int(7)', 				 false,  		0);

		$this->add('Combined_episodenumber',	'int(7)', 	 			 false,  		0);
		$this->add('Combined_season',			'int(7)', 	 			 false,  		0);
		
		$this->add('Overview',					'varchar(1024)',		 true,  		null);
		$this->add('Director',					'varchar(1024)',		 true,  		null);
		$this->add('Writer',					'varchar(1024)',		 true,  		null);
		$this->add('GuestStars',				'varchar(1024)',		 true,  		null);
		
		$this->add('Rating',					'decimal(7.4)',			 true,  		0.0);
		$this->add('RatingCount',				'int(7)', 				 true,  		0);
		
		$this->add('IMDB_ID',					'varchar(64)',		 	 true,  		null);
		$this->add('ProductionCode',			'varchar(128)',		 	 true,  		null);

		$this->add('filename',					'varchar(256)',			 true,  		null);
		$this->add('EpImgFlag',					'int(7)',				 true,  		null);
		$this->add('thumb_added',				'varchar(16)',			 true,  		null);
		$this->add('thumb_height',				'int(7)',				 true,  		null);
		$this->add('thumb_width',				'int(7)',				 true,  		null);
		
		$this->add('FirstAired',				'char(10)',				 true,  		null);
		$this->add('airsafter_season',			'int(7)',				 true,  		null);
		$this->add('airsbefore_episode',		'int(7)',				 true,  		null);
		$this->add('airsbefore_season',			'int(7)',				 true,  		null);

		$this->add('DVD_episodenumber',			'int(7)',				 true,  		null);
		$this->add('DVD_season',				'int(7)',				 true,  		null);
		$this->add('absolute_number',			'int(7)',				 true,  		null);
		$this->add('lastupdated',				'int(10)',				 true,  		null);

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

}
