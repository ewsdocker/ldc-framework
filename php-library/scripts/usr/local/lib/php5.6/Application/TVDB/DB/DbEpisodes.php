<?php
namespace Application\TVDB\DB;

use Library\DBO\DBOConstants as DBOConstants;
use Library\Error;
use Library\MySql\Table;
use Library\MySql\Table\Descriptor;
use Application\TVDB\Exception;
use Library\Properties;
use Library\Utilities\FormatVar;

/*
 *		Application\TVDB\DB\DbEpisodes is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\DB\DbEpisodes
 *
 * TVDB Episodes Data Record(s)
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage DB
 */
class DbEpisodes extends TVDBLib
{
	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $dsn = Data Source Name (mysql:host=<hostname>;port=<connection port>;dbname=<database name>)
	 * @param string $user = user name
	 * @param string $password = password
	 * @param array  $options = (optional) options array
	 * @throws \Library\MySql\Exception, \Library\DBO\Exception
	 */
	public function __construct($dsn, $user, $password, $options=array())
	{
		parent::__construct('Episodes', $dsn, $user, $password, $options);
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
