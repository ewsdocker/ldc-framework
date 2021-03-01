<?php
namespace Application\TVDB\DB\Fields;

use Application\TVDB\DB\DbFields;
use Application\TVDB\DB\Fields\FieldDescriptor;
use Application\TVDB\DB\Exception;

use Library\Properties;
use Library\Utilities\FormatVar;

/*
 *		Application\TVDB\DB\Fields\Series is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\DB\Fields\Series
 *
 * TVDB Series Field definitions
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage DB
 */
class Series extends DbFields
{
	/*
	 * 		Field Name		Field Type		Null Allowed
	 * 
	 * 		recordNumber	int(7)				no
	 * 		id				int(7)				NO
	 * 
	 * 		SeriesId		int(7)				no
	 * 		SeriesName		varchar(128)		yes
	 * 		Actors			varchar(256)		no
	 * 		Language		char(2)				no
	 * 
	 * 		banner			varchar(1024)		yes
	 * 		fanart			varchar(1024)		yes
	 * 		posters			varchar(1024)		yes
	 * 		
	 * 		Airs_DayOfWeek	varchar(128)		yes
	 * 		Airs_Time		varchar(10)			yes
	 * 
	 * 		FirstAired		char(10)			yes
	 * 		Runtime			int(4)				yes
	 * 		Status			char(10)			yes
	 * 
	 * 		ContentRating	char(5)				yes
	 * 		Genre			varchar(256)		yes
	 * 
	 * 		IMDB_ID			varchar(64)			yes
	 * 		zap2it_id		varchar(64)			yes
	 * 
	 * 		Network			varchar(64)			yes
	 * 		Overview		varchar(1024)		yes
	 * 
	 * 		Rating			decimal(7.4)		yes
	 * 		RatingCount		int(7)				yes
	 * 
	 * 		lastupdated		int(16)				yes
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
		parent::__construct('Series');

		/*
		 *     add($fieldName,  	    $fieldDefinition,        $nullAllowed,  $deaultValue);
		 */

		$this->add('recordNumber',		'int(7) auto_increment', false, 		0);
		$this->add('id',			 	'int(7)',			 	 false, 		0);

		$this->add('SeriesName',		'varchar(128)',		 	 true,  		null);
		$this->add('Actors',			'varchar(256)',			 false,  		'');
		$this->add('Overview',			'varchar(1024)',		 true,  		'');

		$this->add('Genre',				'varchar(256)',			 false,  		'');
		$this->add('Language',			'char(2)', 				 false,  		'en');

		$this->add('banner',			'varchar(1024)',		 false,  		'');
		$this->add('fanart',			'varchar(1024)',		 false,  		'');
		$this->add('posters',			'varchar(1024)',		 false,  		'');

		$this->add('Airs_DayOfWeek',	'varchar(128)',			 false,  		'');
		$this->add('Airs_Time',			'varchar(10)',			 false,  		'');
		$this->add('FirstAired',		'char(10)',				 false,  		'');

		$this->add('Runtime',			'int(4)',				 false,  		'');
		$this->add('Status',			'char(10)',				 false,  		'');
		
		$this->add('ContentRating',		'char(5)',				 false,  		'');

		$this->add('IMDB_ID',			'varchar(64)',		 	 true,  		null);
		
		$this->add('zap2it_id',			'varchar(64)',			 true,  		'');

		$this->add('Network',			'varchar(64)',			 true,  		'');

		$this->add('Rating',			'decimal(7.4)',			 true,  		0.0);
		$this->add('RatingCount',		'int(7)', 				 true,  		0);
		
		$this->add('lastupdated',		'int(10)',				 true,  		null);
		
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
