<?php
namespace Library\Compress;
use Library\Error;

/*
 * 		Compress\AdapterZlib is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * AdapterZlib.
 *
 * Compress AdapterZlib class to provide object oriented interface to the Zlib compression extensions
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Compress
 */
class AdapterZlib extends AdapterZlibBase
{
	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string   $fileName       = (optional) name of the file associated with this class instance
	 * @param string   $mode           = (optional) file open mode (default = 'rb')
	 * @param boolean  $useIncludePath = (optional) true = search include path for file, false = don't
	 */
	public function __construct($fileName=null, $mode='rb', $useIncludePath=false)
	{
		parent::__construct($fileName, $mode, $useIncludePath);
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 * @return null
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

}
