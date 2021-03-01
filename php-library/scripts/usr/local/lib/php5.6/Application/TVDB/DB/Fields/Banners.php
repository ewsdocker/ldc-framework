<?php
namespace Application\TVDB\DB\Fields;

use Application\TVDB\DB\DbFields;
use Application\TVDB\DB\Fields\FieldDescriptor;
use Application\TVDB\DB\Exception;

use Library\Properties;
use Library\Utilities\FormatVar;

/*
 *		Application\TVDB\DB\Fields\Banners is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\DB\Fields\Banners
 *
 * TVDB Banners Field definitions
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage DB
 */
class Banners extends DbFields
{
	/*
	 * 		Field Name			Field Type		Null Allowed
	 * 
	 * 		recordNumber		int(7)				No
	 * 		id					int(7)				NO
	 * 
	 * 		SeriesId			int(7),				NO
	 * 		Series				varchar(256),		YES
	 * 		Season				int(3)				YES
	 * 
	 * 		BannerPath			varchar(1024)		NO
	 * 		BannerType			varchar(16)			NO
	 * 		BannerType2			varchar(256)		YES
	 * 		Language			char(2)				NO
	 * 		SeriesName			int(1)				YES
	 * 		Colors				varchar(16)			YES
	 * 
	 * 		ThumbnailPath		varchar(1024)		YES
	 * 		VignettePath		varchar(1024)		YES
	 * 
	 * 		Rating				decimal(7.4)		YES
	 * 		RatingCount			int(7)				NO
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
		parent::__construct('Banners');

		/*
		 *     add($fieldName,      $fieldDefinition,        $nullAllowed,  $deaultValue);
		 */
		$this->add('recordNumber',	'int(7) auto_increment', false, 		0);
		
		$this->add('id', 			'int(7)', 				 false, 		0);
		$this->add('SeriesId', 		'int(7)', 				 false, 		0);
		$this->add('Series', 		'varchar(256)', 		 true,  		null);
		$this->add('Season', 		'int(3)', 				 true,  		null);
		$this->add('BannerPath', 	'varchar(1024)', 		 true,  		null);
		$this->add('BannerType', 	'varchar(16)', 			 false, 		'series');
		$this->add('BannerType2', 	'varchar(256)', 		 true,  		null);
		$this->add('Language', 		'char(2)', 				 false, 		'en');
		$this->add('SeriesName', 	'tinyint(1)',			 true,  		null);
		$this->add('Colors', 		'varchar(16)', 			 true,  		null);
		$this->add('ThumbnailPath',	'varchar(1024)', 		 true,  		null);
		$this->add('VignettePath', 	'varchar(1024)', 		 true,  		null);
		$this->add('Rating', 		'decimal(7.4)', 		 true,  		null);
		$this->add('RatingCount', 	'int(7)', 				 true,  		null);
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
