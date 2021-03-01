<?php
namespace Library\Directory;
use Library\Error;
use Library\PrintU;

/*
 *		Directory\Search is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *		Refer to the file named License.txt provided with the source, 
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * Director\Search
 *
 * Directory search class
 * @author Jay Wheeler
 * @version 1.0
 * @copyright © 2012, 2013 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Directory
 */
class Search extends Contents
{
 	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $name = name of the file(s) to search for
	 * @param string $directoryName = the name of the directory to search in
	 * @param resource $context = a stream resource context, null for none
	 * @throws Directory\Exception
	 */
	public function __construct($directoryName, $context=null)
	{
		parent::__construct($directoryName, SCANDIR_SORT_NONE, $context);
	}

	/**
	 * __destruct
	 *
	 * Destroy the current class instance
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * select
	 * 
	 * Search the directory list array for all files matching the specified pattern
	 * @param string $pattern = pattern to match
	 * @return array $files = matching files
	 */
	public function select($pattern)
	{
		$directoryList = $this->directory;
		$selected = array();
		foreach($directoryList as $key => $name)
		{
			if (fnmatch($pattern, $name, FNM_CASEFOLD | FNM_PERIOD))
			{
				array_push($selected, $name);
			}
		}

		return $selected;
	}

}
