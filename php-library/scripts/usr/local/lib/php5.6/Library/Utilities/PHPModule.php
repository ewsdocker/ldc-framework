<?php
namespace Library\Utilities;

use Library\Error;

/*
 *		Utilities\PHPModule is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * Utilities\PHPModule
 *
 * Returns information about loaded PHP modules
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Utilities
 * @subpackage PHPModule
 */
class PHPModule
{
    /**
     * loaded
     *
     * Accepts a (prioritized) array of modules to check for and returns the first load module found
     * @param array $modules = array of modules to check for
     * @return string $module = false if none loaded, or the name of the first loaded module found
     */
    public static function loaded($modules)
    {
    	foreach($modules as $module)
    	{
    		if (extension_loaded($module))
    		{
    			return $module;
    		}
    	}

    	return false;
    }

}
