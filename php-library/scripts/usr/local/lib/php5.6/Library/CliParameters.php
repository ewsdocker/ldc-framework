<?php
namespace Library;

/*
 *		CliParameters is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 * CliParameters.
 *
 * This static class provides various means for parsing the command line parameters.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license refer to License file provided with the source.
 * @package Library
 * @subpackage CliParameters
 */
class CliParameters
{
	/**
	 * parameters
	 *
	 * parameter array
	 * @var array $parameters
	 */
	private static	$parameters = array();

	/**
	 * argc
	 *
	 * Copy of ARGC, a count of the items in ARGV
	 * @var array $argc
	 */
	private static	$argc = null;

	/**
	 * argv
	 *
	 * Copy of the ARGV array
	 * @var array $argv
	 */
	private static	$argv = null;

	/**
	 * initialized
	 *
	 * Initialized flag - true = initialized, false = not
	 * @var boolean $initialized
	 */
	private static	$initialized = false;

  	/**
  	 * initialize.
  	 *
     * Parse the command line parameters and return as an associative array.
     * @param int|string $largc = (optional) count of arguements in argv, or a string containing the CliParameters
     * @param array $largv = (optional) command line arguements
     * @return array[string]string associative array containing cli parameters
     * @throws CliParameters\Exception
     */
  	public static function initialize($largc=null, $largv=null)
	{
		static::Reset();

		if (is_string($largc))
		{
			return static::altCliParameters($largc);
		}

		$largc = static::argc($largc);
		$largv = static::argv($largv);

	  	if (($largc == null) && ($largv == null))
	  	{
	  		if (array_key_exists('argc', $_SERVER))
	  		{
	  			$largc = static::argc($_SERVER['argc']);
	  			$largv = static::argv($_SERVER['argv']);
	  		}
	  	}

		if (($largc < 1) || ($largc != count($largv)))
		{
			throw new CliParameters\Exception(Error::code('MissingParametersArray'));
		}

      	$index = 1;
      	do
	  	{
	  		if (strlen($largv[$index]) < 1)
	  		{
				throw new CliParameters\Exception(Error::code('InvalidStringSize'));
	  		}


	    	if (strpos($largv[$index], '='))
	    	{
	      		list($key, $value) = explode('=', trim($largv[$index]));
	    	}
	    	else
	    	{
	    		$key = $largv[$index];
				$value = (($index + 1) < count($largv)) ? $largv[++$index] : 1;
	    	}

	    	static::$parameters[strtolower($key)] = $value;

	    	$index++;
	  	}
	  	while ($index <= ($largc - 1));

	  	static::$initialized = true;
	  	return static::$parameters;
	}

  	/**
  	 * applicationName.
     *
     * Parse the command line parameters and return the application basename.
     * @param string $largc = (optional) count of arguements in argv
     * @param array $largv = (optional) command line arguements
     * @return string application name
     */
	public static function applicationName($largc=null, $largv=null)
	{
		if (! static::$initialized)
		{
	  		static::initialize($largc, $largv);
		}

	  	return basename(static::$argv[0], '.php');
	}

  	/**
  	 * applicationPath.
     *
     * Parse the application name and return the path to the application file.
     * @param string $largc = (optional) count of arguements in argv
     * @param array $largv = (optional) command line arguements
     * @return string path to the application file
     */
	public static function applicationPath($largc=null, $largv=null)
	{
		if (! static::$initialized)
		{
	  		static::setArgs($largc, $largv);
		}

		$path = static::$argv[0];
		if (($realpath = realpath(static::$argv[0])) === false)
		{
			throw new CliParameters\Exception(Error::code('FileDoesNotExist'));
		}

	  	return dirname($realpath);
	}

  	/**
  	 * setArgs.
     *
     * Parse the command line parameters and return as an associative array.
     * @param string $largc = (optional) count of arguements in argv
     * @param array $largv = (optional) command line arguements
     * @return array[string]= string associative array containing cli parameters
     */
	public static function setArgs($largc=null, $largv=null)
    {
      	return static::initialize($largc, $largv);
    }

