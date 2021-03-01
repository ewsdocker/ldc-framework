<?php
namespace Library\HTTP;
use Library\Error;
/*
 * 		HTTP\Form is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * Form.
 *
 * A static HTTP Form class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage HTTP
 */
class Form
{
    /**
     * fieldValue
     * 
     * Get a value from the input form
     * @param string $fieldName = name of the field to extract
     * @param mixed $defaultValue = (optional) value to return if $fieldName does not exist
     * @return string|boolean $fieldValue = value of field, if it exists, false if it does not
     */
    public static function fieldValue($fieldName, $defaultValue=false)
    {
    	if (! self::fieldExists($fieldName))
    	{
    		return $defaultValue;
    	}

      	if (isset($_GET[$fieldName]))
      	{
        	return self::stripslashes_array($_GET[$fieldName]);
      	}

    	return self::stripslashes_array($_POST[$fieldName]);
    }

    /**
     * fieldExists
     * 
     * Return true if the field is found
     * @param string $fieldName = name of the field to test
     * @return boolean true = it exists, false if it does not
     */
    public static function fieldExists($fieldName)
    {
      	if (isset($_GET[$fieldName]))
      	{
        	return true;
      	}

      	if (isset($_POST[$fieldName]))
      	{
   	    	return true;
      	}
      	
      	return false;
    }

	/**
	 * stripslashes_array
	 * @param mixed $data = form data to inspect
	 * @return mixed $data = data with multiple slashes stripped
	 */
	public static function stripslashes_array($data) 
  	{
	    return is_array($data) ? array_map('stripslashes_array', $data) : stripslashes($data);
  	}

}
