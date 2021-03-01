<?php
namespace Application\TVDB;

use Library\Error;
use Library\Error\AddMessages;

/*
 *		Application\TVDB\Messages is copyright � 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\Messages.
 *
 * The Application\TVDB\Messages class initializes the custom error codes and associated messages for the Application\TVDB class
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Application
 * @subpackage TVDB
 */
class Messages extends AddMessages
{
	/**
	 * errorList
	 *
	 * Error name to error message array
	 * @var array $errorList
	 */
	private	$errorList =
			  array('TVDB_Unknown'			=> 'Unknown or unspecified TVDB error',
					'TVDB_NoZipUrls'		=> 'Missing or empty ZIP URLs',
					'TVDB_NoBannerUrls'		=> 'Missing or empty BANNER URLs',
					'TVDB_NoXmlUrls'		=> 'Missing or empty XML URLs',
					'TVDB_InvalidLanguage'	=> 'Invalid language name or abbreviation',
					);

	/**
	 * __construct
	 *
	 * Class constructor
	 * Register custom error messages with the error code/message system
	 * @param array $additionalErrors = (optional) additonal error messages array
	 */
	public function __construct($additionalErrors=null)
	{
		if (($additionalErrors !== null) && is_array($additionalErrors))
		{
			$this->errorList = array_merge($this->errorList, $additionalErrors);
		}

		parent::__construct('TVDB', $this->errorList);
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

}