  	/**
  	 * parameterValue.
     *
     * Get the value of the specified parameter from the parameter array, or return the default value supplied for the field.
     * @param string $parameterName = name of the field to retrieve
     * @param string|boolean|integer $defaultValue = (optional) value to return if the field does not exist
     * @return mixed $value
     */
    public static function parameterValue($parameterName, $defaultValue=false)
    {
    	if (! static::$initialized)
    	{
    		static::initialize();
    	}

      	if (! array_key_exists($parameterName, static::$parameters))
      	{
        	return $defaultValue;
      	}

      	return static::$parameters[$parameterName];
    }

  	/**
  	 * parameterExists.
     *
     * Find out if the parameter name is in the parameter array.
     * @param string $parameterName = name of the field to check on
     * @return boolean true if exists, false if it does not.
     */
    public static function parameterExists($parameterName)
    {
      	if ((! static::$initialized) || (! array_key_exists($parameterName, static::$parameters)))
      	{
        	return false;
      	}

      	return true;
    }

    /**
     * parameterCount
     *
     * Returns a count of the items in $parameters
     * @return integer $count
     */
    public static function parameterCount()
    {
    	return count(static::$parameters);
    }

  	/**
  	 * parameters.
     *
     * Get a copy of the parameters array,
     * @return array $parameters
     */
    public static function parameters()
    {
      	return static::$parameters;
    }

    /**
     * argv
     *
     * get/set the argv array value
     * @param array $argv = (optional) argv array, null to query
     * @return array $argv
     */
    public static function argv($argv=null)
    {
    	if ($argv)
    	{
    		static::$argv = $argv;
    	}

    	return static::$argv;
    }

    /**
     * argc
     *
     * get/set the argc parameter count
     * @param array $argc = (optional) argc, null to query
     * @return array $argc
     */
    public static function argc($argc=null)
    {
    	if ($argc !== null)
    	{
    		static::$argc = $argc;
    	}

    	return static::$argc;
    }

	/**
	 * reset
	 *
	 * Reset the args and parameters array
	 * @return null
	 */
	public static function reset()
	{
		static::$argc = null;
		static::$argv = null;
		static::$parameters = array();
		static::$initialized = false;
	}

	/**
	 * altCliParameters
	 *
	 * An alternative entry point allowing a string to be passed in and parsed as if it was typed on the console
	 * @param string $parameterString = string containing arguements to be parsed
     * @return array[string]string associative array containing cli parameters
	 * @throws \Library\CliParameters\Exception
	 */
	public static function altCliParameters($parameterString)
	{
		$argv = explode(' ', trim($parameterString));
		if (count($argv) < 1)
		{
			throw new CliParameters\Exception(Error::code('MissingParametersArray'));
		}

		$largv = array();
		$largIndex= 0;

		$inQuote = false;
		foreach($argv as $index => $string)
		{
			if (! $inQuote)
			{
				$largv[$largIndex] = $string;
				if (($quote = strpos($string, '"')) !== false)
				{
					$inQuote = true;
					if ((strlen($string) > ($quote + 1)) && ((strpos($string, '"', $quote+1)) !== false))
					{
						$largv[$largIndex] = str_replace("\"", "", $largv[$largIndex]);
						$inQuote = false;
					}
				}
			}
			else
			{
				$largv[$largIndex] .= " " . $string;
				if (($quote = strpos($string, '"')) !== false)
				{
					$largv[$largIndex] = str_replace("\"", "", $largv[$largIndex]);
					$inQuote=false;
				}
			}

			if (! $inQuote)
			{
				$largIndex++;
			}

		}

		if (($largc = count($largv)) == 0)
		{
			throw new CliParameters\Exception(Error::code('MissingParametersArray'));
		}

		return static::initialize($largc, $largv);
	}

}
